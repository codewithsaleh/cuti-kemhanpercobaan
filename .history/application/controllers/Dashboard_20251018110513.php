<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Mengatur zona waktu agar sapaan sesuai
        date_default_timezone_set('Asia/Jakarta');
        
        // Memuat semua model yang dibutuhkan
        $this->load->model('m_user');
        $this->load->model('m_jenis_kelamin');
        $this->load->model('m_cuti');
        $this->load->model('m_settings');
    }

    public function dashboard_super_admin()
    {
        if ($this->session->userdata('logged_in') == true AND $this->session->userdata('id_user_level') == 3) {
            // NOTE: This dashboard is now handled by Atasan.php
            // Redirecting to the correct controller to keep the structure clean.
            redirect('Atasan/dashboard');
        } else {
            $this->session->set_flashdata('loggin_err','loggin_err');
            redirect('Login/index');
        }
    }

    public function dashboard_admin()
    {
        if ($this->session->userdata('logged_in') == true AND $this->session->userdata('id_user_level') == 2) {
            
            // Menjalankan pengecekan reset cuti otomatis
            $this->check_automatic_reset();

            // Mengambil semua data yang dibutuhkan oleh dashboard Admin
            $data['cuti'] = $this->m_cuti->count_all_cuti()->row_array();
            $data['cuti_acc'] = $this->m_cuti->count_all_cuti_acc()->row_array();
            $data['cuti_confirm'] = $this->m_cuti->count_all_cuti_confirm()->row_array();
            $data['cuti_reject'] = $this->m_cuti->count_all_cuti_reject()->row_array();
            $data['pegawai'] = $this->m_user->count_all_pegawai()->row_array();
            $this->load->view('admin/dashboard', $data);

        } else {
            $this->session->set_flashdata('loggin_err','loggin_err');
            redirect('Login/index');
        }
    }
    
    public function dashboard_pegawai()
    {
        if ($this->session->userdata('logged_in') == true AND $this->session->userdata('id_user_level') == 1) {
            
            // Membuat sapaan berdasarkan waktu
            $jam = date('G'); // G untuk format 24 jam tanpa awalan nol
            if ($jam >= 4 && $jam < 12) {
                $sapaan = "Selamat Pagi";
            } else if ($jam >= 12 && $jam < 15) {
                $sapaan = "Selamat Siang";
            } else if ($jam >= 15 && $jam < 19) {
                $sapaan = "Selamat Sore";
            } else {
                $sapaan = "Selamat Malam";
            }
            $data['sapaan'] = $sapaan;

            // Mengambil semua data yang dibutuhkan oleh dashboard Pegawai
            $id_user = $this->session->userdata('id_user');
            $data['cuti_pegawai'] = $this->m_cuti->get_all_cuti_first_by_id_user($id_user)->result_array();
            $data['cuti'] = $this->m_cuti->count_all_cuti_by_id($id_user)->row_array();
            $data['cuti_acc'] = $this->m_cuti->count_all_cuti_acc_by_id($id_user)->row_array();
            $data['cuti_confirm'] = $this->m_cuti->count_all_cuti_confirm_by_id($id_user)->row_array();
            $data['cuti_reject'] = $this->m_cuti->count_all_cuti_reject_by_id($id_user)->row_array();
            $data['pegawai'] = $this->m_user->get_pegawai_by_id($id_user)->row_array();
            $data['jenis_kelamin'] = $this->m_jenis_kelamin->get_all_jenis_kelamin()->result_array();
            $data['pegawai_data'] = $this->m_user->get_pegawai_by_id($id_user)->result_array();
            
            // Mengirim semua data ke view
            $this->load->view('pegawai/dashboard', $data);

        } else {
            $this->session->set_flashdata('loggin_err','loggin_err');
            redirect('Login/index');
        }
    }
    
    // Fungsi untuk mengecek dan menjalankan reset otomatis
    private function check_automatic_reset()
    {
        $reset_mode = $this->m_settings->get_setting('reset_mode');
        $last_reset_year = $this->m_settings->get_setting('last_reset_year');
        $current_year = date('Y');

        // Jalankan HANYA jika mode 'otomatis' dan tahun ini belum direset
        if ($reset_mode == 'otomatis' && $last_reset_year < $current_year) {
            
            // Panggil fungsi reset dari model cuti
            $reset_success = $this->m_cuti->reset_cuti_tahunan();

            if ($reset_success) {
                // Jika berhasil, perbarui tahun reset terakhir di database
                $this->m_settings->update_setting('last_reset_year', $current_year);
                
                // Beri notifikasi ke admin
                $this->session->set_flashdata('success_reset', 'Reset cuti tahunan otomatis telah berhasil dijalankan untuk tahun ' . $current_year);
            }
        }
    }
}


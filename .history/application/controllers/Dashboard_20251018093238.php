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
    
    public function dashboard_pegawai() { {
        // Cek session pegawai   // Cek session pegawai
        if (!$this->session->userdata('id_user') || $this->session->userdata('id_user_level') != 1) {
            redirect('Auth');redirect('Auth');
        }

        // Gunakan view yang sudah ada untuk data lengkapggunakan view
        $data['user'] = $this->db->get_where('view_user_complete', ere('view_user_complete', 
            ['id_user' => $this->session->userdata('id_user')])->row_array();a('id_user')])->row_array();

        // Ambil statistik cuti pegawai
        $this->db->select('
            COUNT(*) as total_pengajuan,user.*,
            COUNT(CASE WHEN id_status_cuti = 1 THEN 1 END) as menunggu_konfirmasi,
            COUNT(CASE WHEN id_status_cuti = 2 THEN 1 END) as diterima,tasan_user_detail.nama_lengkap as nama_atasan,
            COUNT(CASE WHEN id_status_cuti = 3 THEN 1 END) as ditolak, as jabatan_atasan
            COUNT(CASE WHEN id_status_cuti = 4 THEN 1 END) as menunggu_admin        ');
        ');
        $this->db->from('cuti');'pegawai_user.id_user_detail = pegawai_user_detail.id_user_detail', 'left');
        $this->db->where('id_user', $this->session->userdata('id_user'));il.id_user_detail', 'left');
        $this->db->where('YEAR(tgl_diajukan)', date('Y'));
        $data['stats_cuti'] = $this->db->get()->row_array();

        // Ambil riwayat cuti terbaru
        $this->db->select('*');
        $this->db->from('view_cuti_complete');
        $this->db->where('id_user', $this->session->userdata('id_user'));
        $this->db->order_by('tgl_diajukan', 'DESC');COUNT(CASE WHEN id_status_cuti = 2 THEN 1 END) as diterima,
        $this->db->limit(5); = 3 THEN 1 END) as ditolak,
        $data['riwayat_cuti'] = $this->db->get()->result_array(); as menunggu_admin
        ');
        // Data sudah lengkap dari view_user_completeb->from('cuti');
        $data['sisa_cuti'] = $data['user']['sisa_cuti'] ?? 12;'));
        $data['jatah_cuti'] = $data['user']['jatah_cuti'] ?? 12;iajukan)', date('Y'));
        $data['nama_atasan'] = $data['user']['nama_atasan'] ?? 'Belum Ada Atasan';data['stats_cuti'] = $this->db->get()->row_array();

        $this->load->view('pegawai/dashboard', $data);    // Ambil riwayat cuti terbaru
    }
    );
    // Fungsi untuk mengecek dan menjalankan reset otomatis   $this->db->where('id_user', $this->session->userdata('id_user'));
    private function check_automatic_reset()
    {
        $reset_mode = $this->m_settings->get_setting('reset_mode');his->db->get()->result_array();
        $last_reset_year = $this->m_settings->get_setting('last_reset_year');
        $current_year = date('Y');

        // Jalankan HANYA jika mode 'otomatis' dan tahun ini belum direseta['jatah_cuti'] = $pegawai_data['jatah_cuti'] ?? 12;
        if ($reset_mode == 'otomatis' && $last_reset_year < $current_year) {atasan'] ?? 'Belum Ada Atasan';
            
            // Panggil fungsi reset dari model cuti        $this->load->view('pegawai/dashboard', $data);
            $reset_success = $this->m_cuti->reset_cuti_tahunan();

            if ($reset_success) {
                // Jika berhasil, perbarui tahun reset terakhir di databasetion check_automatic_reset()
                $this->m_settings->update_setting('last_reset_year', $current_year);
                
                // Beri notifikasi ke admin_reset_year = $this->m_settings->get_setting('last_reset_year');
                $this->session->set_flashdata('success_reset', 'Reset cuti tahunan otomatis telah berhasil dijalankan untuk tahun ' . $current_year);current_year = date('Y');
            }
        }       // Jalankan HANYA jika mode 'otomatis' dan tahun ini belum direset
    }        if ($reset_mode == 'otomatis' && $last_reset_year < $current_year) {
}            


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


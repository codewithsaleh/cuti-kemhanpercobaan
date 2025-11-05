<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Atasan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Memuat model yang akan sering digunakan
        $this->load->model('m_user');
        $this->load->model('m_cuti');

        // Pengecekan sesi untuk seluruh fungsi di controller ini
        if ($this->session->userdata('logged_in') != true || $this->session->userdata('id_user_level') != 3) {
            $this->session->set_flashdata('loggin_err','loggin_err');
            redirect('Login/index');
        }
    }

    public function dashboard()
    {
        $id_atasan = $this->session->userdata('id_user');

        // Data statistik yang sudah ada untuk kotak info
        $data['pegawai'] = $this->m_user->count_bawahan_by_atasan($id_atasan)->row_array();
        $data['cuti'] = $this->m_cuti->count_all_cuti_bawahan($id_atasan)->row_array();
        $data['cuti_confirm'] = $this->m_cuti->count_cuti_bawahan_by_status($id_atasan, 1)->row_array();
        $data['cuti_acc'] = $this->m_cuti->count_cuti_bawahan_by_status($id_atasan, 2)->row_array();
        $data['cuti_reject'] = $this->m_cuti->count_cuti_bawahan_by_status($id_atasan, 3)->row_array();
        
        // [FITUR BARU] Mengambil dan memproses data untuk grafik
        $monthly_summary = $this->m_cuti->get_monthly_leave_summary_by_atasan($id_atasan);
        
        // Inisialisasi array untuk 12 bulan agar grafik selalu menampilkan semua bulan
        $months_indonesian = [
            'January' => 'Jan', 'February' => 'Feb', 'March' => 'Mar', 'April' => 'Apr', 'May' => 'Mei', 'June' => 'Jun', 
            'July' => 'Jul', 'August' => 'Agu', 'September' => 'Sep', 'October' => 'Okt', 'November' => 'Nov', 'December' => 'Des'
        ];
        $chart_labels = array_values($months_indonesian);
        $chart_data = array_fill(0, 12, 0); // Buat array berisi 12 angka nol

        // Isi data dari database ke bulan yang sesuai
        foreach ($monthly_summary as $summary) {
            $month_name = $summary['month'];
            $month_index = array_search($month_name, array_keys($months_indonesian));
            if ($month_index !== false) {
                $chart_data[$month_index] = (int)$summary['total_days'];
            }
        }
        
        // Kirim data yang sudah siap pakai ke view
        $data['chart_labels'] = json_encode($chart_labels);
        $data['chart_data'] = json_encode($chart_data);

        $this->load->view('super_admin/dashboard', $data);
    }

    public function manajemen_cuti($filter = 'menunggu')
    {
        $id_atasan = $this->session->userdata('id_user');
        $data['filter_aktif'] = $filter;

        if ($filter == 'diterima') {
            $data['cuti_list'] = $this->m_cuti->get_cuti_bawahan_by_status($id_atasan, 2)->result_array();
        } elseif ($filter == 'ditolak') {
            $data['cuti_list'] = $this->m_cuti->get_cuti_bawahan_by_status($id_atasan, 3)->result_array();
        } else {
            $data['cuti_list'] = $this->m_cuti->get_cuti_bawahan_by_status($id_atasan, 1)->result_array();
        }

        $this->load->view('super_admin/manajemen_cuti', $data);
    }

    public function data_tim()
    {
        $id_atasan = $this->session->userdata('id_user');
        $data['pegawai_list'] = $this->m_user->get_all_bawahan($id_atasan)->result_array();
        $this->load->view('super_admin/data_tim', $data);
    }

    public function laporan()
    {
        $this->load->view('super_admin/laporan');
    }

    public function settings()
    {
        $this->load->view('super_admin/settings');
    }

    public function setujui_cuti($id_cuti)
    {
        $hasil = $this->m_cuti->update_status_saja($id_cuti, 4); 

        if($hasil){
            $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Pengajuan cuti berhasil disetujui dan diteruskan ke Admin!</div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Gagal memproses persetujuan cuti.</div>');
        }
        redirect('Atasan/manajemen_cuti');
    }

    public function tolak_cuti($id_cuti)
    {
        $hasil = $this->m_cuti->update_status_saja($id_cuti, 3);

        if($hasil){
            $this->session->set_flashdata('pesan', '<div class="alert alert-warning" role="alert">Pengajuan cuti telah ditolak.</div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Gagal memproses penolakan cuti.</div>');
        }
        redirect('Atasan/manajemen_cuti');
    }

    public function proses_laporan()
    {
        $id_atasan = $this->session->userdata('id_user');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $data['laporan_cuti'] = $this->m_cuti->get_cuti_bawahan_by_date_range($id_atasan, $start_date, $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        
        $this->load->view('super_admin/hasil_laporan', $data);
    }

    public function proses_ganti_password()
    {
        $id_user = $this->session->userdata('id_user');
        $current_password = $this->input->post('current_password');
        $new_password = $this->input->post('new_password');
        $confirm_password = $this->input->post('confirm_password');

        if ($new_password !== $confirm_password) {
            $this->session->set_flashdata('password_error', 'Password Baru dan Konfirmasi Password tidak cocok!');
            redirect('Atasan/settings');
            return;
        }

        $user_data = $this->m_user->get_user_by_id($id_user)->row();

        if ($user_data->password !== md5($current_password)) {
            $this->session->set_flashdata('password_error', 'Password Lama yang Anda masukkan salah!');
            redirect('Atasan/settings');
            return;
        }

        $hasil = $this->m_user->update_password_only($id_user, $new_password);
        if($hasil){
            $this->session->set_flashdata('password_success','password_success');
        } else {
            $this->session->set_flashdata('password_error', 'Gagal memperbarui password. Silakan coba lagi.');
        }

        redirect('Atasan/settings');
    }
}


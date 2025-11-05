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
        // Cek session super admin
        if (!$this->session->userdata('logged_in') || $this->session->userdata('id_user_level') != 3) {
            redirect('Auth');
        }

        // Ambil data user yang login
        $data['user'] = $this->db->get_where('view_user_complete', 
            ['id_user' => $this->session->userdata('id_user')])->row_array();

        // PERBAIKAN: Query untuk menghitung bawahan menggunakan user_detail.id_atasan
        $this->db->select('COUNT(*) as total_user');
        $this->db->from('user u');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
        $this->db->where('ud.id_atasan', $data['user']['id_user_detail']); // Gunakan id_user_detail
        $this->db->where('u.is_active', 1);
        $total_bawahan = $this->db->get()->row_array();

        // Ambil statistik cuti keseluruhan
        $this->db->select('
            COUNT(*) as total_pengajuan,
            COUNT(CASE WHEN id_status_cuti = 1 THEN 1 END) as menunggu_konfirmasi,
            COUNT(CASE WHEN id_status_cuti = 2 THEN 1 END) as diterima,
            COUNT(CASE WHEN id_status_cuti = 3 THEN 1 END) as ditolak,
            COUNT(CASE WHEN id_status_cuti = 4 THEN 1 END) as menunggu_admin
        ');
        $this->db->from('cuti');
        $this->db->where('YEAR(tgl_diajukan)', date('Y'));
        $data['stats_cuti_global'] = $this->db->get()->row_array();

        // PERBAIKAN: Query untuk cuti yang butuh persetujuan atasan
        $this->db->select('c.*, ud.nama_lengkap, jc.nama_cuti, sc.status_cuti');
        $this->db->from('cuti c');
        $this->db->join('user u', 'c.id_user = u.id_user', 'left');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti', 'left');
        $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti', 'left');
        $this->db->where('ud.id_atasan', $data['user']['id_user_detail']); // Gunakan id_user_detail
        $this->db->where_in('c.id_status_cuti', [1, 4]); // Menunggu konfirmasi atau menunggu admin
        $this->db->order_by('c.tgl_diajukan', 'DESC');
        $this->db->limit(10);
        $data['pending_approvals'] = $this->db->get()->result_array();

        // Data untuk dashboard
        $data['total_bawahan'] = $total_bawahan['total_user'] ?? 0;
        $data['total_pegawai'] = $this->db->get_where('user', ['id_user_level' => 1, 'is_active' => 1])->num_rows();
        $data['total_admin'] = $this->db->get_where('user', ['id_user_level' => 2, 'is_active' => 1])->num_rows();

        // Riwayat aktivitas recent
        $this->db->select('c.*, ud.nama_lengkap, jc.nama_cuti, sc.status_cuti, sc.color_class');
        $this->db->from('cuti c');
        $this->db->join('user u', 'c.id_user = u.id_user', 'left');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti', 'left');
        $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti', 'left');
        $this->db->order_by('c.updated_at', 'DESC');
        $this->db->limit(5);
        $data['recent_activities'] = $this->db->get()->result_array();

        $this->load->view('atasan/dashboard', $data);
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


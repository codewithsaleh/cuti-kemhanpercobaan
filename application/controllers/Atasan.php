<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Atasan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Memuat model yang akan sering digunakan
        $this->load->model('m_user');
        $this->load->model('m_cuti');

        // Pengecekan sesi untuk seluruh fungsi di controller ini
        if ($this->session->userdata('logged_in') != true || $this->session->userdata('id_user_level') != 3) {
            $this->session->set_flashdata('loggin_err', 'loggin_err');
            redirect('Login/index');
        }
    }


    public function dashboard()
    {
        if (!check_user_level([3])) {
            return;
        }

        $id_atasan = $this->session->userdata('id_user');
        $tahun_ini = date('Y');

        // Ambil data user yang login
        $data['user'] = $this->db->get_where(
            'view_user_complete',
            ['id_user' => $id_atasan]
        )->row_array();

        // Hitung total anggota tim (bawahan)
        $data['pegawai']['total_user'] = $this->db->where('id_atasan', $id_atasan)->count_all_results('user');

        // Hitung statistik cuti dari anggota tim
        $this->db->select('
        COUNT(*) as total_pengajuan,
        COUNT(CASE WHEN c.id_status_cuti = 1 THEN 1 END) as menunggu,
        COUNT(CASE WHEN c.id_status_cuti = 2 THEN 1 END) as disetujui,
        COUNT(CASE WHEN c.id_status_cuti = 3 THEN 1 END) as ditolak
    ');
        $this->db->from('cuti c');
        $this->db->join('user u', 'c.id_user = u.id_user');
        $this->db->where('u.id_atasan', $id_atasan);
        $stats_cuti = $this->db->get()->row_array();

        $data['cuti'] = [
            'total' => $stats_cuti['total_pengajuan'] ?? 0,
            'total_cuti' => $stats_cuti['total_pengajuan'] ?? 0
        ];
        $data['cuti_acc'] = [
            'total' => $stats_cuti['disetujui'] ?? 0,
            'total_cuti' => $stats_cuti['disetujui'] ?? 0
        ];
        $data['cuti_confirm'] = [
            'total' => $stats_cuti['menunggu'] ?? 0,
            'total_cuti' => $stats_cuti['menunggu'] ?? 0
        ];
        $data['cuti_reject'] = [
            'total' => $stats_cuti['ditolak'] ?? 0,
            'total_cuti' => $stats_cuti['ditolak'] ?? 0
        ];

        // [PERBAIKAN] Data cuti terbaru dari anggota tim - FIXED JOIN
        $this->db->select('
        c.*, 
        ud.nama_lengkap, 
        ud.nip,
        jc.nama_cuti, 
        sc.status_cuti,
        sc.color_class,
        DATEDIFF(c.berakhir, c.mulai) + 1 as jumlah_hari
    ');
        $this->db->from('cuti c');
        $this->db->join('user u', 'c.id_user = u.id_user');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail'); // [FIX] Join ke user_detail
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti');
        $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti');
        $this->db->where('u.id_atasan', $id_atasan);
        $this->db->order_by('c.tgl_diajukan', 'DESC');
        $this->db->limit(5);
        $data['cuti_terbaru'] = $this->db->get()->result_array();

        // [TAMBAHAN] Data untuk chart rekap cuti per bulan dari anggota tim
        $data['chart_labels'] = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

        // Query untuk rekap cuti per bulan dari anggota tim
        $this->db->select('
        MONTH(c.mulai) as bulan,
        SUM(DATEDIFF(c.berakhir, c.mulai) + 1) as total_hari
    ');
        $this->db->from('cuti c');
        $this->db->join('user u', 'c.id_user = u.id_user');
        $this->db->where('u.id_atasan', $id_atasan);
        $this->db->where('c.id_status_cuti', 2); // Hanya cuti yang disetujui
        $this->db->where('YEAR(c.mulai)', $tahun_ini);
        $this->db->group_by('MONTH(c.mulai)');
        $this->db->order_by('MONTH(c.mulai)', 'ASC');
        $rekap_bulanan = $this->db->get()->result_array();

        // Inisialisasi data chart dengan 0 untuk semua bulan
        $chart_data = array_fill(0, 12, 0);

        // Isi data dari query
        foreach ($rekap_bulanan as $rekap) {
            $bulan_index = $rekap['bulan'] - 1; // Karena array mulai dari 0
            $chart_data[$bulan_index] = (int) $rekap['total_hari'];
        }

        $data['chart_data'] = $chart_data;

        // Set sapaan berdasarkan waktu
        $hour = date('H');
        if ($hour >= 5 && $hour < 12) {
            $data['sapaan'] = 'Selamat Pagi';
        } elseif ($hour >= 12 && $hour < 15) {
            $data['sapaan'] = 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $data['sapaan'] = 'Selamat Sore';
        } else {
            $data['sapaan'] = 'Selamat Malam';
        }

        $data['title'] = 'Dashboard Super Admin';
        $data['active_menu'] = 'dashboard';

        $this->load->view('super_admin/dashboard', $data);
    }

    public function manajemen_cuti($filter = 'menunggu')
    {
        if (!check_user_level([3])) {
            return;
        }

        $id_atasan = $this->session->userdata('id_user');

        // Ambil data user yang login
        $data['user'] = $this->db->get_where(
            'view_user_complete',
            ['id_user' => $id_atasan]
        )->row_array();

        $data['filter_aktif'] = $filter;

        // Query data cuti bawahan berdasarkan filter
        $this->db->select('
        c.*,
        u.username,
        ud.nama_lengkap,
        ud.nip,
        ud.jabatan,
        jc.nama_cuti,
        sc.status_cuti,
        sc.color_class,
        DATEDIFF(c.berakhir, c.mulai) + 1 as jumlah_hari
    ');
        $this->db->from('cuti c');
        $this->db->join('user u', 'c.id_user = u.id_user');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail');
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti');
        $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti');
        $this->db->where('u.id_atasan', $id_atasan);

        // Filter berdasarkan status
        switch ($filter) {
            case 'diterima':
                $this->db->where('c.id_status_cuti', 2);
                $data['page_title'] = 'Cuti yang Diterima';
                break;
            case 'ditolak':
                $this->db->where('c.id_status_cuti', 3);
                $data['page_title'] = 'Cuti yang Ditolak';
                break;
            case 'menunggu':
            default:
                $this->db->where('c.id_status_cuti', 1);
                $data['page_title'] = 'Pengajuan Cuti Menunggu Persetujuan';
                break;
        }

        $this->db->order_by('c.tgl_diajukan', 'DESC');
        $data['cuti_list'] = $this->db->get()->result_array();

        $data['title'] = 'Manajemen Cuti';
        $data['active_menu'] = 'manajemen_cuti';

        $this->load->view('super_admin/manajemen_cuti', $data);
    }

    public function data_tim()
    {
        if (!check_user_level([3])) {
            return;
        }
        $id_atasan = $this->session->userdata('id_user');
        $data['pegawai_list'] = $this->m_user->get_all_bawahan($id_atasan)->result_array();
        $this->load->view('super_admin/data_tim', $data);
    }

    public function laporan()
    {
        if (!check_user_level([3])) {
            return;
        }
        $this->load->view('super_admin/laporan');
    }

    public function settings()
    {
        if (!check_user_level([3])) {
            return;
        }

        $this->load->view('super_admin/settings');
    }

    public function setujui_cuti($id_cuti)
    {
        if (!check_user_level([3])) {
            return;
        }

        $id_atasan = $this->session->userdata('id_user');

        // Validasi apakah cuti ini dari bawahan yang benar
        $this->db->select('c.*, u.id_atasan');
        $this->db->from('cuti c');
        $this->db->join('user u', 'c.id_user = u.id_user');
        $this->db->where('c.id_cuti', $id_cuti);
        $this->db->where('u.id_atasan', $id_atasan);
        $cuti = $this->db->get()->row_array();

        if (!$cuti) {
            $this->session->set_flashdata('error', 'Cuti tidak ditemukan atau tidak ada akses');
            redirect('Atasan/manajemen_cuti');
        }

        // SET TIMEZONE DULU SEBELUM PAKAI date()
        date_default_timezone_set('Asia/Jakarta');
        $current_time = date('Y-m-d H:i:s');

        // Update status cuti menjadi Menunggu Admin (4)
        $update_data = [
            'id_status_cuti' => 4, // Menunggu Verifikasi Admin
            'approved_by_atasan' => $id_atasan,
            'approved_at_atasan' => $current_time, // WIB time
            'updated_at' => $current_time
        ];

        $this->db->where('id_cuti', $id_cuti);
        $result = $this->db->update('cuti', $update_data);

        if ($result) {
            $this->session->set_flashdata('success', 'Cuti berhasil disetujui dan diteruskan ke Admin');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyetujui cuti');
        }

        redirect('Atasan/manajemen_cuti');
    }

    public function tolak_cuti($id_cuti)
    {
        if (!check_user_level([3])) {
            return;
        }

        $id_atasan = $this->session->userdata('id_user');
        $alasan_penolakan = $this->input->post('alasan_penolakan');

        // Validasi apakah cuti ini dari bawahan yang benar
        $this->db->select('c.*, u.id_atasan');
        $this->db->from('cuti c');
        $this->db->join('user u', 'c.id_user = u.id_user');
        $this->db->where('c.id_cuti', $id_cuti);
        $this->db->where('u.id_atasan', $id_atasan);
        $cuti = $this->db->get()->row_array();

        if (!$cuti) {
            $this->session->set_flashdata('error', 'Cuti tidak ditemukan atau tidak ada akses');
            redirect('Atasan/manajemen_cuti');
        }

        // SET TIMEZONE DULU SEBELUM PAKAI date()
        date_default_timezone_set('Asia/Jakarta');
        $current_time = date('Y-m-d H:i:s');

        // Update status cuti menjadi Ditolak (3)
        $update_data = [
            'id_status_cuti' => 3, // Ditolak
            'alasan_penolakan' => $alasan_penolakan,
            'approved_by_atasan' => $id_atasan,
            'approved_at_atasan' => $current_time, // WIB time
            'rejected_by' => $id_atasan,
            'rejected_at' => $current_time,
            'updated_at' => $current_time
        ];

        $this->db->where('id_cuti', $id_cuti);
        $result = $this->db->update('cuti', $update_data);

        if ($result) {
            $this->session->set_flashdata('success', 'Cuti berhasil ditolak');
        } else {
            $this->session->set_flashdata('error', 'Gagal menolak cuti');
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
        if ($hasil) {
            $this->session->set_flashdata('password_success', 'password_success');
        } else {
            $this->session->set_flashdata('password_error', 'Gagal memperbarui password. Silakan coba lagi.');
        }

        redirect('Atasan/settings');
    }
}


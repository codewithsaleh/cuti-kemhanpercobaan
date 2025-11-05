<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

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
        if (!check_user_level([3])) {
            return;
        }

        $id_user = $this->session->userdata('id_user');
        $tahun_ini = date('Y');

        // Ambil data user yang login
        $data['user'] = $this->db->get_where(
            'view_user_complete',
            ['id_user' => $id_user]
        )->row_array();

        // Hitung statistik pegawai
        $data['total_pegawai'] = $this->db->where('id_user_level', 1)->where('is_active', 1)->count_all_results('user');
        $data['total_admin'] = $this->db->where('id_user_level', 2)->where('is_active', 1)->count_all_results('user');

        // Hitung total cuti tahun ini
        $this->db->select('SUM(DATEDIFF(berakhir, mulai) + 1) as total_hari_cuti');
        $this->db->from('cuti');
        $this->db->where('id_status_cuti', 2); // Status approved
        $this->db->where('YEAR(mulai)', $tahun_ini);
        $total_cuti = $this->db->get()->row();
        $data['total_cuti_tahun_ini'] = $total_cuti->total_hari_cuti ?? 0;

        // Data untuk chart rekap cuti per bulan
        $data['chart_labels'] = [];
        $data['chart_data'] = [];

        // Generate semua bulan dalam tahun ini
        $all_months = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Ags',
            'Sep',
            'Okt',
            'Nov',
            'Des'
        ];

        $data['chart_labels'] = $all_months;

        // Query untuk mendapatkan rekap cuti per bulan
        $this->db->select('
        MONTH(mulai) as bulan,
        SUM(DATEDIFF(berakhir, mulai) + 1) as total_hari
    ');
        $this->db->from('cuti');
        $this->db->where('id_status_cuti', 2); // Hanya cuti yang approved
        $this->db->where('YEAR(mulai)', $tahun_ini);
        $this->db->group_by('MONTH(mulai)');
        $this->db->order_by('MONTH(mulai)', 'ASC');
        $rekap_bulanan = $this->db->get()->result_array();

        // Inisialisasi data chart dengan 0 untuk semua bulan
        $chart_data = array_fill(0, 12, 0);

        // Isi data dari query
        foreach ($rekap_bulanan as $rekap) {
            $bulan_index = $rekap['bulan'] - 1; // Karena array mulai dari 0
            $chart_data[$bulan_index] = (int) $rekap['total_hari'];
        }

        $data['chart_data'] = $chart_data;

        // Data untuk card statistik
        $data['stats'] = [
            'total_pegawai' => $data['total_pegawai'],
            'total_admin' => $data['total_admin'],
            'total_cuti' => $data['total_cuti_tahun_ini']
        ];

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

        $this->load->view('super_admin/dashboard', $data);
    }

    public function dashboard_admin()
    {
        if (!check_user_level([2])) {
            return;
        }

        // Menjalankan pengecekan reset cuti otomatis
        $this->check_automatic_reset();

        // [UPDATE] Menggunakan query langsung untuk semua statistik - EXCLUDE STATUS ARSIP (5)
        $data['stats'] = [
            // Total semua cuti (kecuali arsip)
            'total_cuti' => $this->db->where('id_status_cuti !=', 5)->count_all_results('cuti'),

            // Cuti diterima (status 2) - bukan arsip
            'cuti_acc' => $this->db->where('id_status_cuti', 2)->count_all_results('cuti'),

            // Cuti menunggu (status 1 dan 4) - bukan arsip
            'cuti_menunggu' => $this->db->where_in('id_status_cuti', [1, 4])->count_all_results('cuti'),

            // Cuti ditolak (status 3) - bukan arsip
            'cuti_reject' => $this->db->where('id_status_cuti', 3)->count_all_results('cuti'),

            // Cuti arsip (status 5) - khusus arsip
            'cuti_arsip' => $this->db->where('id_status_cuti', 5)->count_all_results('cuti'),

            // Total Pengguna
            'total_pegawai' => $this->db->where_in('id_user_level', [1, 3])->where('is_active', 1)->count_all_results('user')
        ];

        // Format data untuk view (jika view butuh format lama)
        $data['cuti'] = ['total_cuti' => $data['stats']['total_cuti']];
        $data['cuti_acc'] = ['total_cuti' => $data['stats']['cuti_acc']];
        $data['cuti_confirm'] = ['total_cuti' => $data['stats']['cuti_menunggu']];
        $data['cuti_reject'] = ['total_cuti' => $data['stats']['cuti_reject']];
        $data['cuti_arsip'] = ['total_cuti' => $data['stats']['cuti_arsip']];
        $data['pegawai'] = ['total_user' => $data['stats']['total_pegawai']];

        // Ambil data user yang login
        $data['user'] = $this->db->get_where(
            'view_user_complete',
            ['id_user' => $this->session->userdata('id_user')]
        )->row_array();

        $data['title'] = 'Dashboard Admin';
        $data['active_menu'] = 'dashboard';

        $this->load->view('admin/dashboard', $data);
    }

    public function dashboard_pegawai()
{
    if (!check_user_level([1])) {
        return;
    }

    $id_user = $this->session->userdata('id_user');

    // Ambil data user yang login menggunakan view
    $data['user'] = $this->db->get_where(
        'view_user_complete',
        ['id_user' => $id_user]
    )->row_array();

    // Gunakan view yang sudah ada untuk data lengkap
    if (!$data['user']) {
        // Fallback jika view tidak ada - TAMBAHKAN JOIN UNTUK ATASAN
        $this->db->select('u.*, ud.*, ul.user_level, 
                          atasan.nama_lengkap as nama_atasan,
                          atasan.jabatan as jabatan_atasan');
        $this->db->from('user u');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
        $this->db->join('user_level ul', 'u.id_user_level = ul.id_user_level', 'left');
        $this->db->join('user atasan_user', 'u.id_atasan = atasan_user.id_user', 'left'); // Join untuk atasan
        $this->db->join('user_detail atasan', 'atasan_user.id_user_detail = atasan.id_user_detail', 'left'); // Join detail atasan
        $this->db->where('u.id_user', $id_user);
        $data['user'] = $this->db->get()->row_array();
    }

    // [BARU] Hitung sisa cuti real-time
    $data['sisa_cuti_real'] = $this->hitung_sisa_cuti_real_time($id_user);

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

    // Ambil statistik cuti pegawai untuk variabel yang dibutuhkan view
    $this->db->select('
        COUNT(*) as total_pengajuan,
        COUNT(CASE WHEN id_status_cuti = 1 THEN 1 END) as menunggu_konfirmasi,
        COUNT(CASE WHEN id_status_cuti = 2 THEN 1 END) as diterima,
        COUNT(CASE WHEN id_status_cuti = 3 THEN 1 END) as ditolak,
        COUNT(CASE WHEN id_status_cuti = 4 THEN 1 END) as menunggu_admin
    ');
    $this->db->from('cuti');
    $this->db->where('id_user', $id_user);
    $this->db->where('YEAR(tgl_diajukan)', date('Y'));
    $stats = $this->db->get()->row_array();

    // Format data sesuai yang diharapkan view dengan key 'total_cuti'
    $data['cuti'] = [
        'total' => $stats['total_pengajuan'] ?? 0,
        'total_cuti' => $stats['total_pengajuan'] ?? 0
    ];
    $data['cuti_acc'] = [
        'total' => $stats['diterima'] ?? 0,
        'total_cuti' => $stats['diterima'] ?? 0
    ];
    $data['cuti_confirm'] = [
        'total' => $stats['menunggu_konfirmasi'] ?? 0,
        'total_cuti' => $stats['menunggu_konfirmasi'] ?? 0
    ];
    $data['cuti_reject'] = [
        'total' => $stats['ditolak'] ?? 0,
        'total_cuti' => $stats['ditolak'] ?? 0
    ];
    $data['stats_cuti'] = $stats;

    // Data pegawai untuk variabel $pegawai yang dibutuhkan view
    $data['pegawai'] = [
        'nama_lengkap' => $data['user']['nama_lengkap'] ?? 'Pegawai',
        'nip' => $data['user']['nip'] ?? '-',
        'jabatan' => $data['user']['jabatan'] ?? '-',
        'sisa_cuti' => $data['sisa_cuti_real'], // [UPDATE] Pakai nilai real-time
        'jatah_cuti' => $data['user']['jatah_cuti'] ?? 12,
        // [BARU] Tambahkan data atasan
        'nama_atasan' => $data['user']['nama_atasan'] ?? null,
        'jabatan_atasan' => $data['user']['jabatan_atasan'] ?? null,
        'id_atasan' => $data['user']['id_atasan'] ?? null
    ];

    // Perbaiki query riwayat cuti - gunakan query manual yang sederhana
    $this->db->select('
        c.id_cuti,
        c.perihal_cuti,
        c.tgl_diajukan,
        c.mulai,
        c.berakhir,
        c.id_status_cuti,
        jc.nama_cuti,
        sc.status_cuti,
        sc.color_class,
        DATEDIFF(c.berakhir, c.mulai) + 1 as jumlah_hari
    ');
    $this->db->from('cuti c');
    $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti', 'left');
    $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti', 'left');
    $this->db->where('c.id_user', $id_user);
    $this->db->order_by('c.tgl_diajukan', 'DESC');
    $this->db->limit(5);
    $data['riwayat_cuti'] = $this->db->get()->result_array();

    // Data tambahan
    $data['sisa_cuti'] = $data['sisa_cuti_real']; // [UPDATE] Pakai nilai real-time
    $data['jatah_cuti'] = $data['user']['jatah_cuti'] ?? 12;
    
    // [BARU] Data atasan untuk ditampilkan di view
    $data['nama_atasan'] = $data['user']['nama_atasan'] ?? 'Belum Ada Atasan';
    $data['jabatan_atasan'] = $data['user']['jabatan_atasan'] ?? '';

    $this->load->view('pegawai/dashboard', $data);
}

    // [BARU] Fungsi untuk menghitung sisa cuti real-time
    private function hitung_sisa_cuti_real_time($id_user)
{
    // Cek apakah tahun cuti di user_detail sudah tahun ini
    $this->db->select('ud.tahun_cuti, ud.sisa_cuti, ud.jatah_cuti');
    $this->db->from('user u');
    $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail');
    $this->db->where('u.id_user', $id_user);
    $user_data = $this->db->get()->row_array();

    $tahun_ini = date('Y');

    // Jika tahun cuti bukan tahun ini, otomatis reset ke jatah_cuti
    if ($user_data['tahun_cuti'] != $tahun_ini) {
        return $user_data['jatah_cuti'] ?? 12;
    }

    // Jika tahun sama, gunakan nilai dari user_detail (bukan hitung ulang)
    return $user_data['sisa_cuti'] ?? 12;
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


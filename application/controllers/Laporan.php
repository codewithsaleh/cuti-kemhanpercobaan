<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Memuat library Dompdf yang sudah kita install
use Dompdf\Dompdf;

class Laporan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Load model yang dibutuhkan
        $this->load->model('m_cuti');
        $this->load->model('m_user');

        // Load helper auth
        $this->load->helper('auth_helper');
    }

    public function view_admin()
    {
        if (!check_user_level([2])) {
            return;
        }

        // Ambil data user yang login
        $data['user'] = $this->db->get_where(
            'view_user_complete',
            ['id_user' => $this->session->userdata('id_user')]
        )->row_array();

        // Data untuk filter laporan
        $tahun = $this->input->get('tahun') ?: date('Y');
        $bulan = $this->input->get('bulan') ?: date('m');

        // Query data laporan cuti
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

        // Filter berdasarkan tahun dan bulan
        if ($tahun) {
            $this->db->where('YEAR(c.mulai)', $tahun);
        }
        if ($bulan) {
            $this->db->where('MONTH(c.mulai)', $bulan);
        }

        $this->db->order_by('c.tgl_diajukan', 'DESC');
        $data['laporan_cuti'] = $this->db->get()->result_array();

        // Statistik laporan
        $data['total_pengajuan'] = count($data['laporan_cuti']);
        $data['total_disetujui'] = 0;
        $data['total_ditolak'] = 0;
        $data['total_hari_cuti'] = 0;

        foreach ($data['laporan_cuti'] as $cuti) {
            if ($cuti['id_status_cuti'] == 2) {
                $data['total_disetujui']++;
                $data['total_hari_cuti'] += $cuti['jumlah_hari'];
            } elseif ($cuti['id_status_cuti'] == 3) {
                $data['total_ditolak']++;
            }
        }

        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;
        $data['title'] = 'Laporan Cuti';
        $data['active_menu'] = 'laporan';

        $this->load->view('admin/laporan_view', $data);
    }

    // Fungsi untuk membuat laporan rekapitulasi bulanan

    // Untuk print-only version
    public function cetak_rekap_bulanan()
    {
        if (!check_user_level([2])) {
            return;
        }

        // 1. Ambil input tanggal dari form filter
        $start_date = $this->input->post('tanggal_mulai');
        $end_date = $this->input->post('tanggal_selesai');

        // 2. Jika tanggal kosong, set default ke bulan berjalan
        if (empty($start_date) || empty($end_date)) {
            $start_date = date('Y-m-01'); // Tanggal 1 bulan ini
            $end_date = date('Y-m-t');    // Tanggal terakhir bulan ini
        }

        // 3. Validasi format tanggal
        if (!DateTime::createFromFormat('Y-m-d', $start_date) || !DateTime::createFromFormat('Y-m-d', $end_date)) {
            // Jika format invalid, set ke bulan berjalan
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-t');
        }

        // 4. Minta data dari model berdasarkan rentang tanggal
        $data['laporan_cuti'] = $this->m_cuti->get_cuti_by_date_range($start_date, $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        // 5. Load view langsung (tanpa preview)
        $this->load->view('admin/laporan_rekap_bulanan_pdf', $data);
    }

    // Atau buka di new tab/window
    public function cetak_sisa_cuti()
    {
        // 1. Minta data dari model
        $data['pegawai'] = $this->m_user->get_all_pegawai_with_sisa_cuti();

        // 2. Load view print (bukan PDF)
        $this->load->view('admin/laporan_sisa_cuti_pdf', $data);
    }


}


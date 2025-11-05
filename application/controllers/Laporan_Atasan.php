<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_Atasan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Cek session dan akses superadmin/atasan
        if (!$this->session->userdata('id_user') || $this->session->userdata('id_user_level') != 3) {
            redirect('Auth');
        }
        $this->load->model('m_cuti');
    }

    public function laporan_bulanan()
    {
        $id_atasan = $this->session->userdata('id_user');
        $tahun = date('Y'); // Tahun berjalan

        // Ambil data cuti tim berdasarkan id_atasan
        $data['cuti_tim'] = $this->m_cuti->get_cuti_tim_bulanan($id_atasan, $tahun);
        $data['tahun'] = $tahun;
        $data['atasan'] = $this->db->get_where('view_user_complete', ['id_user' => $id_atasan])->row_array();

        $this->load->view('super_admin/laporan_bulanan', $data);
    }

    public function laporan_tahunan_csv()
    {
        // Cek session
        if (!$this->session->userdata('id_user')) {
            redirect('Auth');
        }

        $id_atasan = $this->session->userdata('id_user');
        $tahun = date('Y');

        // Load model dan data
        $this->load->model('m_cuti');
        $cuti_tim = $this->m_cuti->get_cuti_tim_tahunan($id_atasan, $tahun);
        $atasan = $this->db->get_where('view_user_complete', ['id_user' => $id_atasan])->row_array();

        // Format nama file dengan nama Superadmin
        $nama_atasan = isset($atasan['nama_lengkap']) ? $atasan['nama_lengkap'] : 'Superadmin';

        // Bersihkan nama untuk filename (hapus spasi dan karakter khusus)
        $nama_file = preg_replace('/[^A-Za-z0-9_-]/', '_', $nama_atasan);
        $filename = 'Laporan_Cuti_Tim_Tahunan_' . $nama_file . '_' . $tahun . '.csv';

        // Set headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // BOM untuk UTF-8
        fputs($output, "\xEF\xBB\xBF");

        // Header judul
        fputcsv($output, ['KEMENTERIAN PERTAHANAN'], ';');
        fputcsv($output, ['LAPORAN CUTI TIM TAHUNAN'], ';');
        fputcsv($output, ['PERIODE TAHUN ' . $tahun], ';');
        fputcsv($output, ['ATASAN: ' . $nama_atasan], ';');
        fputcsv($output, [''], ';'); // Baris kosong

        // Header tabel
        fputcsv($output, [
            'NO',
            'NAMA LENGKAP',
            'NIP/NRP',
            'JENIS CUTI',
            'TANGGAL MULAI',
            'TANGGAL BERAKHIR',
            'LAMA CUTI',
            'STATUS CUTI',
            'TANGGAL PENGAJUAN',
            'ALASAN CUTI'
        ], ';');

        // Data
        $no = 1;
        foreach ($cuti_tim as $cuti) {
            fputcsv($output, [
                $no++,
                $cuti['nama_lengkap'],
                '"' . $cuti['nip'] . '"', // Pakai quotes untuk NIP
                $cuti['nama_cuti'],
                date('d/m/Y', strtotime($cuti['mulai'])),
                date('d/m/Y', strtotime($cuti['berakhir'])),
                $cuti['lama_cuti'] . ' hari',
                $cuti['status_cuti'],
                date('d/m/Y', strtotime($cuti['tgl_diajukan'])),
                '"' . $cuti['alasan'] . '"' // Pakai quotes untuk alasan
            ], ';');
        }

        // Footer
        if (!empty($cuti_tim)) {
            fputcsv($output, [''], ';');
            fputcsv($output, ['Mengetahui,'], ';');
            fputcsv($output, ['Atasan'], ';');
            fputcsv($output, [''], ';');
            fputcsv($output, [$nama_atasan], ';');
            fputcsv($output, ['NIP. ' . (isset($atasan['nip']) ? $atasan['nip'] : '')], ';');
        }

        fclose($output);
        exit;
    }

    public function laporan_statistik()
    {
        // Cek session
        if (!$this->session->userdata('id_user')) {
            redirect('Auth');
        }

        $id_atasan = $this->session->userdata('id_user');
        $tahun = date('Y');

        // Load model
        $this->load->model('m_cuti');

        // Data atasan
        $data['atasan'] = $this->db->get_where('view_user_complete', ['id_user' => $id_atasan])->row_array();

        // Total anggota tim
        $data['total_anggota'] = $this->m_cuti->get_total_anggota_tim($id_atasan);

        // Statistik umum
        $data['statistik'] = $this->m_cuti->get_statistik_cuti_tim($id_atasan, $tahun);

        // Data bulanan
        $data['monthly_data'] = $this->m_cuti->get_cuti_per_bulan($id_atasan, $tahun);

        // Data per jenis cuti
        $data['jenis_cuti_stats'] = $this->m_cuti->get_statistik_jenis_cuti($id_atasan, $tahun);

        $this->load->view('super_admin/laporan_statistik', $data);
    }

    public function cetak_daftar_tim()
    {
        // Cek session
        if (!$this->session->userdata('id_user')) {
            redirect('Auth');
        }

        $id_atasan = $this->session->userdata('id_user');

        // Data atasan
        $data['atasan'] = $this->db->get_where('view_user_complete', ['id_user' => $id_atasan])->row_array();

        // Data anggota tim
        $data['anggota_tim'] = $this->get_anggota_tim($id_atasan);

        // Total anggota
        $data['total_anggota'] = count($data['anggota_tim']);

        $this->load->view('super_admin/laporan_daftar_tim', $data);
    }

    private function get_anggota_tim($id_atasan)
    {
        $this->db->select('
        ud.id_user_detail,
        ud.nama_lengkap,
        ud.nip,
        ud.no_telp as no_telepon,
        ud.alamat,
        ud.pangkat,
        ud.jabatan,
        ud.sisa_cuti,
        ud.jatah_cuti,
        ud.tahun_cuti,
        jk.jenis_kelamin,
        u.created_at as tgl_masuk,
        DATEDIFF(CURDATE(), u.created_at) as masa_kerja_hari,
        u.id_user
    ');
        $this->db->from('user u');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail');
        $this->db->join('jenis_kelamin jk', 'ud.id_jenis_kelamin = jk.id_jenis_kelamin', 'left');
        $this->db->where('u.id_atasan', $id_atasan);
        $this->db->where('u.id_user_level', 1); // Hanya pegawai
        $this->db->where('u.is_active', 1); // Hanya yang aktif
        $this->db->order_by('ud.nama_lengkap', 'ASC');

        return $this->db->get()->result_array();
    }
}
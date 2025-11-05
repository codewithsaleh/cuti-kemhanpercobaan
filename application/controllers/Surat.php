<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Surat extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_cuti');
        // Cek login
        if (!$this->session->userdata('id_user')) {
            redirect('login');
        }
    }

    public function cetak_surat($id_cuti)
    {

        $this->load->helper('terbilang');
        $this->load->helper('date');
        
        
        // Ambil data cuti berdasarkan ID
        $this->db->select('
            c.*,
            ud.nama_lengkap,
            ud.nip,
            ud.pangkat,
            ud.jabatan,
            jc.nama_cuti,
            DATEDIFF(c.berakhir, c.mulai) + 1 as lama_cuti
        ');
        $this->db->from('cuti c');
        $this->db->join('user u', 'c.id_user = u.id_user');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail');
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti');
        $this->db->where('c.id_cuti', $id_cuti);
        $this->db->where('u.id_user', $this->session->userdata('id_user')); // Hanya bisa akses data sendiri

        $data['cuti'] = $this->db->get()->row_array();

        if (!$data['cuti']) {
            show_404();
        }

        // Format tanggal Indonesia
        $this->load->helper('date');
        $data['tanggal_surat'] = date_indonesia($data['cuti']['approved_at'] ?? date('Y-m-d'));
        $data['tanggal_mulai'] = date_indonesia($data['cuti']['mulai']);
        $data['tanggal_berakhir'] = date_indonesia($data['cuti']['berakhir']);
        $data['tahun_pengajuan'] = date('Y', strtotime($data['cuti']['tgl_diajukan']));

        $data['title'] = 'Surat Cuti Pegawai - ' . $data['cuti']['nip'];

        $this->load->view('pegawai/surat_cuti_pdf', $data);
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_cuti extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function view_pegawai() {
        // Cek session pegawai
        if (!$this->session->userdata('id_user') || $this->session->userdata('id_user_level') != 1) {
            redirect('Auth');
        }

        // Ambil data user yang login
        $data['user'] = $this->db->get_where('view_user_complete', 
            ['id_user' => $this->session->userdata('id_user')])->row_array();

        // Query data cuti pegawai tanpa pegawai_user.id_atasan
        $this->db->select('
            c.*,
            jc.nama_cuti,
            sc.status_cuti,
            sc.color_class,
            ud.nama_lengkap,
            ud.nip,
            DATEDIFF(c.berakhir, c.mulai) + 1 as jumlah_hari
        ');
        $this->db->from('cuti c');
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti', 'left');
        $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti', 'left');
        $this->db->join('user u', 'c.id_user = u.id_user', 'left');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
        $this->db->where('c.id_user', $this->session->userdata('id_user'));
        $this->db->order_by('c.tgl_diajukan', 'DESC');
        $data['cuti'] = $this->db->get()->result_array();

        $this->load->view('pegawai/data_cuti', $data);
    }
}

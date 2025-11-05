<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form_Cuti extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
    }

    public function add_cuti() {
        // Cek session pegawai
        if (!$this->session->userdata('id_user') || $this->session->userdata('id_user_level') != 1) {
            redirect('Auth');
        }

        if ($this->input->method() === 'post') {
            // Proses form submission
            $this->_process_form();
        } else {
            // Tampilkan form
            $this->_show_form();
        }
    }

    private function _show_form() {
        // Ambil data user yang login
        $user = $this->db->get_where('view_user_complete', 
            ['id_user' => $this->session->userdata('id_user')])->row_array();

        if (!$user) {
            // Fallback jika view tidak ada
            $this->db->select('u.*, ud.*');
            $this->db->from('user u');
            $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
            $this->db->where('u.id_user', $this->session->userdata('id_user'));
            $user = $this->db->get()->row_array();
        }

        // Data pegawai untuk form
        $data['pegawai'] = [
            'id' => $user['id_user'] ?? '',
            'nip' => $user['nip'] ?? '',
            'nama' => $user['nama_lengkap'] ?? '',
            'unit' => $user['jabatan'] ?? ''
        ];

        $data['sisa_cuti'] = $user['sisa_cuti'] ?? 12;

        // Data jenis cuti dari database
        $jenis_cuti_db = $this->db->get_where('jenis_cuti', ['is_active' => 1])->result_array();
        $data['jenis_cuti_list'] = [];
        foreach ($jenis_cuti_db as $jc) {
            $data['jenis_cuti_list'][$jc['id_jenis_cuti']] = $jc['nama_cuti'];
        }

        // Data atasan - ambil admin dan superadmin
        $this->db->select('u.id_user, ud.nama_lengkap, ud.jabatan, ul.user_level');
        $this->db->from('user u');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
        $this->db->join('user_level ul', 'u.id_user_level = ul.id_user_level', 'left');
        $this->db->where_in('u.id_user_level', [2, 3]); // Admin dan Super Admin
        $this->db->where('u.is_active', 1);
        $atasan_list = $this->db->get()->result_array();

        $data['atasan_options'] = ['' => '-- Pilih Atasan Langsung --'];
        foreach ($atasan_list as $atasan) {
            $data['atasan_options'][$atasan['id_user']] = $atasan['nama_lengkap'] . ' (' . $atasan['user_level'] . ')';
        }

        // URL dan action
        $data['action'] = base_url('Form_Cuti/add_cuti');
        $data['back_url'] = base_url('Cuti/view_pegawai');

        $this->load->view('pegawai/form_cuti', $data);
    }

    private function _process_form() {
        // Validasi form
        $this->form_validation->set_rules('jenis_cuti', 'Jenis Cuti', 'required');
        $this->form_validation->set_rules('tanggal_mulai', 'Tanggal Mulai', 'required');
        $this->form_validation->set_rules('tanggal_selesai', 'Tanggal Selesai', 'required');
        $this->form_validation->set_rules('alasan', 'Alasan', 'required|trim');
        $this->form_validation->set_rules('alamat_cuti', 'Alamat Cuti', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            $this->_show_form();
            return;
        }

        // Proses data
        $this->db->trans_start();

        // Generate ID cuti
        $id_cuti = 'cuti-' . substr(md5(uniqid()), 0, 5);

        // Ambil data jenis cuti
        $jenis_cuti = $this->db->get_where('jenis_cuti', 
            ['id_jenis_cuti' => $this->input->post('jenis_cuti')])->row_array();

        // Data cuti
        $cuti_data = [
            'id_cuti' => $id_cuti,
            'id_user' => $this->session->userdata('id_user'),
            'id_jenis_cuti' => $this->input->post('jenis_cuti'),
            'alasan' => $this->input->post('alasan'),
            'tgl_diajukan' => date('Y-m-d'),
            'mulai' => $this->input->post('tanggal_mulai'),
            'berakhir' => $this->input->post('tanggal_selesai'),
            'id_status_cuti' => 1, // Menunggu konfirmasi
            'perihal_cuti' => $jenis_cuti['nama_cuti'] ?? 'Pengajuan Cuti',
            'tujuan' => $this->input->post('alamat_cuti'),
            'berkendaraan' => $this->input->post('berkendaraan') ?? 'Tidak disebutkan',
            'pengikut' => $this->input->post('pengikut') ?? 'Tidak ada',
            'keperluan' => $this->input->post('alasan')
        ];

        // Insert ke database
        $this->db->insert('cuti', $cuti_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal mengajukan cuti!');
        } else {
            $this->session->set_flashdata('success', 'Pengajuan cuti berhasil disubmit!');
        }

        redirect('Cuti/view_pegawai');
    }

    // Method untuk halaman yang sama dengan alias berbeda
    public function view_pegawai() {
        $this->add_cuti();
    }

    public function form_pengajuan() {
        $this->add_cuti();
    }
}

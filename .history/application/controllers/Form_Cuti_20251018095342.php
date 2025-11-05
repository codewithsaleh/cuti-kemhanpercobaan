<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form_Cuti extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function add_cuti() {
        // Cek session pegawai
        if (!$this->session->userdata('id_user') || $this->session->userdata('id_user_level') != 1) {
            redirect('Auth');
        }

        // Ambil data jenis cuti untuk dropdown
        $this->db->select('*');
        $this->db->from('jenis_cuti');
        $this->db->where('is_active', 1);
        $this->db->order_by('nama_cuti', 'ASC');
        $data['jenis_cuti_data'] = $this->db->get()->result_array();

        // Ambil data user yang login
        $data['user'] = $this->db->get_where('view_user_complete', 
            ['id_user' => $this->session->userdata('id_user')])->row_array();

        // Load view form_pengajuan_cuti.php yang sudah ada
        $this->load->view('pegawai/form_pengajuan_cuti', $data);
    }

    public function proses_cuti() {
        // Cek session pegawai
        if (!$this->session->userdata('id_user') || $this->session->userdata('id_user_level') != 1) {
            redirect('Auth');
        }

        if ($this->input->method() === 'post') {
            $this->load->library('form_validation');
            
            // Validasi input
            $this->form_validation->set_rules('id_jenis_cuti', 'Jenis Cuti', 'required');
            $this->form_validation->set_rules('perihal_cuti', 'Perihal Cuti', 'required|trim');
            $this->form_validation->set_rules('alasan', 'Alasan', 'required|trim');
            $this->form_validation->set_rules('mulai', 'Tanggal Mulai', 'required');
            $this->form_validation->set_rules('berakhir', 'Tanggal Berakhir', 'required');

            // Validasi tanggal
            $mulai = $this->input->post('mulai');
            $berakhir = $this->input->post('berakhir');
            
            if (strtotime($mulai) > strtotime($berakhir)) {
                $this->session->set_flashdata('eror_input', 'Tanggal mulai tidak boleh lebih besar dari tanggal berakhir!');
                redirect('Form_Cuti/add_cuti');
                return;
            }

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('eror_input', validation_errors());
                redirect('Form_Cuti/add_cuti');
            } else {
                $this->db->trans_start();

                // Generate ID cuti unik
                $id_cuti = 'cuti-' . substr(md5(uniqid() . time()), 0, 5);

                // Data cuti
                $cuti_data = [
                    'id_cuti' => $id_cuti,
                    'id_user' => $this->session->userdata('id_user'),
                    'id_jenis_cuti' => $this->input->post('id_jenis_cuti'),
                    'alasan' => $this->input->post('alasan'),
                    'tgl_diajukan' => date('Y-m-d'),
                    'mulai' => $this->input->post('mulai'),
                    'berakhir' => $this->input->post('berakhir'),
                    'id_status_cuti' => 1, // Menunggu konfirmasi
                    'perihal_cuti' => $this->input->post('perihal_cuti'),
                    'created_at' => date('Y-m-d H:i:s')
                ];

                // Insert ke database
                $this->db->insert('cuti', $cuti_data);

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('eror_input', 'Gagal mengajukan cuti! Silakan coba lagi.');
                } else {
                    $this->session->set_flashdata('input', 'Permohonan cuti berhasil diajukan!');
                }

                redirect('Form_Cuti/add_cuti');
            }
        } else {
            redirect('Form_Cuti/add_cuti');
        }
    }

    // ...existing code...
}

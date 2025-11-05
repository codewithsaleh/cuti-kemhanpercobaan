<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form_Cuti extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function view_pegawai() {
        // Cek session pegawai
        if (!$this->session->userdata('id_user') || $this->session->userdata('id_user_level') != 1) {
            redirect('Auth');
        }

        // Ambil data jenis cuti untuk form
        $data['jenis_cuti_data'] = $this->db->get_where('jenis_cuti', ['is_active' => 1])->result_array();
        
        // Ambil data user yang login
        $data['user'] = $this->db->get_where('view_user_complete', 
            ['id_user' => $this->session->userdata('id_user')])->row_array();

        // Load view form pengajuan cuti yang sudah ada
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
            $this->form_validation->set_rules('mulai', 'Tanggal Mulai', 'required');
            $this->form_validation->set_rules('berakhir', 'Tanggal Berakhir', 'required');
            $this->form_validation->set_rules('alasan', 'Alasan', 'required|trim');
            $this->form_validation->set_rules('perihal_cuti', 'Perihal Cuti', 'required|trim');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('eror_input', 'Data tidak lengkap atau tidak valid!');
                redirect('Form_Cuti/view_pegawai');
                return;
            }

            // Validasi tanggal
            $mulai = $this->input->post('mulai');
            $berakhir = $this->input->post('berakhir');
            
            if (strtotime($mulai) > strtotime($berakhir)) {
                $this->session->set_flashdata('eror_input', 'Tanggal mulai tidak boleh lebih besar dari tanggal berakhir!');
                redirect('Form_Cuti/view_pegawai');
                return;
            }

            // Hitung jumlah hari cuti
            $jumlah_hari = (strtotime($berakhir) - strtotime($mulai)) / (60 * 60 * 24) + 1;

            // Cek sisa cuti pegawai
            $user_data = $this->db->get_where('view_user_complete', 
                ['id_user' => $this->session->userdata('id_user')])->row_array();
            
            if ($jumlah_hari > ($user_data['sisa_cuti'] ?? 0)) {
                $this->session->set_flashdata('eror_input', 'Sisa cuti Anda tidak mencukupi! Sisa cuti: ' . ($user_data['sisa_cuti'] ?? 0) . ' hari');
                redirect('Form_Cuti/view_pegawai');
                return;
            }

            $this->db->trans_start();

            // Generate ID cuti
            $id_cuti = 'cuti-' . substr(md5(uniqid()), 0, 5);

            // Data cuti
            $cuti_data = [
                'id_cuti' => $id_cuti,
                'id_user' => $this->session->userdata('id_user'),
                'id_jenis_cuti' => $this->input->post('id_jenis_cuti'),
                'alasan' => $this->input->post('alasan'),
                'tgl_diajukan' => date('Y-m-d'),
                'mulai' => $mulai,
                'berakhir' => $berakhir,
                'id_status_cuti' => 1, // Menunggu konfirmasi
                'perihal_cuti' => $this->input->post('perihal_cuti'),
                'tujuan' => $this->input->post('tujuan') ?: '',
                'berkendaraan' => $this->input->post('berkendaraan') ?: '',
                'pengikut' => $this->input->post('pengikut') ?: '',
                'keperluan' => $this->input->post('keperluan') ?: $this->input->post('alasan')
            ];

            // Insert ke database
            if ($this->db->insert('cuti', $cuti_data)) {
                $this->db->trans_complete();
                
                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('eror_input', 'Gagal menyimpan data cuti!');
                } else {
                    $this->session->set_flashdata('input', 'Pengajuan cuti berhasil disubmit!');
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('eror_input', 'Gagal mengajukan cuti!');
            }

            redirect('Form_Cuti/view_pegawai');
        } else {
            redirect('Form_Cuti/view_pegawai');
        }
    }

    // Method alias untuk routing yang berbeda
    public function add_cuti() {
        $this->view_pegawai();
    }

    public function form_cuti() {
        $this->view_pegawai();
    }
}

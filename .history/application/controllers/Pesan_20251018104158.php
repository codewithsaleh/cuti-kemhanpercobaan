<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pesan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        
        if ($this->session->userdata('logged_in') != true) {
            $this->session->set_flashdata('loggin_err', 'loggin_err');
            redirect('Login/index');
        }

        $this->load->model('m_pesan');
        $this->load->model('m_user');
    }

    // =================================================================
    // FUNGSI UNTUK ADMIN
    // =================================================================

    public function view_admin()
    {
        if ($this->session->userdata('id_user_level') != 2) {
            redirect('Dashboard/dashboard_pegawai');
        }
        // [MODIFIKASI] Tidak perlu mengirim daftar pegawai lagi, karena akan dimuat via AJAX
        $this->load->view('admin/pesan_view');
    }

    // [FUNGSI BARU] Endpoint AJAX untuk mengambil daftar percakapan
    public function get_user_list_admin()
    {
        if ($this->session->userdata('id_user_level') != 2) {
            $this->output->set_status_header(403);
            return;
        }

        $admin_id = $this->session->userdata('id_user');
        $conversations = $this->m_pesan->get_conversations_admin($admin_id);

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($conversations));
    }

    public function get_chat_history_admin($pegawai_id)
    {
        if ($this->session->userdata('id_user_level') != 2) {
            $this->output->set_status_header(403);
            return;
        }

        $admin_id = $this->session->userdata('id_user');

        // [BARU] Tandai semua pesan dari pegawai ini sebagai sudah dibaca
        $this->m_pesan->mark_as_read($admin_id, $pegawai_id);

        $chat_history = $this->m_pesan->get_percakapan($admin_id, $pegawai_id);

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($chat_history));
    }

    public function send_message_admin()
    {
        if ($this->session->userdata('id_user_level') != 2) {
            $this->output->set_status_header(403);
            return;
        }

        $penerima_id = $this->input->post('penerima_id');
        $isi_pesan = $this->input->post('isi_pesan');
        $pengirim_id = $this->session->userdata('id_user');

        if (!empty($penerima_id) && !empty($isi_pesan)) {
            $this->m_pesan->simpan_pesan($pengirim_id, $penerima_id, $isi_pesan);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Penerima atau isi pesan tidak boleh kosong.']);
        }
    }
    
    public function tarik_pesan_admin($id_pesan)
    {
        if ($this->session->userdata('id_user_level') != 2) {
            $this->output->set_status_header(403);
            return;
        }

        $id_pengirim = $this->session->userdata('id_user');
        $hasil = $this->m_pesan->tarik_pesan($id_pesan, $id_pengirim);

        if ($hasil) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Pesan tidak dapat ditarik.']);
        }
    }

    // =================================================================
    // FUNGSI UNTUK PEGAWAI (Tidak diubah untuk saat ini)
    // =================================================================

    public function view_pegawai()
    {
        if ($this->session->userdata('id_user_level') != 1) {
            redirect('Dashboard/dashboard_admin');
        }

        $data['admin'] = $this->m_user->get_admin_for_chat();
        $this->load->view('pegawai/pesan_view', $data);
    }

    public function get_chat_history_pegawai($admin_id)
    {
        if ($this->session->userdata('id_user_level') != 1) {
            $this->output->set_status_header(403);
            return;
        }

        $pegawai_id = $this->session->userdata('id_user');
        $chat_history = $this->m_pesan->get_percakapan($pegawai_id, $admin_id);

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($chat_history));
    }

    public function send_message_pegawai()
    {
        if ($this->session->userdata('id_user_level') != 1) {
            $this->output->set_status_header(403);
            return;
        }

        $penerima_id = $this->input->post('penerima_id');
        $isi_pesan = $this->input->post('isi_pesan');
        $pengirim_id = $this->session->userdata('id_user');

        if (!empty($penerima_id) && !empty($isi_pesan)) {
            $this->m_pesan->simpan_pesan($pengirim_id, $penerima_id, $isi_pesan);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Penerima atau isi pesan tidak boleh kosong.']);
        }
    }

    public function tarik_pesan_pegawai($id_pesan)
    {
        if ($this->session->userdata('id_user_level') != 1) {
            $this->output->set_status_header(403);
            return;
        }

        $id_pengirim = $this->session->userdata('id_user');
        $hasil = $this->m_pesan->tarik_pesan($id_pesan, $id_pengirim);

        if ($hasil) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Pesan tidak dapat ditarik.']);
        }
    }
}


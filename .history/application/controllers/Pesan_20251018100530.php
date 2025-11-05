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
        $this->load->library('session');
    }

    // =================================================================
    // FUNGSI UNTUK ADMIN
    // =================================================================

    public function view_admin() {
        // Cek session admin
        if (!$this->session->userdata('id_user') || $this->session->userdata('id_user_level') != 2) {
            redirect('Auth');
        }

        // Ambil data user yang login
        $data['user'] = $this->db->get_where('view_user_complete', 
            ['id_user' => $this->session->userdata('id_user')])->row_array();

        // Ambil daftar kontak
        $this->db->select('*');
        $this->db->from('view_list_kontak_chat');
        $this->db->where('id_user !=', $this->session->userdata('id_user'));
        $this->db->where('is_active', 1);
        $this->db->order_by('last_message_time', 'DESC');
        $data['contacts'] = $this->db->get()->result_array();

        // Ambil percakapan menggunakan query manual jika view error
        $selected_user_id = $this->input->get('with');
        if ($selected_user_id) {
            // Query manual tanpa view
            $this->db->select('
                p.*,
                pengirim.username as username_pengirim,
                pengirim_detail.nama_lengkap as nama_pengirim,
                penerima.username as username_penerima,
                penerima_detail.nama_lengkap as nama_penerima
            ');
            $this->db->from('percakapan p');
            $this->db->join('user pengirim', 'p.id_user_pengirim = pengirim.id_user', 'left');
            $this->db->join('user_detail pengirim_detail', 'pengirim.id_user_detail = pengirim_detail.id_user_detail', 'left');
            $this->db->join('user penerima', 'p.id_user_penerima = penerima.id_user', 'left');
            $this->db->join('user_detail penerima_detail', 'penerima.id_user_detail = penerima_detail.id_user_detail', 'left');
            $this->db->where('(p.id_user_pengirim = "' . $this->session->userdata('id_user') . '" AND p.id_user_penerima = "' . $selected_user_id . '")');
            $this->db->or_where('(p.id_user_pengirim = "' . $selected_user_id . '" AND p.id_user_penerima = "' . $this->session->userdata('id_user') . '")');
            $this->db->order_by('p.created_at', 'ASC');
            $data['messages'] = $this->db->get()->result_array();
            
            // Ambil info user yang dipilih
            $data['selected_user'] = $this->db->get_where('view_user_complete', 
                ['id_user' => $selected_user_id])->row_array();
        } else {
            $data['messages'] = [];
            $data['selected_user'] = null;
        }

        $this->load->view('admin/pesan', $data);
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

    public function view_pegawai() {
        // Cek session pegawai
        if (!$this->session->userdata('id_user') || $this->session->userdata('id_user_level') != 1) {
            redirect('Auth');
        }

        // Ambil data user yang login
        $data['user'] = $this->db->get_where('view_user_complete', 
            ['id_user' => $this->session->userdata('id_user')])->row_array();

        // Ambil daftar admin untuk chat (level 2 dan 3)
        $this->db->select('
            u.id_user,
            u.username,
            u.email,
            u.is_active,
            ud.nama_lengkap,
            ud.nip,
            ud.jabatan,
            ud.foto,
            ul.user_level
        ');
        $this->db->from('user u');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
        $this->db->join('user_level ul', 'u.id_user_level = ul.id_user_level', 'left');
        $this->db->where_in('u.id_user_level', [2, 3]); // Admin dan Super Admin
        $this->db->where('u.is_active', 1);
        $this->db->order_by('ud.nama_lengkap', 'ASC');
        $data['contacts'] = $this->db->get()->result_array();

        // Ambil percakapan jika ada ID user yang dipilih
        $selected_user_id = $this->input->get('with');
        if ($selected_user_id) {
            // Query manual untuk percakapan
            $this->db->select('
                p.*,
                pengirim.username as username_pengirim,
                pengirim_detail.nama_lengkap as nama_pengirim,
                penerima.username as username_penerima,
                penerima_detail.nama_lengkap as nama_penerima
            ');
            $this->db->from('percakapan p');
            $this->db->join('user pengirim', 'p.id_user_pengirim = pengirim.id_user', 'left');
            $this->db->join('user_detail pengirim_detail', 'pengirim.id_user_detail = pengirim_detail.id_user_detail', 'left');
            $this->db->join('user penerima', 'p.id_user_penerima = penerima.id_user', 'left');
            $this->db->join('user_detail penerima_detail', 'penerima.id_user_detail = penerima_detail.id_user_detail', 'left');
            $this->db->where('(p.id_user_pengirim = "' . $this->session->userdata('id_user') . '" AND p.id_user_penerima = "' . $selected_user_id . '")');
            $this->db->or_where('(p.id_user_pengirim = "' . $selected_user_id . '" AND p.id_user_penerima = "' . $this->session->userdata('id_user') . '")');
            $this->db->order_by('p.created_at', 'ASC');
            $data['messages'] = $this->db->get()->result_array();
            
            // Ambil info user yang dipilih
            $data['selected_user'] = $this->db->get_where('view_user_complete', 
                ['id_user' => $selected_user_id])->row_array();
        } else {
            $data['messages'] = [];
            $data['selected_user'] = null;
        }

        // Load view pesan pegawai yang sudah ada
        $this->load->view('pegawai/pesan', $data);
    }

    public function send_message() {
        // Cek session
        if (!$this->session->userdata('id_user')) {
            echo json_encode(['status' => 'error', 'message' => 'Session expired']);
            return;
        }

        $penerima = $this->input->post('penerima');
        $pesan = $this->input->post('pesan');

        if (empty($penerima) || empty($pesan)) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
            return;
        }

        $data = [
            'id_percakapan' => 'msg_' . uniqid(),
            'id_user_pengirim' => $this->session->userdata('id_user'),
            'id_user_penerima' => $penerima,
            'pesan' => $pesan,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->db->insert('percakapan', $data)) {
            echo json_encode(['status' => 'success', 'message' => 'Pesan terkirim']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan']);
        }
    }

    // Method alias untuk routing
    public function pegawai() {
        $this->view_pegawai();
    }

    public function mark_as_read() {
        $user_id = $this->input->post('user_id');
        
        $this->db->where('id_user_pengirim', $user_id);
        $this->db->where('id_user_penerima', $this->session->userdata('id_user'));
        $this->db->where('is_read', 0);
        $this->db->update('percakapan', ['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')]);

        echo json_encode(['status' => 'success']);
    }
}


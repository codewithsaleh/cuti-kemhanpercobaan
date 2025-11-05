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

        // Ambil daftar kontak tanpa view (query manual)
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
        $this->db->where('u.id_user !=', $this->session->userdata('id_user'));
        $this->db->where('u.is_active', 1);
        $this->db->order_by('ud.nama_lengkap', 'ASC');
        $data['contacts'] = $this->db->get()->result_array();

        // Ambil percakapan jika ada ID user yang dipilih
        $selected_user_id = $this->input->get('with');
        if ($selected_user_id) {
            $this->db->select('*');
            $this->db->from('view_percakapan');
            $this->db->where('(id_user_pengirim = "' . $this->session->userdata('id_user') . '" AND id_user_penerima = "' . $selected_user_id . '")');
            $this->db->or_where('(id_user_pengirim = "' . $selected_user_id . '" AND id_user_penerima = "' . $this->session->userdata('id_user') . '")');
            $this->db->order_by('created_at', 'ASC');
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

    public function mark_as_read() {
        $user_id = $this->input->post('user_id');
        
        $this->db->where('id_user_pengirim', $user_id);
        $this->db->where('id_user_penerima', $this->session->userdata('id_user'));
        $this->db->where('is_read', 0);
        $this->db->update('percakapan', ['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')]);

        echo json_encode(['status' => 'success']);
    }
}


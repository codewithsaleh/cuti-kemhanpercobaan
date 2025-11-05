<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->helper('url'); 
    }

    public function index()
    {
        $this->load->view('login');
    }

    public function proses()
    {
        $username = $this->input->post("username");
        $password = $this->input->post("password");

        $user = $this->m_user->cek_login($username);

        if ($user->num_rows() > 0) {
            $user = $user->row_array();

            // **PERBAIKAN: Gunakan password_verify() untuk cek password**
            if (password_verify($password, $user['password'])) {

                // Jika yang login adalah PEGAWAI (Level 1)
                if ($user['id_user_level'] == 1) {

                    $this->session->set_userdata('logged_in', true);
                    $this->session->set_userdata('id_user', $user['id_user']);
                    $this->session->set_userdata('username', $user['username']);
                    $this->session->set_userdata('id_user_level', $user['id_user_level']);
                    $this->session->set_userdata('nama_lengkap', $user['nama_lengkap']);

                    $this->session->set_flashdata('success_login', 'success_login');
                    redirect('Dashboard/dashboard_pegawai');

                    // Jika yang login adalah ADMIN (Level 2)
                } else if ($user['id_user_level'] == 2) {

                    $this->session->set_userdata('logged_in', true);
                    $this->session->set_userdata('id_user', $user['id_user']);
                    $this->session->set_userdata('username', $user['username']);
                    $this->session->set_userdata('id_user_level', $user['id_user_level']);
                    // [PENAMBAHAN] Menyimpan nama lengkap Admin di session
                    $this->session->set_userdata('nama_lengkap', $user['nama_lengkap']);

                    $this->session->set_flashdata('success_login', 'success_login');
                    redirect('Dashboard/dashboard_admin');

                    // Jika yang login adalah ATASAN (Level 3)
                } else if ($user['id_user_level'] == 3) {

                    $this->session->set_userdata('logged_in', true);
                    $this->session->set_userdata('id_user', $user['id_user']);
                    $this->session->set_userdata('username', $user['username']);
                    $this->session->set_userdata('id_user_level', $user['id_user_level']);
                    // [PENAMBAHAN] Menyimpan nama lengkap Atasan di session
                    $this->session->set_userdata('nama_lengkap', $user['nama_lengkap']);

                    $this->session->set_flashdata('success_login', 'success_login');
                    // [PERUBAHAN] Mengarahkan Atasan ke controller khusus: Atasan/dashboard
                    redirect('Atasan/dashboard');

                } else {
                    $this->session->set_flashdata('loggin_err', 'loggin_err');
                    redirect('Login/index');
                }

            } else {
                $this->session->set_flashdata('loggin_err_pass', 'loggin_err_pass');
                redirect('Login/index');
            }
        } else {
            $this->session->set_flashdata('loggin_err_no_user', 'loggin_err_no_user');
            redirect('Login/index');
        }
    }

    public function log_out(){
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('id_user');
        $this->session->set_flashdata('success_log_out','success_log_out');
            redirect('Login/index');
    }

    // ... Sisa fungsi (lupa_password, reset_password, dll) tetap sama ...
    public function lupa_password() { /* ... kode ... */ }
    public function proses_lupa_password() { /* ... kode ... */ }
    public function reset_password($token) { /* ... kode ... */ }
    public function proses_reset_password() { /* ... kode ... */ }
}

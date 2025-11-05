<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_jenis_kelamin');
        $this->load->model('m_cuti');
    }

    public function view_super_admin()
    {
        if ($this->session->userdata('logged_in') == true AND $this->session->userdata('id_user_level') == 3) {
            $data['pegawai'] = $this->m_user->get_all_pegawai()->result_array();
            $data['jenis_kelamin_p'] = $this->m_jenis_kelamin->get_all_jenis_kelamin()->result_array();
            $this->load->view('super_admin/pegawai', $data);
        } else {
            $this->session->set_flashdata('loggin_err','loggin_err');
            redirect('Login/index');
        }
    }
    
    public function view_admin()
    {
        if ($this->session->userdata('logged_in') == true AND $this->session->userdata('id_user_level') == 2) {
            
            $this->db->select('user.*, user_detail.*, user_level.user_level, atasan_detail.nama_lengkap as nama_atasan');
$this->db->from('user');
$this->db->join('user_detail', 'user.id_user_detail = user_detail.id_user_detail', 'left');
$this->db->join('user_level', 'user.id_user_level = user_level.id_user_level', 'left');
$this->db->join('user_detail as atasan_detail', 'user_detail.id_atasan = atasan_detail.id_user_detail', 'left');
$this->db->where_in('user.id_user_level', [1, 3]);
$this->db->group_by('user.id_user');
$this->db->order_by('user.id_user_level', 'ASC');
$this->db->order_by('user_detail.nama_lengkap', 'ASC');
$data['users'] = $this->db->get()->result_array();

            $data['jenis_kelamin_p'] = $this->m_jenis_kelamin->get_all_jenis_kelamin()->result_array();
            $data['atasan_list'] = $this->m_user->get_all_atasan()->result_array();
            $this->load->view('admin/pegawai', $data);

        } else {
            $this->session->set_flashdata('loggin_err','loggin_err');
            redirect('Login/index');
        }
    }

    public function tambah_pegawai()
    {
        if ($this->session->userdata('logged_in') == true AND $this->session->userdata('id_user_level') == 2) {
            $nip = $this->input->post("nip");
            $username = $nip;
            $password = 'kemhan2025';
            $email = $this->input->post("email");
            $nama_lengkap = $this->input->post("nama_lengkap");
            $id_jenis_kelamin = $this->input->post("id_jenis_kelamin");
            $no_telp = $this->input->post("no_telp");
            $alamat = $this->input->post("alamat");
            $pangkat = $this->input->post("pangkat");
            $jabatan = $this->input->post("jabatan");
            $id_atasan = $this->input->post("id_atasan");
            
            $id_user_level = 1; 
            $id = md5($username.$email.rand());

            $hasil = $this->m_user->insert_pegawai(
                $id, $username, $email, $password, $id_user_level, 
                $nama_lengkap, $id_jenis_kelamin, $no_telp, $alamat, 
                $nip, $pangkat, $jabatan, $id_atasan
            );

            if($hasil==false){
                $this->session->set_flashdata('eror','eror');
            } else {
                $this->session->set_flashdata('input','input');
            }
            redirect('Pegawai/view_admin');
        } else {
            $this->session->set_flashdata('loggin_err','loggin_err');
            redirect('Login/index');
        }
    }

    /**
     * [PERBAIKAN FINAL]
     * Logika 'if' dihapus. Controller sekarang SELALU mengambil semua data dari form,
     * dan membiarkan Model yang pintar untuk memprosesnya. Ini jauh lebih aman.
     */
    public function edit_pegawai()
    {
        if ($this->session->userdata('logged_in') == true AND $this->session->userdata('id_user_level') == 2) {
            
            $id = $this->input->post("id_user");
            $username = $this->input->post("username");
            $password = $this->input->post("password");
            $email = $this->input->post("email");
            $nama_lengkap = $this->input->post("nama_lengkap");
            $nip = $this->input->post("nip");
            $id_jenis_kelamin = $this->input->post("id_jenis_kelamin");
            $no_telp = $this->input->post("no_telp");
            $alamat = $this->input->post("alamat");
            $id_user_level = $this->input->post("id_user_level");
            $pangkat = $this->input->post("pangkat");
            $jabatan = $this->input->post("jabatan");
            $id_atasan = $this->input->post("id_atasan"); // SELALU DIAMBIL

            // Panggil model untuk menyimpan perubahan
            $hasil = $this->m_user->update_pegawai(
                $id, $username, $email, $password, $id_user_level, 
                $nama_lengkap, $id_jenis_kelamin, $no_telp, $alamat, 
                $nip, $pangkat, $jabatan, $id_atasan
            );

            if($hasil==false){
                $this->session->set_flashdata('eror_edit','Data Gagal Diedit!');
            } else {
                $this->session->set_flashdata('edit','Data Berhasil Diedit!');
            }
            redirect('Pegawai/view_admin');
        } else {
            $this->session->set_flashdata('loggin_err','loggin_err');
            redirect('Login/index');
        }
    }

    public function hapus_pegawai()
    {
        if ($this->session->userdata('logged_in') == true AND $this->session->userdata('id_user_level') == 2) {
            $id = $this->input->post("id_user");
            $hasil = $this->m_user->delete_pegawai($id);

            if($hasil==false){
                $this->session->set_flashdata('eror_hapus','eror_hapus');
            } else {
                $this->session->set_flashdata('hapus','hapus');
            }
            redirect('Pegawai/view_admin');
        } else {
            $this->session->set_flashdata('loggin_err','loggin_err');
            redirect('Login/index');
        }
    }
    
    public function proses_reset_cuti_pegawai($id_user) { if ($this->session->userdata('logged_in') == true && ($this->session->userdata('id_user_level') == 2 || $this->session->userdata('id_user_level') == 3)) { $hasil = $this->m_cuti->reset_cuti_by_id_user($id_user); if($hasil){ $this->session->set_flashdata('success_reset', 'Jatah cuti pegawai berhasil direset.'); } else { $this->session->set_flashdata('eror_reset', 'Gagal mereset jatah cuti pegawai.'); } if($this->session->userdata('id_user_level') == 2){ redirect('Pegawai/view_admin'); } else { redirect('Pegawai/view_super_admin'); } } else { redirect('Login/index'); } }
    public function import_pegawai() { if ($this->session->userdata('logged_in') == true AND $this->session->userdata('id_user_level') == 2) { } else { $this->session->set_flashdata('loggin_err','loggin_err'); redirect('Login/index'); } }
    public function proses_reset_password($id_user) { if ($this->session->userdata('logged_in') == true && $this->session->userdata('id_user_level') == 2) { $hasil = $this->m_user->reset_password_by_id($id_user); if($hasil){ $this->session->set_flashdata('success_reset', 'Password pegawai berhasil direset ke default.'); } else { $this->session->set_flashdata('eror_reset', 'Gagal mereset password pegawai.'); } redirect('Pegawai/view_admin'); } else { redirect('Login/index'); } }
    public function add_pegawai() {
        // Cek session admin/superadmin
        if (!$this->session->userdata('id_user') || !in_array($this->session->userdata('id_user_level'), [2, 3])) {
            redirect('Auth');
        }

        if ($this->input->method() === 'post') {
            $this->load->library('form_validation');
            
            // Validasi input
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
            $this->form_validation->set_rules('nip', 'NIP/NRP', 'required|trim|is_unique[user_detail.nip]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user.email]');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('Pegawai/add_pegawai');
            } else {
                $this->db->trans_start();

                // Generate ID
                $id_user_detail = md5(uniqid());
                $id_user = $id_user_detail;

                // Data user_detail
                $user_detail_data = [
                    'id_user_detail' => $id_user_detail,
                    'nama_lengkap' => $this->input->post('nama_lengkap'),
                    'nip' => $this->input->post('nip'),
                    'email' => $this->input->post('email'),
                    'no_telp' => $this->input->post('no_telp'),
                    'pangkat' => $this->input->post('pangkat'),
                    'jabatan' => $this->input->post('jabatan'),
                    'id_jenis_kelamin' => $this->input->post('id_jenis_kelamin') ?: null,
                    'alamat' => $this->input->post('alamat'),
                    'id_atasan' => $this->input->post('id_atasan') ?: null,
                    'sisa_cuti' => 12,
                    'jatah_cuti' => 12,
                    'tahun_cuti' => date('Y')
                ];

                // Data user (NIP sebagai username, password default kemhan2025)
                $user_data = [
                    'id_user' => $id_user,
                    'username' => $this->input->post('nip'), // NIP sebagai username
                    'password' => md5('kemhan2025'), // Password default
                    'email' => $this->input->post('email'),
                    'id_user_level' => 1, // Pegawai
                    'id_user_detail' => $id_user_detail,
                    'is_active' => 1
                ];

                // Insert ke database
                $this->db->insert('user_detail', $user_detail_data);
                $this->db->insert('user', $user_data);

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('error', 'Gagal menambah pegawai!');
                } else {
                    $this->session->set_flashdata('success', 'Pegawai berhasil ditambahkan! Username: ' . $this->input->post('nip') . ', Password: kemhan2025');
                }

                redirect('Pegawai/view_admin');
            }
        } else {
            // Tampilkan form
            $this->load->view('admin/add_pegawai');
        }
    }
}


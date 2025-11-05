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
            
            // Ambil data user lengkap menggunakan query manual untuk menghindari error
            $this->db->select('
                u.id_user,
                u.username,
                u.email,
                u.is_active,
                u.id_user_level,
                ul.user_level,
                ud.id_user_detail,
                ud.nama_lengkap,
                ud.nip,
                ud.pangkat,
                ud.jabatan,
                ud.id_atasan,
                ud.sisa_cuti,
                ud.jatah_cuti,
                ud.tahun_cuti,
                ud.no_telp,
                ud.alamat,
                ud.foto,
                jk.id_jenis_kelamin,
                jk.jenis_kelamin
            ');
            $this->db->from('user u');
            $this->db->join('user_level ul', 'u.id_user_level = ul.id_user_level', 'left');
            $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
            $this->db->join('jenis_kelamin jk', 'ud.id_jenis_kelamin = jk.id_jenis_kelamin', 'left');
            $this->db->where_in('u.id_user_level', [1, 2, 3]);
            $this->db->where('u.is_active', 1);
            $this->db->order_by('u.id_user_level', 'ASC');
            $this->db->order_by('ud.nama_lengkap', 'ASC');
            $data['users'] = $this->db->get()->result_array();

            // Ambil data jenis kelamin untuk form
            $data['jenis_kelamin_p'] = $this->db->get('jenis_kelamin')->result_array();

            // Ambil daftar atasan (admin dan superadmin) untuk form
            $this->db->select('u.id_user, ud.nama_lengkap, ud.jabatan, ul.user_level');
            $this->db->from('user u');
            $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
            $this->db->join('user_level ul', 'u.id_user_level = ul.id_user_level', 'left');
            $this->db->where_in('u.id_user_level', [2, 3]); // Admin dan Super Admin
            $this->db->where('u.is_active', 1);
            $this->db->order_by('ud.nama_lengkap', 'ASC');
            $data['atasan_list'] = $this->db->get()->result_array();

            $this->load->view('admin/pegawai', $data);

        } else {
            $this->session->set_flashdata('loggin_err','loggin_err');
            redirect('Login/index');
        }
    }

    public function tambah_pegawai() {
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
                $this->session->set_flashdata('eror', validation_errors());
                redirect('Pegawai/view_admin');
                return;
            }

            $this->db->trans_start();

            // Generate ID unik
            $id_user_detail = md5(uniqid());
            $id_user = $id_user_detail;

            // Data user_detail
            $user_detail_data = [
                'id_user_detail' => $id_user_detail,
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'nip' => $this->input->post('nip'),
                'pangkat' => $this->input->post('pangkat'),
                'jabatan' => $this->input->post('jabatan'),
                'id_jenis_kelamin' => $this->input->post('id_jenis_kelamin') ?: null,
                'no_telp' => $this->input->post('no_telp'),
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
                $this->session->set_flashdata('eror', 'Gagal menambah pegawai!');
            } else {
                $this->session->set_flashdata('input', 'Pegawai berhasil ditambahkan! Username: ' . $this->input->post('nip') . ', Password: kemhan2025');
            }

            redirect('Pegawai/view_admin');
        }
    }

    /**
     * [PERBAIKAN FINAL]
     * Logika 'if' dihapus. Controller sekarang SELALU mengambil semua data dari form,
     * dan membiarkan Model yang pintar untuk memprosesnya. Ini jauh lebih aman.
     */
    public function edit_pegawai() {
        // Cek session admin/superadmin
        if (!$this->session->userdata('id_user') || !in_array($this->session->userdata('id_user_level'), [2, 3])) {
            redirect('Auth');
        }

        if ($this->input->method() === 'post') {
            $this->load->library('form_validation');
            
            $id_user = $this->input->post('id_user');
            
            // Validasi input
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
            $this->form_validation->set_rules('username', 'Username', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('eror_edit', validation_errors());
                redirect('Pegawai/view_admin');
                return;
            }

            $this->db->trans_start();

            // Update data user
            $user_update = [
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email')
            ];

            // Update password jika diisi
            if (!empty($this->input->post('password'))) {
                $user_update['password'] = md5($this->input->post('password'));
            }

            $this->db->where('id_user', $id_user);
            $this->db->update('user', $user_update);

            // Ambil id_user_detail dari user
            $user_detail_id = $this->db->select('id_user_detail')
                                      ->get_where('user', ['id_user' => $id_user])
                                      ->row_array()['id_user_detail'];

            // Update data user_detail
            $user_detail_update = [
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'nip' => $this->input->post('nip'),
                'pangkat' => $this->input->post('pangkat'),
                'jabatan' => $this->input->post('jabatan'),
                'id_jenis_kelamin' => $this->input->post('id_jenis_kelamin'),
                'no_telp' => $this->input->post('no_telp'),
                'alamat' => $this->input->post('alamat')
            ];

            // Update id_atasan hanya untuk pegawai (level 1)
            if ($this->input->post('id_user_level') == 1) {
                $user_detail_update['id_atasan'] = $this->input->post('id_atasan') ?: null;
            }

            $this->db->where('id_user_detail', $user_detail_id);
            $this->db->update('user_detail', $user_detail_update);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('eror_edit', 'Gagal mengupdate data pegawai!');
            } else {
                $this->session->set_flashdata('edit', 'Data pegawai berhasil diupdate!');
            }

            redirect('Pegawai/view_admin');
        }
    }

    public function hapus_pegawai() {
        // Cek session admin/superadmin
        if (!$this->session->userdata('id_user') || !in_array($this->session->userdata('id_user_level'), [2, 3])) {
            redirect('Auth');
        }

        if ($this->input->method() === 'post') {
            $id_user = $this->input->post('id_user');

            $this->db->trans_start();

            // Hapus user (akan cascade delete user_detail)
            $this->db->where('id_user', $id_user);
            $this->db->delete('user');

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('eror_hapus', 'Gagal menghapus data pegawai!');
            } else {
                $this->session->set_flashdata('hapus', 'Data pegawai berhasil dihapus!');
            }

            redirect('Pegawai/view_admin');
        }
    }

    public function proses_reset_password($id_user) {
        // Cek session admin/superadmin
        if (!$this->session->userdata('id_user') || !in_array($this->session->userdata('id_user_level'), [2, 3])) {
            redirect('Auth');
        }

        // Reset password ke kemhan2025
        $this->db->where('id_user', $id_user);
        $this->db->update('user', ['password' => md5('kemhan2025')]);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('success_reset', 'Password berhasil direset ke "kemhan2025"');
        } else {
            $this->session->set_flashdata('eror_reset', 'Gagal mereset password');
        }

        redirect('Pegawai/view_admin');
    }

    public function proses_reset_cuti_pegawai($id_user) {
        // Cek session admin/superadmin
        if (!$this->session->userdata('id_user') || !in_array($this->session->userdata('id_user_level'), [2, 3])) {
            redirect('Auth');
        }

        // Reset cuti ke 12 hari
        $user_detail_id = $this->db->select('id_user_detail')
                                  ->get_where('user', ['id_user' => $id_user])
                                  ->row_array()['id_user_detail'];

        $this->db->where('id_user_detail', $user_detail_id);
        $this->db->update('user_detail', [
            'sisa_cuti' => 12,
            'jatah_cuti' => 12,
            'tahun_cuti' => date('Y')
        ]);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('success_reset', 'Jatah cuti berhasil direset ke 12 hari');
        } else {
            $this->session->set_flashdata('eror_reset', 'Gagal mereset jatah cuti');
        }

        redirect('Pegawai/view_admin');
    }
}


<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_jenis_kelamin');
        $this->load->model('m_cuti');
        $this->load->model('m_settings');
    }

    // Admin
    public function view_admin()
    {
        if (!check_user_level([2])) {
            return;
        }

        // Load models
        $this->load->model('m_settings');
        $this->load->model('m_cuti');
        $this->load->model('m_jenis_kelamin'); // [BARU] Load model jenis kelamin

        // CEK RESET OTOMATIS JIKA MODE OTOMATIS
        $reset_mode = $this->m_settings->get_setting('reset_mode');
        if ($reset_mode == 'otomatis') {
            $this->m_cuti->check_and_reset_auto();
        }

        $data['reset_mode'] = $reset_mode ?? 'manual';
        $data['last_reset_year'] = $this->m_settings->get_setting('last_reset_year');

        // Ambil data user yang login
        $data['user'] = $this->db->get_where(
            'view_user_complete',
            ['id_user' => $this->session->userdata('id_user')]
        )->row_array();

        // [BARU] Ambil data jenis kelamin untuk dropdown
        $data['jenis_kelamin'] = $this->m_jenis_kelamin->get_all_jenis_kelamin()->result_array();

        $data['title'] = 'Pengaturan Sistem';
        $data['active_menu'] = 'settings';

        $this->load->view('admin/settings', $data);
    }


    public function settings_account_admin()
{
    $id = $this->session->userdata('id_user');
    $current_password = $this->input->post("current_password");
    $password = $this->input->post("password");
    $re_password = $this->input->post("re_password");

    // Validasi password saat ini
    $user = $this->db->get_where('user', ['id_user' => $id])->row_array();
    if (!$user || !password_verify($current_password, $user['password'])) {
        $this->session->set_flashdata('error_password', 'Password saat ini salah!');
        redirect('Settings/view_admin');
    }

    // Validasi konfirmasi password
    if ($password !== $re_password) {
        $this->session->set_flashdata('error_password', 'Konfirmasi password tidak sesuai!');
        redirect('Settings/view_admin');
    }

    // Validasi kekuatan password
    $password_strength = $this->check_password_strength($password, $user['username']);
    if ($password_strength['strength'] !== 'strong') {
        $this->session->set_flashdata('error_password', 'Password harus memenuhi semua kriteria keamanan!');
        redirect('Settings/view_admin');
    }

    // Update password
    $hasil = $this->m_user->update_password_only($id, $password);

    if ($hasil == false) {
        $this->session->set_flashdata('error_password', 'Gagal mengubah password!');
        redirect('Settings/view_admin');
    } else {
        $this->session->set_flashdata('success_password', 'Password berhasil diubah!');
        redirect('Settings/view_admin');
    }
}

    public function lengkapi_data_admin()
    {
        if (!check_user_level([2])) {
            return;
        }

        if ($this->input->method() === 'post') {
            $this->load->library('form_validation');

            // Set validation rules
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
            $this->form_validation->set_rules('id_jenis_kelamin', 'Jenis Kelamin', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
            } else {
                $this->db->trans_start();

                $id_user = $this->input->post('id_user');
                $id_user_detail = $this->input->post('id_user_detail');

                // Gunakan timestamp yang sama untuk kedua tabel
                $current_timestamp = date('Y-m-d H:i:s');

                // Data untuk update user_detail
                $user_detail_data = [
                    'nama_lengkap' => $this->input->post('nama_lengkap'),
                    'nip' => $this->input->post('nip'),
                    'pangkat' => $this->input->post('pangkat'),
                    'jabatan' => $this->input->post('jabatan'),
                    'id_jenis_kelamin' => $this->input->post('id_jenis_kelamin'),
                    'no_telp' => $this->input->post('no_telp'),
                    'alamat' => $this->input->post('alamat'),
                    'updated_at' => $current_timestamp
                ];

                // Data untuk update user
                $user_data = [
                    'email' => $this->input->post('email'),
                    'updated_at' => $current_timestamp
                ];

                // Update ke database
                $this->db->where('id_user_detail', $id_user_detail);
                $this->db->update('user_detail', $user_detail_data);

                $this->db->where('id_user', $id_user);
                $this->db->update('user', $user_data);

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('error_lengkapi', 'Gagal mengupdate data diri!');
                } else {
                    // Update session data jika berhasil
                    $user_session = $this->session->userdata('user_data');
                    if ($user_session) {
                        $user_session['nama_lengkap'] = $user_detail_data['nama_lengkap'];
                        $user_session['email'] = $user_data['email'];
                        $this->session->set_userdata('user_data', $user_session);
                    }

                    $this->session->set_flashdata('success_lengkapi', 'Data diri berhasil diupdate!');
                }
            }

            redirect('Settings/view_admin');
        }
    }    

    // Superadmin
    public function view_super_admin()
    {
        if (!check_user_level([3])) {
            return;
        }

        $id_user = $this->session->userdata('id_user');

        // Ambil data lengkap superadmin termasuk user_detail
        $this->db->select('u.*, ud.*');
        $this->db->from('user u');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail');
        $this->db->where('u.id_user', $id_user);
        $superadmin_data = $this->db->get()->row_array();

        $data['superadmin'] = $superadmin_data;
        $data['username'] = $superadmin_data['username'];
        $data['jenis_kelamin'] = $this->m_jenis_kelamin->get_all_jenis_kelamin()->result_array();

        $this->load->view('super_admin/settings', $data);
    }

    public function settings_account_superadmin()
    {
        $id = $this->session->userdata('id_user');
        $current_password = $this->input->post("current_password");
        $password = $this->input->post("password");
        $re_password = $this->input->post("re_password");

        // Validasi password saat ini
        $user = $this->db->get_where('user', ['id_user' => $id])->row_array();
        if (!$user || !password_verify($current_password, $user['password'])) {
            $this->session->set_flashdata('error', 'Password saat ini salah!');
            redirect('Settings/view_super_admin');
        }

        // Validasi konfirmasi password
        if ($password !== $re_password) {
            $this->session->set_flashdata('error', 'Konfirmasi password tidak sesuai!');
            redirect('Settings/view_super_admin');
        }

        // Validasi kekuatan password
        $password_strength = $this->check_password_strength($password, $user['username']);
        if ($password_strength['strength'] !== 'strong') {
            $this->session->set_flashdata('error_password', 'Password harus memenuhi semua kriteria keamanan!');
            redirect('Settings/view_super_admin');
        }

        // Update password
        $hasil = $this->m_user->update_password_only($id, $password);

        if ($hasil == false) {
            $this->session->set_flashdata('error_password', 'Gagal mengubah password!');
            redirect('Settings/view_super_admin');
        } else {
            $this->session->set_flashdata('success_password', 'Password berhasil diubah!');
            redirect('Settings/view_super_admin');
        }
    }

    public function lengkapi_data_superadmin()
    {
        if (!check_user_level([3])) {
            return;
        }

        if ($this->input->method() === 'post') {
            $this->load->library('form_validation');

            // Set validation rules
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
            $this->form_validation->set_rules('id_jenis_kelamin', 'Jenis Kelamin', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
            } else {
                $this->db->trans_start();

                $id_user = $this->input->post('id_user');
                $id_user_detail = $this->input->post('id_user_detail');

                // Gunakan timestamp yang sama untuk kedua tabel
                $current_timestamp = date('Y-m-d H:i:s');

                // Data untuk update user_detail
                $user_detail_data = [
                    'nama_lengkap' => $this->input->post('nama_lengkap'),
                    'nip' => $this->input->post('nip'),
                    'pangkat' => $this->input->post('pangkat'),
                    'jabatan' => $this->input->post('jabatan'),
                    'id_jenis_kelamin' => $this->input->post('id_jenis_kelamin'),
                    'no_telp' => $this->input->post('no_telp'),
                    'alamat' => $this->input->post('alamat'),
                    'updated_at' => $current_timestamp
                ];

                // Data untuk update user
                $user_data = [
                    'email' => $this->input->post('email'),
                    'updated_at' => $current_timestamp
                ];

                // Update ke database
                $this->db->where('id_user_detail', $id_user_detail);
                $this->db->update('user_detail', $user_detail_data);

                $this->db->where('id_user', $id_user);
                $this->db->update('user', $user_data);

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('eror_lengkapi', 'Gagal mengupdate data diri!');
                } else {
                    // Update session data jika berhasil
                    $user_session = $this->session->userdata('user_data');
                    if ($user_session) {
                        $user_session['nama_lengkap'] = $user_detail_data['nama_lengkap'];
                        $user_session['email'] = $user_data['email'];
                        $this->session->set_userdata('user_data', $user_session);
                    }

                    $this->session->set_flashdata('success_lengkapi', 'Data diri berhasil diupdate!');
                }
            }

            redirect('Settings/view_super_admin');
        }
    }

    // Pegawai
    public function view_pegawai()
    {
        if (!check_user_level([1])) {
            return;
        }

        $id_user = $this->session->userdata('id_user');

        // Ambil data lengkap pegawai termasuk email dari user
        $this->db->select('u.*, ud.*');
        $this->db->from('user u');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail');
        $this->db->where('u.id_user', $id_user);
        $pegawai_data = $this->db->get()->row_array();

        $data['pegawai'] = $pegawai_data;
        $data['jenis_kelamin'] = $this->m_jenis_kelamin->get_all_jenis_kelamin()->result_array();

        $this->load->view('pegawai/settings', $data);
    }

    public function settings_account_pegawai()
    {
        $id = $this->session->userdata('id_user');
        $current_password = $this->input->post("current_password");
        $password = $this->input->post("password");
        $re_password = $this->input->post("re_password");

        // [PERBAIKAN] Ambil data user dan convert ke array
        $user_query = $this->m_user->get_user_by_id($id);
        $user = $user_query->row_array(); // Convert object to array

        if (!$user || !password_verify($current_password, $user['password'])) {
            $this->session->set_flashdata('error', 'Password saat ini salah!');
            redirect('Settings/view_pegawai');
        }

        // Validasi konfirmasi password
        if ($password !== $re_password) {
            $this->session->set_flashdata('error', 'Konfirmasi password tidak sesuai!');
            redirect('Settings/view_pegawai');
        }

        // Validasi kekuatan password
        $password_strength = $this->check_password_strength($password, $user['username']);
        if ($password_strength['strength'] !== 'strong') {
            $this->session->set_flashdata('error', 'Password harus memenuhi semua kriteria keamanan!');
            redirect('Settings/view_pegawai');
        }

        // Update password
        $hasil = $this->m_user->update_password_only($id, $password);

        if ($hasil == false) {
            $this->session->set_flashdata('error_password', 'Gagal mengubah password!');
            redirect('Settings/view_pegawai');
        } else {
            $this->session->set_flashdata('success_password', 'Password berhasil diubah!');
            redirect('Settings/view_pegawai');
        }
    }

    public function lengkapi_data_pegawai()
    {
        if (!check_user_level([1])) {
            return;
        }

        if ($this->input->method() === 'post') {
            $this->load->library('form_validation');

            // Set validation rules
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
            $this->form_validation->set_rules('id_jenis_kelamin', 'Jenis Kelamin', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
            } else {
                $this->db->trans_start();

                $id_user = $this->input->post('id_user');
                $id_user_detail = $this->input->post('id_user_detail');

                // [SOLUSI ALTERNATIF] Gunakan MySQL NOW() untuk konsistensi penuh
                $this->db->query("SET time_zone = '+07:00'"); // Set timezone MySQL ke WIB

                // Data untuk update user_detail - gunakan MySQL NOW()
                $user_detail_data = [
                    'nama_lengkap' => $this->input->post('nama_lengkap'),
                    'nip' => $this->input->post('nip'),
                    'pangkat' => $this->input->post('pangkat'),
                    'jabatan' => $this->input->post('jabatan'),
                    'id_jenis_kelamin' => $this->input->post('id_jenis_kelamin'),
                    'no_telp' => $this->input->post('no_telp'),
                    'alamat' => $this->input->post('alamat'),
                    'updated_at' => date('Y-m-d H:i:s') // Tetap PHP time untuk consistency
                ];

                // Data untuk update user - gunakan MySQL NOW()  
                $user_data = [
                    'email' => $this->input->post('email'),
                    'updated_at' => date('Y-m-d H:i:s') // Tetap PHP time untuk consistency
                ];

                // Update ke database dengan manual query untuk timestamp yang sama
                $this->db->query("UPDATE user_detail SET 
                nama_lengkap = ?, 
                nip = ?, 
                pangkat = ?, 
                jabatan = ?, 
                id_jenis_kelamin = ?, 
                no_telp = ?, 
                alamat = ?, 
                updated_at = NOW() 
                WHERE id_user_detail = ?",
                    [
                        $user_detail_data['nama_lengkap'],
                        $user_detail_data['nip'],
                        $user_detail_data['pangkat'],
                        $user_detail_data['jabatan'],
                        $user_detail_data['id_jenis_kelamin'],
                        $user_detail_data['no_telp'],
                        $user_detail_data['alamat'],
                        $id_user_detail
                    ]
                );

                $this->db->query("UPDATE user SET 
                email = ?, 
                updated_at = NOW() 
                WHERE id_user = ?",
                    [
                        $user_data['email'],
                        $id_user
                    ]
                );

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('eror_lengkapi', 'Gagal mengupdate data diri!');
                } else {
                    // Update session data jika berhasil
                    $user_session = $this->session->userdata('user_data');
                    if ($user_session) {
                        $user_session['nama_lengkap'] = $user_detail_data['nama_lengkap'];
                        $user_session['email'] = $user_data['email'];
                        $this->session->set_userdata('user_data', $user_session);
                    }

                    $this->session->set_flashdata('success_lengkapi', 'Data diri berhasil diupdate!');
                }
            }

            redirect('Settings/view_pegawai');
        }
    }

    // [BARU] Function untuk cek kekuatan password
    private function check_password_strength($password, $username)
    {
        $criteria = [
            'length' => strlen($password) >= 8,
            'uppercase' => preg_match('/[A-Z]/', $password),
            'lowercase' => preg_match('/[a-z]/', $password),
            'number' => preg_match('/[0-9]/', $password),
            'special' => preg_match('/[!@#$%]/', $password),
            'not_username' => strtolower($password) !== strtolower($username),
            'not_current_year' => !str_contains($password, date('Y'))
        ];

        // Hitung skor kekuatan
        $score = array_sum($criteria);
        $total_criteria = count($criteria);

        // Tentukan strength
        if ($score == $total_criteria) {
            $strength = 'strong';
        } elseif ($score >= 5) {
            $strength = 'medium';
        } else {
            $strength = 'weak';
        }

        return [
            'strength' => $strength,
            'criteria' => $criteria,
            'score' => $score,
            'total' => $total_criteria
        ];
    }

    // Admin
    public function proses_reset_cuti()
    {
        if (!check_user_level([2])) {
            redirect('Auth');
        }

        // Load models
        $this->load->model('m_settings');
        $this->load->model('m_cuti');

        // Validasi hanya bisa reset manual jika mode manual
        $reset_mode = $this->m_settings->get_setting('reset_mode');
        if ($reset_mode != 'manual') {
            $this->session->set_flashdata('eror', 'Reset manual hanya bisa dilakukan dalam mode manual.');
            redirect('Settings/view_admin');
        }

        $hasil = $this->m_cuti->reset_cuti_tahunan();

        if ($hasil == false) {
            $this->session->set_flashdata('eror', 'Gagal mereset data cuti.');
        } else {
            $this->m_settings->update_setting('last_reset_year', date('Y'));
            $this->session->set_flashdata('success', 'Jatah cuti tahunan berhasil direset secara manual.');
        }
        redirect('Settings/view_admin');
    }

    public function update_reset_mode()
    {
        if (!check_user_level([2])) {
            redirect('Auth');
        }

        // Load model
        $this->load->model('m_settings');

        $reset_mode = $this->input->post('reset_mode');

        if (!in_array($reset_mode, ['manual', 'otomatis'])) {
            $this->session->set_flashdata('eror', 'Mode reset tidak valid!');
            redirect('Settings/view_admin');
        }

        $this->m_settings->update_setting('reset_mode', $reset_mode);
        $this->session->set_flashdata('success', 'Pengaturan mode reset berhasil diperbarui.');
        redirect('Settings/view_admin');
    }





}


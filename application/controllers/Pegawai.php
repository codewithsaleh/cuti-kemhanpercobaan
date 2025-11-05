<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_jenis_kelamin');
        $this->load->model('m_cuti');
    }

    public function view_super_admin()
    {
        if ($this->session->userdata('logged_in') == true and $this->session->userdata('id_user_level') == 3) {
            $data['pegawai'] = $this->m_user->get_all_pegawai()->result_array();
            $data['jenis_kelamin_p'] = $this->m_jenis_kelamin->get_all_jenis_kelamin()->result_array();
            $this->load->view('super_admin/pegawai', $data);
        } else {
            $this->session->set_flashdata('loggin_err', 'loggin_err');
            redirect('Login/index');
        }
    }

    public function view_admin()
    {
        if (!check_user_level([2])) {
            return;
        }

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

    }

    public function tambah_pegawai()
    {
        // Cek session admin
        if (!check_user_level([2])) {
            return;
        }

        if ($this->input->method() === 'post') {
            $this->load->library('form_validation');

            // Validasi input
            $this->form_validation->set_rules('id_user_level', 'Tipe User', 'required');
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
            $this->form_validation->set_rules('nip', 'NIP', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
            $this->form_validation->set_rules('id_jenis_kelamin', 'Jenis Kelamin', 'required');

            // Validasi khusus untuk pegawai
            if ($this->input->post('id_user_level') == 1) {
                $this->form_validation->set_rules('id_atasan', 'Atasan Langsung', 'required');
            }

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('eror', validation_errors());
            } else {
                // CEK DUPLIKAT MANUAL SEBELUM TRANSACTION
                $nip = $this->input->post('nip');
                $email = $this->input->post('email');

                // Cek duplikat NIP di user_detail
                $existing_nip = $this->db->get_where('user_detail', ['nip' => $nip])->row();
                if ($existing_nip) {
                    $this->session->set_flashdata('eror', 'NIP duplikat. Silahkan input dengan data yang berbeda.');
                    redirect('Pegawai/view_admin');
                    return;
                }

                // Cek duplikat email di user
                $existing_email = $this->db->get_where('user', ['email' => $email])->row();
                if ($existing_email) {
                    $this->session->set_flashdata('eror', 'Email duplikat. Silahkan input dengan data yang berbeda.');
                    redirect('Pegawai/view_admin');
                    return;
                }

                // Cek duplikat username (NIP) di user
                $existing_username = $this->db->get_where('user', ['username' => $nip])->row();
                if ($existing_username) {
                    $this->session->set_flashdata('eror', 'NIP duplikat. Silahkan input dengan data yang berbeda.');
                    redirect('Pegawai/view_admin');
                    return;
                }

                $this->db->trans_start();

                // Generate ID unik
                $id_user_detail = 'ud-' . substr(md5(uniqid()), 0, 10);
                $id_user = 'user-' . substr(md5(uniqid()), 0, 10);

                // Data user_detail
                $user_detail_data = [
                    'id_user_detail' => $id_user_detail,
                    'nama_lengkap' => $this->input->post('nama_lengkap'),
                    'id_jenis_kelamin' => $this->input->post('id_jenis_kelamin'),
                    'no_telp' => $this->input->post('no_telp'),
                    'alamat' => $this->input->post('alamat'),
                    'nip' => $this->input->post('nip'),
                    'pangkat' => $this->input->post('pangkat'),
                    'jabatan' => $this->input->post('jabatan'),
                    'jatah_cuti' => 12,
                    'sisa_cuti' => 12,
                    'tahun_cuti' => date('Y'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // **PERBAIKAN: Gunakan password_hash() untuk password default**
                $password_default = 'kemhan2025';
                $password_hashed = password_hash($password_default, PASSWORD_DEFAULT);

                // Data user
                $user_data = [
                    'id_user' => $id_user,
                    'username' => $this->input->post('nip'),
                    'password' => $password_hashed,
                    'email' => $this->input->post('email'),
                    'id_user_level' => $this->input->post('id_user_level'),
                    'id_user_detail' => $id_user_detail,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Hanya set id_atasan untuk pegawai (level 1)
                if ($this->input->post('id_user_level') == 1 && $this->input->post('id_atasan')) {
                    $user_data['id_atasan'] = $this->input->post('id_atasan');
                }

                // Insert ke database
                $this->db->insert('user_detail', $user_detail_data);
                $this->db->insert('user', $user_data);

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('eror', 'Gagal menambahkan user!');
                } else {
                    // Tentukan pesan sukses berdasarkan tipe user
                    $user_type = '';
                    switch ($this->input->post('id_user_level')) {
                        case 1:
                            $user_type = 'Pegawai';
                            break;
                        case 2:
                            $user_type = 'Admin';
                            break;
                        case 3:
                            $user_type = 'Super Admin';
                            break;
                    }

                    $this->session->set_flashdata('success', $user_type . ' berhasil ditambahkan! Password default: kemhan2025');
                }
            }

            redirect('Pegawai/view_admin');
        }
    }

    public function edit_pegawai()
    {
        // Cek session admin
        if (!check_user_level([2])) {
            return;
        }

        if ($this->input->method() === 'post') {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('username', 'Username', 'required|trim');
            $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
            $this->form_validation->set_rules('id_jenis_kelamin', 'Jenis Kelamin', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('eror_edit', validation_errors());
            } else {
                $this->db->trans_start();

                $id_user = $this->input->post('id_user');
                $id_user_level = $this->input->post('id_user_level');

                // Update user_detail
                $user_detail_data = [
                    'nama_lengkap' => $this->input->post('nama_lengkap'),
                    'nip' => $this->input->post('nip'),
                    'no_telp' => $this->input->post('no_telp'),
                    'alamat' => $this->input->post('alamat'),
                    'pangkat' => $this->input->post('pangkat'),
                    'jabatan' => $this->input->post('jabatan'),
                    'id_jenis_kelamin' => $this->input->post('id_jenis_kelamin'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Update user
                $user_data = [
                    'username' => $this->input->post('username'),
                    'email' => $this->input->post('email'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // **PERBAIKAN: Gunakan password_hash() jika password diisi**
                if (!empty($this->input->post('password'))) {
                    $user_data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                }

                // Set id_atasan di tabel user JIKA user adalah Pegawai (level 1)
                if ($id_user_level == 1) {
                    $user_data['id_atasan'] = $this->input->post('id_atasan') ?: null;
                } else {
                    // Untuk Admin/Super Admin, set id_atasan menjadi null
                    $user_data['id_atasan'] = null;
                }

                // Get id_user_detail untuk update
                $user_info = $this->db->get_where('user', ['id_user' => $id_user])->row_array();

                if ($user_info) {
                    $this->db->where('id_user_detail', $user_info['id_user_detail']);
                    $this->db->update('user_detail', $user_detail_data);

                    $this->db->where('id_user', $id_user);
                    $this->db->update('user', $user_data);

                    $this->db->trans_complete();

                    if ($this->db->trans_status() === FALSE) {
                        $this->session->set_flashdata('eror_edit', 'Gagal mengupdate user!');
                    } else {
                        $this->session->set_flashdata('edit', 'Data user berhasil diupdate!' . (!empty($this->input->post('password')) ? ' Password juga telah diupdate.' : ''));
                    }
                } else {
                    $this->session->set_flashdata('eror_edit', 'User tidak ditemukan!');
                }
            }

            redirect('Pegawai/view_admin');
        }
    }

    public function hapus_pegawai()
    {
        // Cek session admin
        if (!check_user_level([2])) {
            return;
        }

        $id = $this->input->post("id_user");

        // Cek apakah user ada
        $user = $this->db->get_where('user', ['id_user' => $id])->row_array();

        if (!$user) {
            $this->session->set_flashdata('eror_hapus', 'User tidak ditemukan!');
            redirect('Pegawai/view_admin');
        }

        $this->db->trans_start();

        // Dapatkan id_user_detail untuk menghapus data di user_detail
        $id_user_detail = $user['id_user_detail'];

        // Hapus dari tabel user terlebih dahulu (karena ada foreign key constraint)
        $this->db->where('id_user', $id);
        $delete_user = $this->db->delete('user');

        // Hapus dari tabel user_detail
        if ($delete_user) {
            $this->db->where('id_user_detail', $id_user_detail);
            $delete_user_detail = $this->db->delete('user_detail');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('eror_hapus', 'Gagal menghapus user!');
        } else {
            $this->session->set_flashdata('hapus', 'User berhasil dihapus!');
        }

        redirect('Pegawai/view_admin');
    }

    public function proses_reset_cuti_pegawai($id_user)
    {
        if ($this->session->userdata('logged_in') == true && ($this->session->userdata('id_user_level') == 2 || $this->session->userdata('id_user_level') == 3)) {
            $hasil = $this->m_cuti->reset_cuti_by_id_user($id_user);
            if ($hasil) {
                $this->session->set_flashdata('success_reset', 'Jatah cuti pegawai berhasil direset.');
            } else {
                $this->session->set_flashdata('eror_reset', 'Gagal mereset jatah cuti pegawai.');
            }
            if ($this->session->userdata('id_user_level') == 2) {
                redirect('Pegawai/view_admin');
            } else {
                redirect('Pegawai/view_super_admin');
            }
        } else {
            redirect('Login/index');
        }
    }

    public function import_pegawai()
    {
        if (!check_user_level([2])) {
            return;
        }

        // Create upload directory
        $this->create_upload_directory();

        // Check if file was uploaded
        if (empty($_FILES['file_csv']['name'])) {
            $this->session->set_flashdata('error', 'Silakan pilih file CSV untuk diimpor.');
            redirect('Pegawai/view_admin');
            return;
        }

        // File upload configuration
        $config['upload_path'] = './assets/uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = 2048; // 2MB
        $config['file_name'] = 'import_pegawai_' . date('Ymd_His');
        $config['overwrite'] = false;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file_csv')) {
            // Upload failed
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', 'Upload file gagal: ' . $error);
            redirect('Pegawai/view_admin');
        } else {
            // Upload success - process the CSV file
            $upload_data = $this->upload->data();
            $file_path = $upload_data['full_path'];

            // Process the CSV file
            $result = $this->process_csv_file($file_path);

            // Delete the uploaded file after processing
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Set flash message based on result
            if ($result['success']) {
                $message = 'Berhasil mengimpor ' . $result['imported'] . ' pengguna.';
                if ($result['failed'] > 0) {
                    $message .= ' ' . $result['failed'] . ' data gagal diimpor.';
                }
                if ($result['duplicate'] > 0) {
                    $message .= ' ' . $result['duplicate'] . ' data duplikat (NIP/Email sudah ada).';
                }
                $this->session->set_flashdata('import_success', $message);
            } else {
                $this->session->set_flashdata('import_error', $result['message']);
            }

            redirect('Pegawai/view_admin');
        }
    }

    private function process_csv_file($file_path)
    {
        $result = [
            'success' => false,
            'imported' => 0,
            'failed' => 0,
            'duplicate' => 0,
            'message' => ''
        ];

        // Check if file exists
        if (!file_exists($file_path)) {
            $result['message'] = 'File CSV tidak ditemukan.';
            return $result;
        }

        // Open and read the CSV file
        $file = fopen($file_path, 'r');
        if (!$file) {
            $result['message'] = 'Tidak dapat membuka file CSV.';
            return $result;
        }

        // Read and validate header row
        $header = fgetcsv($file);
        $expected_columns = ['email', 'nama_lengkap', 'nip', 'pangkat', 'jabatan', 'id_jenis_kelamin', 'no_telp', 'alamat', 'id_user_level'];

        // Validate header
        if ($header === false || $header !== $expected_columns) {
            fclose($file);
            $result['message'] = 'Format CSV tidak valid. Harap gunakan template yang disediakan. Kolom yang diharapkan: ' . implode(', ', $expected_columns);
            return $result;
        }

        $imported_count = 0;
        $failed_count = 0;
        $duplicate_count = 0;
        $row_number = 1;

        // Process each row
        while (($row = fgetcsv($file)) !== FALSE) {
            $row_number++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Ensure row has same number of columns as header
            if (count($row) != count($header)) {
                $failed_count++;
                continue;
            }

            // Map row data to columns
            $data = array_combine($header, $row);

            // Trim all values
            $data = array_map('trim', $data);

            // Validate required fields
            if (empty($data['email']) || empty($data['nama_lengkap']) || empty($data['nip']) || empty($data['id_user_level'])) {
                $failed_count++;
                continue;
            }

            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $failed_count++;
                continue;
            }

            // Validate gender (1 or 2)
            if (!in_array($data['id_jenis_kelamin'], ['1', '2'])) {
                $failed_count++;
                continue;
            }

            // Validate user level (1, 2, or 3)
            if (!in_array($data['id_user_level'], ['1', '2', '3'])) {
                $failed_count++;
                continue;
            }

            // Check if user already exists by NIP (di user_detail)
            $existing_nip = $this->db->get_where('user_detail', ['nip' => $data['nip']])->row();
            if ($existing_nip) {
                $duplicate_count++;
                continue;
            }

            // Check if email already exists by email (di user)
            $existing_email = $this->db->get_where('user', ['email' => $data['email']])->row();
            if ($existing_email) {
                $duplicate_count++;
                continue;
            }

            // Check if username (NIP) already exists (di user)
            $existing_username = $this->db->get_where('user', ['username' => $data['nip']])->row();
            if ($existing_username) {
                $duplicate_count++;
                continue;
            }

            // Insert into database
            if ($this->create_user_from_csv($data)) {
                $imported_count++;
            } else {
                $failed_count++;
            }
        }

        fclose($file);

        $result['success'] = true;
        $result['imported'] = $imported_count;
        $result['failed'] = $failed_count;
        $result['duplicate'] = $duplicate_count;
        $result['message'] = 'Proses impor selesai.';

        return $result;
    }

    private function create_user_from_csv($data)
    {
        // Start transaction
        $this->db->trans_start();

        try {
            // Generate ID unik seperti di tambah_pegawai()
            $id_user_detail = 'ud-' . substr(md5(uniqid()), 0, 10);
            $id_user = 'user-' . substr(md5(uniqid()), 0, 10);

            // 1. Insert into user_detail (mengikuti struktur dari tambah_pegawai)
            $user_detail_data = [
                'id_user_detail' => $id_user_detail,
                'nama_lengkap' => $data['nama_lengkap'],
                'id_jenis_kelamin' => $data['id_jenis_kelamin'],
                'no_telp' => $data['no_telp'] ?? '',
                'alamat' => $data['alamat'] ?? '',
                'nip' => $data['nip'],
                'pangkat' => $data['pangkat'] ?? '',
                'jabatan' => $data['jabatan'] ?? '',
                'jatah_cuti' => 12,
                'sisa_cuti' => 12,
                'tahun_cuti' => date('Y'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // 2. Insert into user table (mengikuti struktur dari tambah_pegawai)
            $password_default = 'kemhan2025';
            $password_hashed = password_hash($password_default, PASSWORD_DEFAULT);

            $user_data = [
                'id_user' => $id_user,
                'username' => $data['nip'], // NIP as username
                'password' => $password_hashed,
                'email' => $data['email'],
                'id_user_level' => $data['id_user_level'],
                'id_user_detail' => $id_user_detail,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Insert ke database
            $this->db->insert('user_detail', $user_detail_data);
            $this->db->insert('user', $user_data);

            // Commit transaction
            $this->db->trans_commit();
            return true;

        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->trans_rollback();
            log_message('error', 'Import user failed: ' . $e->getMessage());
            return false;
        }
    }

    private function create_upload_directory()
    {
        $upload_path = './assets/uploads/';
        $template_path = './assets/templates/';

        // Create upload directory
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        // Create templates directory
        if (!is_dir($template_path)) {
            mkdir($template_path, 0777, true);
        }

        // Create .htaccess untuk security di uploads
        $htaccess = $upload_path . '.htaccess';
        if (!file_exists($htaccess)) {
            file_put_contents($htaccess, "Deny from all");
        }
    }

    public function proses_reset_password($id_user)
    {
        if (!check_user_level([2])) {
            return;
        }
        $hasil = $this->m_user->reset_password_by_id($id_user);
        if ($hasil) {
            $this->session->set_flashdata('success_reset', 'Password pegawai berhasil direset ke default.');
        } else {
            $this->session->set_flashdata('eror_reset', 'Gagal mereset password pegawai.');
        }
        redirect('Pegawai/view_admin');

    }

}


<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cuti extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_cuti');
        $this->load->model('m_user');
        $this->load->model('m_jenis_kelamin');
        $this->load->library('session');
    }


    public function view_super_admin()
    {
        if ($this->session->userdata('logged_in') == true and $this->session->userdata('id_user_level') == 3) {

            $data['cuti'] = $this->m_cuti->get_all_cuti()->result_array();
            $this->load->view('super_admin/cuti', $data);

        } else {

            $this->session->set_flashdata('loggin_err', 'loggin_err');
            redirect('Login/index');

        }
    }

    public function view_admin($filter = 'menunggu')
    {
        if (!check_user_level([2])) {
            return;
        }

        $id_user = $this->session->userdata('id_user');

        // Ambil data user yang login
        $data['user'] = $this->db->get_where(
            'view_user_complete',
            ['id_user' => $id_user]
        )->row_array();

        // QUERY BASE yang sama untuk semua filter
        $this->db->select('c.*, u.username, ud.nama_lengkap, ud.nip, ud.jabatan, 
              jc.nama_cuti, sc.status_cuti, sc.color_class, 
              atasan.nama_lengkap as nama_atasan,
              c.status_terakhir, c.tahun_reset');
        $this->db->from('cuti c');
        $this->db->join('user u', 'c.id_user = u.id_user');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail');
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti');
        $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti');
        $this->db->join('user atasan_user', 'u.id_atasan = atasan_user.id_user', 'left');
        $this->db->join('user_detail atasan', 'atasan_user.id_user_detail = atasan.id_user_detail', 'left');

        switch ($filter) {
            case 'semua':
                $data['page_title'] = 'Semua Pengajuan Cuti';
                // Tampilkan SEMUA cuti tanpa filter status
                // Tidak perlu where clause
                break;

            case 'diterima':
                $data['page_title'] = 'Cuti yang Diterima Final';
                $this->db->where('c.id_status_cuti', 2); // Status 2 = Diterima Final
                break;

            case 'ditolak':
                $data['page_title'] = 'Cuti yang Ditolak';
                $this->db->where('c.id_status_cuti', 3); // Status 3 = Ditolak
                break;

            case 'arsip':
                $data['page_title'] = 'Cuti yang Diarsipkan';
                $this->db->where('c.id_status_cuti', 5); // Status 5 = Arsip
                break;

            case 'menunggu':
            default:
                $data['page_title'] = 'Pengajuan Cuti Menunggu Proses';
                $this->db->where_in('c.id_status_cuti', [1, 4]); // Status 1 & 4 = Menunggu
                break;
        }

        $this->db->order_by('c.tgl_diajukan', 'DESC');
        $data['cuti'] = $this->db->get()->result_array();

        // Data untuk filter
        $data['current_filter'] = $filter;

        // Ambil data jenis cuti untuk filter (jika diperlukan)
        $data['jenis_cuti_list'] = $this->db->get_where('jenis_cuti', ['is_active' => 1])->result_array();

        $data['title'] = 'Manajemen Cuti';
        $data['active_menu'] = 'cuti';

        $this->load->view('admin/cuti', $data);
    }

    public function view_pegawai()
    {
        // Cek session pegawai
        if (!check_user_level([1])) {
            return;
        }

        // Ambil data user yang login
        $data['user'] = $this->db->get_where(
            'view_user_complete',
            ['id_user' => $this->session->userdata('id_user')]
        )->row_array();

        // Query untuk data cuti pegawai - TAMBAH KOLOM BARU
        $this->db->select('
        c.*,
        jc.nama_cuti,
        sc.status_cuti,
        sc.color_class,
        DATEDIFF(c.berakhir, c.mulai) + 1 as jumlah_hari,
        ud.nama_lengkap,
        ud.nip,
        c.status_terakhir,  
    ');
        $this->db->from('cuti c');
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti', 'left');
        $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti', 'left');
        $this->db->join('user u', 'c.id_user = u.id_user', 'left');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
        $this->db->where('c.id_user', $this->session->userdata('id_user'));
        $this->db->order_by('c.tgl_diajukan', 'DESC');
        $data['cuti'] = $this->db->get()->result_array();

        // Load view data_cuti.php yang sudah ada
        $this->load->view('pegawai/data_cuti', $data);
    }

    // Method untuk menampilkan halaman yang sama dengan alias berbeda
    public function data_cuti_pegawai()
    {
        $this->view_pegawai();
    }

    //Pegawai
    public function hapus_cuti()
    {

        $id_cuti = $this->input->post("id_cuti");
        $id_user = $this->input->post("id_user");

        $hasil = $this->m_cuti->delete_cuti($id_cuti);

        if ($hasil == false) {
            $this->session->set_flashdata('eror_hapus', 'eror_hapus');
        } else {
            $this->session->set_flashdata('hapus', 'hapus');
        }

        redirect('Cuti/view_pegawai/' . $id_user);
    }

    public function hapus_cuti_admin()
    {

        $id_cuti = $this->input->post("id_cuti");

        $hasil = $this->m_cuti->delete_cuti($id_cuti);

        if ($hasil == false) {
            $this->session->set_flashdata('eror_hapus', 'eror_hapus');
        } else {
            $this->session->set_flashdata('hapus', 'hapus');
        }

        redirect('Cuti/view_admin');
    }

    public function edit_cuti_admin()
    {
        $id_cuti = $this->input->post("id_cuti");
        $alasan = $this->input->post("alasan");
        $perihal_cuti = $this->input->post("perihal_cuti");
        $tgl_diajukan = $this->input->post("tgl_diajukan");
        $mulai = $this->input->post("mulai");
        $berakhir = $this->input->post("berakhir");


        $hasil = $this->m_cuti->update_cuti($alasan, $perihal_cuti, $tgl_diajukan, $mulai, $berakhir, $id_cuti);

        if ($hasil == false) {
            $this->session->set_flashdata('eror_edit', 'eror_edit');
        } else {
            $this->session->set_flashdata('edit', 'edit');
        }

        redirect('Cuti/view_admin');
    }

    // Admin
    public function proses_verifikasi()
    {
        // Cek session admin
        if (!check_user_level([2])) {
            return;
        }

        $id_cuti = $this->input->post("id_cuti");
        $alasan_verifikasi = $this->input->post("alasan_verifikasi");
        $alasan_penolakan = $this->input->post("alasan_penolakan"); // TAMBAH INI
        $id_status_cuti = $this->input->post("id_status_cuti");

        // Validasi input
        if (empty($id_cuti) || empty($id_status_cuti)) {
            $this->session->set_flashdata('eror_input', 'Data tidak lengkap!');
            redirect('Cuti/view_admin');
        }

        // Validasi status cuti
        if (!in_array($id_status_cuti, [2, 3])) {
            $this->session->set_flashdata('eror_input', 'Status cuti tidak valid!');
            redirect('Cuti/view_admin');
        }

        // **VALIDASI ALASAN PENOLAKAN JIKA STATUS 3**
        if ($id_status_cuti == 3 && empty($alasan_penolakan)) {
            $this->session->set_flashdata('eror_input', 'Alasan penolakan wajib diisi!');
            redirect('Cuti/view_admin');
        }

        // Cek status cuti sebelumnya
        $cuti_sebelumnya = $this->db->select('id_status_cuti')
            ->from('cuti')
            ->where('id_cuti', $id_cuti)
            ->get()
            ->row_array();

        if (!$cuti_sebelumnya) {
            $this->session->set_flashdata('eror_input', 'Cuti tidak ditemukan!');
            redirect('Cuti/view_admin');
        }

        // **UBAH VALIDASI: Boleh proses jika status = 4 (menunggu admin)**
        if ($cuti_sebelumnya['id_status_cuti'] != 4) {
            $this->session->set_flashdata('eror_input', 'Cuti belum siap untuk keputusan final atau sudah diproses!');
            redirect('Cuti/view_admin');
        }

        // **PROSES VERIFIKASI - KIRIM KEDUA ALASAN KE MODEL**
        $hasil = $this->m_cuti->confirm_cuti($id_cuti, $id_status_cuti, $alasan_verifikasi, $alasan_penolakan);

        if ($hasil) {
            $status_text = ($id_status_cuti == 2) ? 'disetujui secara final' : 'ditolak';
            $this->session->set_flashdata('input', 'Pengajuan cuti berhasil ' . $status_text . '!');
        } else {
            $this->session->set_flashdata('eror_input', 'Proses verifikasi gagal.');
        }

        redirect('Cuti/view_admin');
    }

    // Pegawai
    public function add_cuti_pegawai()
    {
        // Cek session pegawai
        if (!check_user_level([1])) {
            return;
        }

        if ($this->input->method() === 'post') {
            $this->load->library('form_validation');

            // Validasi input
            $this->form_validation->set_rules('id_jenis_cuti', 'Jenis Cuti', 'required');
            $this->form_validation->set_rules('mulai', 'Tanggal Mulai', 'required');
            $this->form_validation->set_rules('berakhir', 'Tanggal Berakhir', 'required');
            $this->form_validation->set_rules('alasan', 'Alasan', 'required|trim');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('Cuti/add_cuti_pegawai');
            } else {
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
                    'mulai' => $this->input->post('mulai'),
                    'berakhir' => $this->input->post('berakhir'),
                    'id_status_cuti' => 1, // Menunggu Persetujuan Atasan
                    'perihal_cuti' => $this->input->post('perihal_cuti') ?: 'Pengajuan Cuti',
                    'tujuan' => $this->input->post('tujuan'),
                    'berkendaraan' => $this->input->post('berkendaraan'),
                    'pengikut' => $this->input->post('pengikut'),
                    'keperluan' => $this->input->post('keperluan')
                ];

                // Insert ke database
                $this->db->insert('cuti', $cuti_data);

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('error', 'Gagal mengajukan cuti!');
                } else {
                    $this->session->set_flashdata('success', 'Pengajuan cuti berhasil disubmit! Menunggu persetujuan atasan.');
                }

                redirect('Cuti/view_pegawai');
            }
        } else {
            // Ambil data jenis cuti untuk form
            $data['jenis_cuti'] = $this->db->get_where('jenis_cuti', ['is_active' => 1])->result_array();

            // Ambil data user untuk form
            $data['user'] = $this->db->get_where(
                'view_user_complete',
                ['id_user' => $this->session->userdata('id_user')]
            )->row_array();

            $this->load->view('pegawai/form_pengajuan_cuti', $data);
        }
    }


    public function view_pegawai_acc()
    {
        // Cek session pegawai
        if (!$this->session->userdata('id_user') || $this->session->userdata('id_user_level') != 1) {
            redirect('Auth');
        }

        $id_user = $this->session->userdata('id_user');

        // Ambil data user
        $data['user'] = $this->db->get_where(
            'view_user_complete',
            ['id_user' => $id_user]
        )->row_array();

        // Query untuk cuti yang diterima (status = 2)
        $this->db->select('
        c.*,
        jc.nama_cuti,
        sc.status_cuti,
        sc.color_class,
        DATEDIFF(c.berakhir, c.mulai) + 1 as jumlah_hari,
        ud.nama_lengkap,
        ud.nip
    ');
        $this->db->from('cuti c');
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti', 'left');
        $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti', 'left');
        $this->db->join('user u', 'c.id_user = u.id_user', 'left');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
        $this->db->where('c.id_user', $id_user);
        $this->db->where('c.id_status_cuti', 2); // Hanya status diterima
        $this->db->order_by('c.tgl_diajukan', 'DESC');

        $data['cuti_diterima'] = $this->db->get()->result_array();

        // Load view
        $this->load->view('pegawai/cuti_diterima', $data);
    }

    public function view_pegawai_menunggu()
    {
        // Cek session pegawai
        if (!$this->session->userdata('id_user') || $this->session->userdata('id_user_level') != 1) {
            redirect('Auth');
        }

        $id_user = $this->session->userdata('id_user');

        // Ambil data user
        $data['user'] = $this->db->get_where(
            'view_user_complete',
            ['id_user' => $id_user]
        )->row_array();

        // Query untuk cuti yang menunggu (status = 1 atau 4)
        $this->db->select('
        c.*,
        jc.nama_cuti,
        sc.status_cuti,
        sc.color_class,
        DATEDIFF(c.berakhir, c.mulai) + 1 as jumlah_hari,
        ud.nama_lengkap,
        ud.nip
    ');
        $this->db->from('cuti c');
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti', 'left');
        $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti', 'left');
        $this->db->join('user u', 'c.id_user = u.id_user', 'left');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
        $this->db->where('c.id_user', $id_user);
        $this->db->where_in('c.id_status_cuti', [1, 4]); // Status menunggu atasan (1) atau admin (4)
        $this->db->order_by('c.tgl_diajukan', 'DESC');

        $data['cuti_menunggu'] = $this->db->get()->result_array();

        // Load view
        $this->load->view('pegawai/cuti_menunggu', $data);
    }

    public function edit_cuti_pegawai($id_cuti)
    {
        // Cek session dan kepemilikan cuti
        if (!check_user_level([1])) {
            return;
        }

        $id_user = $this->session->userdata('id_user');

        // Cek apakah cuti milik user dan masih status menunggu
        $cuti = $this->db->get_where('cuti', [
            'id_cuti' => $id_cuti,
            'id_user' => $id_user,
            'id_status_cuti' => 1 // Hanya bisa edit yang status menunggu atasan
        ])->row_array();

        if (!$cuti) {
            $this->session->set_flashdata('error', 'Cuti tidak ditemukan atau tidak dapat diedit');
            redirect('Cuti/view_pegawai_menunggu');
        }

        if ($this->input->method() === 'post') {
            // Proses update data cuti
            $this->load->library('form_validation');

            $this->form_validation->set_rules('id_jenis_cuti', 'Jenis Cuti', 'required');
            $this->form_validation->set_rules('mulai', 'Tanggal Mulai', 'required');
            $this->form_validation->set_rules('berakhir', 'Tanggal Berakhir', 'required');
            $this->form_validation->set_rules('alasan', 'Alasan', 'required|trim');

            if ($this->form_validation->run() == TRUE) {
                $update_data = [
                    'id_jenis_cuti' => $this->input->post('id_jenis_cuti'),
                    'alasan' => $this->input->post('alasan'),
                    'mulai' => $this->input->post('mulai'),
                    'berakhir' => $this->input->post('berakhir'),
                    'perihal_cuti' => $this->input->post('perihal_cuti') ?: 'Pengajuan Cuti',
                    'tujuan' => $this->input->post('tujuan'),
                    'berkendaraan' => $this->input->post('berkendaraan'),
                    'pengikut' => $this->input->post('pengikut'),
                    'keperluan' => $this->input->post('keperluan'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $this->db->where('id_cuti', $id_cuti);
                $this->db->update('cuti', $update_data);

                $this->session->set_flashdata('success', 'Pengajuan cuti berhasil diupdate!');
                redirect('Cuti/view_pegawai_menunggu');
            } else {
                // Jika validasi gagal, tetap tampilkan form dengan error
                $data['cuti'] = $cuti;
                $data['jenis_cuti'] = $this->db->get_where('jenis_cuti', ['is_active' => 1])->result_array();
                $data['user'] = $this->db->get_where(
                    'view_user_complete',
                    ['id_user' => $id_user]
                )->row_array();

                $this->load->view('pegawai/edit_cuti', $data);
                return;
            }
        }

        // Ambil data untuk form (GET request)
        $data['cuti'] = $cuti;
        $data['jenis_cuti'] = $this->db->get_where('jenis_cuti', ['is_active' => 1])->result_array();
        $data['user'] = $this->db->get_where(
            'view_user_complete',
            ['id_user' => $id_user]
        )->row_array();

        $this->load->view('pegawai/edit_cuti', $data);
    }

    public function hapus_cuti_menunggu()
    {
        // Cek session
        if (!check_user_level([1])) {
            return;
        }

        $id_cuti = $this->input->post('id_cuti');
        $id_user = $this->session->userdata('id_user');

        // Cek kepemilikan dan status cuti
        $cuti = $this->db->get_where('cuti', [
            'id_cuti' => $id_cuti,
            'id_user' => $id_user,
            'id_status_cuti' => 1 // Hanya bisa hapus yang status menunggu atasan
        ])->row_array();

        if ($cuti) {
            $this->db->where('id_cuti', $id_cuti);
            $this->db->delete('cuti');

            $this->session->set_flashdata('success', 'Pengajuan cuti berhasil dibatalkan!');
        } else {
            $this->session->set_flashdata('error', 'Cuti tidak ditemukan atau tidak dapat dibatalkan');
        }

        redirect('Cuti/view_pegawai_menunggu');
    }

    public function view_pegawai_reject()
    {
        // Cek session pegawai
        if (!check_user_level([1])) {
            return;
        }

        $id_user = $this->session->userdata('id_user');

        // Ambil data user
        $data['user'] = $this->db->get_where(
            'view_user_complete',
            ['id_user' => $id_user]
        )->row_array();

        // Query untuk cuti yang ditolak (status = 3)
        $this->db->select('
        c.*,
        jc.nama_cuti,
        sc.status_cuti,
        sc.color_class,
        DATEDIFF(c.berakhir, c.mulai) + 1 as jumlah_hari,
        ud.nama_lengkap,
        ud.nip
    ');
        $this->db->from('cuti c');
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti', 'left');
        $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti', 'left');
        $this->db->join('user u', 'c.id_user = u.id_user', 'left');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
        $this->db->where('c.id_user', $id_user);
        $this->db->where('c.id_status_cuti', 3); // Status ditolak
        $this->db->order_by('c.tgl_diajukan', 'DESC');

        $data['cuti_ditolak'] = $this->db->get()->result_array();

        // Load view
        $this->load->view('pegawai/cuti_ditolak', $data);
    }

    public function ajukan_ulang_cuti($id_cuti)
    {
        // Cek session dan kepemilikan cuti
        if (!check_user_level([1])) {
            return;
        }

        $id_user = $this->session->userdata('id_user');

        // Cek apakah cuti milik user dan status ditolak
        $cuti = $this->db->get_where('cuti', [
            'id_cuti' => $id_cuti,
            'id_user' => $id_user,
            'id_status_cuti' => 3 // Hanya bisa ajukan ulang yang status ditolak
        ])->row_array();

        if (!$cuti) {
            $this->session->set_flashdata('error', 'Cuti tidak ditemukan atau tidak dapat diajukan ulang');
            redirect('Cuti/view_pegawai_reject');
        }

        // Update status menjadi menunggu (status 1) dan reset alasan verifikasi
        $update_data = [
            'id_status_cuti' => 1, // Kembali ke status menunggu
            'alasan_verifikasi' => NULL, // Reset alasan penolakan
            'approved_by' => NULL,
            'approved_at' => NULL,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id_cuti', $id_cuti);
        $this->db->update('cuti', $update_data);

        $this->session->set_flashdata('success', 'Pengajuan cuti berhasil diajukan ulang! Menunggu persetujuan atasan.');
        redirect('Cuti/view_pegawai_menunggu');
    }

    public function hapus_cuti_ditolak()
    {
        // Cek session
        if (!check_user_level([1])) {
            return;
        }

        $id_cuti = $this->input->post('id_cuti');
        $id_user = $this->session->userdata('id_user');

        // Cek kepemilikan dan status cuti
        $cuti = $this->db->get_where('cuti', [
            'id_cuti' => $id_cuti,
            'id_user' => $id_user,
            'id_status_cuti' => 3 // Hanya bisa hapus yang status ditolak
        ])->row_array();

        if ($cuti) {
            $this->db->where('id_cuti', $id_cuti);
            $this->db->delete('cuti');

            $this->session->set_flashdata('success', 'Pengajuan cuti yang ditolak berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Cuti tidak ditemukan atau tidak dapat dihapus');
        }

        redirect('Cuti/view_pegawai_reject');
    }
}


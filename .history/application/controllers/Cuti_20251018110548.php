<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuti extends CI_Controller {

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
    if ($this->session->userdata('logged_in') == true AND $this->session->userdata('id_user_level') == 3) {

        $data['cuti'] = $this->m_cuti->get_all_cuti()->result_array();
        $this->load->view('super_admin/cuti', $data);

    }else{

        $this->session->set_flashdata('loggin_err','loggin_err');
        redirect('Login/index');

    }
    }
    
    
    public function view_admin($filter = 'menunggu')
    {
        if ($this->session->userdata('logged_in') == true AND $this->session->userdata('id_user_level') == 2) {

           
            switch ($filter) {
                case 'semua':
                    $data['page_title'] = 'Semua Pengajuan Cuti';
                    $data['cuti'] = $this->m_cuti->get_all_cuti()->result_array();
                    break;
                case 'diterima':
                    $data['page_title'] = 'Cuti yang Diterima';
                    $data['cuti'] = $this->m_cuti->get_cuti_by_status(2)->result_array(); // Status 2 = Diterima
                    break;
                case 'ditolak':
                    $data['page_title'] = 'Cuti yang Ditolak';
                    $data['cuti'] = $this->m_cuti->get_cuti_by_status(3)->result_array(); // Status 3 = Ditolak
                    break;
                case 'menunggu':
                default:
                    $data['page_title'] = 'Verifikasi Pengajuan Cuti';
                    
                    $data['cuti'] = $this->m_cuti->get_cuti_for_admin()->result_array(); // Status 4 = Menunggu Verifikasi
                    break;
            }
            
            $this->load->view('admin/cuti', $data);

        }else{

            $this->session->set_flashdata('loggin_err','loggin_err');
            redirect('Login/index');
    
        }

    }
    
    public function view_pegawai()
    {
        // Cek session pegawai
        if (!$this->session->userdata('id_user') || $this->session->userdata('id_user_level') != 1) {
            redirect('Auth');
        }

        // Ambil data user yang login
        $data['user'] = $this->db->get_where('view_user_complete', 
            ['id_user' => $this->session->userdata('id_user')])->row_array();

        // Query untuk data cuti pegawai - menggunakan query yang sederhana
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
        $this->db->where('c.id_user', $this->session->userdata('id_user'));
        $this->db->order_by('c.tgl_diajukan', 'DESC');
        $data['cuti'] = $this->db->get()->result_array();

        // Load view data_cuti.php yang sudah ada
        $this->load->view('pegawai/data_cuti', $data);
    }

    // Method untuk menampilkan halaman yang sama dengan alias berbeda
    public function data_cuti_pegawai() {
        $this->view_pegawai();
    }

    public function hapus_cuti()
    {

        $id_cuti = $this->input->post("id_cuti");
        $id_user = $this->input->post("id_user");

        $hasil = $this->m_cuti->delete_cuti($id_cuti);
        
        if($hasil==false){
            $this->session->set_flashdata('eror_hapus','eror_hapus');
        }else{
            $this->session->set_flashdata('hapus','hapus');
        }

        redirect('Cuti/view_pegawai/'.$id_user);
    }

    public function hapus_cuti_admin()
    {

        $id_cuti = $this->input->post("id_cuti");

        $hasil = $this->m_cuti->delete_cuti($id_cuti);
        
        if($hasil==false){
            $this->session->set_flashdata('eror_hapus','eror_hapus');
        }else{
            $this->session->set_flashdata('hapus','hapus');
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
        
        if($hasil==false){
            $this->session->set_flashdata('eror_edit','eror_edit');
        }else{
            $this->session->set_flashdata('edit','edit');
        }

        redirect('Cuti/view_admin');
    }

    public function proses_verifikasi()
    {
        $id_cuti = $this->input->post("id_cuti");
        $alasan_verifikasi = $this->input->post("alasan_verifikasi");
        $submit_action = $this->input->post('submit');

        $id_status_cuti = 0;
        if ($submit_action == 'terima') {
            $id_status_cuti = 2; // Kode untuk 'Diterima'
        } elseif ($submit_action == 'tolak') {
            $id_status_cuti = 3; // Kode untuk 'Ditolak'
        }

        if ($id_status_cuti != 0) {
            $hasil = $this->m_cuti->confirm_cuti($id_cuti, $id_status_cuti, $alasan_verifikasi);
        
            if($hasil){
                $this->session->set_flashdata('input','Status cuti berhasil diperbarui.');
            }else{
                $this->session->set_flashdata('eror_input','Proses verifikasi gagal.');
            }
        } else {
            $this->session->set_flashdata('eror_input','Aksi tidak valid.');
        }
        
        // Selalu kembali ke "kotak masuk" utama setelah verifikasi
        redirect('Cuti/view_admin');
    }

    public function add_cuti_pegawai() {
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
                    'id_status_cuti' => 1, // Menunggu konfirmasi
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
                    $this->session->set_flashdata('success', 'Pengajuan cuti berhasil disubmit!');
                }

                redirect('Cuti/view_pegawai');
            }
        } else {
            // Ambil data jenis cuti untuk form
            $data['jenis_cuti'] = $this->db->get_where('jenis_cuti', ['is_active' => 1])->result_array();
            
            // Ambil data user untuk form
            $data['user'] = $this->db->get_where('view_user_complete', 
                ['id_user' => $this->session->userdata('id_user')])->row_array();

            $this->load->view('pegawai/add_cuti', $data);
        }
    }
}


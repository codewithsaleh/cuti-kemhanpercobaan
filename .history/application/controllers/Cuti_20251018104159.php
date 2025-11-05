<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuti extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_cuti');
        $this->load->model('m_user');
        $this->load->model('m_jenis_kelamin');
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
    
    public function view_pegawai($id_user)
    {
        if ($this->session->userdata('logged_in') == true AND $this->session->userdata('id_user_level') == 1) {

        $data['cuti'] = $this->m_cuti->get_all_cuti_by_id_user($id_user)->result_array();
        $data['pegawai'] = $this->m_user->get_pegawai_by_id($this->session->userdata('id_user'))->row_array();
        $data['jenis_kelamin'] = $this->m_jenis_kelamin->get_all_jenis_kelamin()->result_array();
        $data['pegawai_data'] = $this->m_user->get_pegawai_by_id($this->session->userdata('id_user'))->result_array();
        $this->load->view('pegawai/cuti', $data);

        }else{

            $this->session->set_flashdata('loggin_err','loggin_err');
            redirect('Login/index');

        }
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
}


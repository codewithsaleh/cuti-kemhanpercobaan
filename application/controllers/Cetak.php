<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_cuti');
    }

    public function surat_cuti_pdf($id_cuti){

        // Mengambil data cuti berdasarkan ID, pastikan semua data yang dibutuhkan ada
        $data['cuti'] = $this->m_cuti->get_all_cuti_by_id_cuti($id_cuti)->result_array();

        // [PENTING] Jika tidak ada data cuti, tampilkan pesan error
        if (empty($data['cuti'])) {
            show_error('Data cuti tidak ditemukan.', 404, 'Error');
            return;
        }

        $this->load->library('pdf');
    
        $this->pdf->setPaper('A4', 'portrait'); // Menggunakan A4 agar lebih standar
        $this->pdf->set_option('isRemoteEnabled', true);
        $this->pdf->filename = "surat-cuti-".$data['cuti'][0]['nama_lengkap'].".pdf";
        
        // [PERBAIKAN UTAMA] Memuat view template surat cuti yang baru dan benar
        $this->pdf->load_view('pegawai/surat_cuti_pdf', $data);
    }

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_kalender extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Cuti_model');
    }
    
    public function events() {
        // Set header untuk JSON response
        header('Content-Type: application/json');
        
        // Ambil parameter tanggal dari request
        $start = $this->input->get('start');
        $end = $this->input->get('end');
        
        // Query untuk mengambil data cuti yang disetujui
        $this->db->select('
            c.id_cuti,
            c.mulai,
            c.berakhir,
            c.perihal_cuti,
            c.alasan,
            ud.nama_lengkap,
            ud.nip,
            jc.nama_cuti,
            sc.status_cuti,
            sc.color_class
        ');
        $this->db->from('cuti c');
        $this->db->join('user u', 'c.id_user = u.id_user', 'left');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti', 'left');
        $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti', 'left');
        
        // Filter hanya cuti yang disetujui (status = 2)
        $this->db->where('c.id_status_cuti', 2);
        
        // Filter berdasarkan rentang tanggal jika ada
        if ($start) {
            $this->db->where('c.berakhir >=', $start);
        }
        if ($end) {
            $this->db->where('c.mulai <=', $end);
        }
        
        $this->db->order_by('c.mulai', 'ASC');
        $cuti_data = $this->db->get()->result_array();
        
        // Format data untuk FullCalendar
        $events = array();
        foreach ($cuti_data as $cuti) {
            $events[] = array(
                'id' => $cuti['id_cuti'],
                'title' => $cuti['nama_lengkap'] . ' - ' . $cuti['nama_cuti'],
                'start' => $cuti['mulai'],
                'end' => date('Y-m-d', strtotime($cuti['berakhir'] . ' +1 day')), // +1 day karena FullCalendar end adalah exclusive
                'backgroundColor' => '#28a745', // Hijau untuk cuti disetujui
                'borderColor' => '#1e7e34',
                'textColor' => '#ffffff',
                'extendedProps' => array(
                    'nip' => $cuti['nip'],
                    'jenis_cuti' => $cuti['nama_cuti'],
                    'alasan' => $cuti['alasan'],
                    'perihal' => $cuti['perihal_cuti'],
                    'status' => $cuti['status_cuti']
                )
            );
        }
        
        // Return JSON response
        echo json_encode($events);
    }
    
    public function hari_libur() {
        // Set header untuk JSON response
        header('Content-Type: application/json');
        
        // Data hari libur nasional 2024 (bisa dipindah ke database)
        $hari_libur = array(
            array(
                'title' => 'Tahun Baru',
                'start' => '2024-01-01',
                'backgroundColor' => '#dc3545',
                'borderColor' => '#c82333',
                'textColor' => '#ffffff'
            ),
            array(
                'title' => 'Hari Raya Idul Fitri',
                'start' => '2024-04-10',
                'end' => '2024-04-12',
                'backgroundColor' => '#dc3545',
                'borderColor' => '#c82333',
                'textColor' => '#ffffff'
            ),
            array(
                'title' => 'Hari Kemerdekaan',
                'start' => '2024-08-17',
                'backgroundColor' => '#dc3545',
                'borderColor' => '#c82333',
                'textColor' => '#ffffff'
            ),
            array(
                'title' => 'Hari Raya Idul Adha',
                'start' => '2024-06-17',
                'backgroundColor' => '#dc3545',
                'borderColor' => '#c82333',
                'textColor' => '#ffffff'
            ),
            array(
                'title' => 'Hari Natal',
                'start' => '2024-12-25',
                'backgroundColor' => '#dc3545',
                'borderColor' => '#c82333',
                'textColor' => '#ffffff'
            )
        );
        
        echo json_encode($hari_libur);
    }
}

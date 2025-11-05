<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kalender extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('auth_helper');
        $this->load->model('m_cuti');
    }
    // Fungsi untuk menampilkan halaman kalender
    public function view_admin()
    {
        if (!check_user_level([2])) {
            return;
        }
        $this->load->view('admin/kalender_view');
    }

    // Fungsi untuk menyediakan data cuti dalam format JSON yang dibutuhkan oleh FullCalendar
    public function get_events()
    {
        header('Content-Type: application/json');
        $events = $this->m_cuti->get_approved_cuti_for_calendar();
        echo json_encode($events);
    }
}

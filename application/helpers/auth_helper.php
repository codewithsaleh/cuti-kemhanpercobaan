<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('check_login')) {
    /**
     * Cek apakah user sudah login dan redirect ke halaman yang sesuai
     */
    function check_login() {
        $CI =& get_instance();
        
        // Cek session logged_in
        if (!$CI->session->userdata('logged_in')) {
            redirect('Login/index');
            return false;
        }
        
        return true;
    }
}

if (!function_exists('check_user_level')) {
    /**
     * Cek level user dan redirect ke halaman yang sesuai
     * @param array $allowed_levels Level yang diizinkan mengakses halaman
     */
    function check_user_level($allowed_levels = []) {
        $CI =& get_instance();
        
        // Cek login dulu
        if (!check_login()) {
            return false;
        }
        
        $user_level = $CI->session->userdata('id_user_level');
        
        // Jika tidak ada level yang diizinkan, atau user level tidak termasuk dalam yang diizinkan
        if (empty($allowed_levels) || !in_array($user_level, $allowed_levels)) {
            // Redirect ke dashboard sesuai level user
            switch ($user_level) {
                case 1: // Pegawai
                    redirect('Dashboard/dashboard_pegawai');
                    break;
                case 2: // Admin
                    redirect('Dashboard/dashboard_admin');
                    break;
                case 3: // Super Admin
                    redirect('Dashboard/dashboard_super_admin');
                    break;
                default:
                    redirect('Login/index');
                    break;
            }
            return false;
        }
        
        return true;
    }
}

if (!function_exists('is_pegawai')) {
    /**
     * Cek apakah user adalah Pegawai (level 1)
     */
    function is_pegawai() {
        $CI =& get_instance();
        return $CI->session->userdata('id_user_level') == 1;
    }
}

if (!function_exists('is_admin')) {
    /**
     * Cek apakah user adalah Admin (level 2)
     */
    function is_admin() {
        $CI =& get_instance();
        return $CI->session->userdata('id_user_level') == 2;
    }
}

if (!function_exists('is_super_admin')) {
    /**
     * Cek apakah user adalah Super Admin (level 3)
     */
    function is_super_admin() {
        $CI =& get_instance();
        return $CI->session->userdata('id_user_level') == 3;
    }
}

if (!function_exists('get_user_level_name')) {
    /**
     * Dapatkan nama level user
     */
    function get_user_level_name($level_id) {
        switch ($level_id) {
            case 1: return 'Pegawai';
            case 2: return 'Admin';
            case 3: return 'Super Admin';
            default: return 'Unknown';
        }
    }
}

if (!function_exists('logout_user')) {
    /**
     * Logout user dan redirect ke login
     */
    function logout_user() {
        $CI =& get_instance();
        $CI->session->sess_destroy();
        redirect('Login/index');
    }
}
?>
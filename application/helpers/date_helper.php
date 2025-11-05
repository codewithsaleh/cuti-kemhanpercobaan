<?php
if (!function_exists('date_indonesia')) {
    function date_indonesia($date)
    {
        if (!$date || $date == '0000-00-00') {
            return '-';
        }

        $bulan = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        $tanggal = date('j', strtotime($date));
        $bulan_num = date('n', strtotime($date));
        $tahun = date('Y', strtotime($date));

        return $tanggal . ' ' . $bulan[$bulan_num] . ' ' . $tahun;
    }
}
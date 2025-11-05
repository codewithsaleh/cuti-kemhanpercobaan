<?php
if (!function_exists('terbilang')) {
    function terbilang($x)
    {
        $x = abs($x);
        $abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");

        if ($x < 12) {
            return " " . $abil[$x];
        } else if ($x < 20) {
            return terbilang($x - 10) . " Belas";
        } else if ($x < 100) {
            return terbilang(floor($x / 10)) . " Puluh" . terbilang($x % 10);
        } else if ($x < 200) {
            return " Seratus" . terbilang($x - 100);
        } else if ($x < 1000) {
            return terbilang(floor($x / 100)) . " Ratus" . terbilang($x % 100);
        } else if ($x < 2000) {
            return " Seribu" . terbilang($x - 1000);
        } else if ($x < 1000000) {
            return terbilang(floor($x / 1000)) . " Ribu" . terbilang($x % 1000);
        } else if ($x < 1000000000) {
            return terbilang(floor($x / 1000000)) . " Juta" . terbilang($x % 1000000);
        }

        return "";
    }
}
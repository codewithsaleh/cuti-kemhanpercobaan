<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method


*/

// Route untuk 404 page
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Custom route untuk handle 404
$route['(:any)'] = function() {
    show_404();
};

$route['default_controller'] = 'Login';

$route['api/kalender/events'] = 'Api_kalender/events';
$route['api/kalender/hari-libur'] = 'Api_kalender/hari_libur';
$route['pesan'] = 'Pesan/view_admin';
$route['pesan/send'] = 'Pesan/send_message';

$route['pesan/admin'] = 'Pesan/view_admin';
$route['pesan/send'] = 'Pesan/send_message';
$route['pesan/get-new'] = 'Pesan/get_new_messages';

// Route untuk pegawai - data cuti
$route['pegawai/data-cuti'] = 'Cuti/view_pegawai';
$route['pegawai/cuti'] = 'Cuti/view_pegawai';
$route['data-cuti-pegawai'] = 'Cuti/view_pegawai';

// Route untuk handle kedua controller
$route['Cuti/add_cuti_pegawai'] = 'Cuti/add_cuti_pegawai';
$route['Cuti/view_pegawai_acc'] = 'Cuti/view_pegawai_acc';
$route['pegawai/ajukan-cuti'] = 'Cuti/add_cuti_pegawai';

// $route['Cuti/view_pegawai_menunggu'] = 'Cuti/view_pegawai_menunggu';
$route['Cuti/edit_cuti_pegawai/(:any)'] = 'Cuti/edit_cuti_pegawai/$1';
// $route['Cuti/hapus_cuti_menunggu'] = 'Cuti/hapus_cuti_menunggu';
$route['pegawai/cuti/diterima'] = 'Cuti/view_pegawai_acc';
$route['pegawai/cuti/menunggu'] = 'Cuti/view_pegawai_acc';
$route['pegawai/cuti/ditolak'] = 'Cuti/view_pegawai_acc';

$route['Cuti/view_pegawai_reject'] = 'Cuti/view_pegawai_reject';
$route['Cuti/ajukan_ulang_cuti/(:any)'] = 'Cuti/ajukan_ulang_cuti/$1';
$route['Cuti/hapus_cuti_ditolak'] = 'Cuti/hapus_cuti_ditolak';
$route['Cuti/proses_verifikasi'] = 'Cuti/proses_verifikasi';

$route['Pegawai/proses_reset_cuti_pegawai/(:any)'] = 'Pegawai/proses_reset_cuti_pegawai/$1';

$route['Surat/cetak_surat/(:any)'] = 'Surat/cetak_surat/$1';

// Laporan Superadmin
// Tambahkan di routes.php jika belum ada
$route['Laporan_Atasan/laporan_bulanan'] = 'Laporan_Atasan/laporan_bulanan';
$route['Laporan_Atasan/laporan_tahunan_csv'] = 'Laporan_Atasan/laporan_tahunan_csv';

$route['test/exception'] = 'Cuti/test_exception';
$route['test/general-error'] = 'Cuti/test_general_error'; 
$route['test/php-error'] = 'Cuti/test_php_error';
$route['test/db-error'] = 'Cuti/test_db_error';
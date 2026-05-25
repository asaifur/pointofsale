<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
$route['default_controller'] = 'Auth';
$route['korra/projects'] = 'korra/projects';
$route['korra/submit_contact'] = 'korra/submit_contact';
$route['verifikasi-otp'] = 'auth/verifikasi_otp_view';

// ======================================
// ROUTES UNTUK SISTEM POS
// ======================================

// SALES ROUTES
$route['sales'] = 'sales/index';
$route['sales/index'] = 'sales/index';
$route['sales/daftar'] = 'sales/daftar';
$route['sales/detail/(:num)'] = 'sales/detail/$1';
$route['sales/cetak_struk/(:num)'] = 'sales/cetak_struk/$1';
$route['sales/search_produk'] = 'sales/search_produk';
$route['sales/get_produk/(:num)'] = 'sales/get_produk/$1';
$route['sales/search_pelanggan'] = 'sales/search_pelanggan';
$route['sales/buat_transaksi'] = 'sales/buat_transaksi';
$route['sales/tambah_item'] = 'sales/tambah_item';
$route['sales/hapus_item'] = 'sales/hapus_item';
$route['sales/simpan_transaksi'] = 'sales/simpan_transaksi';
$route['sales/batalkan'] = 'sales/batalkan';

// LAPORAN ROUTES
$route['laporan'] = 'laporan/harian';
$route['laporan/harian'] = 'laporan/harian';
$route['laporan/bulanan'] = 'laporan/bulanan';
$route['laporan/periode'] = 'laporan/periode';
$route['laporan/stok'] = 'laporan/stok';
$route['laporan/best_seller'] = 'laporan/best_seller';
$route['laporan/customer'] = 'laporan/customer';
$route['laporan/export_csv/(:alpha)'] = 'laporan/export_csv/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Auth::index');
$routes->post('auth/login_process', 'Auth::login_process');
$routes->get('auth/logout', 'Auth::logout');

// Grouping Admin TIK
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('dashboard', 'Admin::index'); 

    // Master Barang
    $routes->get('master_barang', 'Admin::master_barang');
    $routes->post('simpan_barang', 'Admin::simpan_barang');
    $routes->post('update_barang/(:num)', 'Admin::update_barang/$1'); 
    $routes->get('hapus_barang/(:num)', 'Admin::hapus_barang/$1');   
    
    // Stok via Nota
    $routes->get('tambah_stok', 'Admin::tambah_stok');
    $routes->post('review_nota', 'Admin::review_nota');
    $routes->post('simpan_stok_final', 'Admin::simpan_stok_final');

    // Persetujuan & WA
    $routes->get('persetujuan', 'Admin::persetujuan');
    $routes->get('tindak_lanjut/(:any)', 'Admin::tindak_lanjut/$1');
    $routes->post('proses_tindak_lanjut', 'Admin::proses_tindak_lanjut');
    $routes->get('kirim_notif_wa/(:any)', 'Admin::kirim_notif_wa/$1');
    
    // Manajemen User
    $routes->get('manajemen_user', 'Admin::manajemen_user');
    $routes->post('simpan_user', 'Admin::simpan_user'); 
    $routes->post('update_user/(:num)', 'Admin::update_user/$1');
    $routes->get('hapus_user/(:num)', 'Admin::hapus_user/$1'); // SUDAH DIPERBAIKI (Tanpa prefiks admin/)

    // Laporan
    // Rute Tampilan Menu Laporan Utama
    $routes->get('laporan', 'Admin::laporan');

    // Rute Proses Cetak Laporan (Tambahkan ini)
    $routes->get('laporan/barang_masuk', 'Admin::cetak_barang_masuk');
    $routes->get('laporan/stok_opname', 'Admin::cetak_stok_opname');
    $routes->get('laporan/stok_bulanan', 'Admin::cetak_stok_bulanan');
    $routes->get('laporan/rekap_permintaan', 'Admin::cetak_rekap_permintaan');
    $routes->get('laporan/cetak_berita_acara', 'Admin::cetak_berita_acara');
});

// Grouping User
$routes->group('user', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'User::index');
    $routes->get('dashboard', 'User::index');
    $routes->get('form_permohonan', 'User::form_permohonan');
    $routes->post('kirim_permohonan', 'User::kirim_permohonan');
    $routes->get('riwayat', 'User::riwayat');
    $routes->get('download_pdf/(:any)', 'User::download_pdf/$1');
});
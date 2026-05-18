<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ============================================================================
// RUTE UTAMA & AUTH
// ============================================================================
$routes->get('/', 'Home::index');
$routes->get('login',     'Auth::login');
$routes->post('login',    'Auth::loginProcess');
$routes->get('logout',    'Auth::logout');
$routes->get('register',  'Auth::register');
$routes->post('register', 'Auth::registerProcess');
$routes->get('dashboard', 'Dashboard::index');

// ============================================================================
// RUTE LOGISTIK & ONGKIR
// ============================================================================
$routes->group('logistik', function ($routes) {
    // Form cek ongkir: GET = tampil form | POST = proses & tampil hasil
    $routes->get('tesongkir',  'Logistik::tesOngkir');
    $routes->post('tesongkir', 'Logistik::tesOngkir');

    // Halaman konfirmasi detail sebelum simpan (POST only)
    $routes->post('detail-pesanan', 'Logistik::detailPesanan');

    // Simpan pesanan ke database (POST only)
    $routes->post('simpan-pesanan', 'Logistik::simpanPesanan');

    // Riwayat semua pesanan (GET)
    $routes->get('daftar-pesanan', 'Logistik::daftarPesanan');

    // Hapus pesanan pending (POST only)
    $routes->post('hapus-pesanan/(:num)', 'Logistik::hapusPesanan/$1');
});

// ============================================================================
// RUTE PEMBAYARAN (User)
// ============================================================================
$routes->group('pembayaran', function ($routes) {
    // User: form upload bukti (GET = tampil form, POST = proses upload)
    $routes->get('upload/(:num)',  'Pembayaran::formUpload/$1');
    $routes->post('upload/(:num)', 'Pembayaran::prosesUpload/$1');

    // User: lihat status pembayarannya
    $routes->get('status/(:num)', 'Pembayaran::statusPembayaran/$1');
});

// ============================================================================
// RUTE ADMIN
// ============================================================================
$routes->group('admin', function ($routes) {
    // Admin: daftar semua pembayaran
    $routes->get('pembayaran', 'Pembayaran::daftarAdmin');

    // Admin: konfirmasi pembayaran (POST only)
    $routes->post('pembayaran/konfirmasi/(:num)', 'Pembayaran::konfirmasi/$1');

    // Admin: tolak pembayaran (POST only)
    $routes->post('pembayaran/tolak/(:num)', 'Pembayaran::tolak/$1');
});

// ============================================================================
// RUTE LAINNYA
// ============================================================================
$routes->get('otomasi', 'Otomasi::index');

// Dev tool: diagnostik API (hapus setelah production)
$routes->get('diagnostik-api', 'DiagnostikAPI::index');
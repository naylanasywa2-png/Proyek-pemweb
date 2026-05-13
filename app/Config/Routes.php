<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- RUTE UTAMA & AUTH ---
$routes->get('/', 'Home::index');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::loginProcess');
$routes->get('logout', 'Auth::logout');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::registerProcess');
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

// --- RUTE LOGISTIK & API ---
$routes->group('logistik', function ($routes) {
    // Cek ongkir (GET = tampilkan form, POST = proses)
    $routes->get('tesongkir',  'Logistik::tesOngkir');
    $routes->post('tesongkir', 'Logistik::tesOngkir');

    // Halaman konfirmasi detail sebelum simpan (POST only)
    $routes->post('detail-pesanan', 'Logistik::detailPesanan');

    // Simpan ke DB (POST only, dari halaman konfirmasi)
    $routes->post('simpan-pesanan', 'Logistik::simpanPesanan');

    // Daftar pesanan
    $routes->get('daftar-pesanan', 'Logistik::daftarPesanan');

    // Hapus pesanan - sekarang POST (lebih aman dari GET)
    $routes->post('hapus-pesanan/(:num)', 'Logistik::hapusPesanan/$1');

    // Dev tools
    $routes->get('testemail',  'Logistik::testEmail');
    $routes->get('test-db',    'Logistik::testSimpanOrder');
});

// --- RUTE OTOMASI ---
$routes->get('otomasi', 'Otomasi::index');

// --- [SEMENTARA] Diagnostik API - hapus setelah selesai testing ---
$routes->get('diagnostik-api', 'DiagnostikAPI::index');
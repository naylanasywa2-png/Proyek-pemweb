<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- RUTE UTAMA & AUTH (Tugas Ega/Nasywa) ---
$routes->get('/', 'Home::index');
$routes->get('/login', 'Login::index');
$routes->get('/dashboard', 'Dashboard::index'); // Tambahan rute dashboard Nasywa

// --- RUTE LOGISTIK & API (Tugas Cindy) ---
$routes->group('logistik', function($routes) {
    $routes->get('tesongkir', 'Logistik::tesOngkir');
    $routes->post('tesongkir', 'Logistik::tesOngkir');
    $routes->get('testemail', 'Logistik::testEmail');
    $routes->get('test-db', 'Logistik::testSimpanOrder');
    $routes->post('simpan-pesanan', 'Logistik::simpanPesanan');
    $routes->get('daftar-pesanan', 'Logistik::daftarPesanan');
    $routes->get('hapus-pesanan/(:num)', 'Logistik::hapusPesanan/$1');
});

// --- RUTE OTOMASI & RENDER (Tugas Vanti) ---
$routes->get('otomasi', 'Otomasi::index');
// Tambahkan rute render jika Vanti sudah membuat controller-nya

// --- [SEMENTARA] Diagnostik API Komerce - hapus setelah selesai ---
$routes->get('diagnostik-api', 'DiagnostikAPI::index');
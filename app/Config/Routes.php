<?php

namespace Config;

$routes = \Config\Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// --- 1. RUTE UMUM (Akses Publik) ---
$routes->get('/', 'Auth::index'); 
$routes->get('login', 'Auth::login'); 
$routes->get('register', 'Auth::register'); 
$routes->get('logout', 'Auth::logout');
$routes->get('katalog', 'Auth::getKatalog'); // Katalog bisa dilihat tanpa login

// --- 2. AUTHENTICATION ACTIONS ---
$routes->post('auth/register_action', 'Auth::register_action');
$routes->post('auth/login_action', 'Auth::login_action');

// --- 3. DETAIL TEMA (Static Pages) ---
$routes->get('katalog/scrapbook', 'Auth::getScrapbook');
$routes->get('katalog/vintage', 'Auth::getVintage');
$routes->get('katalog/mafia', 'Auth::getMafia');
$routes->get('katalog/streetwear', 'Auth::getUrban');
$routes->get('katalog/grand-academy', 'Auth::getGrandAcademy');
$routes->get('katalog/formal', 'Auth::getFormal');
$routes->get('katalog/game', 'Auth::getGame');

// --- BARIS UNTUK MESIN OTOMASI VANTI ---
$routes->get('otomasi', 'Otomasi::index');
$routes->post('otomasi/prosesGame', 'Otomasi::prosesGame');
$routes->get('otomasi/downloadAlbum/(:segment)', 'Otomasi::downloadAlbum/$1');
$routes->post('otomasi/prosesScrapbook', 'Otomasi::prosesScrapbook');
$routes->get('otomasi/downloadScrapbook/(:segment)', 'Otomasi::downloadScrapbook/$1');

// --- 4. AREA TERPROTEKSI (Harus Login) ---
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // Dashboard & Pengaturan Umum
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('pengaturan', 'Auth::getPengaturan');
    
    // --- FITUR USER (PEMBELI) ---
    $routes->get('user/history', 'User::history'); 
    $routes->get('pembayaran', 'Auth::getPembayaran');
    $routes->post('pembayaran/konfirmasi', 'Auth::prosesPembayaran'); 
    
    // Proses Order
    $routes->get('order/create', 'Order::create');
    $routes->post('order/checkout', 'Order::checkout');
    
    // --- AREA VENDOR / DESAINER ---
    $routes->group('vendor', function($routes) {
        $routes->get('/', 'Vendor::index');
        $routes->get('dashboard', 'Vendor::index');
        $routes->get('pesanan', 'Vendor::pesanan');
        $routes->get('profil', 'Vendor::profil');
        
        // Portofolio Vendor
        $routes->get('portfolio', 'Vendor::portfolio'); 
        $routes->post('portfolio/save', 'Vendor::savePortfolio'); // Rute simpan
        
        // Aksi Pesanan
        $routes->get('updateStatus/(:num)', 'Vendor::updateStatus/$1');
    });

    // Role Lain (Opsional)
    $routes->get('desainer/dashboard', 'Desainer::index');
    $routes->get('admin/dashboard', 'Admin::index');
});

// --- 5. RUTE LOGISTIK & API ---
$routes->group('logistik', function ($routes) {
    $routes->get('tesongkir',    'Logistik::tesOngkir');
    $routes->post('tesongkir',   'Logistik::tesOngkir');
    $routes->post('detail-pesanan', 'Logistik::detailPesanan');
    $routes->post('simpan-pesanan', 'Logistik::simpanPesanan');
    $routes->get('daftar-pesanan',  'Logistik::daftarPesanan');
    $routes->post('hapus-pesanan/(:num)', 'Logistik::hapusPesanan/$1');
    $routes->get('testemail',    'Logistik::testEmail');
    $routes->get('test-db',       'Logistik::testSimpanOrder');
});

// --- 6. DIAGNOSTIK ---
$routes->get('diagnostik-api', 'DiagnostikAPI::index');
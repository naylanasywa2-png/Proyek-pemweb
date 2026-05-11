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

// --- 1. RUTE UMUM (Tanpa Login) ---
$routes->get('/', 'Auth::index'); 
$routes->get('katalog', 'Auth::getKatalog'); 

// --- 2. AUTHENTICATION ---
$routes->get('login', 'Auth::login'); 
$routes->get('register', 'Auth::register'); 
$routes->post('auth/register_action', 'Auth::register_action');
$routes->post('auth/login_action', 'Auth::login_action');
$routes->get('logout', 'Auth::logout');

// --- 3. DETAIL TEMA KATALOG ---
$routes->get('katalog/scrapbook', 'Auth::getScrapbook');
$routes->get('katalog/vintage', 'Auth::getVintage');
$routes->get('katalog/mafia', 'Auth::getMafia');
$routes->get('katalog/streetwear', 'Auth::getUrban');
$routes->get('katalog/grand-academy', 'Auth::getGrandAcademy');
$routes->get('katalog/formal', 'Auth::getFormal');
$routes->get('katalog/game', 'Auth::getGame');

// --- 4. AREA TERPROTEKSI (Harus Login / Filter Auth) ---
// Semua yang ada di dalam group ini akan dicek oleh satpam AuthFilter
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // Pesanan Saya
    $routes->get('user/history', 'User::history'); 
    
    // HALAMAN PEMBAYARAN (Baru ditambahkan ✨)
    $routes->get('pembayaran', 'Auth::getPembayaran');
    $routes->post('pembayaran/konfirmasi', 'Auth::prosesPembayaran'); // Jika nanti ada upload bukti
    
    // Proses Order
    $routes->get('order/create', 'Order::create');
    $routes->post('order/checkout', 'Order::checkout');
    
    // Area Khusus (Role Based)
    $routes->get('desainer/dashboard', 'Desainer::index');
    $routes->get('admin/dashboard', 'Admin::index');
    $routes->get('vendor/dashboard', 'Vendor::index');
});
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

// --- RUTE PUBLIK ---
$routes->get('/', 'Auth::getKatalog'); 
$routes->get('login', 'Auth::index'); 
$routes->get('register', 'Auth::register');
$routes->get('katalog', 'Auth::getKatalog');

// Rute Tema Katalog (Ditangani Auth & User)
$routes->get('katalog/scrapbook', 'Auth::getScrapbook');
$routes->get('katalog/vintage', 'Auth::getVintage');
$routes->get('katalog/mafia', 'Auth::getMafia');
$routes->get('katalog/streetwear', 'Auth::getUrban');
$routes->get('katalog/grand-academy', 'Auth::getGrandAcademy');
$routes->get('katalog/formal', 'Auth::formal'); // Diubah ke User agar sinkron
$routes->get('katalog/game', 'Auth::getGame');

// --- BARIS UNTUK MESIN OTOMASI VANTI ---
$routes->get('otomasi', 'Otomasi::index');
$routes->post('otomasi/prosesGame', 'Otomasi::prosesGame');
$routes->post('otomasi/prosesGame', 'Otomasi::prosesGame');
$routes->get('otomasi/downloadAlbum/(:segment)', 'Otomasi::downloadAlbum/$1');
$routes->post('otomasi/prosesScrapbook', 'Otomasi::prosesScrapbook');
$routes->get('otomasi/downloadScrapbook/(:segment)', 'Otomasi::downloadScrapbook/$1');

// Proses Form & Auth
$routes->post('auth/register_action', 'Auth::register_action');
$routes->post('auth/login_action', 'Auth::login_action');
$routes->get('logout', 'Auth::logout');


// --- AREA TERPROTEKSI (Filter Auth) ---
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('admin/dashboard', 'Admin::index');
    $routes->get('user/home', 'User::index');
    
    // Rute History & User Area
    $routes->get('user/history', 'User::history'); 
    
    $routes->get('vendor/dashboard', 'Vendor::index');
    $routes->get('desainer/dashboard', 'Desainer::index');
    
    $routes->post('order/checkout', 'Order::checkout');
    $routes->get('order/create', 'Order::create');
    
});
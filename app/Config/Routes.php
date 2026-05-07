<?php

namespace Config;

$routes = \Config\Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
// GANTI baris di bawah ini jadi index supaya standar
$routes->setDefaultMethod('index'); 

$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// --- RUTE PUBLIK ---
$routes->get('/', 'Auth::getKatalog'); 
$routes->get('katalog', 'Auth::getKatalog');
$routes->get('login', 'Auth::index'); 
$routes->get('register', 'Auth::register');

// Proses Form & Auth
$routes->post('auth/register_action', 'Auth::register_action');
$routes->post('auth/login_action', 'Auth::login_action');
$routes->get('logout', 'Auth::logout');

// Rute Tema Katalog
$routes->get('katalog/scrapbook', 'Auth::getScrapbook');
$routes->get('katalog/vintage', 'Auth::getVintage');
$routes->get('katalog/mafia', 'Auth::getMafia');
$routes->get('katalog/streetwear', 'Auth::getUrban');
$routes->get('katalog/grand-academy', 'Auth::getGrandAcademy');
$routes->get('katalog/formal', 'Auth::getFormal');
$routes->get('katalog/game', 'Auth::getGame');

// --- AREA DESAINER (Keluarkan dari filter dulu supaya bisa test) ---
$routes->get('desainer/dashboard', 'Desainer::index');
$routes->post('desainer/upload_desain', 'Desainer::upload_desain');

// --- AREA TERPROTEKSI LAINNYA ---
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('user/home', 'User::index');
    $routes->get('admin/dashboard', 'Admin::index');
    $routes->get('vendor/dashboard', 'Vendor::index');
    
    $routes->get('user/history', 'User::history'); 
    $routes->get('order/create', 'Order::create');
    $routes->post('order/checkout', 'Order::checkout');
    $routes->get('order/history', 'Order::history');
});
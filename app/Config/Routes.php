<?php

namespace Config;

$routes = \Config\Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Settings
 * --------------------------------------------------------------------
 */
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

// --- 1. RUTE UTAMA ---
$routes->get('/', 'Auth::index'); 
$routes->get('katalog', 'Auth::getKatalog'); 

// --- 2. AUTHENTICATION (DIPERBAIKI) ---
$routes->get('login', 'Auth::login'); 

// Baris ini yang memastikan link 'Daftar' bekerja
$routes->get('register', 'Auth::register'); 

// Tambahan: Mengatasi jika user mengetik index.php di URL secara manual
$routes->get('index.php/register', 'Auth::register');
$routes->get('index.php/login', 'Auth::login');

// Proses Form Action
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

// --- 4. AREA DESAINER ---
$routes->get('desainer/dashboard', 'Desainer::index');
$routes->post('desainer/upload_desain', 'Desainer::upload_desain');

// --- 5. AREA TERPROTEKSI ---
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('user/home', 'User::index');
    $routes->get('admin/dashboard', 'Admin::index');
    $routes->get('vendor/dashboard', 'Vendor::index');
    $routes->get('user/history', 'User::history'); 
    $routes->get('order/create', 'Order::create');
    $routes->post('order/checkout', 'Order::checkout');
    $routes->get('order/history', 'Order::history');
});
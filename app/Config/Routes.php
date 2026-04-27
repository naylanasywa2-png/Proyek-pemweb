<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// --- PINTU MASUK (Bisa diakses siapa saja) ---
$routes->get('/', 'Auth::index');
$routes->get('/auth', 'Auth::index');
$routes->get('/register', 'Auth::register');
$routes->post('auth/register_action', 'Auth::register_action');
$routes->post('auth/login_action', 'Auth::login_action');
$routes->get('/logout', 'Auth::logout');

// --- AREA TERLARANG (Harus Login / Dijaga Satpam) ---
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // Dashboard Utama sesuai Role
    $routes->get('admin/dashboard', 'Admin::index');
    $routes->get('user/home', 'User::index');
    $routes->get('vendor/dashboard', 'Vendor::index');
    $routes->get('desainer/dashboard', 'Desainer::index');

    // Fitur Transaksi & Escrow (Sudah masuk dalam grup filter)
    $routes->post('order/checkout', 'Order::checkout');
    $routes->get('order/bayar/(:num)', 'Order::bayar/$1');
    $routes->get('order/kirim/(:num)', 'Order::kirim/$1');
    $routes->get('order/selesai/(:num)', 'Order::konfirmasi_selesai/$1');
    
});

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
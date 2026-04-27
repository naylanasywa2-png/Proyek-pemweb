<?php

namespace Config;

use App\Controllers\Services;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// --- PINTU MASUK ---
$routes->get('/', 'Auth::index');
$routes->get('/auth', 'Auth::index');
$routes->get('/register', 'Auth::register');
$routes->post('auth/register_action', 'Auth::register_action');
$routes->post('auth/login_action', 'Auth::login_action');
$routes->get('/logout', 'Auth::logout');

// --- PUNYA VANTI (Otomasi) ---
$routes->get('otomasi', 'Otomasi::index');
$routes->get('otomasi-test', 'Otomasi::test');

// --- AREA TERLARANG (Filter Auth) ---
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('admin/dashboard', 'Admin::index');
    $routes->get('user/home', 'User::index');
    $routes->get('vendor/dashboard', 'Vendor::index');
    $routes->get('desainer/dashboard', 'Desainer::index');

    $routes->post('order/checkout', 'Order::checkout');
    $routes->get('order/bayar/(:num)', 'Order::bayar/$1');
    $routes->get('order/kirim/(:num)', 'Order::kirim/$1');
    $routes->get('order/selesai/(:num)', 'Order::konfirmasi_selesai/$1');
});

if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
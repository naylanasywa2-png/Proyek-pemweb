<?php

namespace Config;

use App\Controllers\Services;

$routes = \Config\Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// Halaman utama: Mengarah ke fungsi index di Home Controller
$routes->get('/', 'Home::index');
$routes->get('/katalog', 'Home::katalog');
$routes->get('login', 'Login::index'); 
$routes->post('login/auth', 'Login::auth');

$routes->get('order/create', 'Order::create');
$routes->get('order/create', 'Order::create');
$routes->post('order/checkout', 'Order::checkout');
$routes->get('user/history', 'User::history');

$routes->get('/katalog/game', 'Home::game');
$routes->get('/katalog/scrapbook', 'Home::scrapbook');
$routes->get('/katalog/vintage', 'Home::vintage');
$routes->get('/katalog/mafia', 'Home::mafia');
$routes->get('dashboard', 'Home::index');
$routes->get('logout', 'Auth::logout'); // Sesuaikan jika kamu sudah buat Controller Auth

// Jika nanti kamu buat form pemesanan, tambahkan di sini:
// $routes->get('user/form_order', 'User::form_order');
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
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'Login::index');
$routes->get('logistik/testemail', 'Logistik::testEmail');
$routes->get('logistik/tesongkir', 'Logistik::tesOngkir');
$routes->post('logistik/tesongkir', 'Logistik::tesOngkir');
$routes->get('logistik/test-db', 'Logistik::testSimpanOrder');
$routes->post('logistik/simpan-pesanan', 'Logistik::simpanPesanan');
$routes->get('logistik/daftar-pesanan', 'Logistik::daftarPesanan');
$routes->get('logistik/hapus-pesanan/(:num)', 'Logistik::hapusPesanan/$1');

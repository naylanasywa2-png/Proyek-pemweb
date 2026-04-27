<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'Login::index');
$routes->get('otomasi', 'Otomasi::index');
$routes->get('otomasi-test', 'Otomasi::test');
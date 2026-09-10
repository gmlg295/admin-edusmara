<?php

use CodeIgniter\Router\RouteCollection;
//routes for SMS (School Management System)
if (is_file(APPPATH . 'Modules/SMS/Config/Routes.php')) {
    require APPPATH . 'Modules/SMS/Config/Routes.php';
}
/** @var RouteCollection $routes */
$routes->get('/', 'Auth::index');

$routes->group('home', ['filter' => 'adminfilter'], function($routes) {
    $routes->get('/', 'Home::index');
});

$routes->group('auth', function($routes) {
    $routes->get('/', 'Auth::index');
    $routes->post('login', 'Auth::login');
    $routes->get('refresh-captcha', 'Auth::refreshCaptcha');
    $routes->get('device-conf', 'Auth::getDeviceConf');
    $routes->post('device-logs', 'DeviceLog::store');
    $routes->get('logout', 'Auth::logout');
});

$routes->group('dashboard', ['filter' => 'adminfilter'], function($routes) {
    $routes->get('/', 'Dashboard::index');
});

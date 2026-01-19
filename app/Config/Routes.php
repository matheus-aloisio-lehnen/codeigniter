<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('api', function (RouteCollection $routes) {
    $routes->get('/', 'HomeController::index');
    $routes->post('login', 'AuthController::login');
    $routes->resource('user', ['controller' => 'UserController', 'except' => ['new', 'edit'],]);
});
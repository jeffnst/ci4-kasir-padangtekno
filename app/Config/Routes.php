<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login');
$routes->group('api', ['namespace' => 'App\Controllers\Api', 'filter' => 'authapi'], function ($routes) {
    $routes->get('users', 'UserController::index');  // GET all users
    $routes->get('users/(:num)', 'UserController::show/$1');  // GET user by ID
    $routes->post('users', 'UserController::create');  // Create user
    $routes->put('users/(:num)', 'UserController::update/$1');  // Update user
    $routes->delete('users/(:num)', 'UserController::delete/$1');  // Delete user
});

$routes->post('api/login', 'Api\AuthController::login');
$routes->get('/login', 'AuthController::login');
$routes->post('/auth/login', 'AuthController::processLogin');
$routes->get('/logout', 'AuthController::logout');

$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'auth']);

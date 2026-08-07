<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Auth::login');

// Authentication Routes
$routes->group('auth', function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('processLogin', 'Auth::processLogin');
    $routes->get('register', 'Auth::register');
    $routes->post('processRegister', 'Auth::processRegister');
    $routes->get('logout', 'Auth::logout');
});

// Customer Routes
$routes->group('customer', function($routes) {
    $routes->get('dashboard', 'Customer::dashboard');
    $routes->get('carDetails/(:num)', 'Customer::carDetails/$1');
    $routes->get('makeReservation/(:num)', 'Customer::makeReservation/$1');
    $routes->post('processReservation', 'Customer::processReservation');
    $routes->get('payment/(:num)', 'Customer::payment/$1');
    $routes->post('processPayment', 'Customer::processPayment');
    $routes->get('myReservations', 'Customer::myReservations');
    $routes->get('cancelReservation/(:num)', 'Customer::cancelReservation/$1');

    $routes->get('profile', 'Customer::profile');
$routes->post('updateProfile', 'Customer::updateProfile');
$routes->post('changePassword', 'Customer::changePassword');
});

// Admin Routes
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('getRevenueData', 'Admin::getRevenueData');
    
    // Car Management
    $routes->get('manageCars', 'Admin::manageCars');
    $routes->get('addCar', 'Admin::addCar');
    $routes->post('storeCar', 'Admin::storeCar');
    $routes->get('editCar/(:num)', 'Admin::editCar/$1');
    $routes->post('updateCar/(:num)', 'Admin::updateCar/$1');
    $routes->get('deleteCar/(:num)', 'Admin::deleteCar/$1');
    
    // Customer Management
    $routes->get('manageCustomers', 'Admin::manageCustomers');
    $routes->get('getCustomerDetails/(:num)', 'Admin::getCustomerDetails/$1');
    
    // Reservation Management
    $routes->get('manageReservations', 'Admin::manageReservations');
    $routes->post('updateReservationStatus/(:num)', 'Admin::updateReservationStatus/$1');
    $routes->get('viewReservation/(:num)', 'Admin::viewReservation/$1');
    
    // Payment Management
    $routes->get('managePayments', 'Admin::managePayments');
    $routes->get('printReceipt/(:num)', 'Admin::printReceipt/$1');
    // Add to your Routes.php in the admin group
$routes->get('api/payments/(:num)', 'Admin::getPaymentDetails/$1');
    
    // Reports
    $routes->get('reports', 'Admin::reports');
    $routes->get('printCarManagementReport', 'Admin::printCarManagementReport');
    $routes->get('printReservationsReport', 'Admin::printReservationsReport');
    $routes->get('printCustomersReport', 'Admin::printCustomersReport');
$routes->get('printPaymentsReport', 'Admin::printPaymentsReport');

// Profile Management
$routes->get('profile', 'Admin::profile');
$routes->post('updateProfile', 'Admin::updateProfile');
$routes->post('changePassword', 'Admin::changePassword');
});

// API Routes for Cars
$routes->group('cars', function($routes) {
    $routes->get('/', 'Cars::index');
    $routes->get('(:num)', 'Cars::show/$1');
    $routes->post('/', 'Cars::store');
    $routes->post('(:num)', 'Cars::update/$1');
    $routes->delete('(:num)', 'Cars::delete/$1');
    $routes->get('available', 'Cars::getAvailableCars');
    $routes->get('checkAvailability/(:num)', 'Cars::checkAvailability/$1');
});

// API Routes for Reservations
$routes->group('reservations', function($routes) {
    $routes->get('/', 'Reservations::index');
    $routes->get('(:num)', 'Reservations::show/$1');
    $routes->post('/', 'Reservations::store');
    $routes->post('updateStatus/(:num)', 'Reservations::updateStatus/$1');
    $routes->post('cancel/(:num)', 'Reservations::cancel/$1');
    $routes->get('calculateCost', 'Reservations::calculateCost');
    $routes->get('userReservations', 'Reservations::getUserReservations');
});

$routes->get('(:any)', function() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
});

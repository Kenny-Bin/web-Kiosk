<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

//$routes->get('test', 'Test::index');

$routes->group('webReceipt', function ($webReceiptRoutes) {

    $webReceiptRoutes->get('visitChk', 'WebReceipt::visitChk');
    $webReceiptRoutes->post('name', 'WebReceipt::nameWrite');
    $webReceiptRoutes->get('name', 'WebReceipt::nameWrite');
    $webReceiptRoutes->post('phone', 'WebReceipt::phoneWrite');
    $webReceiptRoutes->post('number', 'WebReceipt::birthPhoneWrite');
    $webReceiptRoutes->get('number', 'WebReceipt::birthPhoneWrite');
    $webReceiptRoutes->post('search', 'WebReceipt::checkPtnt');
    $webReceiptRoutes->post('jno', 'WebReceipt::jnoWrite');
    $webReceiptRoutes->post('address', 'WebReceipt::addressWrite');
    $webReceiptRoutes->post('visitInfo', 'WebReceipt::visitInfoWrite');
    $webReceiptRoutes->post('rectInfo', 'WebReceipt::rectInfo');
    $webReceiptRoutes->post('receipt', 'WebReceipt::receipt');
    $webReceiptRoutes->post('treatment', 'WebReceipt::treatment');
    $webReceiptRoutes->post('visitPath', 'WebReceipt::visitPath');

});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
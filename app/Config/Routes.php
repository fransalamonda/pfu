<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index');
$routes->get('/', 'Home::login');
$routes->get('/Home/beranda', 'Home::beranda');
$routes->post('/Home/login_act', 'Home::login_act');
$routes->get('/Home/logout', 'Home::logout');
$routes->get('/Home/register', 'Home::register');
$routes->post('/Home/register_auth', 'Home::register_auth');
$routes->post('/Home/auth_cek', 'Home::auth_cek');
$routes->post('/Home/register_pwu', 'Home::register_pwu');

// $routes->post('/Home/list_action', 'Home::list_action');
//$routes->post('/Home/open_detail', 'Home::open_detail');
// $routes->post('/Home/view_action', 'Home::view_action');
// $routes->get('/Home/condition', 'Home::condition');

$routes->get('/Wbi', 'Wbi::index');
$routes->get('/Wbi/beranda', 'Wbi::beranda');
$routes->post('/Wbi/login_act', 'Wbi::login_act');
$routes->post('/Wbi/list_data', 'Wbi::list_data');
$routes->post('/Wbi/list_history', 'Wbi::list_history');
$routes->post('/Wbi/add_act', 'Wbi::add_act');
$routes->post('/Wbi/add_act_pending', 'Wbi::add_act_pending');
$routes->post('/Wbi/add_act_in_progress', 'Wbi::add_act_in_progress');

$routes->post('/Wbi/ms_plant', 'Wbi::ms_plant');
$routes->post('/Wbi/list_action', 'Wbi::list_action');
$routes->post('/Wbi/open_detail', 'Wbi::open_detail');
$routes->post('/Wbi/view_action', 'Wbi::view_action');
$routes->post('/Wbi/view_action_pending', 'Wbi::view_action_pending');
$routes->post('/Wbi/view_action_inprogress', 'Wbi::view_action_inprogress');
$routes->post('/Wbi/view_action_history', 'Wbi::view_action_history');
$routes->get('/Wbi/condition', 'Wbi::condition');

//$routes->get('/In_progress', 'In_progress::index');
//$routes->get('/In_progress', 'In_progress::index');
$routes->get('/Upload_schedule', 'Upload_schedule::index');
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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

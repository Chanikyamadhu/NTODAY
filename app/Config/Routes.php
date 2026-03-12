<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes  
 */

// --- 1. పబ్లిక్ రూట్స్ (Home & News) ---
$routes->get('/', 'Home::index');
$routes->get('news/(:any)', 'Home::view/$1');
$routes->get('category/(:any)', 'NewsController::category/$1');
$routes->get('trending', 'NewsController::trending');
$routes->get('videos', 'Home::videos');
// app/Config/Routes.php
$routes->get('privacy_policy', 'Home::privacyPolicy');
$routes->get('terms_conditions', 'Home::termsConditions');
$routes->get('editors_policy', 'Home::editorsPolicy');
$routes->get('reporters_policy', 'Home::reportersPolicy');
// app/Config/Routes.php లో యాడ్ చేయండి
$routes->get('contact-us', 'Home::contactUs');

$routes->get('live-tv', 'Home::live_tv');
$routes->get('live-radio', 'Home::live_radio');

// --- 2. ఆథెంటికేషన్ రూట్స్ (Auth Module) ---
$routes->get('login', 'AuthController::index');
$routes->post('login/auth', 'AuthController::auth');
$routes->get('logout', 'AuthController::logout');
$routes->get('signup', 'AuthController::signup');
$routes->post('signup/store', 'AuthController::store');

// Forgot Password
$routes->get('login/forgotPassword', 'AuthController::forgotPassword');
$routes->post('login/sendResetLink', 'AuthController::processForgotPassword');

// --- 3. అడ్మిన్ గ్రూప్ (Admin Module) ---
// ఇక్కడ 'authGuard:admin' లేదా 'auth:admin' మీ ఫిల్టర్ పేరును బట్టి మార్చుకోండి
$routes->group('admin', ['filter' => 'authGuard:admin'], function($routes) {
    
    // Dashboard & Global Controls
    $routes->get('dashboard', 'Admin\AdminController::index');
    $routes->get('approve/(:num)', 'Admin\AdminController::approveReporter/$1');
    $routes->get('reject/(:num)', 'Admin\AdminController::rejectReporter/$1');

    // User & Staff Management
    $routes->get('reporters', 'Admin\AdminController::manageReporters');
    $routes->get('reporters/manage', 'Admin\AdminController::manageReporters');
    $routes->get('reporters/pending', 'Admin\AdminController::pendingReporters');
    $routes->get('reporters/approve/(:num)', 'Admin\AdminController::approveReporter/$1');
    $routes->get('reporters/reject/(:num)', 'Admin\AdminController::rejectReporter/$1');

    // Add this line for editing reporters
    $routes->get('reporters/edit/(:num)', 'Admin\AdminController::editReporter/$1');
    $routes->post('reporters/update/(:num)', 'Admin\AdminController::updateReporter/$1');

    $routes->get('reporters/stats/(:num)', 'Admin\AdminController::reporterStats/$1');

    // రిపోర్టర్ డీయాక్టివేషన్ కోసం
    $routes->get('reporters/deactivate/(:num)', 'Admin\AdminController::deactivateReporter/$1');
    $routes->get('reporters/activate/(:num)', 'Admin\AdminController::activateReporter/$1');
    
    $routes->get('editors', 'Admin\AdminController::manageEditors');
    $routes->get('editors/manage', 'Admin\AdminController::manageEditors');
    $routes->get('editors/add', 'Admin\AdminController::addEditor');
    $routes->post('editors/store', 'Admin\AdminController::storeEditor');

    // News Management (Nested Group)
    $routes->group('news', function($routes) {
        $routes->get('/', 'Admin\News::index');
        $routes->get('manage', 'Admin\News::index');
        $routes->get('pending', 'Admin\News::pending');
        $routes->get('approved', 'Admin\News::approved');
        $routes->get('add', 'Admin\News::add');
        $routes->post('store', 'Admin\News::store');
        $routes->get('approve/(:num)', 'Admin\News::approve/$1');
        $routes->post('reject/(:num)', 'Admin\News::reject/$1');
        $routes->get('edit/(:num)', 'Admin\News::edit/$1');
        $routes->post('update/(:num)', 'Admin\News::update/$1');
        $routes->get('delete/(:num)', 'Admin\News::delete/$1');

        $routes->post('news/uploadImage', 'Admin\News::uploadImage');
    });

    // Categories & Locations
    $routes->group('categories', function($routes) {
        $routes->get('/', 'Admin\CategoryController::index');
        $routes->post('store', 'Admin\CategoryController::store');
        $routes->post('update', 'Admin\CategoryController::update'); 
        $routes->get('toggleStatus/(:num)/(:num)', 'Admin\CategoryController::toggleStatus/$1/$2');
        $routes->get('delete/(:num)', 'Admin\CategoryController::delete/$1');
        
        // API Calls
        $routes->get('getChildren/(:num)', 'Admin\CategoryController::getChildren/$1');
        $routes->get('getLocationDetails/(:num)', 'Admin\CategoryController::getLocationDetails/$1');
    });

    // Banner Ads Management
    $routes->get('banners', 'Admin\AdminController::manageBanners');
    $routes->post('banners/store', 'Admin\AdminController::storeBanner');
    $routes->get('banners/delete/(:num)', 'Admin\AdminController::deleteBanner/$1');
});

// --- 4. రిపోర్టర్ గ్రూప్ (Reporter Module) ---
$routes->group('reporter', ['filter' => 'authGuard:reporter'], function($routes) {
    
    $routes->get('dashboard', 'ReporterController::index');
    $routes->get('add-news', 'ReporterController::create');
    $routes->post('store', 'ReporterController::store');
    $routes->get('edit/(:num)', 'ReporterController::edit/$1');
    $routes->post('update/(:num)', 'ReporterController::update/$1');
    $routes->get('delete/(:num)', 'ReporterController::delete/$1');
    $routes->get('logout', 'AuthController::logout');
    
    $routes->get('profile', 'ReporterController::profile');
    $routes->post('update-profile', 'ReporterController::updateProfile');
    
    // AJAX / Dynamic Selects
    $routes->get('categories/getChildren/(:num)', 'ReporterController::getChildren/$1');
});
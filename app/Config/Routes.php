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
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Default route to home page (optional)
$routes->get('/', 'LoginController::login');

// Routes for login and logout
// $routes->get('login', 'LoginController::login');              // Show login form
$routes->post('login/authenticate', 'LoginController::authenticate');  // Handle login submission
$routes->get('logout', 'LoginController::logout');             // Handle logout

// Routes for user registration
$routes->get('register', 'RegistrationController::register');  // Show registration form
$routes->post('register/process', 'RegistrationController::processRegistration');  // Handle registration

// Admin routes - these routes are only accessible to users with 'Admin' role
$routes->group('admin', ['filter' => 'role:Admin'], function($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    // Display the settings page (list all questions)
    $routes->get('settings', 'AdminController::settings');
    
    // Add a new question
    $routes->post('addQuestion', 'AdminController::addQuestion');
    
    // Edit an existing question (GET request to load the edit form)
    $routes->get('editQuestion/(:num)', 'AdminController::editQuestion/$1');
    
    // Update the question after editing (POST request to update the question)
    $routes->post('updateQuestion/(:num)', 'AdminController::updateQuestion/$1');
    
    // Delete a question
    $routes->post('deleteQuestion/(:num)', 'AdminController::deleteQuestion/$1');
});

// Client routes - these routes are only accessible to users with 'Client' role
$routes->group('client', ['filter' => 'role:Client'], function($routes) {
    $routes->get('questionnaire', 'ClientController::questionnaire');  // Client: View and answer questions
    $routes->post('submitAnswers', 'ClientController::submitAnswers'); // Client: Submit answers
    $routes->post('captureScreenshotAjax', 'ClientController::captureScreenshotAjax');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * Environment-based routes can override any of the above routes.
 * Load additional route files depending on the current environment.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

<?php

// all requests are redirected to this file.
// use your .htaccess file to set this up.

error_reporting(-1);
require_once(__dir__ . '/../vendor/autoload.php');
require(__dir__ . '/../resources/config.php');


$klein = new Klein\Klein;

// First, let's setup the layout our site will use. By passing
// an anonymous function in Klein will respond to all methods/URI's.
$klein->respond(function ($request, $response, $service) {
    $service->layout('../resources/layouts/default.php');
});

// Home page view
$klein->respond('/', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'TempoVideo';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/home.php');
});

// Filmaanbod view
$klein->respond('/filmaanbod', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Filmaanbod';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/filmaanbod.php');
});

// Over Ons view
$klein->respond('/over_ons', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Over ons';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/over_ons.php');
});

// Contact view
$klein->respond('GET', '/contact', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Contact';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/contact.php');
});
// Contact view
$klein->respond('POST', '/contact', function ($request, $response, $service) {
  // add some data to the view.
  $service->pageTitle = 'Contact';

  // This is the function that renders the view inside the layout.
  $service->render(VIEWS.'/contact.php');
});

// HTTP ERRORS
$klein->onHttpError(function ($code, $router) {
    switch ($code) {
        case 404:
            $router->response()->body(
                '404 - Ik kan niet vinden waar u naar zoekt.'
            );
            break;
        case 405:
            $router->response()->body(
                '405 - U heeft geen toestemming hier te komen.'
            );
            break;
        default:
            $router->response()->body(
                'Oh nee, er is iets ergs gebeurt! Errorcode:'. $code
            );
    }
});

$klein->dispatch();

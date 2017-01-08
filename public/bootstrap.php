<!-- <?php

// all requests are redirected to this file.
// use your .htaccess file to set this up.

error_reporting(-1);
require_once(__dir__ . '/../vendor/autoload.php');
require(__dir__ . '/../resources/config.php');


$klein = new Klein\Klein;

// First, let's setup the layout our site will use. By passing
// an anonymous function in Klein will respond to all methods/URI's.
$klein->respond(function ($request, $response, $service) {
    $service->layout('layouts/default.php');
});

// Home page view
$klein->respond('/', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'TempoVideo';

    // This is the function that renders the view inside the layout.
    $service->render('views/home.php');
});

// Filmaanbod view
$klein->respond('/filmaanbod', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Filmaanbod';

    // This is the function that renders the view inside the layout.
    $service->render('../views/filmaanbod.php');
});

$klein->dispatch(); -->

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

// Home page view
$klein->respond('/mail', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'TempoVideo';

    // This is the function that renders the view inside the layout.
    $service->render(MAIL.'/testmail.php');
});

// Filmaanbod view
$klein->respond('/film/aanbod', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Filmaanbod';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/filmaanbod.php');
});
$klein->respond('/film/[:naam]', function ($request, $response, $service) {
    $service->layout('../resources/layouts/film.php');
});
$klein->respond('/film/[:naam]', function ($request, $response, $service) {
    // add some data to the view.
    $naam = $request->naam;
    $titelNaam = $naam;
    $titelNaam = str_replace('_', ' ', $titelNaam);
    $titelNaam = strtoupper($titelNaam);
    $service->pageTitle = $titelNaam;
    $service->filmNaam = $naam;

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/filmdetail.php');
});



// EIGENAAR film toevoegen
$klein->respond('/eigenaar/overzicht', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Filmtoevoegen';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/eigenaar_overzicht.php');
});

// EIGENAAR film toevoegen
$klein->respond('/eigenaar/film_toevoegen', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Filmtoevoegen';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/filmtoevoegen.php');
});


// EIGENAAR film verwijderen
$klein->respond('/eigenaar/film_verwijderen', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Film Verwijderen';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/filmverwijderen.php');
});

// EIGENAAR film info aanpassen
$klein->respond('/eigenaar/film_aanpassen', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Film Aanpassen';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/filmaanpassen.php');
});
// EIGENAAR Klant Blokkeren
$klein->respond('/eigenaar/klant_blokkeren', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Klant Blokkeren';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/klantblokkeren.php');
});


// KLANT overzicht
$klein->respond('/klant/overzicht', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Overzicht';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/klant_overzicht.php');
});
// Afrekenen
$klein->respond('/winkelmand/afrekenen', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Afrekenen';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/afrekenen.php');
});


// WINKELMAND
$klein->respond('/winkelmand', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Winkelmand';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/winkelmand.php');
});


// Over Ons view
$klein->respond('/over_ons', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Over ons';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/over_ons.php');
});

// Contact view
$klein->respond('/contact', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Contact';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/contact.php');
});


// Login view
$klein->respond('/login', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Login';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/login.php');
});


// Register view
$klein->respond('/registreer', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Registreer';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/registreer.php');
});

// Register view
$klein->respond('/uitloggen', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Uitloggen';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/uitloggen.php');
});

// COVER API
$klein->respond('/cover/[:naam]', function ($request, $response, $service) {
    $naam = $request->naam;
    $path = FOTO . "/" . $naam;

    $filename = basename($path);
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpeg"; break;
        default:
    }

    header('Content-Type:'.$ctype);
    header('Content-Length: ' . filesize($path));
    readfile($path);
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
$klein->respond('/baliemedewerker/overzicht', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Overzicht';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/baliemedewerker_overzicht.php');
});
$klein->respond('/baliemedewerker/inkomendeorders', function ($request, $response, $service) {
    // add some data to the view.
    $service->pageTitle = 'Overzicht';

    // This is the function that renders the view inside the layout.
    $service->render(VIEWS.'/inkomendeorders.php');
});
$klein->dispatch();

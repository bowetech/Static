<?php

/**
 * Static - A PHP Framework For Static Web Artisans
 *  
 *  PHP version 5.4
 *  Twig - Template engine
 *  Mailer - Email Script 
 *
 * @package  Static
 * @author   Clive Stewart <bowetech@gmail.com>
 * 
 */


/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application.
|
*/
require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/
$app = require_once __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Kernel\Kernel::class);

$response = $kernel->handle(
	$request =  Kernel\Request::capture()
);

$app->run();
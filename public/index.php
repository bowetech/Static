<?php

/**
 * Static - A PHP Framework For Static Web Artisans
 *  
 *  PHP version 7.4
 *  Twig - Template engine
 *  Mailer - Email Script 
 *
 * @package  Static
 * @author   Clive Stewart <bowetech@gmail.com>
 * 
 */

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists(__DIR__ . '/../cache/framework/maintenance.php')) {
	require __DIR__ . '/../cache/framework/maintenance.php';
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application.
|
*/
require_once __DIR__ . '/../vendor/autoload.php';

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
$app = require_once __DIR__ . '/../bootstrap/app.php';


$kernel = $app->make(Intelligent\Contracts\Kernel\Kernel::class);

$response = $kernel->handle(
	$request =  Intelligent\Kernel\Request::capture()
);

$app->run();

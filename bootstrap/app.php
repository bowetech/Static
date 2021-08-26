<?php

declare(strict_types=1);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Static application instance
| which serves as the "glue" for all the components of Static, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Intelligent\Kernel\Application(
	$_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);


/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
*/
$app->singleton(
	Intelligent\Contracts\Kernel\Kernel::class,
	App\Http\Kernel::class
);


/*
|--------------------------------------------------------------------------
| Error and Exception handling
|--------------------------------------------------------------------------
*/
error_reporting(true);
set_error_handler('Intelligent\Kernel\Error::errorHandler');
set_exception_handler('Intelligent\Kernel\Error::exceptionHandler');


/*
|--------------------------------------------------------------------------
| Load ENV ( Environment File)
|--------------------------------------------------------------------------
*/
$env = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$env->load();



/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/
return $app;

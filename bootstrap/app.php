<?php

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

$app = new Kernel\Application(
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

/*
|--------------------------------------------------------------------------
| Error and Exception handling
|--------------------------------------------------------------------------
*/
error_reporting(true);
set_error_handler('Kernel\Error::errorHandler');
set_exception_handler('Kernel\Error::exceptionHandler');

/*
|--------------------------------------------------------------------------
| Load ENV ( Environment File)
|--------------------------------------------------------------------------
*/
$env = new Symfony\Component\Dotenv\Dotenv;
$env->load(
	dirname(__DIR__) . '/.env'
);

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
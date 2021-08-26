<?php

declare(strict_types=1);

namespace App\Http;

use Intelligent\Kernel\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

	protected $middleware = [
		//\Intelligent\Kernel\Middleware\Session\StartSession::class,
		// \App\Http\Middleware\HandleCors::class,		
	];

	protected $routeMiddleware = [
		//'auth' => \App\Http\Middleware\Authenticate::class,
		//'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

	];
}

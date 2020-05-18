<?php

namespace Kernel\Support\Providers;

use Kernel\Router;
use Kernel\Application;
use Kernel\Support\ServiceProviderInterface;

class RouterServiceProvider implements ServiceProviderInterface
{
	protected $app;

	public function __construct(Application $app)
	{
		$this->app = $app;

		$this->register();
	}

	/**
	 * Register router application services.
	 *
	 * @return void   
	 */
	public function register(): void
	{
		$this->app->singleton('router', Router::class);
	}
}
<?php

namespace Kernel\Support;

use Kernel\Application;

interface ServiceProviderInterface
{
	/**
	 * Register the services to Container.
	 * @param Application $app
	 * @return void
	 */
	public function __construct(Application $app);


	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register();
}
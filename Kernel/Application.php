<?php

namespace Kernel;

use Kernel\Support\ServiceProviderInterface;
use Kernel\Support\Providers\RouterServiceProvider;

class Application extends Container
{
	/**
	 * The Static framework version.
	 *
	 * @var String
	 */
	const VERSION = '2.0.0';

	const DIRECTORY_SEPARATOR = "/";

	/**
	 * The base path for the Static installation.
	 *
	 * @var string
	 */
	protected $basePath;

	/**
	 * The base path for the Static installation.
	 *
	 * @var string
	 */
	protected  $loadedProviders = [];

	/**
	 * The base path for the Static installation.
	 *
	 * @var string
	 */
	public function __construct($basePath = null)
	{
		if ($basePath) {
			$this->setBasePath($basePath);
		}
		$this->registerBaseBindings();
		$this->registerBaseServiceProviders();
		$this->registerCoreContainerAliases();
	}

	/**
	 * Register services and start main the application services.
	 * @return void
	 */
	public function run()
	{
		//echo "<pre>";
		//print_r($this);
	}
	/**
	 * Get the version number of the application.
	 *
	 * @return String
	 */
	public function version()
	{
		return static::VERSION;
	}

	/**
	 * The base path for the Static installation.
	 *
	 * @var string
	 */
	public function setBasePath($basePath)
	{
		$this->basePath = rtrim($basePath, '\/');

		return $this;
	}

	/**
	 * Register the basic bindings into the container.
	 *
	 * @return void
	 */
	public function registerBaseBindings()
	{
		static::setInstance($this);
		$this->instance('app', $this);
		$this->instance('router', Router::class);
		$this->instance(Container::class, $this);
	}

	/**
	 * Register all of the base service providers.
	 *
	 * @return void
	 */
	public function registerBaseServiceProviders()
	{
		$this->register(new RouterServiceProvider($this));
	}

	/**
	 * Register the core class aliases in the container.
	 *
	 * @return void
	 */
	public function registerCoreContainerAliases()
	{
		foreach ([
			'app'                  => [self::class, \Kernel\Container::class],
			'router'               => [\Kernel\Router::class],

		] as $key => $aliases) {
			foreach ($aliases as $alias) {
				$this->alias($key, $alias);
			}
		}
	}

	/**
	 * Register Providers of the base service providers.
	 *
	 * @return void
	 */
	public function register(ServiceProviderInterface $provider)
	{
		if (!$this->providerHasBeenLoaded($provider)) {
			$provider->register($this);

			$this->loadedProviders[] = get_class($provider);
		}

		return $this;
	}

	/**
	 * Check if Providers service has been loaded.
	 *
	 * @return  boolean 
	 */
	protected function providerHasBeenLoaded(ServiceProviderInterface $provider)
	{
		return array_key_exists(get_class($provider), $this->loadedProviders);
	}
}
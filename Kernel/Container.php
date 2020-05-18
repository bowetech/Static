<?php

namespace Kernel;

use ArrayAccess;
use ReflectionClass;
use RuntimeException;

class Container  implements ArrayAccess
{
	/** 
	 * The current globally available container (if any).
	 *
	 * @var static
	 */
	protected static $instance;

	/**
	 * The container's bindings.
	 *
	 * @var array[]
	 */
	protected $bindings = [];

	/**
	 * The container's shared instances.
	 *
	 * @var object[]
	 */
	protected $instances = [];

	/**
	 * The registered type aliases.
	 *
	 * @var string[]
	 */
	protected $aliases = [];


	/**
	 * The registered aliases keyed by the abstract name.
	 *
	 * @var array[]
	 */
	protected $abstractAliases = [];

	/**
	 * Register a binding with the container.
	 *
	 * @param  string  $abstract
	 * @param  \Closure|string|null  $concrete
	 * * @param  bool  $singleton
	 * @return void
	 */
	public function bind($abstract, $concrete = null, $singleton = false)
	{

		$this->bindings[$abstract] =  compact('concrete', 'singleton');
	}

	/**
	 * Register a shared binding in the container.
	 *
	 * @param  string  $abstract
	 * @param  \Closure|string|null  $concrete
	 * @return void
	 */
	public function singleton($abstract, $concrete)
	{
		return $this->bind($abstract, $concrete, true);
	}

	/**
	 *  Check if instance is a singleton 
	 * @param string $abstract
	 * @return  boolean | array binding 
	 */
	public function isSingleton($abstract)
	{
		$binding = $this->getBinding($abstract);

		if ($binding === null) {
			return false;
		}

		return $binding['singleton'];
	}

	/**
	 * Get the container's bindings.
	 * @param string $key 
	 * @return array binding
	 */
	public function getBinding($key)
	{
		if (!array_key_exists($key, $this->bindings)) {
			return null;
		}
		return $this->bindings[$key];
	}

	/**
	 * Get the instance of a singleton if exsists.
	 * @param string $key 
	 * @return instance  singleton
	 */
	public function getSingletonInstance($key)
	{
		return $this->singletonResolved($key) ? $this->instances[$key] : null;
	}

	/**
	 * Checks if the given key or index exists in the array.
	 * @param string $key 
	 * @return array  singleton
	 */
	public function singletonResolved($key)
	{
		return array_key_exists($key, $this->instances);
	}

	/**
	 * Resolve the given type from the container.
	 *
	 * @param  string  $abstract
	 * @param  array  $parameters
	 * @return mixed
	 *
	 * @throws  Error  if no success
	 */
	public function make($abstract, array $parameters = [])
	{
		return $this->resolve($abstract, $parameters);
	}

	/**
	 * Get the alias for an abstract if available.
	 *
	 * @param  string  $abstract
	 * @return string
	 */
	public function getAlias($abstract)
	{
		if (!isset($this->aliases[$abstract])) {
			return $abstract;
		}

		return  $this->getAlias($this->aliases[$abstract]);
	}

	/**
	 * Resolve the given type from the container.
	 *
	 * @param  string  $abstract
	 * @param  array  $parameters
	 * @return mixed
	 */
	public function resolve($abstract,  $parameters = [])
	{
		$class = $this->getBinding($abstract);

		if ($class === null) {
			$class = $abstract;
		} else {
			$class = $class['concrete'];
		}

		if ($this->isSingleton($abstract) && $this->singletonResolved($abstract)) {

			return $this->getSingletonInstance($abstract);
		}

		$object =  $this->buildObject($class, $parameters);

		return $this->buildInstance($abstract, $object);
	}

	/**
	 * Build  Class dependence to supply to new instance of class
	 *
	 * @param  mixed  $class
	 * @param  array  $parameters
	 * @return object  $args
	 */
	public function buildObject($class, $parameters)
	{
		$reflection = new ReflectionClass($class);

		if (!$constructor = $reflection->getConstructor()) {

			return $reflection->newInstanceArgs($parameters);
		}

		$dependencies = $constructor->getParameters();

		$args = $this->buildDependencies($parameters, $dependencies, $class);

		return  $reflection->newInstanceArgs($args);
	}

	/**
	 * Build  Class dependence to supply to new instance of class
	 *
	 * @param  object $parameters
	 * @param  mixed  $dependencies
	 * @param  mixed  $class
	 * @return mixed  $parameters	 *
	 */
	protected function buildDependencies($parameters, $dependencies, $class)
	{
		foreach ($dependencies as $dependency) {

			if ($dependency->isOptional()) continue;

			if ($dependency->isArray()) continue;

			$class = $dependency->getClass();

			if ($class === null) continue;

			if (get_class($this) === $class->name) {
				array_unshift($parameters, $this);
				continue;
			}
			array_unshift($parameters, $this->resolve($class->name));
		}

		return $parameters;
	}

	/**
	 * Register a binding as an instance in the container or retrun object.
	 *
	 * @param  string  $key
	 * @param  mixed   $object
	 * @return mixed
	 */
	public function buildInstance($key, $object)
	{
		if ($this->isSingleton($key)) {
			$this->instances[$key] = $object;
		}

		return $object;
	}

	/**
	 * Register an existing instance as shared in the container.
	 *
	 * @param  string  $abstract
	 * @param  mixed   $instance
	 * @return mixed
	 */
	public function instance($abstract, $instance)
	{
		$this->instances[$abstract] = $instance;

		return $instance;
	}

	/**
	 * Set the shared instance of the container.
	 *
	 * @param  Container\null  $container
	 * @return  Container\Container|static
	 */
	public static function setInstance(Container $container = null)
	{
		return static::$instance = $container;
	}

	/**
	 * Get the globally available instance of the container.
	 *
	 * @return static
	 */
	public static function getInstance()
	{
		if (is_null(static::$instance)) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	/**
	 * Alias a type to a different name.
	 *
	 * @param  string  $abstract
	 * @param  string  $alias
	 * @return void
	 *
	 * @throws \RuntimeException
	 */
	public function alias($abstract, $alias)
	{
		if ($alias === $abstract) {
			throw new RuntimeException("[{$abstract}] is aliased to itself.");
		}

		$this->aliases[$alias] = $abstract;

		$this->abstractAliases[$abstract][] = $alias;
	}
	/**
	 * ArrayAccess interface functions 
	 */

	/**
	 * Get the value at a given offset.
	 *
	 * @param  string  $key
	 * @return mixed
	 */

	public function offsetGet($key)
	{
		return $this->resolve($key);
	}

	/**
	 * Set the value at a given offset.
	 *
	 * @param  string  $key
	 * @param  mixed  $value
	 * @return void
	 */
	public function offsetSet($key, $value)
	{
		return $this->bind($key, $value);
	}

	/**
	 * Determine if a given offset exists.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function offsetExists($key)
	{
		return  array_key_exists($key, $this->bindings);
	}

	/**
	 * Unset the value at a given offset.
	 *
	 * @param  string  $key
	 * @return void
	 */
	public function offsetUnset($key)
	{
		unset($this->bindings[$key]);
	}
}
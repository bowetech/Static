<?php

namespace Kernel\Support\Facades;

use RuntimeException;

/**
 * Facade 
 * 
 * @param String $name: name of the function
 * @param Array  $args : parameters of the function eg: $arg[0]
 * 
 * @return void  execute the function on the class returned from 
 * 				 getFacadeAccessor() function
 */
abstract class Facade
{

	public static function __callStatic($name, $args)
	{
		return app()->resolve(static::getFacadeAccessor())->$name(...$args);
	}

	protected static function getFacadeAccessor()
	{
		#  Overrided by the implimenting class 
		throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
	}
}
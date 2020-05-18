<?php

use Kernel\Env;
use  Kernel\Container;

/**
 * Get the available container instance.
 *
 * @param  string|null  $abstract
 * @param  array   $parameters
 * @return mixed|\Kernel\Application
 */
if (!function_exists('app')) {
	function app($abstract = null, array $parameters = [])
	{
		if (is_null($abstract)) {

			return Container::getInstance();
		}

		return Container::getInstance()->make($abstract, $parameters);
	}
}

/**
 * Converts string to StudlyCaps and removes hypens
 * 
 * @param string $string The string to convert
 * @return string in StudlyCaps
 */
if (!function_exists('convertToStudlyCaps')) {
	function convertToStudlyCaps($string)
	{
		return str_replace('-', '', ucwords($string, '-'));
	}
}

/**
 * Converts string to camelCase and removes hypens
 * 
 * @param string $string The string to convert
 * @return string in camelCase
 */
if (!function_exists('convertToCamelCase')) {
	function convertToCamelCase($string)
	{
		return  lcfirst(convertToStudlyCaps($string));
	}

	/**
	 * Get Environmental.
	 *
	 * @param  string  $key
	 * @param  string |null $default
	 * @return mixed 
	 */
	if (!function_exists('env')) {

		function env($key, $default = null)
		{
			return Env::get($key, $default);
		}
	}
}
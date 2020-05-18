<?php

namespace Kernel;

class Env

{
	/**
	 * Gets the value of an environment variable.
	 *
	 * @param  string  $key
	 * @param  mixed  $default
	 * @return mixed
	 */
	public static function get($key, $default = null)
	{
		$value = getenv($key);

		if ($value === false) {
			return $default;
		}
		switch (strtolower($value)) {
			case null:
			case '':
				return $default;
			case 'true':
			case '(true)':
				return true;
			case 'false':
			case '(false)':
				return false;
			case 'empty':
			case '(empty)':
				return '';
			case 'null':
			case '(null)':
				return $default;
		}


		return $value;
	}
}
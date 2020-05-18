<?php

namespace Kernel;

/**
 * Router
 * 
 */
class Router
{
	/**
	 * Associative array of routes (the routing table)
	 * @var array
	 */
	public static $routes = [];

	/**
	 *  array of paramas (the routing table)
	 * @var array
	 */
	protected  $params = [];


	public function __construct()
	{
		require_once __DIR__ . '/../routes/web.php';
	}

	/**
	 * Add a route to the routing table
	 * 
	 * @param string $route  The route URL
	 * @param array $paramas  Parameters (controller, action etc)	 * 
	 */
	public function get($route, $params = [])
	{
		$route = preg_replace('/\//', '\\/', $route);
		$route = preg_replace('/\{([a-z-]+)\}/', '(?P<\1>[a-z-]+)', $route);
		$route = preg_replace('/\{([a-z-]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
		$route = '/^' . $route . '$/i';

		self::$routes[$route] = $params;
	}

	/**
	 * Match the route to the routes in the routing table, setting the $params
	 * property if a route is found
	 * 
	 * @param string $url  The route URL
	 * @return boolen true if a match found , false otherwise	 * 
	 */
	public function match($url)
	{
		foreach (self::$routes as $route => $params) {

			if (preg_match($route, $url, $matches)) {

				foreach ($matches as $key => $match) {

					if (is_string($key)) {
						$params[$key] = $match;
					}
				}

				$this->params = $params;
				return true;
			}
		}

		return false;
	}

	/**	 
	 *  Return Params 
	 * 
	 * @param void
	 * @return mixed 
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * Get all the routes from the routing table
	 * 
	 * @return array
	 */
	public function getRoutes()
	{
		return self::$routes;
	}


	public function dispatcher($url)
	{
		$url = $this->removeQueryStringVariable($url);

		if ($this->match($url)) {
			$controller = convertToStudlyCaps($this->params['controller']);

			//$controller = "App\Controllers\\$controller";
			$controller = $this->getNamespace() . $controller;

			if (class_exists($controller)) {
				$controller_object = new $controller($this->params);

				$action = convertToCamelCase($this->params['action']);

				if (is_callable([$controller_object, $action])) {

					$controller_object->$action();
				} else {

					//echo "Action method $action not found"; 
					throw new \Exception("Method $action (in controller $controller) not found");
				}
			} else {

				//echo $controller ." Object NOT Found";
				throw new \Exception("Controller class $controller not found");
			}
		} else {

			//echo  'Controller class $controller no found';
			throw new \Exception('No route Matched.', 404);
		}
	}

	protected function removeQueryStringVariable($url)
	{
		if ($url != '') {
			$url_parts = explode('&', $url, 2);

			if (strpos($url_parts[0], '=') === false) {
				$url = $url_parts[0];
			} else {
				$url = '';
			}
		}
		return $url;
	}

	protected function getNamespace()
	{
		$namespace = 'App\Controllers\\';

		if (array_key_exists('namespace', $this->params)) {
			$namespace .= $this->params['namespace'] . '\\';
		}

		return $namespace;
	}
}
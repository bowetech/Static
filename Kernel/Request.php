<?php

namespace Kernel;

/**
 * Handles Http Requests 
 *  
 */
class Request
{
	/**
	 * Request body parameters ($_GET)
	 * 
	 * @var string
	 */
	public $get;

	/**
	 * Request body parameters ($_POST)
	 * 
	 * @var string
	 */
	public $post;

	/**
	 * Request body parameters ($rules)
	 * 
	 * @var string
	 */
	public $rules;

	/**
	 * Request body parameters ($errors)
	 * 
	 * @var array
	 */
	public $errors;


	/**
	 * Initializing the class properties
	 * 
	 * @return void
	 */
	public function __construct(array $get, array $post, array $rules)
	{
		$this->get = $get;
		$this->post = $post;
		$this->rules = $rules;
		$this->errors = $this->err();
	}

	/**
	 * Get the request method
	 * 
	 * @return static
	 */
	public static function capture()
	{
		return self::createRequest($_SERVER);
	}

	/**
	 * Get environment Globals
	 *
	 * @param   Request  $server Request
	 * @return static
	 */
	public static function createRequest(array $server = [])
	{
		return $server['QUERY_STRING'];
	}

	/**
	 * Passes the $_POST & $_GET global arrays to class constuctor 
	 * 
	 * @return Array new instance of the class 
	 */
	public static function createFromGlobals($rules)
	{
		return new static(
			$_GET,
			$_POST,
			$rules
		);
	}

	/**
	 * Validate require filds and holds the Errors of form 
	 * 
	 * @return Array  
	 */
	public function err()
	{
		foreach ($this->rules as $key => $rule) {

			$this->errors[]  = ['name' => $key, 'value' => $this->post[$key], 'rules' => $rule];
		}
		return	$this->errors;
	}
}
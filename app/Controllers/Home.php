<?php

namespace App\Controllers;

use Kernel\Controller;
use Kernel\View;
use Kernel\Config;

/**
 *
 * Frontpage Controller Class
 */
class Home extends Controller
{
	public function index()
	{
		$path = Config::get('app.name');

		return View::render('pages/home.html', [
			'firstname' => 'Clive',
			'lastname' => 'Stewart',
			'config' => $path,
		]);
	}
}
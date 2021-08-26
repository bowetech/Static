<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Intelligent\Kernel\View;
use Intelligent\Kernel\Config;
use Intelligent\Kernel\Controller;


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

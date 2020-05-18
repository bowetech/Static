<?php

namespace App\Controllers;

use Kernel\Controller;
use Kernel\View;

/**
 *
 * Page Controller Class
 */
class Page extends Controller
{
	public function about()
	{
		View::render('pages/about.html');
	}

	public function docs()
	{
		View::render('pages/docs.html');
	}
}
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Intelligent\Kernel\View;
use Intelligent\Kernel\Controller;


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

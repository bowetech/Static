<?php

namespace Kernel;

use Kernel\Support\Facades\Route;

class Kernel
{
	/**
	 * Handle an incoming HTTP request.
	 * Perform any final actions for the request lifecycle
	 * 
	 * @param  \Kernel\Request $request 
	 * @return  void
	 */
	public function handle($request)
	{
		Route::dispatcher($request);
	}
}
<?php

use Intelligent\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Examples of Routes
| 
| Route::get('', ['controller' => 'Home', 'action' => 'index']);
| Route::get('Contact', ['controller' => 'Contact', 'action' => 'index']);  
| Route::get('{controller}/{action}');
| Route::get('{controller}/{id:\d+}/{action}');
| Route::get('admin/{controller}/{action}', ['namespace' => 'Admin']);
| $router->get('home', ['controller' => 'home', 'action' => 'index']);
|
*/




Route::get('', ['controller' => 'home', 'action' => 'index']);
Route::get('home', ['controller' => 'home', 'action' => 'index']);
Route::get('docs', ['controller' => 'page', 'action' => 'docs']);
Route::get('about', ['controller' => 'page', 'action' => 'about']);
Route::get('contact', ['controller' => 'contact', 'action' => 'index']);

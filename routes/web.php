<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->post('/login', 'Controller@login');
$router->post('/create', 'Controller@create');
$router->post('/list', 'UserController@index');
$router->get('/edit/{id}', 'UserController@edit');
$router->post('/update/{id}', 'UserController@update');
$router->post('/delete/{id}', 'UserController@delete');
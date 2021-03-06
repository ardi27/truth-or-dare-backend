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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'users'], function () use ($router) {
    $router->get('/', 'UsersController@index');
    $router->get('/profile', "UsersController@profile");
    $router->patch('/update_profile', 'UsersController@updateProfile');
    $router->patch('/update_password', 'UsersController@updatePassword');
});
$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
});
$router->group((['prefix' => 'truth']), function () use ($router) {
    $router->get('/list', 'TruthController@index');
    $router->get('/find/{uuid}', 'TruthController@detail');
    $router->get('/random', 'TruthController@random');
    $router->get('/by_user', 'TruthController@getByUser');
    $router->post('/store', 'TruthController@store');
    $router->patch('/update/{uuid}', 'TruthController@update');
    $router->delete('/delete/{uuid}', 'TruthController@delete');
});
$router->group((['prefix' => 'dare']), function () use ($router) {
    $router->get('/list', 'DareController@index');
    $router->get('/find/{uuid}', 'DareController@detail');
    $router->get('/random', 'DareController@random');
    $router->get('/by_user', 'DareController@getByUser');
    $router->post('/store', 'DareController@store');
    $router->patch('/update/{uuid}', 'DareController@update');
    $router->delete('/delete/{uuid}', 'DareController@delete');
});

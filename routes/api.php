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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/user/register', 'UserController@register');
    $router->post('/user/sign-in', 'UserController@signIn');
    $router->post('/user/recover-password', 'UserController@recoverPassword');

    $router->group(['middleware' => 'auth:api'], function () use ($router) {
        $router->get('/user/companies', 'CompanyController@index');
        $router->post('/user/companies', 'CompanyController@store');
    });
});

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

$router->group(['prefix' => 'api'], function ($router) {
    $router->get('img/{img}', 'UserController@img');
    $router->post('user', 'UserController@user');

    $router->group(['prefix' => 'user'], function ($router) {
        $router->get('config', 'UserController@config');

        $router->group(['prefix' => 'img'], function ($router) {
            $router->get('list', 'UserController@imgList');
            $router->post('add', 'UserController@imgAdd');
            $router->put('remove', 'UserController@imgRemove');
        });

        $router->group(['prefix' => 'update'], function ($router) {
            $router->put('config', 'UserController@updateConfig');
        });
    });


    $router->group(['prefix' => 'auth'], function ($router) {
        $router->post('register', 'AuthController@register');
        $router->post('login', 'AuthController@login');
        $router->post('logout', 'AuthController@logout');
        $router->post('refresh', 'AuthController@refresh');

        $router->group(['prefix' => 'update'], function ($router) {
            $router->post('profile', 'AuthController@updateProfile');
            $router->post('password', 'AuthController@updatePassword');
        });
    });
});

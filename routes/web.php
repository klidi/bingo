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

$router->group(['prefix' => 'games/{gameId}'], function () use ($router) {
    // nothing fency here
    $router->post('/users/{userId}/stripe', [
        'as' => 'profile', 'uses' => 'StripeController@createStripe'
    ]);
});


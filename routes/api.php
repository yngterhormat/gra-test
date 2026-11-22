<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where the API routes for an application can be defined.
|
*/

$router->get('/couriers', 'CourierController@index');
$router->get('/couriers/{id}', 'CourierController@show');
$router->post('/couriers', 'CourierController@store');
$router->put('/couriers/{id}', 'CourierController@update');
$router->delete('/couriers/{id}', 'CourierController@destroy');

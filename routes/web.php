<?php

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


$router->group(['namespace' => 'Api'], function() use ($router) {
    $router->get("/vehicles/{year}/{manufacturer}/{model}", ['uses' => 'ConnectorController@vehicles']);
    $router->post("/vehicles", ['uses' => 'ConnectorController@vehicles']);
});

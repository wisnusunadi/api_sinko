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
    return ('API Sinko');
});

$router->group(['middleware' => 'auth','prefix' => 'api'], function ($router)
{
    $router->post('store/coo', 'PostsController@accept_coo');
    $router->post('store/kirim', 'PostsController@accept_pengiriman');
    $router->get('ekspedisi', 'PostsController@ekspedisi');
    $router->get('paket_produk', 'PostsController@paket_produk');
    $router->get('coo', 'PostsController@coo');
    $router->get('pengiriman', 'PostsController@pengiriman');
    $router->get('stok', 'PostsController@stok');
});
$router->group(['prefix' => 'api'], function () use ($router)
{
   $router->post('login', 'AuthController@login');
});


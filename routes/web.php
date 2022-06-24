<?php

use Illuminate\Http\Request;
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

$router->get('/test','AuthController@test');

$router->group(['middleware'=>"auth"],function($router){
    $router->get('/check','ExampleController@test');
});
$router->post('/order','ExampleController@order');
$router->post('/login1','ExampleController@postLogin');//using jwt


$router->group(['middleware'=>"auth"],function($router){
$router->get('/product','ProductController@index');
$router->get('/product/{id}','ProductController@show');
$router->post('/product/create','ProductController@store');
$router->post('/product/update/{id}','ProductController@update');
$router->post('/product/delete/{id}','ProductController@destroy');
});

//using guzzle http
$router->post('/login','AuthController@login');
$router->get('learn', function() use ($router) {
    $client   = new \GuzzleHttp\Client();
    $response = $client->get('https://curl.haxx.se/libcurl/c/libcurl-errors.html');
    $result = json_decode($response->getBody(),true);
    return $result;
});


//using simple auth
$router->post('/loginnew','AuthController@loginnew');
$router->post('/registernew','AuthController@registernew');
$router->post('/logoutnew','AuthController@logoutnew');

//using jwt
$router->post('/signin','jwtAuthController@Signin');
$router->post('/logoutuser','jwtAuthController@LogoutUser');
$router->post('/me','jwtAuthController@me');
$router->post('/refresh','jwtAuthController@refresh');

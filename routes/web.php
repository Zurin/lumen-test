<?php
use Illuminate\Http\Request;
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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$router->get('/', function () use ($router) {
    $res['status'] = 200;
    $res['result'] = "Halo! Selamat datang di API RackPrint";
    return response($res);
});

$router->post('/tes', function (Request $request) use ($router) {
    $res['status'] = 200;
    $res['result'] = "Tes";
    $res['data'] = array('input' => $request->text, );
    return response($res);
});

$router->post('/upload_file', 'FileController@upload');
$router->get('/get_file/{name}', 'FileController@get_file');

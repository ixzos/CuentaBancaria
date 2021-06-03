<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('/usuarios','App\Http\Controllers\UsuarioController');
Route::resource('/cuentas','App\Http\Controllers\CuentaController');
Route::put('/usuarios/transact/', 'App\Http\Controllers\UsuarioController@transaccion');
Route::put('usuarios/{cedula}', 'App\Http\Controllers\UsuarioController@update');
Route::put('cuentas/{cedulaEnvio}', 'App\Http\Controllers\CuentaController@update');





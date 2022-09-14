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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/entrada', 'App\Http\Controllers\API\ParqueoController@registrarEntrada')->name('registrarEntrada');
Route::post('/salida', 'App\Http\Controllers\API\ParqueoController@registrarSalida')->name('registrarSalida');
Route::post('/informe', 'App\Http\Controllers\API\ParqueoController@informeImporte')->name('informeImporte');
Route::post('/tiempodeuso', 'App\Http\Controllers\API\ParqueoController@listadoTiempo')->name('listadoTiempo');
Route::post('/mayoruso', 'App\Http\Controllers\API\ParqueoController@listadoUso')->name('listadoUso');

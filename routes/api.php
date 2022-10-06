<?php

use Illuminate\Http\Request;

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

Route::get('/provincias', 'Api\TerritoryController@index');
Route::get('/cantones/{provinciaId}/provincias', 'Api\TerritoryController@cantones');
Route::get('/distritos/{cantonId}/cantones', 'Api\TerritoryController@distritos');
Route::get('/barrios/{distritoId}/distritos', 'Api\TerritoryController@barrios');
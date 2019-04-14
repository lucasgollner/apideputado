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


Route::get('gravaDeputadosBD', 'DeputadoController@gravaDeputadosBD')->name('gravaDeputadosBD');

Route::get('gravaVerbasBD', 'VerbaController@gravaVerbasBD')->name('gravaVerbasBD'); 

Route::get('gravaRedesSociaisDB', 'RedesocialdeputadoController@gravaRedesSociaisDB')->name('gravaRedesSociaisDB'); 


Route::get('topDeputados', 'DeputadoController@topDeputados')->name('topDeputados'); 

Route::get('topRedesSociais', 'RedesocialdeputadoController@topRedesSociais')->name('topRedesSociais'); 

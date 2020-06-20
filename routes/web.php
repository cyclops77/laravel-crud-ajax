<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/produk','ProdukController@index');
Route::get('/getData','ProdukController@getData');
Route::post('/storeData','ProdukController@store');
Route::get('/editData/{id}','ProdukController@edit');
Route::post('/updateData','ProdukController@update');
Route::post('/destroyData','ProdukController@destroy');
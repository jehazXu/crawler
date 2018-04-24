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


Route::post('jd/crawler','JdController@crawler')->name('api.jd.crawler');
Route::post('tmall/crawler','TmallController@crawler')->name('api.tmall.crawler');

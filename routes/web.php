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
Route::get('/', 'PagesController@root')->name('root');
route::get('jd','JdController@show')->name('jd');
Route::resource('tmallproduct', 'TmallProductController')->only(['index','store','update','destroy']);
Route::resource('shoutao', 'ShoutaoController')->only(['index','store','update','destroy']);
route::post('getranking','ShoutaoController@getRanking')->name('shoutao.getranking');

Route::get('test', 'ShoutaoController@test')->name('test');

Route::resource('productanalys','ProductAnalysisController');
Route::resource('analsisinfos','AnalsisInfosController');

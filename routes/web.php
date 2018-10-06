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

Route::get('/', 'IndexController@index');
Route::get('search', 'IndexController@search')->name('search');
//Route::get('check_duplicates', 'IndexController@check_duplicates');
Route::get('{slug}', 'IndexController@category')->name('category');
Route::get('p/{slug}', ['as' => 'article', 'uses' => 'ArticleController@show']);
//Route::get('delete/{id}', 'IndexController@deleteCategory');
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


Route::group([
    'prefix'    => 'admin', // 預設URL
    'as'    => '', // route  命名 重導用
    'namespace' => 'Admin',  // 預設CONTROLLER App\Http\Controllers\Jiaoyou
], function() {
    
    Route::get('/', "AdminController@index");
});



Route::group([
    'prefix'    => '',
    'as'    => '',
    'namespace' => 'Jiaoyou',  // App\Http\Controllers\Jiaoyou
], function() {
    Route::resource('/profiles', "ProfilesController");
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

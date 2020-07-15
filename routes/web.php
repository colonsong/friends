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


Route::get('/testCode', "TestCodeController@index");

Route::group([
    'prefix'    => '',
    'as'    => '',
    'namespace' => 'Chat',  // App\Http\Controllers\Chat
], function() {
    Route::get('/chatsocket', "SockController@run");
});


/**
 *
 *
 * Route::get($uri, $callback);
 * route::post($uri, $callback);
 * route::put($uri, $callback);
 * route::patch($uri, $callback);
 * route::delete($uri, $callback);
 * route::options($uri, $callback);
 *
 *
 *
 * RESOURCE 動詞	路徑	        行為	 路由名稱
GET	        /photo	                index	photo.index
GET	        /photo/create	        create	photo.create
POST	    /photo	                store	photo.store
GET	        /photo/{photo}	        show	photo.show
GET	        /photo/{photo}/edit	    edit	photo.edit
PUT/PATCH	/photo/{photo}	        update	photo.update
DELETE	    /photo/{photo}	        destroy	photo.destroy
 */
Route::group([
    'middleware' => 'admin.user',
    'prefix'    => 'admin', // 預設URL
    'as'    => '', // route  命名 重導用
    'namespace' => 'Admin',  // 預設CONTROLLER App\Http\Controllers\Admin
], function() {

    Route::get('/', "AdminController@index");
    Route::get('/profiles/edit', "ProfilesController@edit");
});

Route::get('/profiles/get', "Jiaoyou\ProfilesController@get");

Route::get('/redisTest/test', "RedisController@test");


Route::group([
    'prefix'    => '',
    'as'    => '',
    'namespace' => 'Jiaoyou',  // App\Http\Controllers\Jiaoyou
], function() {
    Route::resource('/profiles', "ProfilesController");
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/hello-world', function () {
    return view('hello_world');
});

Route::get('/vueinfinite', function () {
    return view('vue_infinite');
});


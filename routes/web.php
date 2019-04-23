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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group([
    'middleware' => 'auth'], function(){
    Route::get('get/user/list' , 'ChatController@getUserList');
    Route::post('get/user/chat' , 'ChatController@getUserChat');
    Route::get('get/user/chat' , 'ChatController@getUserChat');
    Route::post('get/user/avatar' , 'ChatController@getUserAvatar');
    Route::post('store/message' , 'ChatController@storeMessage');
    Route::post('get/user/details' , 'ChatController@userDetails');
    Route::view('chat', 'chat');
});

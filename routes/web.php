<?php

Auth::routes();
Route::post('register/user' , 'HomeController@register_user')->name('register.user');
Route::view('/', 'auth.login');

Route::group([
    'middleware' => 'auth'], function(){
    Route::get('get/user/list' , 'ChatController@getUserList');
    Route::post('get/user/chat' , 'ChatController@getUserChat');
    Route::post('store/message' , 'ChatController@storeMessage');
    Route::post('delete/message' , 'ChatController@deleteMessage');
    Route::post('get/user/details' , 'ChatController@userDetails');
    Route::view('chat', 'chat');
});

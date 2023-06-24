<?php

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

//登录注册不需要jwt中间值
Route::namespace('User')->prefix('user')->name('user.')->group(function () {
    Route::post('register', 'UserController@register')->name('register'); //用户注册
    Route::post('login', 'UserController@login')->name('login'); //用户登录
});


//个人中心需要jwt验证中间件
Route::namespace('User')->middleware(['jwt.auth.api'])->prefix('user')->name('user.')->group(function () {
    Route::get('getInfo', 'UserController@getInfo')->name('getInfo');//个人中心
    Route::post('loginOut', 'UserController@loginOut')->name('loginOut');//退出登录
});



});

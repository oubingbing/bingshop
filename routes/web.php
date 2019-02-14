<?php


Route::group(['namespace' => 'Auth'], function () {

    /** 注册 **/
    Route::post("register","RegisterController@register");

    /** 注册视图 **/
    Route::get("register","RegisterController@registerView");

    /** 登录视图 **/
    Route::get('/login','LoginController@loginView');

    /** 登录视图 **/
    Route::get('/','LoginController@loginView');

    /** 登录 **/
    Route::post("/login","LoginController@login");

    /** 退出登录 */
    Route::get("/logout","LoginController@logout");
});
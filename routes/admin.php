<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin','namespace' => 'Admin','middleware'=>['web']], function () {

    Route::group(['middleware'=>['authUser']], function () {

        /** 首页 **/
        Route::get("/","IndexController@index");

        /** 后台主页 */
        Route::get('/dashboard','IndexController@dashboard');

        /** 用户列表视图 **/
        Route::get("/user/index","UserController@index");

        /** 获取用户列表 **/
        Route::get("/users","UserController@userList");

        /** 类型视图 **/
        Route::get("/category/index","CategoryController@index");

        /** 新建优惠类型 **/
        Route::post("/category/create","CategoryController@createCategory");

        /** 编辑优惠类型 **/
        Route::patch("/category/edit","CategoryController@edit");

        /** 删除优惠类型 **/
        Route::delete("/category/{id}/delete","CategoryController@delete");

        /** 新建优惠类型 **/
        Route::get("/categories","CategoryController@categories");

        /** 获取类型的分页列表 **/
        Route::get("/category_list","CategoryController@categoryList");

        /** 商品视图 **/
        Route::get("/goods/index","CategoryController@index");
    });
});


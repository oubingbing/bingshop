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

        /** 新建商品类目 **/
        Route::post("/category/create","CategoryController@createCategory");

        /** 编辑商品类目 **/
        Route::patch("/category/edit","CategoryController@edit");

        /** 删除商品类目 **/
        Route::delete("/category/{id}/delete","CategoryController@delete");

        /** 获取全部商品类目数据 **/
        Route::get("/categories","CategoryController@categories");

        /** 获取商品类目的分页列表 **/
        Route::get("/category_list","CategoryController@categoryList");

        /** 获取七牛token **/
        Route::get("/qiniu/token","QiNiuController@token");

        /** 商品视图 **/
        Route::get("/goods/index","GoodsController@index");

        /** 新建商品 **/
        Route::post("/goods/create","GoodsController@createGoods");

        /** 商品列表 **/
        Route::get("/goods","GoodsController@goodsList");

        /** 订单页面 **/
        Route::get("/order/index","OrderController@index");

        /** 订单列表 **/
        Route::get("/orders","OrderController@orderList");
    });
});


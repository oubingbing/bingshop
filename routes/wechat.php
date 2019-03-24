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

use App\Http\Controllers\Auth\LoginController;
use App\Http\Wechat\CategoryController;
use App\Http\Wechat\GoodsController;
use App\Http\Wechat\OrderController;
use App\Http\Wechat\ShoppingCartController;
use App\Http\Wechat\UserController;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['prefix' => 'wechat','middleware' => 'api.throttle', 'limit' => 100, 'expires' => 1], function ($api) {

        $api->group(['prefix' => 'auth'], function ($api) {
            /** 登录 */
            $api->post('/login', LoginController::class . '@apiLogin');
        });

        $api->group(['middleware' => 'wechat'], function ($api) {

            /** 获取用户信息 **/
            $api->get('/user',UserController::class . '@personal');

            /** 获取商品分类列表 **/
            $api->get("/categories",CategoryController::class . "@categories");

            /** 获取指定商品分类的商品列表 **/
            $api->get("/category/{id}/goods",CategoryController::class . "@categoryGoods");

            /** 获取商品详情 **/
            $api->get("/goods/{id}",GoodsController::class . "@detail");

            /** 加入购物车 **/
            $api->post("/cart",ShoppingCartController::class . "@addToCart");

            /** 获取用户购物车商品 **/
            $api->get("/carts",ShoppingCartController::class . "@carts");

            /** 删除购物车中指定的sku商品 **/
            $api->delete("/cart/{id}/delete",ShoppingCartController::class . "@delete");

            /** 创建商品订单 **/
            $api->post('/order',OrderController::class . '@createOrder');
        });

    });

});

$api->post('/pay/callback', OrderController::class . '@payCallback');



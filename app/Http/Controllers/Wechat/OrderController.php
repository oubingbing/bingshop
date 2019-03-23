<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2019/3/23
 * Time: 14:06
 */

namespace App\Http\Wechat;


use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function createOrder()
    {
        $user = request()->input('user');
        $addressId = request()->input('address_id');
        $sku = request()->input('sku');

        //确认商品库存

        return $sku;

    }

}
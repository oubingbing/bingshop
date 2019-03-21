<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21 0021
 * Time: 15:31
 */

namespace App\Enum;


class CartEnum
{
    /** 购物车商品状态 - 正常 **/
    const STATUS_NORMAL = 1;
    /** 购物车商品状态 - 已加入订单 **/
    const STATUS_ADDED_ORDER = 2;
    /** 购物车商品状态 - 被清出购物车 **/
    const STATUS_REMOVED = 3;
}
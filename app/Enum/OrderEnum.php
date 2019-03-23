<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2019/3/23
 * Time: 17:13
 */

namespace App\Enum;


class OrderEnum
{
    //订单状态，1=未支付，2=已支付, 3=支付失败，4=代发货，5=配送中，6=退款中，7=已完成
    /** 订单状态 - 未支付 **/
    const STATUS_NOT_PAY = 1;

    /** 订单类型 - 普通订单 **/
    const TYPE_NORMAL = 1;
    /** 订单类型 - 优惠券订单 **/
    const TYPE_COUPON = 2;
    /** 订单类型 - vip订单 **/
    const TYPE_VIP = 3;

    /** 下单用户类型 - 微信小程序 **/
    const USER_TYPE_MINI_PROGRAM = 1;
}
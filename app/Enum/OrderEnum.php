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
    /** 订单状态 - 未支付 **/
    const STATUS_NOT_PAY       = 1;
    /** 订单状态 - 已支付 **/
    const STATUS_PAID          = 2;
    /** 订单状态 - 支付失败 **/
    const STATUS_PAY_FAIL      = 3;
    /** 订单状态 - 代发货 **/
    const STATUS_WAIT_DISPATCH = 4;
    /** 订单状态 - 配送中 **/
    const STATUS_DISPATCHING   = 5;
    /** 订单状态 - 退款中 **/
    const STATUS_REFUNDING     = 6;
    /** 订单状态 - 已完成 **/
    const STATUS_FINISH        = 7;

    /** 微信支付状态 - 等待支付中 **/
    const TRADE_STATE_WAIT       = 'WAIT';
    /** 微信支付状态 - 支付成功 **/
    const TRADE_STATE_SUCCESS    = 'SUCCESS';
    /*** 微信支付状态 - 转入退款 */
    const TRADE_STATE_REFUND     = 'REFUND';
    /** 微信支付状态- 未支付 **/
    const TRADE_STATE            = 'NOTPA';
    /** 微信支付状态 - 已关闭 **/
    const TRADE_STATE_CLOSED     = 'CLOSED';
    /** 微信支付状态 - 已撤销 **/
    const TRADE_STATE_REVOKED    = 'REVOKED';
    /** 微信支付状态 - 用户支付中 **/
    const TRADE_STATE_USERPAYING = 'USERPAYING';
    /** 微信支付状态 - 支付失败 **/
    const TRADE_STATE_PAYERROR   = 'PAYERROR';

    /** 订单类型 - 普通订单 **/
    const TYPE_NORMAL = 1;
    /** 订单类型 - 优惠券订单 **/
    const TYPE_COUPON = 2;
    /** 订单类型 - vip订单 **/
    const TYPE_VIP    = 3;

    /** 下单用户类型 - 微信小程序 **/
    const USER_TYPE_MINI_PROGRAM = 1;
}
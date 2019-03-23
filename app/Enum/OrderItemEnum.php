<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2019/3/23
 * Time: 21:33
 */

namespace App\Enum;


class OrderItemEnum
{
    /** 子订单状态 - 正常状态 **/
    const STATUS_NORMAL = 1;
    /** 子订单状态 - 退款中 **/
    const STATUS_REFUNDING = 2;
    /** 子订单状态- 同意退款 **/
    const STATUS_AGREE_REFUND = 3;
    /** 子订单状态 - 拒绝退款 **/
    const STATUS_REJECT_REFUND = 4;
    /** 子订单状态 - 退款完成 **/
    const STATUS_REFUND_FINISH = 5;

}
<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2019/3/23
 * Time: 13:41
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrderModel extends BaseModel
{
    const TABLE_NAME = 'orders';
    protected $table = self::TABLE_NAME;

    /** Field user_id 下单用户 **/
    const FIELD_ID_USER = 'user_id';

    /** Field user_type 下单用户类型，1=微信小程序用户 **/
    const FIELD_USER_TYPE = 'user_type';

    /** Field amount 订单总金额 **/
    const FIELD_AMOUNT = 'amount';

    /** Field actual_amount 订单实际支付金额 **/
    const FIELD_ACTUAL_AMOUNT = 'actual_amount';

    /** Field order_number 订单号 **/
    const FIELD_ORDER_NUMBER = 'order_number';

    /** Field transaction_id 微信支付订单号 **/
    const FIELD_ID_TRANSACTION = 'transaction_id';

    /** Field user_address_id 用户收货地址 **/
    const FIELD_ID_USER_ADDRESS = 'user_address_id';

    /** Field freight 邮费 **/
    const FIELD_FREIGHT = 'freight';

    /** Field free_shipping 是否包邮，1=是，2=否 **/
    const FIELD_FREE_SHIPPING = 'free_shipping';

    /** Field payment_type支付方式,1=微信支付，2=支付宝支付 **/
    const FIELD_PAYMENT_TYPE = 'payment_type';

    /** Field status 订单状态,1=未支付，2=已支付, 3=支付失败，4=代发货，5=配送中，6=退款中，7=已完成 **/
    const FIELD_STATUS = 'status';

    /** Field trade_state 确认支付状态 **/
    const FIELD_TRADE_STATUS = 'trade_state';

    /** Field type 订单类型 **/
    const FIELD_TYPE = 'type';

    /** Field remark 订单备注 **/
    const FIELD_REMARK = 'remark';

    /** field paid_at，下单时间 */
    const FIELD_PAID_AT = 'paid_at';

    const REL_ORDER_ITEMS = 'orderItems';

    protected $fillable = [
        self::FIELD_ID_USER,
        self::FIELD_USER_TYPE,
        self::FIELD_AMOUNT,
        self::FIELD_ACTUAL_AMOUNT,
        self::FIELD_ORDER_NUMBER,
        self::FIELD_ID_TRANSACTION,
        self::FIELD_ID_USER_ADDRESS,
        self::FIELD_FREIGHT,
        self::FIELD_FREE_SHIPPING,
        self::FIELD_PAYMENT_TYPE,
        self::FIELD_STATUS,
        self::FIELD_TYPE,
        self::FIELD_REMARK,
        self::FIELD_PAID_AT,
        self::FIELD_TRADE_STATUS
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItemModel::class,OrderItemModel::FIELD_ID_ORDER,self::FIELD_ID);
    }
}
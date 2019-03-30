<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2019/3/23
 * Time: 13:58
 */

namespace App\Models;


class OrderItemModel extends BaseModel
{
    const TABLE_NAME = 'order_items';
    protected $table = self::TABLE_NAME;

    /** Field order_id所属订单 **/
    const FIELD_ID_ORDER = 'order_id';

    /** Field sku_id 商品 **/
    const FIELD_ID_SKU = 'sku_id';

    /** Field amount订单总金额 **/
    const FIELD_AMOUNT = 'amount';

    /** Field actual_amount 商品实际金额 **/
    const FIELD_ACTUAL_AMOUNT = 'actual_amount';

    /** Field quantity 商品数量 **/
    const FIELD_QUANTITY = 'quantity';

    /** Field status 子订单状态，1=正常状态，2=发起退款(退款中)，3=同意退款，4=拒绝退款，5=退款完成 **/
    const FIELD_STATUS = 'status';

    /** Field sku_snapshot 商品快照 **/
    const FIELD_SKU_SNAPSHOT = 'sku_snapshot';

    protected $casts = [
        self::FIELD_SKU_SNAPSHOT=>'array'
    ];

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_SKU,
        self::FIELD_ACTUAL_AMOUNT,
        self::FIELD_AMOUNT,
        self::FIELD_QUANTITY,
        self::FIELD_STATUS,
        self::FIELD_SKU_SNAPSHOT
    ];


}
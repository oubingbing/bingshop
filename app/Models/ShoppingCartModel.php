<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21 0021
 * Time: 14:50
 */

namespace App\Models;


class ShoppingCartModel extends BaseModel
{
    const TABLE_NAME = 'shopping_carts';
    protected $table = self::TABLE_NAME;

    /** Field user_id 用户ID **/
    const FIELD_ID_USER = 'user_id';

    /** Field sku_id 商品规格 **/
    const FIELD_ID_SKU = 'sku_id';

    /** Field purchase_num 购买数量 **/
    const FIELD_PURCHASE_NUM = 'purchase_num';

    /** Field status 购物车商品状态 **/
    const FIELD_STATUS = 'status';

    const REL_SKU = 'sku';

    protected $fillable = [
        self::FIELD_ID_USER,
        self::FIELD_ID_SKU,
        self::FIELD_PURCHASE_NUM,
        self::FIELD_PURCHASE_NUM
    ];

    public function sku()
    {
        return $this->belongsTo(SkuModel::class,self::FIELD_ID_SKU,SkuModel::FIELD_ID)->select([
            SkuModel::FIELD_ID,
            SkuModel::FIELD_PRICE,
            SkuModel::FIELD_PRICE,
            SkuModel::FIELD_VIP_PRICE,
            SkuModel::FIELD_ID_GOODS,
            SkuModel::FIELD_STOCK
        ]);
    }
}
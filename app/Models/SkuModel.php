<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/15 0015
 * Time: 15:33
 */

namespace App\Models;


class SkuModel extends BaseModel
{
    const TABLE_NAME = 'skus';
    protected $table = self::TABLE_NAME;

    /** Field goods_id 商品ID **/
    const FIELD_ID_GOODS = 'goods_id';

    /** Field price 商品价格 **/
    const FIELD_PRICE = 'price';

    /** Field vip_price 会员价格 **/
    const FIELD_VIP_PRICE = 'vip_price';

    /** Field chalk_line_price 划线价格 **/
    const FIELD_CHALK_LINE_PRICE = 'chalk_line_price';

    /** Field cost_price 成本价 **/
    const FIELD_COST_PRICE = 'cost_price';

    /** Field stock 商品库存 **/
    const FIELD_STOCK = 'stock';

    /** Field attachments 图片等附件 **/
    const FIELD_ATTACHMENTS = 'attachments';

    const REL_SKU_STANDARD_VALUE_MAP = 'skuStandardMap';
    const REL_GOODS = 'goods';
    const REL_STANDARD_VALUES = 'standardValues';

    protected $casts = [
        self::FIELD_ATTACHMENTS=>'array'
    ];

    protected $fillable = [
        self::FIELD_ID_GOODS,
        self::FIELD_PRICE,
        self::FIELD_VIP_PRICE,
        self::FIELD_CHALK_LINE_PRICE,
        self::FIELD_COST_PRICE,
        self::FIELD_STOCK,
        self::FIELD_ATTACHMENTS
    ];

    public function goods()
    {
        return $this->belongsTo(GoodsModel::class,self::FIELD_ID_GOODS,GoodsModel::FIELD_ID);
    }

    public function skuStandardMap()
    {
        return $this->hasMany(SkuStandardValueModel::class,SkuStandardValueModel::FIELD_ID_SKU,self::FIELD_ID);
    }

    public function standardValues(){
        return $this->belongsToMany(StandardValueModel::class,'sku_standard_values',SkuStandardValueModel::FIELD_ID_SKU,SkuStandardValueModel::FIELD_ID_STANDARD_VALUE);

    }

}
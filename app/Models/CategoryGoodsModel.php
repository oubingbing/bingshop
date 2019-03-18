<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18 0018
 * Time: 17:18
 */

namespace App\Models;


class CategoryGoodsModel extends BaseModel
{
    const TABLE_NAME = 'goods_category_maps';
    protected $table = self::TABLE_NAME;

    /** Field goods_id **/
    const FIELD_ID_GOODS = 'goods_id';

    /** Field category_id **/
    const FIELD_ID_CATEGORY = 'category_id';

    protected $fillable = [
        self::FIELD_ID_CATEGORY,
        self::FIELD_ID_GOODS
    ];
}
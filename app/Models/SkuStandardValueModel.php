<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/15 0015
 * Time: 15:40
 */

namespace App\Models;


class SkuStandardValueModel extends BaseModel
{
    const TABLE_NAME = 'sku_standard_values';
    protected $table = self::TABLE_NAME;

    /** Field sku_id **/
    const FIELD_ID_SKU = 'sku_id';

    /** Field standard_value_id **/
    const FIELD_ID_STANDARD_VALUE = 'standard_value_id';

    protected $fillable = [
        self::FIELD_ID_SKU,
        self::FIELD_ID_STANDARD_VALUE
    ];

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/14 0014
 * Time: 17:25
 */

namespace App\Models;


class StandardValueModel extends BaseModel
{
    const TABLE_NAME = 'standard_values';
    protected $table = self::TABLE_NAME;

    /** Field standard_id 所属规格 **/
    const FIELD_ID_STANDARD = 'standard_id';

    /** Field value 规格值 **/
    const FIELD_VALUE = 'value';

    const REL_STANDARD = 'standard';

    protected $fillable = [
        self::FIELD_ID_STANDARD,
        self::FIELD_VALUE
    ];

    public function standard()
    {
        return $this->belongsTo(StandardModel::class,self::FIELD_ID_STANDARD,StandardModel::FIELD_ID);
    }
}
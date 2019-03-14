<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/14 0014
 * Time: 17:13
 */

namespace App\Models;


class StandardModel extends BaseModel
{
    const TABLE_NAME = 'standards';
    protected $table = self::TABLE_NAME;

    /** Field name 规格名称 **/
    const FIELD_NAME = 'name';

    /** Field admin_id 规格创建人 **/
    const FIELD_ID_ADMIN = 'admin_id';

    protected $fillable = [
        self::FIELD_NAME,
        self::FIELD_ID_ADMIN
    ];
}
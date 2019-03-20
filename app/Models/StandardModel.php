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

    const REL_STANDARD_VALUE = 'standardValues';

    protected $fillable = [
        self::FIELD_NAME,
        self::FIELD_ID_ADMIN
    ];

    /**
     * 拥有的规格值
     *
     * @author yezi
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function standardValues()
    {
        return $this->hasMany(StandardValueModel::class,StandardValueModel::FIELD_ID_STANDARD,self::FIELD_ID);
    }
}
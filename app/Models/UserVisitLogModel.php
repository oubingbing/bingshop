<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2018/3/18
 * Time: 下午5:54
 */

namespace App\Models;


class UserVisitLogModel extends BaseModel
{
    const TABLE_NAME = 'user_visit_logs';
    protected $table = self::TABLE_NAME;


    /** Field user_id */
    const FIELD_ID_USER = 'user_id';

    /** Field nickname */
    const FIELD_NICKNAME = 'nickname';

    const REL_USER = 'user';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_NICKNAME,
        self::FIELD_CREATED_AT,
        self::FIELD_UPDATED_AT,
        self::FIELD_DELETED_AT
    ];

    public function user()
    {
        return $this->belongsTo(User::class,self::FIELD_ID_USER,User::FIELD_ID);
    }
}
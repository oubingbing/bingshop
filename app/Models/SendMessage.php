<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/12 0012
 * Time: 15:45
 */

namespace App\Models;


class SendMessage extends BaseModel
{
    const TABLE_NAME = 'send_messages';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field mobile */
    const FIELD_MOBILE = 'mobile';

    /** Field code */
    const FIELD_CODE = 'code';


    /** Field status 发送状态1=成功，2=失败 */
    const FIELD_STATUS = 'status';

    /** Field expired_at 短信验证码有效期 */
    const FIELD_EXPIRED_AT = 'expired_at';


    /** 发送成功 */
    const ENUM_STATUS_SUCCESS = 1;
    /** 发送失败 */
    const ENUM_STATUS_FAIL = 2;

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_CODE,
        self::FIELD_MOBILE,
        self::FIELD_STATUS,
        self::FIELD_EXPIRED_AT,
        self::FIELD_CREATED_AT,
        self::FIELD_UPDATED_AT,
        self::FIELD_DELETED_AT
    ];

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/28 0028
 * Time: 14:27
 */

namespace App\Models;


class AdminModel extends BaseModel
{
    const TABLE_NAME = 'admins';
    protected $table = self::TABLE_NAME;

    /** field id 用户Id */
    const FIELD_ID = 'id';

    /** field nickname 用户昵称 */
    const FIELD_NICKNAME = 'nickname';

    /** Field avatar 头像 */
    const FIELD_AVATAR = 'avatar';

    /** Field email */
    const FIELD_EMAIL = 'email';

    /** Field password 密码 */
    const FIELD_PASSWORD = 'password';

    const FIELD_SALT = 'salt';

    const FIELD_REMEMBER_TOKEN = 'remember_token';

    /** field remember_token_expire token过期时间 */
    const FIELD_TOKEN_EXPIRE_AT = 'remember_token_expire';

    const USER_AVATAR = 'http://image.kucaroom.com/boy.png';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_NICKNAME,
        self::FIELD_EMAIL,
        self::FIELD_PASSWORD,
        self::FIELD_SALT,
        self::FIELD_REMEMBER_TOKEN,
        self::FIELD_TOKEN_EXPIRE_AT
    ];

}
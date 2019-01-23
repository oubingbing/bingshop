<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/5/26
 * Time: 17:08
 */

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable,SoftDeletes;

    const TABLE_NAME = 'admins';
    protected $table = self::TABLE_NAME;

    /** field id 用户Id */
    const FIELD_ID = 'id';

    /** field nickname 用户昵称 */
    const FIELD_NICKNAME = 'nickname';

    /** Field avatar 头像 */
    const FIELD_AVATAR = 'avatar';

    /** Field phone */
    const FIELD_PHONE = 'phone';

    /** Field password 密码 */
    const FIELD_PASSWORD = 'password';

    const FIELD_SALT = 'salt';

    const FIELD_REMEMBER_TOKEN = 'remember_token';

    const FIELD_DONATION_QR_CODE = 'donation_qr_code';

    /** field created_at */
    const FIELD_CREATED_AT = 'created_at';

    /** field updated_at */
    const FIELD_UPDATED_AT = 'updated_at';

    /** field deleted_at */
    const FIELD_DELETED_AT = 'deleted_at';

    const USER_AVATAR = 'http://image.kucaroom.com/boy.png';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_NICKNAME,
        self::FIELD_PHONE,
        self::FIELD_PASSWORD,
        self::FIELD_SALT,
        self::FIELD_REMEMBER_TOKEN,
        self::FIELD_DONATION_QR_CODE
    ];
}
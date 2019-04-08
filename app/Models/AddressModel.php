<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8 0008
 * Time: 17:06
 */

namespace App\Models;


class AddressModel extends BaseModel
{
    const TABLE_NAME = 'admins';
    protected $table = self::TABLE_NAME;

    /** Field user_id 用户 **/
    const FIELD_ID_USER = 'user_id';

    /** Field receiver 收件人 **/
    const FIELD_RECEIVER = 'receiver';

    /** Field phone 手机号码 **/
    const FIELD_PHONE = 'phone';

    /** Field province_id 省份 **/
    const FIELD_ID_PROVINCE = 'province_id';

    /** Field city_id 城市 **/
    const FIELD_ID_CITY = 'city_id';

    /** Field country_id 县区 **/
    const FIELD_ID_COUNTRY = 'country_id';

    /** Field detail_address 详细地址 **/
    const FIELD_DETAIL_ADDRESS = 'detail_address';

    /** Field type 类型,1=默认，2=非默认 **/
    const FIELD_TYPE = 'type';

    protected $fillable = [
        self::FIELD_ID_USER,
        self::FIELD_ID_PROVINCE,
        self::FIELD_ID_COUNTRY,
        self::FIELD_ID_CITY,
        self::FIELD_PHONE,
        self::FIELD_RECEIVER,
        self::FIELD_DETAIL_ADDRESS,
        self::FIELD_TYPE
    ];

}
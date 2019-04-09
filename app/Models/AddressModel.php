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
    const TABLE_NAME = 'address';
    protected $table = self::TABLE_NAME;

    /** Field user_id 用户 **/
    const FIELD_ID_USER = 'user_id';

    /** Field receiver 收件人 **/
    const FIELD_RECEIVER = 'receiver';

    /** Field phone 手机号码 **/
    const FIELD_PHONE = 'phone';

    /** Field nation 国家 **/
    const FIELD_NATION = 'nation';

    /** Field province 省份 **/
    const FIELD_PROVINCE = 'province';

    /** Field city 城市 **/
    const FIELD_CITY = 'city';

    /** Field district 县区 **/
    const FIELD_DISTRICT = 'district';

    /** Field street 街道 **/
    const FIELD_STREET = 'street';

    /** Field detail_address 详细地址 **/
    const FIELD_DETAIL_ADDRESS = 'detail_address';

    /** Field latitude 详细地址 **/
    const FIELD_LATITUDE = 'latitude';

    /** Field longitude 详细地址 **/
    const FIELD_LONGITUDE = 'longitude';

    /** Field type 类型,1=默认，2=非默认 **/
    const FIELD_TYPE = 'type';

    protected $fillable = [
        self::FIELD_ID_USER,
        self::FIELD_NATION,
        self::FIELD_PROVINCE,
        self::FIELD_DISTRICT,
        self::FIELD_CITY,
        self::FIELD_STREET,
        self::FIELD_PHONE,
        self::FIELD_LATITUDE,
        self::FIELD_LONGITUDE,
        self::FIELD_RECEIVER,
        self::FIELD_DETAIL_ADDRESS,
        self::FIELD_TYPE
    ];

}
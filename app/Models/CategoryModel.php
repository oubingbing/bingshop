<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/14 0014
 * Time: 11:30
 */

namespace App\Models;


class CategoryModel extends BaseModel
{
    const TABLE_NAME = 'categories';
    protected $table = self::TABLE_NAME;

    /** Field name 分类名称 **/
    const FIELD_NAME = 'name';

    /** Field attachments 图片等资源 **/
    const FIELD_ATTACHMENTS = 'attachments';

    /** Field admin_id 创建人 **/
    const FIELD_ID_ADMIN = 'admin_id';

    /** Field status **/
    const FIELD_STATUS = 'status';

    /** Field type **/
    const FIELD_TYPE = 'type';

    /** Field sort 排序 **/
    const FIELD_SORT = 'sort';

    protected $casts = [
        'attachments'=>'array'
    ];

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_NAME,
        self::FIELD_ID_ADMIN,
        self::FIELD_STATUS,
        self::FIELD_TYPE,
        self::FIELD_SORT
    ];
}
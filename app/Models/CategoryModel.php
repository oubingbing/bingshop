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

    protected $casts = [
        'attachments'=>'array'
    ];

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_NAME,
        self::FIELD_ATTACHMENTS
    ];
}
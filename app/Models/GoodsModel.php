<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/15 0015
 * Time: 11:59
 */

namespace App\Models;


class GoodsModel extends BaseModel
{
    const TABLE_NAME = 'goods';
    protected $table = self::TABLE_NAME;

    /** Field name 商品名称 **/
    const FIELD_NAME = 'name';

    /** Field describe 商品描述 **/
    const FIELD_DESCRIBE = 'describe';

    /** Field share_describe 分享描述 **/
    const FIELD_SHARE_DESCRIBE = 'share_describe';

    /** Field images_attachments 商品附件图片 **/
    const FIELD_IMAGES_ATTACHMENTS = 'images_attachments';

    /** Field video_attachments 视频附件 **/
    const FIELD_VIDEO_ATTACHMENTS = 'video_attachments';

    /** Field sku_type 规格sku类型,1=是否是多个sku,2=多个sku **/
    const FIELD_SKU_TYPE = 'sku_type';

    /** Field status 商品状态,0=下架，1=在售 **/
    const FIELD_STATUS = 'status';

    /** Field start_sale_type 商品上架类型,1=立即上架销售，2=自定义上架时间，3=暂不售卖，放入仓库 **/
    const FIELD_START_SALE_TYPE = 'start_sale_type';

    /** Field start_selling_at 定时上架销售时间 **/
    const FIELD_START_SELLING_AT = 'start_selling_at';

    /** Field stop_sale_type 下架方式，1=售完即可下架，2=自定义下架时间，3=售完不下架 **/
    const FIELD_STOP_SALE_TYPE = 'stop_sale_type';

    /** Field stop_selling_at 定时下架停止销售时间 **/
    const FIELD_STOP_SELLING_AT = 'stop_selling_at';

    /** Field limit_purchase_num 限制购买数量，0=无限制 **/
    const FIELD_LIMIT_PURCHASE_NUM = 'limit_purchase_num';

    /** Field distribution_mode 配送方式，1=快递发货，2=同城配送，3=到店自提 **/
    const FIELD_DISTRIBUTION_MODE = 'distribution_mode';

    /** Field postage_cost 邮费 **/
    const FIELD_POSTAGE_COST = 'postage_cost';

    const REL_SKU = 'sku';

    protected $casts = [
        self::FIELD_IMAGES_ATTACHMENTS=>'array'
    ];

    protected $fillable = [
        self::FIELD_NAME,
        self::FIELD_DESCRIBE,
        self::FIELD_SHARE_DESCRIBE,
        self::FIELD_IMAGES_ATTACHMENTS,
        self::FIELD_VIDEO_ATTACHMENTS,
        self::FIELD_SKU_TYPE,
        self::FIELD_STATUS,
        self::FIELD_START_SALE_TYPE,
        self::FIELD_START_SELLING_AT,
        self::FIELD_STOP_SALE_TYPE,
        self::FIELD_STOP_SELLING_AT,
        self::FIELD_LIMIT_PURCHASE_NUM,
        self::FIELD_DISTRIBUTION_MODE,
        self::FIELD_DISTRIBUTION_MODE
    ];

    public function sku()
    {
        return $this->hasMany(SkuModel::class,self::FIELD_ID,SkuModel::FIELD_ID_GOODS);
    }
}
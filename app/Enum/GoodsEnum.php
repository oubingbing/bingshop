<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18 0018
 * Time: 16:36
 */

namespace App\Enum;


class GoodsEnum
{
    /** 单个sku **/
    const SINGLE_SKU = 1;
    /** 多个sku **/
    const BATCH_SKU = 2;

    /** 商品状态-在售 **/
    const SALE_STATUS_UP = 1;
    /** 商品状态-下架 **/
    const SALE_STATUS_DOWN = 2;

}
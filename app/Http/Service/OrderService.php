<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2019/3/23
 * Time: 14:06
 */

namespace App\Http\Service;


use App\Enum\GoodsEnum;
use App\Enum\OrderEnum;
use App\Exceptions\ApiException;
use App\Models\GoodsModel;
use App\Models\OrderModel as Model;
use App\Models\SkuModel;

class OrderService
{
    public function generateOrderNumber()
    {
        return "E".date("YmdH:i:s").rand(10000,100000);
    }

    public function createOrder($userId,$skuData,$addressId,$payment=1,$remark=null)
    {

        $skuIds = collect($skuData)->pluck('sku_id');
        $skuService = app(SkuService::class);

        //计算商品总金额,看看是否使用了优惠券，或者是VIP折扣价

        $needToPayAmount = 0;
        $needToPayActualAmount = 0;
        foreach ($skuData as $skuItem){
            $sku = $skuService->findSkuById($skuItem['sku_id']);
            if(!$sku){
                throw new ApiException("商品不存在");
            }
            $goods = $sku->{SkuModel::REL_GOODS};
            $goodsName = $goods->{GoodsModel::FIELD_NAME};
            if($goods->{GoodsModel::FIELD_STATUS} != GoodsEnum::SALE_STATUS_UP){
                throw new ApiException("$goodsName：该商品已下架，不可下单购买");
            }

            if($sku->{SkuModel::FIELD_STOCK} > $skuItem['purchase_num']){
                throw new ApiException("$goodsName：该商品库存不足");
            }

            //计算总的商品价格
            $needToPayAmount += $sku->{SkuModel::FIELD_PRICE};
            $needToPayActualAmount += $sku->{SkuModel::FIELD_PRICE};
        }

        $order = Model::create([
            Model::FIELD_ID_USER=>$userId,
            Model::FIELD_AMOUNT=>$needToPayAmount,
            Model::FIELD_ACTUAL_AMOUNT=>$needToPayActualAmount,
            Model::FIELD_ORDER_NUMBER=>$this->generateOrderNumber(),
            Model::FIELD_ID_USER_ADDRESS=>$addressId,
            Model::FIELD_FREIGHT=>1,
            Model::FIELD_FREE_SHIPPING=>1,
            Model::FIELD_PAYMENT_TYPE=>$payment,
            Model::FIELD_STATUS=>OrderEnum::STATUS_NOT_PAY,
            Model::FIELD_TYPE=>OrderEnum::TYPE_NORMAL,
            Model::FIELD_REMARK=>$remark,
            Model::FIELD_USER_TYPE=>OrderEnum::USER_TYPE_MINI_PROGRAM
        ]);

        return $order;
    }

    public function storeOrder()
    {

    }

}
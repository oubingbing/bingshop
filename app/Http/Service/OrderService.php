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
use App\Enum\OrderItemEnum;
use App\Exceptions\ApiException;
use App\Models\GoodsModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel as Model;
use App\Models\SkuModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * 生成订单号
     *
     * @author yezi
     * @return string
     */
    public function generateOrderNumber()
    {
        return "E".date("YmdHis").rand(10000,100000);
    }

    public function createOrder($userId,$skuData,$addressId,$payment=1,$remark=null)
    {
        $skuService = app(SkuService::class);

        //计算商品总金额,看看是否使用了优惠券，或者是VIP折扣价

        $needToPayAmount = 0;
        $needToPayActualAmount = 0;
        $orderItems = [];
        $nowDateTimeString = Carbon::now()->toDateTimeString();
        foreach ($skuData as $skuItem){
            $sku = $skuService->findSkuById($skuItem['sku_id']);
            if(!$sku){
                throw new ApiException("商品不存在");
            }
            $goods = $sku->{SkuModel::REL_GOODS};
            $goodsName = $goods->{GoodsModel::FIELD_NAME};
            if($goods->{GoodsModel::FIELD_STATUS} != GoodsEnum::SALE_STATUS_UP){
                throw new ApiException("{$goodsName}：该商品已下架，不可下单购买");
            }

            if($sku->{SkuModel::FIELD_STOCK} < $skuItem['purchase_num']){
                throw new ApiException("{$goodsName}：该商品库存不足");
            }

            //计算总的商品价格
            $needToPayAmount += $sku->{SkuModel::FIELD_PRICE};
            $needToPayActualAmount += $sku->{SkuModel::FIELD_PRICE};

            array_push($orderItems,[
                OrderItemModel::FIELD_ID_SKU=>$skuItem['sku_id'],
                OrderItemModel::FIELD_AMOUNT=>($sku->{SkuModel::FIELD_PRICE}*$skuItem['purchase_num']),
                OrderItemModel::FIELD_ACTUAL_AMOUNT=>($sku->{SkuModel::FIELD_PRICE}*$skuItem['purchase_num']),
                OrderItemModel::FIELD_QUANTITY=>$skuItem['purchase_num'],
                OrderItemModel::FIELD_STATUS=>OrderItemEnum::STATUS_NORMAL,
                OrderItemModel::FIELD_SKU_SNAPSHOT=>collect($sku)->toJson(),
                OrderItemModel::FIELD_CREATED_AT=>$nowDateTimeString,
                OrderItemModel::FIELD_UPDATED_AT=>$nowDateTimeString
            ]);
        }

        $order = $this->storeOrder($userId,$needToPayAmount,$needToPayActualAmount,$addressId,$payment,$remark);
        if(!$order){
            throw new ApiException("保存订单失败");
        }

        $orderItems = collect($orderItems)->map(function ($item)use($order){
           $item[OrderItemModel::FIELD_ID_ORDER] = $order->id;
            return $item;
        });

        $orderItemResult = $this->storeOrderItems($orderItems);
        if(!$orderItemResult){
            throw new ApiException("保存订单失败");
        }

        return $order;
    }

    /**
     * 保存母订单
     *
     * @author yezi
     * @param $userId
     * @param $needToPayAmount
     * @param $needToPayActualAmount
     * @param $addressId
     * @param $payment
     * @param $remark
     * @return mixed
     */
    public function storeOrder($userId,$needToPayAmount,$needToPayActualAmount,$addressId,$payment,$remark)
    {
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
            Model::FIELD_USER_TYPE=>OrderEnum::USER_TYPE_MINI_PROGRAM,
            Model::FIELD_ORDERED_AT=>Carbon::now()->toDateTimeString()
        ]);
        return $order;
    }

    /**
     * 保存子订单
     *
     * @author yezi
     * @param $orderItems
     * @return mixed
     */
    public function storeOrderItems($orderItems)
    {
        $result = DB::table(OrderItemModel::TABLE_NAME)->insert(collect($orderItems)->toArray());
        return $result;
    }

}
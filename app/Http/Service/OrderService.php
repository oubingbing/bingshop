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
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    /**
     * 创建订单
     *
     * @author yezi
     * @param $userId
     * @param $skuData
     * @param $addressId
     * @param int $payment
     * @param null $remark
     * @return mixed
     * @throws ApiException
     */
    public function createOrder($userId,$skuData,$addressId,$payment=1,$remark=null)
    {
        $skuService = app(SkuService::class);

        //计算商品总金额,看看是否使用了优惠券，或者是VIP折扣价

        $needToPayAmount       = 0;
        $needToPayActualAmount = 0;
        $orderItems            = [];
        $nowDateTimeString     = Carbon::now()->toDateTimeString();
        foreach ($skuData as $skuItem){
            $sku = $skuService->findSkuById($skuItem['sku_id']);
            if(!$sku){
                throw new ApiException("商品不存在");
            }
            $goods     = $sku->{SkuModel::REL_GOODS};
            $goodsName = $goods->{GoodsModel::FIELD_NAME};
            if($goods->{GoodsModel::FIELD_STATUS} != GoodsEnum::SALE_STATUS_UP){
                throw new ApiException("{$goodsName}：该商品已下架，不可下单购买");
            }

            if($sku->{SkuModel::FIELD_STOCK} < $skuItem['purchase_num']){
                throw new ApiException("{$goodsName}：该商品库存不足");
            }

            //计算总的商品价格
            $needToPayAmount += ($sku->{SkuModel::FIELD_PRICE}*$skuItem['purchase_num']);
            $needToPayActualAmount += ($sku->{SkuModel::FIELD_PRICE}*$skuItem['purchase_num']);

            array_push($orderItems,[
                OrderItemModel::FIELD_ID_SKU        => $skuItem['sku_id'],
                OrderItemModel::FIELD_AMOUNT        => ($sku->{SkuModel::FIELD_PRICE}*$skuItem['purchase_num']),
                OrderItemModel::FIELD_ACTUAL_AMOUNT => ($sku->{SkuModel::FIELD_PRICE}*$skuItem['purchase_num']),
                OrderItemModel::FIELD_QUANTITY      => $skuItem['purchase_num'],
                OrderItemModel::FIELD_STATUS        => OrderItemEnum::STATUS_NORMAL,
                OrderItemModel::FIELD_SKU_SNAPSHOT  => collect($sku)->toJson(),
                OrderItemModel::FIELD_CREATED_AT    => $nowDateTimeString,
                OrderItemModel::FIELD_UPDATED_AT    => $nowDateTimeString
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
            Model::FIELD_ID_USER         => $userId,
            Model::FIELD_AMOUNT          => $needToPayAmount,
            Model::FIELD_ACTUAL_AMOUNT   => $needToPayActualAmount,
            Model::FIELD_ORDER_NUMBER    => $this->generateOrderNumber(),
            Model::FIELD_ID_USER_ADDRESS => $addressId,
            Model::FIELD_FREIGHT         => 1,
            Model::FIELD_FREE_SHIPPING   => 1,
            Model::FIELD_PAYMENT_TYPE    => $payment,
            Model::FIELD_STATUS          => OrderEnum::STATUS_NOT_PAY,
            Model::FIELD_TYPE            => OrderEnum::TYPE_NORMAL,
            Model::FIELD_REMARK          => $remark,
            Model::FIELD_USER_TYPE       => OrderEnum::USER_TYPE_MINI_PROGRAM,
            Model::FIELD_TRADE_STATUS    => OrderEnum::TRADE_STATE_WAIT
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

    /**
     * 根据订单号查询订单
     *
     * @author yezi
     * @param $orderNumber
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findOrderByNumber($orderNumber)
    {
        $order = Model::query()->where(Model::FIELD_ORDER_NUMBER,$orderNumber)->first();
        return $order;
    }

    /**
     * 再次确认订单是否支付成功
     * 
     * @author yezi
     * @param $app
     * @param $message
     * @return array
     */
    public function queryOrderPayStatus($app,$message)
    {
        $queryResult = $app->order->queryByTransactionId($message['transaction_id']);
        $status      = true;
        Log::info(['message'=>'查询结果','data'=>$queryResult]);
        if ($queryResult['return_code'] === 'SUCCESS') {
            if (array_get($message, 'result_code') === 'FAIL') {
                $status = false;
            }
        } else {
            //订单查询失败
            $status = false;
        }
        
        return ['result'=>$queryResult,'status'=>$status];
    }

    /**
     * 处理确认订单的支付失败
     *
     * @author yezi
     * @param $order
     * @param $result
     * @return mixed
     */
    public function handlePayFail($order,$result)
    {
        //处理查询订单后确认未支付的逻辑处理
        Log::info(['message'=>'确认订单，用户支付失败','data'=>$result]);
        $order->{Model::FIELD_STATUS}         = OrderEnum::STATUS_PAY_FAIL;
        $order->{Model::FIELD_TRADE_STATUS}   = $result['trade_state'];
        $order->{Model::FIELD_ID_TRANSACTION} = $result['transaction_id'];
        $ret = $order->save();
        return $ret;
    }

    /**
     * 处理支出回调
     *
     * @author yezi
     * @param $order
     * @param $message
     * @param $tradeState
     * @return bool
     */
    public function handlePay($order,$message,$tradeState)
    {
        $status = true;
        if ($message['return_code'] === 'SUCCESS') {
            if (array_get($message, 'result_code') === 'SUCCESS') {// 用户是否支付成功
                $order->{Model::FIELD_STATUS} = OrderEnum::STATUS_PAID;
                $order->{Model::FIELD_ID_TRANSACTION} = $message['transaction_id'];
                $order->{Model::FIELD_PAID_AT} = Carbon::now()->toDateTimeString();

            } elseif (array_get($message, 'result_code') === 'FAIL') {// 用户支付失败
                $order->{Model::FIELD_STATUS} = OrderEnum::STATUS_PAY_FAIL;
            }

            $order->{Model::FIELD_TRADE_STATUS} = $tradeState;
            $saveResult = $order->save();
            if(!$saveResult){
                Log::error(['message'=>'处理支付保存订单失败','data'=>$message]);
                $status = false;
            }

        } else {
            $status = false;
        }

        return $status;
    }

    /**
     * 创建订单
     *
     * @author yezi
     * @param $userId
     * @param $skuData
     * @param $addressId
     * @return mixed
     * @throws ApiException
     */
    public function buildOrder($userId,$skuData,$addressId)
    {
        $skuIds = collect($skuData)->pluck('sku_id');

        $order = $this->createOrder($userId,$skuData,$addressId);
        if(!$order){
            throw new ApiException("创建订单失败");
        }
        //更新用户购物车状态
        $updateResult = app(ShoppingCartService::class)->removeUserSkuToOrder($userId,collect($skuIds)->toArray());
        if(!$updateResult){
            throw new ApiException("更新购物车信息失败");
        }

        return $order;
    }

    /**
     * 重新支付订单
     *
     * @author yezi
     * @param $userId
     * @param $orderNumber
     * @return OrderService|\Illuminate\Database\Eloquent\Model|null|object
     * @throws ApiException
     */
    public function repayOrder($userId,$orderNumber)
    {
        //记录日志

        $order = $this->findOrderByNumber($orderNumber);
        if(!$order){
            throw new ApiException("订单不存在");
        }

        if($order->{Model::FIELD_ID_USER} != $userId){
            throw new ApiException("订单不存在");
        }

        if($order->{Model::FIELD_STATUS} == OrderEnum::STATUS_PAID){
            throw new ApiException("订单已支付，无需重复支付");
        }

        return $order;
    }

    public function countStatus($userId)
    {
        $result = Model::query()
            ->where(Model::FIELD_ID_USER,$userId)
            ->groupBy(Model::FIELD_STATUS)
            ->select([\DB::raw('count(id) as num'),Model::FIELD_STATUS])
            ->get();

        $retData = [];
        foreach ($result as $item){
            $key = '';
            switch ($item['status']){
                case OrderEnum::STATUS_NOT_PAY:
                    $key = 'wait';
                    break;
                case OrderEnum::STATUS_PAID:
                    $key = 'paid';
                    break;
                case OrderEnum::STATUS_PAY_FAIL:
                    $key = 'pay_fail';
                    break;
                case OrderEnum::STATUS_WAIT_DISPATCH:
                    $key = 'wait_dispatch';
                    break;
                case OrderEnum::STATUS_DISPATCHING:
                    $key = 'dispatch';
                    break;
                case OrderEnum::STATUS_REFUNDING:
                    $key = 'refunding';
                    break;
                case OrderEnum::STATUS_FINISH:
                    $key = 'finish';
                    break;
            }
            $retData[$key] = $item['num'];
        }

        return $retData;
    }
}
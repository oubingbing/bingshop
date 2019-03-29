<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2019/3/23
 * Time: 14:06
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\OrderService;
use App\Http\Service\ShoppingCartService;
use App\Models\OrderModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    private $orderService;
    private $cartService;

    public function __construct(OrderService $orderService,ShoppingCartService $cartService)
    {
        $this->orderService = $orderService;
        $this->cartService  = $cartService;
    }

    /**
     * 微信支付回调
     *
     * @author yezi
     * @return mixed
     */
    public function payCallback()
    {
        $app       = app('wechat.payment');
        $response  = $app->handlePaidNotify(function ($message, $fail)use($app){
            $order = $this->orderService->findOrderByNumber($message['out_trade_no']);

            if (!$order || $order->paid_at) {
                Log::notice(['message'=>'订单不存在或订单已支付','data'=>$message]);
                return true;
            }

            //再次确认订单是否已经支付
            $checkResult = $this->orderService->queryOrderPayStatus($app,$message);
            if($checkResult['status'] == false){
                //确认支付未完成
                $savePayFail = $this->orderService->handlePayFail($order,$checkResult['result']);
                if(!$savePayFail){
                    Log::notice(['message'=>'更新确认未支付订单失败','data'=>$checkResult]);
                }
                return true;
            }

            $tradeState      = $checkResult['result']['trade_state'];
            $handlePayResult = $this->orderService->handlePay($order,$message,$tradeState);
            if(!$handlePayResult){
                return $fail('通信失败，请稍后再通知我');
            }

            return true;
        });

        return $response;
    }

    /**
     * 创建订单
     *
     * @author yezi
     * @return mixed
     * @throws ApiException
     */
    public function createOrder()
    {
        $user        = request()->input('user');
        $addressId   = request()->input('address_id');
        $skuData     = request()->input('sku');
        $orderNumber = request()->input('order_number');

        //确认商品库存

        try {
            \DB::beginTransaction();

            //锁住订单中的sku，可读不可写
            $skuIds = collect($skuData)->pluck('sku_id');
            DB::table(OrderModel::TABLE_NAME)->whereIn(OrderModel::FIELD_ID,collect($skuIds)->toArray())->sharedLock()->get();

            if($orderNumber){
                //未支付订单，重新支付
                $order = $this->orderService->repayOrder($user->id,$orderNumber);
            }else{
                //新建订单
                $order = $this->orderService->buildOrder($user->id,$skuData,$addressId);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new ApiException($e->getMessage());
        }

        $app    = app('wechat.payment');
        $result = $app->order->unify([
            'body'             => "测试下单",
            'out_trade_no'     => $order->{OrderModel::FIELD_ORDER_NUMBER},
            'total_fee'        => $order->{OrderModel::FIELD_ACTUAL_AMOUNT}*100,
            'notify_url'       => env('WECHAT_PAY_CALLBACK_URL'),
            'trade_type'       => 'JSAPI',
            'openid'           => $user->{User::FIELD_ID_OPENID},
        ]);
        $config = $app->jssdk->bridgeConfig($result['prepay_id'], false); // 返回数组

        return ['order_number'=>$order->{OrderModel::FIELD_ORDER_NUMBER},'config'=>$config];
    }

}
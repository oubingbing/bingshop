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
use App\Models\OrderModel;
use App\Models\User;

class OrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function payCallback()
    {
        $app = app('wechat.payment');
        $response = $app->handlePaidNotify(function ($message, $fail) {
            // 你的逻辑
            return true;
            // 或者错误消息
           // $fail('Order not exists.');
        });

        return $response;
    }

    public function createOrder()
    {
        $user = request()->input('user');
        $addressId = request()->input('address_id');
        $sku = request()->input('sku');

        //确认商品库存

        try {
            \DB::beginTransaction();

            $order = $this->orderService->createOrder($user->id,$sku,$addressId);
            if($order){
                $app = app('wechat.payment');
                $result = $app->order->unify([
                    'body' => '腾讯充值中心-QQ会员充值',
                    'out_trade_no' => $order->{OrderModel::FIELD_ORDER_NUMBER},
                    'total_fee' => 0.01,
                    //'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
                    'trade_type' => 'JSAPI',
                    'openid' => $user->{User::FIELD_ID_OPENID},
                ]);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new ApiException($e->getMessage());
        }

        return $result;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2019/3/23
 * Time: 14:06
 */

namespace App\Http\Wechat;


use App\Enum\OrderEnum;
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
        $orderId = request()->input('order_id');

        //确认商品库存

        try {
            \DB::beginTransaction();

            //锁住订单中的sku，可读不可写
            $skuIds = collect($skuData)->pluck('sku_id');
            DB::table(OrderModel::TABLE_NAME)->whereIn(OrderModel::FIELD_ID,collect($skuIds)->toArray())->sharedLock()->get();

            if($orderId){
                //未支付订单，重新支付
                $order = $this->orderService->repayOrder($user->id,$orderId);
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

        return ['id'=>$order->{OrderModel::FIELD_ID},'config'=>$config];
    }

    /**
     * 统计用户订单数量状态
     *
     * @author 叶子
     * @return array
     */
    public function countOrderStatus()
    {
        $user = request()->input('user');

        $result = $this->orderService->countStatus($user->id);

        return $result;
    }

    /**
     * 获取订单详情
     *
     * @author yezi
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     * @throws ApiException
     */
    public function detail($id)
    {
        $user = request()->input('user');

        $order = $this->orderService->findById($id);
        if(!$order){
            throw new ApiException("订单不存在");
        }

        if($order->{OrderModel::FIELD_ID_USER} != $user->id){
            throw new ApiException("订单不存在");
        }

        return $order;
    }

    /**
     * 获取用户订单列表
     *
     * @author yezi
     * @return array
     */
    public function orderList()
    {
        $user       = request()->input('user');
        $status     = request()->input('filter');
        $pageSize   = request()->input('page_size', 20);
        $pageNumber = request()->input('page_number', 1);
        $orderBy    = request()->input('order_by', 'created_at');
        $sortBy     = request()->input('sort_by', 'desc');

        if($status == 0){
            $filter = [];
        }else{
            if($status == 2){
                $filter = [OrderEnum::STATUS_PAID,OrderEnum::STATUS_WAIT_DISPATCH];
            }elseif ($status == 1){
                $filter = [OrderEnum::STATUS_NOT_PAY,OrderEnum::STATUS_PAY_FAIL];
            }else{
                $filter = [$status];
            }
        }

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $field      = [
            OrderModel::FIELD_ID,
            OrderModel::FIELD_ID_USER,
            OrderModel::FIELD_ORDER_NUMBER,
            OrderModel::FIELD_AMOUNT,
            OrderModel::FIELD_ACTUAL_AMOUNT,
            OrderModel::FIELD_ID_USER_ADDRESS,
            OrderModel::FIELD_STATUS
        ];
        $query  = $this->orderService->queryBuilder($user->id,$filter)->orderBy($orderBy,$sortBy)->done();
        $orders = paginate($query, $pageParams, $field, function ($item) {
            return $item;
        });

        return $orders;
    }
}
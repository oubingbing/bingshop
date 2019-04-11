<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/4 0004
 * Time: 10:47
 */

namespace App\Http\Controllers\Admin;


use App\Enum\OrderEnum;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\OrderService;
use App\Http\Service\ShoppingCartService;
use App\Models\OrderModel;

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
     * 订单表视图
     *
     * @author yezi
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view("admin.order");
    }

    /**
     * 获取订单列表
     *
     * @author yezi
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
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
            OrderModel::FIELD_STATUS,
            OrderModel::FIELD_CREATED_AT
        ];
        $query  = $this->orderService->queryBuilder($filter)->orderBy($orderBy,$sortBy)->done();
        $orders = paginate($query, $pageParams, $field, function ($item) {
            return $item;
        });

        return webResponse('ok',$orders);
    }

    /**
     * 订单发货
     *
     * @author yezi
     * @return string
     */
    public function deliver()
    {
        $user    = request()->input('user');
        $orderId = request()->input('order_id');

        if(!$orderId){
            throw new ApiException("参数不能为空");
        }

        //需要记录订单变更日志

        $result  = $this->orderService->deliverOrder($orderId);
        return webResponse("发货成功",$result);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/4 0004
 * Time: 10:47
 */

namespace App\Http\Controllers\Admin;


use App\Enum\OrderEnum;
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

    public function index()
    {
        return view("admin.order");
    }

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

}
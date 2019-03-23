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

class OrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
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

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new ApiException($e->getMessage());
        }

        return '创建成功';
    }

}
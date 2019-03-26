<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21 0021
 * Time: 14:55
 */

namespace App\Http\Wechat;


use App\Enum\GoodsEnum;
use App\Exceptions\WebException;
use App\Http\Controllers\Controller;
use App\Http\Service\ShoppingCartService;
use App\Http\Service\SkuService;
use App\Models\GoodsModel;
use App\Models\SkuModel;
use League\Flysystem\Exception;

class ShoppingCartController extends Controller
{
    private $cartService;
    private $skuService;

    public function __construct(ShoppingCartService $cartService,SkuService $skuService)
    {
        $this->cartService = $cartService;
        $this->skuService  = $skuService;
    }

    public function addToCart()
    {
        $user        = request()->input('user');
        $skuId       = request()->input('sku_id');
        $purchaseNum = request()->input('purchase_num');

        if(!$skuId){
            throw new WebException("商品不能为空");
        }

        if(!$purchaseNum){
            throw new WebException("购买数量不能为空");
        }

        $sku   = $this->skuService->findSkuById($skuId);
        $goods = $sku['goods'];
        if($purchaseNum > $sku->{SkuModel::FIELD_STOCK}){
            throw new WebException("商品库存不足");
        }

        //商品是否在售
        if($goods->{GoodsModel::FIELD_STATUS} != GoodsEnum::SALE_STATUS_UP){
            throw new WebException("商品已下架");
        }

        //检测是否有购买数量的限制
        if($goods->{GoodsModel::FIELD_LIMIT_PURCHASE_NUM} != 0){
            //检测购物车是否已经有了
            $cartNum = $this->cartService->countNormalCartNum($skuId,$user->id);
            //检测完成的订单是否也有
            $orderPurchaseNum = 0 ;//查询已购买的加上现在购买的是否已经超过了限制购买的数量
            if(($purchaseNum+$orderPurchaseNum+$cartNum) > $sku['goods'][GoodsModel::FIELD_LIMIT_PURCHASE_NUM]){
                throw new WebException("购买数量超过了限制");
            }
        }

        $cart = $this->cartService->addToCart($user->id,$skuId,$purchaseNum);
        if(!$cart){
            throw new WebException("保存数据失败");
        }

        return ['message'=>'加入购物车成功','data'=>$cart];
    }

    public function carts()
    {
        $user = request()->input('user');

        $carts = $this->cartService->getUserCart($user->id);

        return $carts;
    }

    public function delete($id)
    {
        $user = request()->input('user');

        $result = $this->cartService->deleteUserSku($user->id,$id);
        if(!$result){
            throw new Exception("移出购物车失败");
        }

        return $result;
    }

    public function getCartNum()
    {
        $user = request()->input('user');
        $num  = $this->cartService->sumCartNum($user->id);
        return $num;
    }
}
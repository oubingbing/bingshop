<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21 0021
 * Time: 14:59
 */

namespace App\Http\Service;


use App\Enum\CartEnum;
use App\Models\GoodsModel;
use App\Models\ShoppingCartModel as Model;
use App\Models\SkuModel;

class ShoppingCartService
{
    /**
     * 获取用户在购物车中sku商品
     *
     * @author yezi
     * @param $skuId
     * @param $uid
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getCartBySkuIdAndUserId($skuId,$uid)
    {
        $carts = Model::query()
            ->where(Model::FIELD_ID_SKU,$skuId)
            ->where(Model::FIELD_ID_USER,$uid)
            ->select([
                Model::FIELD_ID,
                Model::FIELD_ID_SKU,
                Model::FIELD_ID_USER,
                Model::FIELD_PURCHASE_NUM,
                Model::FIELD_STATUS
            ])
            ->get();
        return $carts;
    }

    /**
     * 获取购物车中用户存在的商品数量
     *
     * @author yezi
     * @param $skuId
     * @param $uid
     * @return int
     */
    public function countNormalCartNum($skuId,$uid)
    {
        $carts = $this->getCartBySkuIdAndUserId($skuId,$uid);
        $count = 0;
        foreach ($carts as $cart){
            if($cart->{Model::FIELD_STATUS} == CartEnum::STATUS_NORMAL){
                $count += $cart->{Model::FIELD_PURCHASE_NUM};
            }
        }
        return $count;
    }

    /**
     * 加入购物车
     *
     * @author yezi
     * @param $userId
     * @param $skuId
     * @param $purchaseNum
     * @return mixed
     */
    public function addToCart($userId,$skuId,$purchaseNum)
    {
        $cart = Model::create([
            Model::FIELD_ID_USER      => $userId,
            Model::FIELD_ID_SKU       => $skuId,
            Model::FIELD_PURCHASE_NUM => $purchaseNum,
            Model::FIELD_STATUS       => CartEnum::STATUS_NORMAL
        ]);
        return $cart;
    }

    /**
     * 获取用户购物车商品数据
     *
     * @author yezi
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getUserCart($userId)
    {
        $carts = Model::query()
            ->with([
                Model::REL_SKU.'.'.SkuModel::REL_GOODS=>function($query){
                    $query->select([
                        GoodsModel::FIELD_ID,
                        GoodsModel::FIELD_NAME,
                        GoodsModel::FIELD_IMAGES_ATTACHMENTS
                    ]);
                },
                Model::REL_SKU.'.'.SkuModel::REL_STANDARD_VALUES
            ])
            ->where(Model::FIELD_STATUS,CartEnum::STATUS_NORMAL)
            ->where(Model::FIELD_ID_USER,$userId)
            ->select([\DB::raw('sum(purchase_num) as purchase_num'),Model::FIELD_ID_SKU])
            ->groupBy([Model::FIELD_ID_SKU,Model::FIELD_PURCHASE_NUM])
            ->get();

        return $carts;
    }

    /**
     * 删除用户的购物车商品
     *
     * @author yezi
     * @param $userId
     * @param $skuId
     * @return int
     */
    public function deleteUserSku($userId,$skuId)
    {
        $result = Model::query()
            ->where(Model::FIELD_ID_USER,$userId)
            ->where(Model::FIELD_ID_SKU,$skuId)
            ->where(Model::FIELD_STATUS,CartEnum::STATUS_NORMAL)
            ->update([Model::FIELD_STATUS=>CartEnum::STATUS_REMOVED]);
        return $result;
    }

    /**
     * 统计用户购物车商品数量
     *
     * @author yezi
     * @param $userId
     * @return mixed
     */
    public function sumCartNum($userId)
    {
        $num = Model::query()
            ->where(Model::FIELD_STATUS,CartEnum::STATUS_NORMAL)
            ->where(Model::FIELD_ID_USER,$userId)
            ->sum(Model::FIELD_PURCHASE_NUM);
        return $num;
    }

}
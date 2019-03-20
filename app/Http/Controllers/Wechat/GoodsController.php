<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19 0019
 * Time: 13:56
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\GoodsService;
use App\Http\Service\SkuService;
use App\Http\Service\StandardService;
use App\Models\GoodsModel;
use App\Models\SkuModel;

class GoodsController extends Controller
{
    private $goodsService;
    private $standardService;
    private $skuService;

    function __construct(GoodsService $activityService,StandardService $standardService,SkuService $skuService)
    {
        $this->goodsService = $activityService;
        $this->standardService = $standardService;
        $this->skuService = $skuService;
    }

    public function detail($goodsId)
    {
        $user = request()->input('user');
        $goods = $this->goodsService->findGoodsById($goodsId);
        if(!$goods){
            throw new ApiException("商品不存在");
        }
        $standards = $this->skuService->getSkuByGoodsId($goodsId);

        return ['goods'=>$goods,'standards'=>$standards];
    }



}
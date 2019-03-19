<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19 0019
 * Time: 13:56
 */

namespace App\Http\Wechat;


use App\Http\Controllers\Controller;
use App\Http\Service\GoodsService;
use App\Http\Service\SkuService;
use App\Http\Service\StandardService;

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

    

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/28 0028
 * Time: 17:05
 */

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Exceptions\WebException;
use App\Http\Controllers\Controller;
use App\Http\Service\GoodsService;
use App\Http\Service\SkuService;
use App\Http\Service\StandardService;
use App\Models\GoodsModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function index()
    {
        return view("admin.goods");
    }

    public function createGoods()
    {
        $user = request()->input("user");
        $standardItems = request()->input('standard_items');
        $skuData = request()->input('sku_data');

        if(!$standardItems){
            throw new ApiException("规格参数不能为空",500);
        }

        $Goods = new GoodsModel();

        try {
            \DB::beginTransaction();

            $this->standardService->checkStoreStandardItems($standardItems,$user->id);
            $goods = $this->goodsService->storeGoods($Goods);
            $this->skuService->storeSkuList($goods['id'],$skuData,$standardItems);


            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new WebException($e->getMessage());
        }

        return webResponse('',"编辑成功");
    }

}
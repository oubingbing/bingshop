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

    public function createGoods(Request $request)
    {
        $user = $request->input("user");
        $standardItems = $request->input('standard_items');
        $skuData = $request->input('sku_data');
        $goodsName = $request->input('goods_name');
        $goodsDescribe = $request->input('goods_describe');
        $goodsShareDescribe = $request->input('goods_share_describe');
        $attachments = $request->input('attachments');
        $checkedCategory = $request->input('checked_category');
        $goodsPrice = $request->input('goods_price');
        $chalkLinePrice = $request->input('chalk_line_price');
        $goodsStock = $request->input('goods_stock');
        $saleStartType = $request->input('sale_start_type');
        $startSaleTime = $request->input('start_sale_time');
        $saleStopType = $request->input('sale_stop_type');
        $stopSaleTime = $request->input('stop_sale_time');
        $limitSaleModel = $request->input('limit_sale_model');
        $limitSaleModelValue = $request->input('limit_sale_model_value');
        $postType = $request->input('post_type');
        $postCostType = $request->input('post_cost_type');
        $postCost = $request->input('post_cost');

        //校验参数
        $valid = $this->goodsService->validRegister($request,$standardItems,$goodsPrice,$goodsStock);
        if(!$valid['status']){
            throw new WebException($valid['message']);
        }

        dd("test");

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
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/28 0028
 * Time: 17:05
 */

namespace App\Http\Controllers\Admin;

use App\Enum\GoodsEnum;
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

        //商品上架日期和下架日期的校验
        $startSaleTime = Carbon::parse($startSaleTime);
        $stopSaleTime = Carbon::parse($stopSaleTime);
        if($startSaleTime->lt(Carbon::now()->startOfDay())){
            throw new WebException("商品开售日期不能小于今天");
        }

        if($startSaleTime->gt($stopSaleTime)){
            throw new WebException("商品开售日期不能大于下架日期");
        }

        $Goods = new GoodsModel();
        $Goods->{GoodsModel::FIELD_NAME} = $goodsName;
        $Goods->{GoodsModel::FIELD_DESCRIBE} = $goodsDescribe;
        $Goods->{GoodsModel::FIELD_SHARE_DESCRIBE} = $goodsShareDescribe;
        $Goods->{GoodsModel::FIELD_IMAGES_ATTACHMENTS} = $attachments;
        $Goods->{GoodsModel::FIELD_SKU_TYPE} = $skuData?GoodsEnum::SINGLE_SKU:GoodsEnum::BATCH_SKU;
        $Goods->{GoodsModel::FIELD_STATUS} = GoodsEnum::SALE_STATUS_DOWN;
        $Goods->{GoodsModel::FIELD_START_SALE_TYPE} = $saleStartType;
        $Goods->{GoodsModel::FIELD_START_SELLING_AT} = $startSaleTime->toDateTimeString();
        $Goods->{GoodsModel::FIELD_STOP_SALE_TYPE} = $saleStopType;
        $Goods->{GoodsModel::FIELD_STOP_SELLING_AT} = $stopSaleTime->toDateTimeString();
        $Goods->{GoodsModel::FIELD_LIMIT_PURCHASE_NUM} = $limitSaleModel==1?0:$limitSaleModelValue;
        $Goods->{GoodsModel::FIELD_DISTRIBUTION_MODE} = $postType;
        $Goods->{GoodsModel::FIELD_POSTAGE_COST} = $postCost;

        try {
            \DB::beginTransaction();

            $goods = $this->goodsService->storeGoods($Goods);
            if(!$goods){
                throw new WebException("新建商品失败");
            }

            //关联商品类目
            $attachCategoryResult = $this->goodsService->attachCategory($Goods->id,$checkedCategory);
            if(!$attachCategoryResult){
                throw new WebException("关联商品类目失败");
            }

            //如果管理间没有创建sku，那创建默认的sku,默认的sku没有规格数据，只有商品的价格库存相关的数据
            if(!$skuData){
                $defaultSkuResult = $this->skuService->storeDefaultSku($Goods->id,$goodsPrice,$goodsPrice,$costPrice=0,$chalkLinePrice,$goodsStock,$attachments);
                if(!$defaultSkuResult){
                    throw new WebException("保存数据失败");
                }
            }else{
                //保存商品sku数据
                $this->standardService->checkStoreStandardItems($standardItems,$user->id);
                $this->skuService->storeSkuList($Goods->id,$skuData,$standardItems);
            }


            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            //\Log::info("新建商品错误",collect($e)->toArray());
            throw new WebException($e->getMessage());
        }

        return webResponse("新建商品成功",$Goods);
    }

}
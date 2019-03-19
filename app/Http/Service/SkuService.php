<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/15 0015
 * Time: 15:11
 */

namespace App\Http\Service;


use App\Exceptions\WebException;
use App\Models\SkuModel as Model;
use App\Models\SkuStandardValueModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

class SkuService
{
    private $standardService;

    public function __construct()
    {
        $this->standardService = app(StandardService::class);
    }

    /**
     * 保存前端传递过来的sku列表
     *
     * @author yezi
     * @param $goodsId
     * @param $skuData
     * @param $standardItems
     * @throws WebException
     */
    public function storeSkuList($goodsId,$skuData,$standardItems)
    {
        $standardNames = $names = collect($standardItems)->pluck('name');
        $standardData = $this->standardService->getStandardInfo($standardNames);
        if(!$standardData){
            throw new WebException("获取规格数据错误");
        }

        $skuStandardValues = [];
        $now = Carbon::now()->toDateTimeString();
        foreach ($skuData as $sku){
            $skuStoreResult = $this->storeSku($sku,$goodsId);
            if(!$skuStoreResult){
                throw new WebException("保存数据失败");
            }
            //匹配数据库中的规格数据，用户管理sku
            foreach ($sku['levels'] as $level){
                //为了代码逻辑清晰，循环去查询数据库的规格值
                $standardValue = $this->standardService->findStandardValueByValue($level['value'],$level['name']);
                if(!$standardValue){
                    throw new WebException("规格数据获取失败");
                }
                array_push($skuStandardValues,[
                    SkuStandardValueModel::FIELD_ID_SKU=>$skuStoreResult['id'],
                    SkuStandardValueModel::FIELD_ID_STANDARD_VALUE=>$standardValue['id'],
                    SkuStandardValueModel::FIELD_CREATED_AT=>$now,
                    SkuStandardValueModel::FIELD_UPDATED_AT=>$now
                ]);
            }
        }

        if($skuStandardValues){
            DB::table(SkuStandardValueModel::TABLE_NAME)->insert($skuStandardValues);
        }
    }

    /**
     * 保存商品sku数据
     *
     * @author yezi
     * @param $sku
     * @param $goodsId
     * @return mixed
     */
    public function storeSku($sku,$goodsId)
    {
        $result = Model::create([
            Model::FIELD_ID_GOODS=>$goodsId,
            Model::FIELD_PRICE=>$sku['price'],
            Model::FIELD_VIP_PRICE=>isset($sku['vip_price'])?$sku['vip_price']:$sku['price'],
            Model::FIELD_COST_PRICE=>$sku['cost_price'],
            Model::FIELD_CHALK_LINE_PRICE=>isset($sku['chalk_line_price'])?$sku['chalk_line_price']:0,
            Model::FIELD_STOCK=>$sku['stock'],
            Model::FIELD_ATTACHMENTS=>isset($sku['attachments'])?$sku['attachments']:[]
        ]);
        return $result;
    }

    /**
     * 保存默认sku没有规格数据的，只有sku
     *
     * @author yezi
     * @param $goodsId
     * @param $price
     * @param $vipPrice
     * @param $costPrice
     * @param $chalkLinePrice
     * @param $stock
     * @param $attachments
     * @return mixed
     * @throws WebException
     */
    public function storeDefaultSku($goodsId,$price,$vipPrice,$costPrice,$chalkLinePrice,$stock,$attachments)
    {
        $sku = [
            Model::FIELD_PRICE=>$price,
            Model::FIELD_VIP_PRICE=>$vipPrice,
            Model::FIELD_COST_PRICE=>$costPrice,
            Model::FIELD_CHALK_LINE_PRICE=>$chalkLinePrice,
            Model::FIELD_STOCK=>$stock,
            Model::FIELD_ATTACHMENTS=>$attachments
        ];
        $result = $this->storeSku($sku,$goodsId);
        if(!$result){
            throw new WebException("保存数据失败");
        }

        return $result;
    }
}
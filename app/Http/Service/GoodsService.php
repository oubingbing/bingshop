<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/29 0029
 * Time: 17:36
 */

namespace App\Http\Service;


use App\Exceptions\WebException;
use App\Models\CategoryGoodsModel;
use App\Models\GoodsModel as Model;
use App\Models\GoodsModel;
use App\Models\SkuModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GoodsService
{
    private $builder;

    /**
     * 校验输入信息
     *
     * @author yeiz
     *
     * @param $request
     * @return array
     */
    public function validRegister($request,$standardItems,$goodsPrice,$goodsStock)
    {
        $rules = [
            'goods_name' => 'required',
            //'attachments' => 'required|array',
            'checked_category' => 'required',
            'sale_start_type' => 'required | in:1,2,3',
            'sale_stop_type' => 'required | in:1,2,3',
            'limit_sale_model' => 'required | in:1,2',
            'post_type' => 'required | in:1,2,3',
            'post_cost_type' => 'required | in:1',
            'post_cost' => 'required'
        ];
        $message = [
            'goods_name.required' => '商品名不能为空',
            'attachments.required' => '图片不能为空',
            'checked_category.required' => '商品类目不能为空',
            'sale_start_type.required' => '商品上架类型不能为空',
            'sale_stop_type.required' => '商品下架类型不能为空',
            'limit_sale_model.required' => '商品限购类型不能为空',
            'post_type.required' => '商品发货类型不能为空',
            'post_cost_type.required' => '商品邮费类型不能为空',
            'post_cost.required' => '商品邮费不能为空',
            'sale_start_type.in' => '商品上架类型参数错误',
            'sale_stop_type.in' => '商品下架类型参数错误',
            'limit_sale_model.in' => '商品限购类型参数错误',
            'post_type.in' => '商品配送方式类型参数错误',
            'post_cost_type.in' => '运费类型参数错误',
        ];
        $validator = \Validator::make($request->all(),$rules,$message);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return ['status'=>false,'message'=>$errors->first()];
        }else{
            if(!$standardItems){
                if(!$goodsPrice){
                    throw new WebException("商品价格不能为空",500);
                }
                if(!$goodsStock){
                    throw new WebException("商品库存不能为空",500);
                }
                if(!is_numeric($goodsPrice)){
                    throw new WebException("商品价格必须是数字");
                }
                if(!is_numeric($goodsStock)){
                    throw new WebException("商品库存必须是数字");
                }
            }

            return ['status'=>true,'message'=>'success'];
        }
    }

    /**
     * 保存商品
     *
     * @author yezi
     * @param Model $model
     * @return bool
     */
    public function storeGoods(Model $model)
    {
        $result = $model->save();
        return $result;
    }

    /**
     * 关联商品分类
     *
     * @author yezi
     * @param $goodsId
     * @param $categories
     * @return mixed
     */
    public function attachCategory($goodsId,$categories)
    {
        $categories = collect($categories)->map(function ($item)use($goodsId){
           return [
               CategoryGoodsModel::FIELD_ID_CATEGORY=>$item,
               CategoryGoodsModel::FIELD_ID_GOODS=>$goodsId,
               CategoryGoodsModel::FIELD_CREATED_AT=>Carbon::now()->toDateTimeString(),
               CategoryGoodsModel::FIELD_UPDATED_AT=>Carbon::now()->toDateTimeString()
           ];
        });

        $result = DB::table(CategoryGoodsModel::TABLE_NAME)->insert($categories->toArray());
        return $result;
    }

    public function findGoodsById($goodsId)
    {
        return Model::query()->find($goodsId);
    }

    public function getGoodsIdsByCategoryId($categoryId)
    {
        $goodsIds = CategoryGoodsModel::query()->where(CategoryGoodsModel::FIELD_ID_CATEGORY,$categoryId)->pluck(CategoryGoodsModel::FIELD_ID_GOODS);
        if($goodsIds){
            $fields = [
                GoodsModel::FIELD_ID,
                GoodsModel::FIELD_NAME,
                GoodsModel::FIELD_DESCRIBE,
                GoodsModel::FIELD_IMAGES_ATTACHMENTS,
                GoodsModel::FIELD_SKU_TYPE
            ];
            $goodsList = app(GoodsService::class)->getGoodsByIds(collect($goodsIds)->toArray(),$fields);
            return $goodsList;
        }

        return $goodsIds;
    }

    /**
     * 根据数组IDS查询商品信息
     *
     * @author yezi
     * @param $ids
     * @param null $fields
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getGoodsByIds($ids,$fields=null)
    {
        $query = Model::query()
            ->with([Model::REL_SKU=>function($queryBuilder){
                $queryBuilder->select([
                    SkuModel::FIELD_ID,
                    SkuModel::FIELD_ID_GOODS,
                    SkuModel::FIELD_PRICE,
                    SkuModel::FIELD_PRICE,
                    SkuModel::FIELD_VIP_PRICE,
                    SkuModel::FIELD_CHALK_LINE_PRICE,
                    SkuModel::FIELD_STOCK
                ]);
            }])
            ->whereIn(Model::FIELD_ID,$ids);
        if($fields){
            $query->select($fields);
        }
        $result = $query->get();
        return $result;
    }

    public function format($goods)
    {
        return $goods;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/29 0029
 * Time: 17:36
 */

namespace App\Http\Service;


use App\Exceptions\WebException;
use App\Models\GoodsModel as Model;

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
            'sale_start_type' => 'required',
            'sale_stop_type' => 'required',
            'limit_sale_model' => 'required',
            'post_type' => 'required',
            'post_cost_type' => 'required',
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

    public function storeGoods(Model $model)
    {
        $result = $model->save();
        return $result;
    }

}
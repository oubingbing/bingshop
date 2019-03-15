<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/29 0029
 * Time: 17:36
 */

namespace App\Http\Service;


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
    public function validRegister($request)
    {
        $rules = [
            'title' => 'required',
            'attachments' => 'required',
            'url' => 'required',
            'content' => 'required',
            'bank_id' => 'required',
            //'categories' => 'required',
            'limit_type' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
        ];
        $message = [
            'title.required' => '标题不能为空',
            'attachments.required' => '图片不能为空',
            'url.required' => '链接不能为空',
            'content.required' => '详情不能为空',
            'bank_id.required' => '银行不能为空',
            //'categories.required' => '类型不能为空',
            'limit_type.required' => '数量限制类型不能为空',
            'start_at.required' => '开始日期不能为空',
            'end_at.required' => '截止日期不能为空',
        ];
        $validator = \Validator::make($request->all(),$rules,$message);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return ['status'=>false,'message'=>$errors->first()];
        }else{
            return ['status'=>true,'message'=>'success'];
        }
    }

    public function storeGoods(Model $model)
    {
        $result = $model->save();
        return $result;
    }

}
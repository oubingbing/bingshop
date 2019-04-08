<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8 0008
 * Time: 17:25
 */

namespace App\Http\Service;


use App\Models\AddressModel as Model;

class AddressService
{
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
            'name'           => 'required',
            'phone'          => 'required',
            'province_id'    => 'required',
            'city_id'        => 'required',
            'country_id'     => 'required',
            'detail_address' => 'required',
        ];
        $message = [
            'name.required'           => '商品名不能为空',
            'phone.required'          => '图片不能为空',
            'province_id.required'    => '商品类目不能为空',
            'city_id.required'        => '商品上架类型不能为空',
            'country_id.required'     => '商品下架类型不能为空',
            'detail_address.required' => '商品限购类型不能为空',
        ];
        $validator = \Validator::make($request->all(),$rules,$message);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return ['status'=>false,'message'=>$errors->first()];
        }

        return ['status'=>true,'message'=>'success'];
    }

    /**
     * 保存数据
     *
     * @author yezi
     * @param $params
     * @return mixed
     */
    public function storeAddress($params)
    {
        $address = Model::create([
            Model::FIELD_ID_USER        => $params['user_id'],
            Model::FIELD_RECEIVER       => $params['receiver'],
            Model::FIELD_PHONE          => $params['phone'],
            Model::FIELD_ID_PROVINCE    => $params['province_id'],
            Model::FIELD_ID_CITY        => $params['city_id'],
            Model::FIELD_ID_COUNTRY     => $params['country_id'],
            Model::FIELD_DETAIL_ADDRESS => $params['detail_address'],
            Model::FIELD_TYPE           => $params['type']
        ]);
        return $address;
    }

    /**
     * 查找用户的收货地址是否已存在
     *
     * @author yezi
     * @param $userId
     * @param $name
     * @param $phone
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findUserAddressByNamePhone($userId,$name,$phone)
    {
        $address = Model::query()
            ->where(Model::FIELD_ID_USER,$userId)
            ->where(Model::FIELD_RECEIVER,$name)
            ->where(Model::FIELD_PHONE,$phone)
            ->first();
        return $address;
    }

}
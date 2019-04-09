<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8 0008
 * Time: 17:25
 */

namespace App\Http\Service;


use App\Enum\OrderEnum;
use App\Exceptions\ApiException;
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
            'receiver'           => 'required',
            'phone'          => 'required',
            'province'       => 'required',
            'city'           => 'required',
            'district'       => 'required',
            'detail_address' => 'required',
        ];
        $message = [
            'receiver.required'           => '收货人不能为空',
            'phone.required'          => '联系电话不能为空',
            'province.required'       => '省份不能为空',
            'city.required'           => '城市不能为空',
            'district.required'       => '县区不能为空',
            'detail_address.required' => '详细不能为空',
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
    public function storeAddress($userId,$params)
    {
        $address = Model::create([
            Model::FIELD_ID_USER        => $userId,
            Model::FIELD_RECEIVER       => $params['receiver'],
            Model::FIELD_PHONE          => $params['phone'],
            Model::FIELD_NATION         => $params['nation'],
            Model::FIELD_PROVINCE       => $params['province'],
            Model::FIELD_CITY           => $params['city'],
            Model::FIELD_DISTRICT       => $params['district'],
            Model::FIELD_STREET         => $params['street'],
            Model::FIELD_LONGITUDE      => $params['longitude'],
            Model::FIELD_LATITUDE       => $params['latitude'],
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

    /**
     * 将用户的所有地址设置为非默认状态
     *
     * @author yezi
     * @param $userId
     * @return int
     */
    public function setNotDefaultAddress($userId)
    {
        $result = Model::query()->where(Model::FIELD_ID_USER,$userId)->update([Model::FIELD_TYPE=>OrderEnum::ADDRESS_TYPE_NOT_DEFAULT]);
        return $result;
    }

    /**
     * 获取用户地址信息
     *
     * @author yezi
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAddressByUser($userId)
    {
        $result = Model::query()
            ->select([
                Model::FIELD_ID,
                Model::FIELD_RECEIVER,
                Model::FIELD_PHONE,
                Model::FIELD_NATION,
                Model::FIELD_PROVINCE,
                Model::FIELD_CITY,
                Model::FIELD_DISTRICT,
                Model::FIELD_STREET,
                Model::FIELD_DETAIL_ADDRESS,
                Model::FIELD_CREATED_AT,
                Model::FIELD_TYPE
            ])
            ->where(Model::FIELD_ID_USER,$userId)
            ->orderBy(Model::FIELD_CREATED_AT,'desc')
            ->get();
        return $result;
    }

    /**
     * 删除收货地址
     *
     * @author yezi
     * @param $userId
     * @param $addressId
     * @return mixed
     */
    public function delete($userId,$addressId)
    {
        return Model::query()->where(Model::FIELD_ID_USER,$userId)->where(Model::FIELD_ID,$addressId)->delete();
    }

    /**
     * 查找地址
     *
     * @author yezi
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function findById($id)
    {
        return Model::query()->find($id);
    }

    public function edit($userId,$params)
    {
        $address = $this->findById($params['address_id']);
        if(!$address || $address->{Model::FIELD_ID_USER} != $userId){
            throw new ApiException("地址不存在");
        }

        $address->{Model::FIELD_RECEIVER}       = $params['receiver'];
        $address->{Model::FIELD_PHONE}          = $params['phone'];
        $address->{Model::FIELD_NATION}         = $params['nation'];
        $address->{Model::FIELD_PROVINCE}       = $params['province'];
        $address->{Model::FIELD_CITY}           = $params['city'];
        $address->{Model::FIELD_DISTRICT}       = $params['district'];
        $address->{Model::FIELD_STREET}         = $params['street'];
        $address->{Model::FIELD_LONGITUDE}      = $params['longitude'];
        $address->{Model::FIELD_LATITUDE}       = $params['latitude'];
        $address->{Model::FIELD_DETAIL_ADDRESS} = $params['detail_address'];
        $address->{Model::FIELD_TYPE}           = $params['type'];
        $result                                 = $address->save();

        return $result;
    }

}
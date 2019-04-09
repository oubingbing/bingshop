<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8 0008
 * Time: 17:05
 */

namespace App\Http\Wechat;


use App\Enum\OrderEnum;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\AddressService;
use App\Models\AddressModel;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    private $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * 新增收货地址
     *
     * @author yezi
     * @param Request $request
     * @return mixed
     * @throws ApiException
     */
    public function createAddress(Request $request)
    {
        $user = request()->input('user');
        $params = request()->input();

        $valid = $this->addressService->validRegister($request);
        if(!$valid['status']){
            throw new ApiException($valid['message']);
        }

        if(!isMobile($params['phone'])){
            throw new ApiException("手机号码不正确");
        }

        try {
            \DB::beginTransaction();

            //新增
            if($params['type'] == OrderEnum::ADDRESS_TYPE_DEFAULT){
                //将其他地址设置为非默认
                $this->addressService->setNotDefaultAddress($user->id);
            }

            if($params['address_id']){
                //编辑
                $address = $this->addressService->edit($user->id,$params);
            }else{
                $address = $this->addressService->storeAddress($user->id,$params);
                if(!$address){
                    throw new ApiException("保存失败");
                }
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new ApiException($e->getMessage());
        }

        return $address;
    }

    /**
     * 获取用户收货地址列表
     *
     * @author yezi
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAddress()
    {
        $user = request()->input('user');

        $address = $this->addressService->getAddressByUser($user->id);

        return $address;
    }

    /**
     * 删除收货地址
     *
     * @author yezi
     * @param $id
     * @return string
     * @throws ApiException
     */
    public function deleteAddress($id)
    {
        $user = request()->input('user');

        $result = $this->addressService->delete($user->id,$id);
        if(!$result){
            throw new ApiException("删除失败");
        }

        return (string)$result;
    }

    public function addressDetail($id)
    {
        $user = request()->input('user');

        $address = $this->addressService->findById($id);
        if($address->{AddressModel::FIELD_ID_USER} != $user->id){
            $address = '';
        }

        return $address;
    }
}
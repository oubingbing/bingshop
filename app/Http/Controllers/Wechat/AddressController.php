<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8 0008
 * Time: 17:05
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\AddressService;
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

        $findAddress = $this->addressService->findUserAddressByNamePhone($user->id,$params['name'],$params['phone']);
        if($findAddress){
            throw new ApiException("收货地址已存在");
        }

        $address = $this->addressService->storeAddress($params);
        if(!$address){
            throw new ApiException("保存失败");
        }

        return $address;
    }

}
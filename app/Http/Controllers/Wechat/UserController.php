<?php

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\AuthService;
use App\Http\Service\CustomerService;
use App\Http\Service\SendMessageService;
use App\Http\Service\UserService;
use App\Http\Service\YunPianService;
use App\Jobs\UserLogs;
use App\Models\Colleges;
use App\Models\Customer;
use App\Models\User;
use App\Models\WechatApp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{
    private $userService;
    private $customerService;

    public function __construct(UserService $userService,CustomerService $customerService)
    {
        $this->userService = $userService;
        $this->customerService = $customerService;
    }

    /**
     * 获取用户信息
     *
     * @author yezi
     *
     * @return array|string
     */
    public function user($id)
    {
        $user = User::find($id);

        return $user;
    }

    /**
     * 获取个人信息
     *
     * @author yezi
     *
     * @return array|string
     */
    public function personal()
    {
        $user = request()->input('user');

        return $user;
    }

    /**
     * 发送短信验证码
     *
     * @author yezi
     * @return mixed
     * @throws ApiException
     */
    public function sendMessage()
    {
        $user = request()->input('user');
        $phone = request()->input("phone");

        if(empty($phone)){
            throw new ApiException("手机号码不能为空");
        }

        $validPhone = validMobile($phone);
        if(!$validPhone){
            throw new ApiException("手机号码格式不正确");
        }

        $result = app(YunPianService::class)->sendMessageCode($phone);
        if($result['code'] != 0){
            throw new ApiException('发送失败！',500);
        }

        return $result;
    }

    /**
     * 绑定账号
     *
     * @author yezi
     * @return string
     * @throws ApiException
     */
    public function bindUser()
    {
        $user = request()->input('user');
        $phone = request()->input("phone");
        $code = request()->input("code");

        if(empty($phone)){
            throw new ApiException("手机号不能为空");
        }

        if(empty($code)){
            throw new ApiException("验证码不能为空");
        }

        $validCodeResult = app(AuthService::class)->validMessageCode($phone,$code);
        if(!$validCodeResult){
            throw new ApiException("验证码错误");
        }

        $validPhone = validMobile($phone);
        if(!$validPhone){
            throw new ApiException("手机号码格式不正确");
        }

        try{
            \DB::beginTransaction();

            $bindResult = $this->userService->bindUser($user->id,$phone);
            if(!$bindResult){
                throw new ApiException("绑定失败");
            }

            $updateUser = $this->customerService->updateAvatar($phone,$user->{User::FIELD_AVATAR});
            if(!$updateUser){
                throw new ApiException("绑定失败");
            }

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new ApiException($exception->getMessage());
        }

        $customer = $this->customerService->getCustomerByPhone($phone);

        if(!collect($customer)->isEmpty()){
            return [
                'id'=>$customer->id,
                'avatar'=>$customer->{Customer::FIELD_AVATAR},
                "nickname"=>$customer->{Customer::FIELD_NICKNAME},
                "phone"=>$customer->{Customer::FIELD_PHONE}
            ];
        }else{
            return $customer;
        }
    }

    public function bindUserInfo()
    {
        $user = request()->input('user');
        $customer = $user->{User::REL_CUSTOMER};

        if(!collect($customer)->isEmpty()){
            return [
                'id'=>$customer->id,
                'avatar'=>$customer->{Customer::FIELD_AVATAR},
                "nickname"=>$customer->{Customer::FIELD_NICKNAME},
                "phone"=>$customer->{Customer::FIELD_PHONE}
            ];
        }else{
            return $customer;
        }
    }

    public function getDonationQrCode()
    {
        $user = request()->input('user');
        $customer = $user->{User::REL_CUSTOMER};

        return $customer->{Customer::FIELD_DONATION_QR_CODE};
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\WebException;
use App\Http\Controllers\Controller;
use App\Http\Service\AuthService;
use App\Http\Service\CustomerService;
use App\Http\Service\YunPianService;
use App\Models\Customer;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * 注册视图
     *
     * @author yezi
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerView()
    {
        return view('auth.register');
    }

    /**
     * 注册
     *
     * @author yezi
     * @param Request $request
     * @return mixed
     * @throws WebException
     */
    public function register(Request $request)
    {
        $nickname = request()->input("nickname");
        $phone = request()->input("phone");
        $password = request()->input("password");
        $code = request()->input("code");

        if(empty($code)){
            throw new WebException("验证码不能为空");
        }

        $validCodeResult = $this->authService->validMessageCode($phone,$code);
        if(!$validCodeResult){
            throw new WebException("验证码错误");
        }

        $validPhone = validMobile($phone);
        if(!$validPhone){
            throw new WebException("手机号码格式不正确");
        }

        $valid = $this->authService->validRegister($request);
        if(!$valid['valid']){
            throw new WebException($valid['message']);
        }

        try{
            \DB::beginTransaction();

            $result = $this->authService->createCustomer($nickname,$phone,$password);

            //初始化用户信息
            app(CustomerService::class)->initCustomer($result->{Customer::FIELD_ID});

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new WebException($exception);
        }

        return $result;
    }

    /**
     * 发送短信验证码
     *
     * @author yezi
     * @return mixed
     * @throws WebException
     */
    public function sendMessage()
    {
        $phone = request()->input("phone");

        if(empty($phone)){
            throw new WebException("手机号码不能为空");
        }

        $validPhone = validMobile($phone);
        if(!$validPhone){
            throw new WebException("手机号码格式不正确");
        }

        $result = app(YunPianService::class)->sendMessageCode($phone);
        if($result['code'] != 0){
            throw new WebException('发送失败！',500);
        }

        return $result;
    }
}

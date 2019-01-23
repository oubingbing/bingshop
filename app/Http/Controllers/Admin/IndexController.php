<?php


namespace App\Http\Controllers\Admin;


use App\Exceptions\WebException;
use App\Http\Controllers\Controller;
use App\Http\Service\CustomerService;
use App\Http\Service\QiNiuService;
use App\Models\CollegeArticle;
use App\Models\Customer;

class IndexController extends Controller
{
    /**
     * 进入管理后台首页
     *
     * @author yezi
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $customer = request()->get('user');

        $token = app(QiNiuService::class)->getToken();

        return view('admin.index',["token"=>$token,"nickname"=>$customer->{Customer::FIELD_NICKNAME}]);
    }

    public function setDonationQrCode()
    {
        $user = request()->get("user");
        $url = request()->input("url");

        if(empty($url)){
            throw new WebException("赞赏码不能为空");
        }

        $result = app(CustomerService::class)->updateDonationQrCode($user->id,$url);

        return (string)$result;
    }

    public function getDonationQrCode()
    {
        $user = request()->get("user");
        $customer = app(CustomerService::class)->getCustomerById($user->id);
        return $customer->{Customer::FIELD_DONATION_QR_CODE};
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/12 0012
 * Time: 15:47
 */

namespace App\Http\Service;


use App\Exceptions\ApiException;
use App\Models\SendMessage as Model;
use App\Models\SendMessage;
use Carbon\Carbon;

class SendMessageService
{
    /**
     * 保存发送记录
     *
     * @author yezi
     *
     * @param $mobile
     * @param $code
     * @param $status
     * @param $type
     * @param $sessionId
     * @param $expire
     * @return mixed
     */
    public function saveSendMessageLog($mobile,$code,$status,$expire)
    {
        $result = Model::create([
            Model::FIELD_CODE=>$code,
            Model::FIELD_MOBILE=>$mobile,
            Model::FIELD_STATUS=>$status,
            Model::FIELD_EXPIRED_AT=>$expire
        ]);

        return $result;
    }

    public function getLogByCode($code)
    {
        $result = Model::query()
            ->where(Model::FIELD_CODE,$code)
            ->orderBy(Model::FIELD_CREATED_AT,'DESC')
            ->first();
        return $result;
    }

    /**
     * 验证码校验
     *
     * @author yezi
     *
     * @param $code
     * @throws ApiException
     */
    public function validCode($code)
    {
        $log = $this->getLogByCode($code);
        if(!$log){
            return ["status"=>false,"message"=>"验证码错误"];
        }

        if(Carbon::now()->gte(Carbon::parse($log->{SendMessage::FIELD_EXPIRED_AT}))){
            return ["status"=>false,"message"=>"验证码已过期，请重新发送！"];
        }

        return ["status"=>true,"message"=>"校验通过"];
    }



}
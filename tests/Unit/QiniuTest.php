<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/13
 * Time: 下午5:31
 */

namespace Tests\Unit;


use App\Http\QiNiuLogic\QiNiuLogic;
use App\Http\Service\QiNiuService;
use Tests\TestCase;

class QiniuTest extends TestCase
{
    /**
     * @test
     */
    public function uploadToken()
    {
        $token = app(QiNiuService::class)->getToken();

        dd($token);
    }

}
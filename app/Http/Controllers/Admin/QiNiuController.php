<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/29 0029
 * Time: 9:29
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Service\QiNiuService;

class QiNiuController extends Controller
{
    public function token()
    {
        $qiNiuToken = app(QiNiuService::class)->getToken();
        return webResponse($qiNiuToken);
    }

}
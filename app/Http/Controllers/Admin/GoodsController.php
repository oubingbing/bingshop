<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/28 0028
 * Time: 17:05
 */

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Exceptions\WebException;
use App\Http\Controllers\Controller;
use App\Http\Service\GoodsService;
use App\Http\Service\StandardService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    private $goodsService;
    private $standardService;

    function __construct(GoodsService $activityService,StandardService $standardService)
    {
        $this->goodsService = $activityService;
        $this->standardService = $standardService;
    }

    public function index()
    {
        return view("admin.goods");
    }

    public function createGoods()
    {
        $user = request()->input("user");
        $standardItems = request()->input('standard_items');

        if(!$standardItems){
            throw new ApiException("规格参数不能为空",500);
        }

        try {
            \DB::beginTransaction();

            $this->standardService->checkStoreStandardItems($standardItems,$user->id);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new WebException($e->getMessage());
        }

        return webResponse('',"编辑成功");
    }

}
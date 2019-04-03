<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19 0019
 * Time: 13:56
 */

namespace App\Http\Wechat;


use App\Enum\GoodsEnum;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\GoodsService;
use App\Http\Service\SkuService;
use App\Http\Service\StandardService;
use App\Models\GoodsModel;
use App\Models\SkuModel;

class GoodsController extends Controller
{
    private $goodsService;
    private $standardService;
    private $skuService;

    function __construct(GoodsService $activityService,StandardService $standardService,SkuService $skuService)
    {
        $this->goodsService = $activityService;
        $this->standardService = $standardService;
        $this->skuService = $skuService;
    }

    /**
     * 获取商品详情
     *
     * @author yezi
     * @param $goodsId
     * @return array
     * @throws ApiException
     */
    public function detail($goodsId)
    {
        $user = request()->input('user');
        $goods = $this->goodsService->findGoodsById($goodsId);
        if(!$goods){
            throw new ApiException("商品不存在");
        }
        $standards = $this->skuService->getSkuByGoodsId($goodsId);

        return ['goods'=>$goods,'standards'=>$standards];
    }

    /**
     * 获取商品列表信息
     *
     * @author yezi
     * @return array
     */
    public function goodsList()
    {
        $pageSize   = request()->input('page_size', 20);
        $pageNumber = request()->input('page_number', 1);
        $orderBy    = request()->input('order_by', 'created_at');
        $sortBy     = request()->input('sort_by', 'desc');
        $filter     = request()->input('filter');

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $query      = $this->goodsService->queryBuilder()->filter($filter,GoodsEnum::SALE_STATUS_UP)->sort($orderBy, $sortBy)->done();

        $field = [
            GoodsModel::FIELD_ID,
            GoodsModel::FIELD_NAME,
            GoodsModel::FIELD_DESCRIBE,
            GoodsModel::FIELD_IMAGES_ATTACHMENTS,
            GoodsModel::FIELD_STATUS
        ];
        $goodsData = paginate($query, $pageParams, $field, function ($item) {

            return $item;

        });

        return $goodsData;
    }


}
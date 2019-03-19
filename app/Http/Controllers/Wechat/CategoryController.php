<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19 0019
 * Time: 13:56
 */

namespace App\Http\Wechat;


use App\Http\Controllers\Controller;
use App\Http\Service\CategoryService;
use App\Http\Service\GoodsService;
use App\Models\GoodsModel;

class CategoryController extends Controller
{
    private $categoryService;
    private $goodsService;

    function __construct(CategoryService $categoryService,GoodsService $goodsService)
    {
        $this->categoryService = $categoryService;
        $this->goodsService = $goodsService;
    }

    public function categories()
    {
        $user = request()->input('user');

        $categories = $this->categoryService->categoryList();
        if(!$categories){
            return ['categories'=>[],'goods'=>[]];
        }

        $goodsIds = $this->goodsService->getGoodsIdsByCategoryId(collect($categories)->first()->id);
        $goodsList = [];
        if($goodsIds){
            $fields = [
                GoodsModel::FIELD_ID,
                GoodsModel::FIELD_NAME,
                GoodsModel::FIELD_DESCRIBE,
                GoodsModel::FIELD_IMAGES_ATTACHMENTS
            ];
            $goodsList = $this->goodsService->getGoodsByIds(collect($goodsIds)->toArray(),$fields);
        }

        return ['categories'=>$categories,'goods'=>$goodsList];
    }

}
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

    /**
     * 获取商品分类列表
     *
     * @author yezi
     * @return array
     */
    public function categories()
    {
        $user = request()->input('user');

        $categories = $this->categoryService->categoryList();
        if(!$categories){
            return ['categories'=>[],'goods'=>[]];
        }

        $goods = $this->goodsService->getGoodsIdsByCategoryId(collect($categories)->first()->id);

        return ['categories'=>$categories,'goods'=>$goods];
    }

    public function categoryGoods($categoryId)
    {
        $user = request()->input('user');

        $goods = $this->goodsService->getGoodsIdsByCategoryId($categoryId);
        $goods = collect($goods)->map(function ($item){
           return $this->goodsService->format($item);
        });

        return $goods;
    }

}
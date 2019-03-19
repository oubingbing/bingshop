<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/28 0028
 * Time: 17:05
 */

namespace App\Http\Controllers\Admin;


use App\Exceptions\WebException;
use App\Http\Controllers\Controller;
use App\Http\Service\CategoryService;
use App\Models\ActivityCategoryModel;
use App\Models\CategoryModel;

class CategoryController extends Controller
{
    private $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.category');
    }

    /**
     * 新建类型
     *
     * @author yezi
     * @throws WebException
     */
    public function createCategory()
    {
        $user = request()->get("user");
        $name = request()->input("name");
        $sort = request()->input("sort");

        if(!$name){
            throw new WebException("类型名称不能为空");
        }

        $repeat = $this->service->checkRepeatByName($name);
        if($repeat){
            throw new WebException("类型已存在，不可重复创建");
        }

        $category = new CategoryModel();
        $category->{CategoryModel::FIELD_NAME} = $name;
        $category->{CategoryModel::FIELD_SORT} = $sort;
        $category->{CategoryModel::FIELD_ID_ADMIN} = $user->id;

        try {
            \DB::beginTransaction();

            $result = $this->service->store($category);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new WebException($e);
        }

        return webResponse($result,"新建成功");
    }

    /**
     * 获取所有的优惠类型
     *
     * @author yezi
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function categories()
    {
        $user = request()->get("user");
        $list = $this->service->categoryList();
        return webResponse('success',$list);
    }

    /**
     * 类型分页列表
     *
     * @author yezi
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function categoryList()
    {
        $user = request()->input('user');
        $pageSize = request()->input('page_size', 20);
        $pageNumber = request()->input('page_number', 1);
        $orderBy = request()->input('order_by', 'created_at');
        $sortBy = request()->input('sort_by', 'desc');

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $query = $this->service->queryBuilder()->sort($orderBy, $sortBy)->done();
        $userList = paginate($query, $pageParams, '*', function ($item) use ($user) {
            return $item;
        });

        return webResponse('success',$userList);
    }

    /**
     * 编辑类型
     *
     * @author yezi
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws WebException
     */
    public function edit()
    {
        $user = request()->input("user");
        $id = request()->input('id');
        $name = request()->input("name");
        $sort = request()->input("sort");

        if(!$id){
            throw new WebException("类型不能为空");
        }

        if(!$name){
            throw new WebException("类型名称不能为空");
        }

        $category = $this->service->getCategoryById($id);
        if(!$category){
            throw new WebException("优惠类型不存在");
        }

        try {
            \DB::beginTransaction();

            $result = $this->service->edit($id,$name,$sort);
            if(!$result){
                throw new WebException("删除失败");
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new WebException($e->getMessage());
        }

        return webResponse("编辑成功",$result);
    }

    public function delete($id)
    {
        $user = request()->input('user');

        $category = $this->service->getCategoryById($id);
        if(!$category){
            throw new WebException("类型不存在");
        }

        $use = $this->service->checkUse($id);
        if($use){
            throw new WebException("该优惠类型已经被使用，不可删除");
        }

        try {
            \DB::beginTransaction();

            $result = $this->service->delete($id);
            if(!$result){
                throw new WebException("删除失败");
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new WebException($e->getMessage());
        }

        return webResponse("删除成功",$result);

    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/13 0013
 * Time: 13:50
 */

namespace App\Http\Service;


use App\Models\CategoryModel as Model;

class CategoryService
{
    private $builder;

    public function store(Model $category)
    {
        $result = Model::create([
            Model::FIELD_NAME=>$category->{Model::FIELD_NAME},
            Model::FIELD_ATTACHMENTS=>$category->{Model::FIELD_ATTACHMENTS},
            Model::FIELD_ID_ADMIN=>$category->{Model::FIELD_ID_ADMIN},
            Model::FIELD_SORT=>$category->{Model::FIELD_SORT}
        ]);
        return $result;
    }

    /**
     * 根据优惠类型的名称获取
     *
     * @author yezi
     * @param $name
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getCategoryByName($name)
    {
        $result = Model::query()->where(Model::FIELD_NAME,$name)->first();
        return $result;
    }

    /**
     * 检测类型名称是否重复
     *
     * @author yezi
     * @param $name
     * @return bool
     */
    public function checkRepeatByName($name)
    {
        $result = $this->getCategoryByName($name);
        return $result?true:false;
    }

    /**
     * 获取所有优惠类型
     *
     * @author yezi
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function categoryList()
    {
        $categories = Model::query()->select([
            Model::FIELD_ID,
            Model::FIELD_NAME
        ])
            ->orderBy(Model::FIELD_SORT,'desc')->get();

        return $categories;
    }

    /**
     * 构建查询构造器
     *
     * @author yezi
     * @return $this
     */
    public function queryBuilder()
    {
        $this->builder = Model::query();
        return $this;
    }

    /**
     * 排序
     *
     * @author yezi
     * @param $orderBy
     * @param $sortBy
     * @return $this
     */
    public function sort($orderBy,$sortBy)
    {
        $this->builder->orderBy($orderBy,$sortBy);
        return $this;
    }

    /**
     * 返回查询实例
     *
     * @author yezi
     * @return mixed
     */
    public function done()
    {
        return $this->builder;
    }

    /**
     * 根据ID查询类型
     *
     * @author yezi
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getCategoryById($id)
    {
        $result = Model::query()->find($id);
        return $result;
    }

    /**
     * 保存编辑信息
     *
     * @author yezi
     * @param $id
     * @param $name
     * @return bool
     */
    public function edit($id,$name,$sort)
    {
        $category = $this->getCategoryById($id);
        $category->{Model::FIELD_NAME} = $name;
        $category->{Model::FIELD_SORT} = $sort;
        $result = $category->save();
        return $result;
    }

    /**
     * 检测是否已经被使用过了
     *
     * @author yezi
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function checkUse($id)
    {
        /*$result = ActivityCateGoryMapModel::query()->where(ActivityCateGoryMapModel::FIELD_ID_CATEGORY,$id)->first();
        return $result;*/
    }

    public function delete($id)
    {
        $result = Model::query()->where(Model::FIELD_ID,$id)->delete();
        return $result;
    }

}
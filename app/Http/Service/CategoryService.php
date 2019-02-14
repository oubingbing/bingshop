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
    public function store(Model $category)
    {
        $result = Model::create([
            Model::FIELD_NAME=>$category->{Model::FIELD_NAME},
            Model::FIELD_ATTACHMENTS=>$category->{Model::FIELD_ATTACHMENTS}
        ]);
        return $result;
    }

}
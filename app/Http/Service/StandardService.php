<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/14 0014
 * Time: 17:07
 */

namespace App\Http\Service;


use App\Models\StandardModel as Model;

class StandardService
{
    /**
     * 获取所有的规格数据
     *
     * @author yezi
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getStandardInfo()
    {
        $standards = Model::query()->select([
            Model::FIELD_ID,
            Model::FIELD_NAME,
            Model::FIELD_ID_ADMIN
        ])->get();

        return $standards;
    }

}
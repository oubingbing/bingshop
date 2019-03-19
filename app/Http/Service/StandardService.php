<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/14 0014
 * Time: 17:07
 */

namespace App\Http\Service;


use App\Exceptions\WebException;
use App\Models\StandardModel as Model;
use App\Models\StandardValueModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

class StandardService
{
    /**
     * 新增规格数据
     *
     * @author yezi
     * @param $standardItems
     * @param $admin
     * @throws Exception
     * @throws WebException
     */
    public function storeBatchStandard($standardItems,$admin)
    {
        foreach ($standardItems as $standard){
            $result = $this->storeStandard($standard,$admin);
            if(!$result){
                throw new WebException("保存规格出错");
            }
            $storeValues = $this->storeStandardValues($result->id,$standard['values']);
            if(!$storeValues){
                throw new Exception("保存规格值错误");
            }
        }
    }

    /**
     * 新增规格
     *
     * @author yezi
     * @param $standard
     * @param $adminId
     * @return mixed
     */
    public function storeStandard($standard,$adminId)
    {
        $result = Model::create([
            Model::FIELD_NAME=>$standard['name'],
            Model::FIELD_ID_ADMIN=>$adminId
        ]);
        return $result;
    }

    public function findStandardByName($name)
    {
        $standard = Model::query()->find(Model::FIELD_NAME,$name);
        return $standard;
    }

    /**
     * 新增规格值
     *
     * @author yezi
     * @param $standardId
     * @param $standardValues
     * @return mixed
     */
    public function storeStandardValues($standardId,$standardValues)
    {
        $now = Carbon::now()->toDateTimeString();
        $standardValues = collect($standardValues)->map(function($item)use($standardId,$now){
                return [
                    StandardValueModel::FIELD_ID_STANDARD=>$standardId,
                    StandardValueModel::FIELD_VALUE=>$item,
                    StandardValueModel::FIELD_CREATED_AT=>$now,
                    StandardValueModel::FIELD_UPDATED_AT=>$now
                ];
        });
        $insertResult = DB::table(StandardValueModel::TABLE_NAME)->insert($standardValues->toArray());
        return $insertResult;
    }

    /**
     * 获取所有的规格数据
     *
     * @author yezi
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getStandardInfo($names)
    {
        $standards = Model::query()
            ->with([Model::REL_STANDARD_VALUE=>function($query){
                $query->select([
                    StandardValueModel::FIELD_ID,
                    StandardValueModel::FIELD_ID_STANDARD,
                    StandardValueModel::FIELD_VALUE
                ]);
            }])
            ->select([
            Model::FIELD_ID,
            Model::FIELD_NAME,
            Model::FIELD_ID_ADMIN
        ]);

        if($names){
            $standards->whereIn(Model::FIELD_NAME,collect($names)->toArray());
        }

        return $standards->get();
    }

    /**
     * 检测规格数据的新增
     *
     * @author yezi
     * @param $standardItems
     */
    public function checkStoreStandardItems($standardItems,$adminId)
    {
        $names = collect($standardItems)->pluck('name');
        $standards = Model::query()->whereIn(Model::FIELD_NAME,[collect($names)->toArray()])->pluck(Model::FIELD_NAME);
        if(count($standards) > 0){
            //判断哪些规格是新的，需要新增到数据库
            $standards = collect($standards)->toArray();
            $newStandards = [];
            collect($standardItems)->map(function ($item)use($standards){
                if(in_array($standards,$item['name'])){
                    array_push($standards,$item);
                }
                return $item;
            });
            if($newStandards){
                //新增规格
                $this->storeBatchStandard($newStandards,$adminId);
            }
        }else{
            //提交上来的都是新的规格，需要新增到数据库
            $this->storeBatchStandard($standardItems,$adminId);
        }
    }

    /**
     * 查询规格值数据
     *
     * @author yezi
     * @param $standardValue
     * @param $standardName
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function findStandardValueByValue($standardValue,$standardName)
    {
        $standardValueBuilder = StandardValueModel::query()->where(StandardValueModel::FIELD_VALUE,$standardValue);
        if($standardName){
            $standardValueBuilder->whereHas(StandardValueModel::REL_STANDARD,function ($query)use($standardName){
                $query->where(Model::FIELD_NAME,$standardName);
            });
        }

        return $standardValueBuilder->first();
    }

}
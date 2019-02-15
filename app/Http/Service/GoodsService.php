<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/29 0029
 * Time: 17:36
 */

namespace App\Http\Service;


use App\Models\ActivityBankMapModel;
use App\Models\ActivityCateGoryMapModel;
use App\Models\ActivityCategoryModel;
use App\Models\ActivityModel as Model;
use App\Models\BankModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GoodsService
{
    private $builder;

    /**
     * 校验输入信息
     *
     * @author yeiz
     *
     * @param $request
     * @return array
     */
    public function validRegister($request)
    {
        $rules = [
            'title' => 'required',
            'attachments' => 'required',
            'url' => 'required',
            'content' => 'required',
            'bank_id' => 'required',
            //'categories' => 'required',
            'limit_type' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
        ];
        $message = [
            'title.required' => '标题不能为空',
            'attachments.required' => '图片不能为空',
            'url.required' => '链接不能为空',
            'content.required' => '详情不能为空',
            'bank_id.required' => '银行不能为空',
            //'categories.required' => '类型不能为空',
            'limit_type.required' => '数量限制类型不能为空',
            'start_at.required' => '开始日期不能为空',
            'end_at.required' => '截止日期不能为空',
        ];
        $validator = \Validator::make($request->all(),$rules,$message);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return ['status'=>false,'message'=>$errors->first()];
        }else{
            return ['status'=>true,'message'=>'success'];
        }
    }

    /**
     * 保存活动
     *
     * @author yezi
     * @param Model $activityModel
     * @return mixed
     */
    public function store(Model $activityModel)
    {
        $result = Model::create([
            Model::FIELD_ID_ADMIN=>$activityModel->{Model::FIELD_ID_ADMIN},
            Model::FIELD_TITLE=>$activityModel->{Model::FIELD_TITLE},
            Model::FIELD_URL=>$activityModel->{Model::FIELD_URL},
            Model::FIELD_ATTACHMENTS=>$activityModel->{Model::FIELD_ATTACHMENTS},
            Model::FIELD_NUMBER=>$activityModel->{Model::FIELD_NUMBER},
            Model::FIELD_LIMIT_TYPE=>$activityModel->{Model::FIELD_LIMIT_TYPE},
            Model::FIELD_START_AT=>Carbon::parse($activityModel->{Model::FIELD_START_AT})->toDateTimeString(),
            Model::FIELD_END_AT=>Carbon::parse($activityModel->{Model::FIELD_END_AT})->toDateTimeString(),
            Model::FIELD_CONTENT=>$activityModel->{Model::FIELD_CONTENT},
            Model::FIELD_TYPE=>$activityModel->{Model::FIELD_TYPE},
            Model::FIELD_STATUS=>$activityModel->{Model::FIELD_STATUS},
        ]);

        return $result;
    }

    public function createManyActivityCategoryMap($activityId,$categories)
    {
        $insertArray = [];
        foreach ($categories as $item){
            array_push($insertArray,[
                ActivityCateGoryMapModel::FIELD_ID_ACTIVITY=>$activityId,
                ActivityCateGoryMapModel::FIELD_ID_CATEGORY=>$item,
                ActivityCateGoryMapModel::FIELD_CREATED_AT=>Carbon::now(),
                ActivityCateGoryMapModel::FIELD_UPDATED_AT=>Carbon::now(),
            ]);
        }

        if($insertArray){
            $result = DB::table(ActivityCateGoryMapModel::TABLE_NAME)->insert($insertArray);
            return $result;
        }else{
            return false;
        }
    }

    public function destroyActivityCategoryMap($activityId)
    {
        $result = ActivityCateGoryMapModel::query()->where(ActivityCateGoryMapModel::FIELD_ID_ACTIVITY,$activityId)->forceDelete();
        return $result;
    }

    public function mapActivityBank($activityId,$banks)
    {
        $insertArray = [];
        foreach ($banks as $item){
            array_push($insertArray,[
                ActivityBankMapModel::FIELD_ID_ACTIVITY=>$activityId,
                ActivityBankMapModel::FIELD_ID_BANK=>$item,
                ActivityBankMapModel::FIELD_CREATED_AT=>Carbon::now(),
                ActivityBankMapModel::FIELD_UPDATED_AT=>Carbon::now(),
            ]);
        }

        if($insertArray){
            $result = DB::table(ActivityBankMapModel::TABLE_NAME)->insert($insertArray);
            return $result;
        }else{
            return false;
        }
    }

    public function destroyActivityBankMap($activityId)
    {
        $result = ActivityBankMapModel::query()->where(ActivityBankMapModel::FIELD_ID_ACTIVITY,$activityId)->forceDelete();
        return $result;
    }

    public function createBuilder()
    {
        $this->builder = Model::query()
            ->where(Model::FIELD_END_AT,'>=',Carbon::now()->startOfDay());
        return $this;
    }

    public function filter($filterBanks,$filterCategories,$userId=null,$getMyArticle=null)
    {
        if($filterBanks){
            $this->builder->whereHas(Model::REL_BANKS,function ($query)use($filterBanks){
                $query->whereIn(ActivityBankMapModel::FIELD_ID_BANK,$filterBanks);
            });
        }

        if($filterCategories){
            $this->builder->whereHas(Model::REL_CATEGORIES,function ($query)use($filterCategories){
                $query->whereIn(ActivityCateGoryMapModel::FIELD_ID_CATEGORY,$filterCategories);
            });
        }

        if($getMyArticle && $getMyArticle == 1){
            $this->builder->where(Model::FIELD_ID_ADMIN,$userId);
        }

        return $this;
    }

    public function filterType($type,$filter=null)
    {
        $this->builder->where(Model::FIELD_TYPE,$type);
        if($filter){
            $this->builder->where(Model::FIELD_TITLE,'like',"%$filter%");
        }
        return $this;
    }

    public function getToday()
    {
        $this->builder->where(Model::FIELD_END_AT,'>=',Carbon::now()->startOfDay())
            ->where(Model::FIELD_START_AT,'<=',Carbon::now()->endOfDay());
        return $this;
    }

    public function getFuture()
    {
        $this->builder->where(Model::FIELD_START_AT,'>',Carbon::now()->endOfDay());
        return $this;
    }

    public function orderBy($orderBy,$sort)
    {
        $this->builder->orderBy($orderBy,$sort);
        return $this;
    }

    public function getTodayActivity()
    {
        
    }

    public function done()
    {
        return $this->builder;
    }

    public function formatSingle($activity)
    {
        $time = Carbon::parse($activity->{Model::FIELD_START_AT})->toTimeString();
        if($time){
            $time = substr($time,0,5);
        }

        $starCarbon = Carbon::parse($activity->{Model::FIELD_START_AT});

        $activity->time = $time;
        $activity->start_date = "{$starCarbon->year}.{$starCarbon->month}.$starCarbon->day";
        $activity->end_date = Carbon::parse($activity->{Model::FIELD_END_AT})->toDateString();

        $endCarbon = Carbon::parse($activity->{Model::FIELD_END_AT});

        if($starCarbon->year == $endCarbon->year){
            $activity->end_month = $endCarbon->month.'.'.$endCarbon->day;
        }else{
            $activity->end_month = $endCarbon->year.'.'.$endCarbon->month.'.'.$endCarbon->day;
        }

        return $activity;
    }

    public function getActivityById($id)
    {
        $activity = Model::query()->with([Model::REL_BANKS=>function($query){
            $query->select([
                BankModel::FIELD_NAME,
                BankModel::FIELD_ATTACHMENTS
            ]);
        },Model::REL_CATEGORIES=>function($query){
            $query->select([
                ActivityCategoryModel::FIELD_NAME,
                ActivityCategoryModel::FIELD_TYPE
            ]);
        }])->find($id);
        return $activity;
    }

    public function deleteActivity($id)
    {
        $result = Model::query()->where(Model::FIELD_ID,$id)->delete();
        return $result;
    }
}
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
use App\Http\Service\ActivityService;
use App\Models\ActivityCategoryModel;
use App\Models\ActivityModel;
use App\Models\BankModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    private $activityService;

    function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    public function index()
    {
        return view("admin.activity");
    }

    public function shareIndex()
    {
        return view("admin.shareActivity");
    }

    public function createActivity(Request $request)
    {
        $user = request()->get('user');
        $title = request()->input('title');
        $attachments = request()->input("attachments");
        $url = request()->input('url');
        $content = request()->input('content');
        $bankIds = request()->input("bank_id");
        $categoryArray = request()->input("categories");
        $limitType = request()->input('limit_type');
        $startAt = request()->input('start_at');
        $endAt = request()->input('end_at');
        $number = $request->input("number");

        $valid = $this->activityService->validRegister($request);
        if(!$valid['status']){
            throw new WebException($valid['message']);
        }

        if($limitType){
            if($number<=0){
                throw new WebException("数量限制不能小于0");
            }
        }

        if(Carbon::parse($startAt)->lt(Carbon::parse(Carbon::now()->startOfDay()))){
            throw new WebException("活动开始日期不能小于当前日期");
        }

        if(Carbon::parse($startAt)->gt(Carbon::parse($endAt))){
            throw new WebException("活动开始日期不能大于截止日期");
        }

        if(empty($categoryArray)){
            throw new WebException("分类不能为空");
        }

        if(empty($bankIds)){
            throw new WebException("银行不能为空");
        }

        if($bankIds){
            $bankIds = array_unique($bankIds);
        }

        if($categoryArray){
            $categoryArray = array_unique($categoryArray);
        }

        $activity = new ActivityModel();
        $activity->{ActivityModel::FIELD_TITLE} = $title;
        $activity->{ActivityModel::FIELD_ATTACHMENTS} = $attachments;
        $activity->{ActivityModel::FIELD_CONTENT} = $content;
        $activity->{ActivityModel::FIELD_LIMIT_TYPE} = $limitType?1:2;
        $activity->{ActivityModel::FIELD_NUMBER} = $number;
        $activity->{ActivityModel::FIELD_ID_ADMIN} = $user->id;
        $activity->{ActivityModel::FIELD_URL} = $url;
        $activity->{ActivityModel::FIELD_START_AT}=$startAt;
        $activity->{ActivityModel::FIELD_END_AT}=$endAt;
        $activity->{ActivityModel::FIELD_TYPE} = ActivityModel::ENUM_TYPE_ADMIN;
        $activity->{ActivityModel::FIELD_STATUS} = ActivityModel::ENUM_STATUS_UP;

        try {
            \DB::beginTransaction();

            $result = $this->activityService->store($activity);
            if(!$result){
                throw new WebException("保存失败",5001);
            }

            $createResult = $this->activityService->createManyActivityCategoryMap($result->id,$categoryArray);
            if(!$createResult){
                throw new WebException("保存失败",5002);
            }

            $mapActivityBankResult = $this->activityService->mapActivityBank($result->id,$bankIds);
            if(!$mapActivityBankResult){
                throw new WebException("保存失败",5003);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new WebException($e->getMessage());
        }

        return webResponse($result,"新建成功");
    }

    public function edit($id,Request $request)
    {
        $user = request()->get('user');
        $title = request()->input('title');
        $attachments = request()->input("attachments");
        $url = request()->input('url');
        $content = request()->input('content');
        $bankIds = request()->input("bank_id");
        $categoryArray = request()->input("categories");
        $limitType = request()->input('limit_type');
        $startAt = request()->input('start_at');
        $endAt = request()->input('end_at');
        $number = request()->input("number");

        $valid = $this->activityService->validRegister($request);
        if(!$valid['status']){
            throw new WebException($valid['message']);
        }

        if($limitType){
            if($number<=0){
                throw new WebException("数量限制不能小于0");
            }
        }

        if(Carbon::parse($startAt)->gt(Carbon::parse($endAt))){
            throw new WebException("活动开始日期不能大于截止日期");
        }

        $activity = $this->activityService->getActivityById($id);
        if(!$activity){
            throw new WebException("活动不存在");
        }

        if($bankIds){
            $bankIds = array_unique($bankIds);
        }

        if($categoryArray){
            $categoryArray = array_unique($categoryArray);
        }

        $activity->{ActivityModel::FIELD_TITLE} = $title;
        $activity->{ActivityModel::FIELD_ATTACHMENTS} = $attachments;
        $activity->{ActivityModel::FIELD_CONTENT} = $content;
        $activity->{ActivityModel::FIELD_LIMIT_TYPE} = $limitType?1:2;
        $activity->{ActivityModel::FIELD_NUMBER} = $number;
        $activity->{ActivityModel::FIELD_ID_ADMIN} = $user->id;
        $activity->{ActivityModel::FIELD_URL} = $url;
        $activity->{ActivityModel::FIELD_START_AT}=$startAt;
        $activity->{ActivityModel::FIELD_END_AT}=$endAt;
        $activity->{ActivityModel::FIELD_TYPE} = ActivityModel::ENUM_TYPE_ADMIN;
        $activity->{ActivityModel::FIELD_STATUS} = ActivityModel::ENUM_STATUS_UP;

        try {
            \DB::beginTransaction();

            $result = $activity->save();
            if(!$result){
                throw new WebException("编辑失败");
            }

            $this->activityService->destroyActivityCategoryMap($id);
            $this->activityService->destroyActivityBankMap($id);

            $createResult = $this->activityService->createManyActivityCategoryMap($id,$categoryArray);
            if(!$createResult){
                throw new WebException("保存失败");
            }

            $mapActivityBankResult = $this->activityService->mapActivityBank($id,$bankIds);
            if(!$mapActivityBankResult){
                throw new WebException("保存失败");
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new WebException($e->getMessage());
        }

        return webResponse($result,"编辑成功");
    }

    public function activityList()
    {
        $user = request()->get('user');
        $orderBy = request()->input('order_by', 'start_at');
        $sortBy = request()->input('sort_by', 'asc');
        $pageSize = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $type = request()->input("type");
        $filter = request()->input("filter");

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $builder = $this->activityService
            ->createBuilder()
            //->getFuture()
            ->filterType($type,$filter)
            ->orderBy($orderBy,$sortBy)
            ->done()
            ->with([ActivityModel::REL_BANKS=>function($query){
                $query->select([
                    BankModel::FIELD_NAME,
                    BankModel::FIELD_ATTACHMENTS
                ]);
            },ActivityModel::REL_CATEGORIES=>function($query){
                $query->select([
                    ActivityCategoryModel::FIELD_NAME,
                    ActivityCategoryModel::FIELD_TYPE
                ]);
            }]);

        $selectData = [
            ActivityModel::FIELD_ID,
            ActivityModel::FIELD_TITLE,
            ActivityModel::FIELD_LIMIT_TYPE,
            ActivityModel::FIELD_NUMBER,
            ActivityModel::FIELD_START_AT,
            ActivityModel::FIELD_END_AT,
            ActivityModel::FIELD_CONTENT,
            ActivityModel::FIELD_CREATED_AT,
            ActivityModel::FIELD_ATTACHMENTS,
            ActivityModel::FIELD_URL
        ];

        $result = paginate($builder,$pageParams,$selectData,function ($item){
            return $this->activityService->formatSingle($item);
        });

        return webResponse($result,"获取成功");
    }

    public function delete($id)
    {
        $user = request()->input('user');

        try {
            \DB::beginTransaction();

            $result = $this->activityService->deleteActivity($id);
            if(!$result){
                throw new WebException("删除失败");
            }

            $this->activityService->destroyActivityCategoryMap($id);
            $this->activityService->destroyActivityBankMap($id);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new WebException($e->getMessage());
        }

        return webResponse($result,"删除成功");
    }

}
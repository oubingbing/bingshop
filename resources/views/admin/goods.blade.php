@extends('layouts/admin')
<style>
    [v-cloak] {
        display: none;
    }

    .standard-container{
        width: 70%;
        display: flex;
        flex-direction: column;
        border-style:solid;
        border-width: 1px;
        border-color: #EBEBEB;
        padding: 20px 20px;
        margin-bottom: 10px;
    }

    .standard-container-table{
        width: 70%;
        display: flex;
        flex-direction: column;
        border-style:solid;
        border-width: 1px;
        border-color: #EBEBEB;
        padding: 20px 20px;
        margin-bottom: 10px;
    }

    .standard-item{
        width: 100%;
        display: flex;
        flex-direction: row;
        padding-bottom:10px;
    }

    .standard-item input{
        width: 30%;
    }

    .standard-item img{
        width: 20px;
        height:20px;
        margin-left: 20px;
    }

    .standard-item .add-world{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 20%;
        color: #4169E1;
    }

    .standard-title{
        width: 10%;
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    .add-standard-button{
        background: white;
        margin-top: 10px;
        margin-bottom: 10px;
        color: #4169E1;
    }

    .standard{
        width: 100%;
        display: flex;
        flex-direction: column;
    }

    .standard-tips{
        color: darkgray;
        margin-top: 10px;
    }

    .standard-detail{

    }

    .standard-input{
        width: 50px;
        padding: 5px 5px;
    }

</style>
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="{{asset('css/shop.css')}}">
    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">银行</a>
        <a>
          <cite>首页</cite></a>
      </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
            <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body" id="app" v-cloak>

        <div class="layui-row">
            <div class="layui-form layui-col-md12 x-so">
                <input type="text" v-model="filter" name="username"  placeholder="请输入标题" autocomplete="off" class="layui-input">
                <button class="layui-btn"  lay-submit="" lay-filter="sreach" v-on:click="search"><i class="layui-icon">&#xe615;</i></button>
            </div>
        </div>

        <xblock>
            <button class="layui-btn" v-on:click="showBankView"><i class="layui-icon"></i>添加</button>
            <span class="x-right" style="line-height:40px">共有数据：@{{total}} 条</span>
        </xblock>

        <!-- 添加银行的页面 -->
        <div class="add_activity" style="margin-top: -50px" v-show="showBankForm">
            <form class="activity-form" style="width: 70%;margin-top: -100px;overflow: scroll;height: 600px">
                <div class="close-view">
                    <img class="close-button" v-on:click="closeBankForm" src="{{asset('images/close.png')}}" alt="">
                </div>
                <div class="activity-form-left">
                    <div class="layui-form-item">
                        <label for="username" class="layui-form-label">
                            <span class="x-red">*</span>商品名
                        </label>
                        <div class="layui-input-inline">
                            <input type="text" v-model="title" required="" lay-verify="required"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="phone" class="layui-form-label">
                            <span class="x-red">*</span>商品买点
                        </label>
                        <div class="layui-input-inline">
                            <textarea placeholder="请输入内容" v-model="content" class="layui-textarea"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="L_email" class="layui-form-label">
                            <span class="x-red">*</span>分享描述
                        </label>
                        <div class="layui-input-inline">
                            <textarea placeholder="请输入内容" v-model="content" class="layui-textarea"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="username" class="layui-form-label">
                            <span class="x-red">*</span>商品图片
                        </label>
                        <div class="layui-input-inline">
                            <img class="bank-image" v-bind:src="attachments[0]" v-if="attachments.length>=1" alt="">
                            <img class="upload-buttong" src="{{asset('images/upload_img.png')}}" onclick="javascript:$('#cover-picture').click()" alt="">
                        </div>
                        <input type="file" id="cover-picture" style="display: none" class="layui-input" @change="selectBankImage($event)"/>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>商品类目</label>
                        <div class="layui-input-block">
                            <el-checkbox-group v-model="checkedCategory" @change="handleCheckedCategoryChange">
                            <el-checkbox v-for="category in categories" :label="category.id" :key="category.id">@{{category.name}}</el-checkbox>
                            </el-checkbox-group>
                        </div>
                    </div>

                    <div class="" style="width: 100%;">
                        <label for="L_pass" class="layui-form-label">
                            <span class="x-red">*</span>商品规格
                        </label>
                        <div class="layui-input-inline standard-container">
                            <div class="standard">
                                <div class="standard-item">
                                    <div class="standard-title">规格名：</div>
                                    <input type="text" v-model="title" required="" lay-verify="required"
                                           autocomplete="off" class="layui-input">
                                </div>
                                <div class="standard-item">
                                    <div class="standard-title">规格值：</div>
                                    <input type="text" v-model="title" required="" lay-verify="required"
                                           autocomplete="off" class="layui-input">
                                    <div class="add-world">添加规格值</div>
                                </div>
                            </div>

                            <div class="add-standard-button">添加规格项</div>
                            <small class="standard-tips">如有颜色、尺码等多种规格，请添加商品规格</small>

                        </div>
                    </div>

                    <div class="" style="width: 100%;">
                        <label for="L_pass" class="layui-form-label">
                            <span class="x-red">*</span>商品明细
                        </label>
                        <div class="layui-input-inline standard-container-table">
                            <table class="layui-table standard-detail">
                                <thead>
                                <tr>
                                    <th><small>颜色</small></th>
                                    <th><small>尺寸</small></th>
                                    <th><small>售格</small></th>
                                    <th><small>成本格</small></th>
                                    <th><small>库存</small></th>
                                </thead>
                                <tbody>
                                <tr>
                                    <td rowspan="2">1</td>
                                    <td >1寸</td>
                                    <td>
                                        <input type="text"
                                               class="standard-input"
                                               required=""
                                               lay-verify="required"
                                               autocomplete="off" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="standard-input"
                                               required=""
                                               lay-verify="required"
                                               autocomplete="off" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="standard-input"
                                               required=""
                                               lay-verify="required"
                                               autocomplete="off" class="layui-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        2寸
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="standard-input"
                                               required=""
                                               lay-verify="required"
                                               autocomplete="off" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="standard-input"
                                               required=""
                                               lay-verify="required"
                                               autocomplete="off" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="standard-input"
                                               required=""
                                               lay-verify="required"
                                               autocomplete="off" class="layui-input">
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label for="L_pass" class="layui-form-label">
                            <span class="x-red">*</span>数量限制
                        </label>
                        <div class="layui-input-inline">
                            <el-switch
                                    v-model="notLimit"
                                    active-color="#13ce66"
                            @change="switchLimit"
                            inactive-color="#ff4949">
                            </el-switch>

                        </div>
                    </div>
                    <div class="layui-form-item" v-show="showNumberInput">
                        <label for="L_pass" class="layui-form-label">
                            <span class="x-red">*</span>数量
                        </label>
                        <div class="layui-input-inline">
                            <input type="text" v-model="number" required="" lay-verify="pass"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="L_pass" class="layui-form-label">
                            <span class="x-red">*</span>开始日期
                        </label>
                        <div class="layui-input-inline">
                            <el-date-picker
                                    v-model="start_date"
                                    type="datetime"
                                    value-format="yyyy-MM-dd HH:mm:ss"
                                    placeholder="选择日期时间">
                            </el-date-picker>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="L_pass" class="layui-form-label">
                            <span class="x-red">*</span>截止日期
                        </label>
                        <div class="layui-input-inline">
                            <el-date-picker
                                    v-model="end_date"
                                    type="datetime"
                                    value-format="yyyy-MM-dd HH:mm:ss"
                                    placeholder="选择日期时间">
                            </el-date-picker>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="L_repass" class="layui-form-label">
                        </label>
                        <div  class="layui-btn" lay-filter="add" lay-submit="" v-on:click="submitActivityInfo">
                            保存
                        </div>
                    </div>

                </div>

                <div class="activity-form-right">

                </div>

            </form>

        </div>

        <table class="layui-table">
            <thead>
            <tr>
                <th>序号</th>
                <th>标题</th>
                <th>内容</th>
                <th>图片</th>
                <th>类型</th>
                <th>银行</th>
                <th>开始日期</th>
                <th>截止日期</th>
                <th>类型</th>
                <th>数量</th>
                <th>创建时间</th>
                <th>操作</th>
            </thead>
            <tbody>
            <tr v-for="activity in activities">
                <td>@{{ activity.id }}</td>
                <td>@{{ activity.title }}</td>
                <td>@{{ activity.content }}</td>
                <td><img class="bank-image" style="width: 50px;height: 50px;" v-bind:src="activity.attachments[0]" alt=""></td>
                <td>
                    <span v-for="category in activity.categories">【@{{ category.name }}】</span>
                </td>
                <td>
                    <span v-for="bank in activity.banks">【@{{ bank.name }}】</span>
                </td>
                <td>@{{ activity.start_at }}</td>
                <td>@{{ activity.end_at }}</td>
                <td>@{{ activity.limit_type==1?'限制':'无限' }}</td>
                <td>@{{ activity.number }}</td>
                <td>@{{ activity.created_at }}</td>
                <td>
                    <a title="编辑"  v-on:click="edit(activity.id)" href="javascript:;">
                        <i class="layui-icon">&#xe642;</i>
                    </a>
                    <a title="删除" v-on:click="deleteActivity(activity.id)" href="javascript:;">
                        <i class="layui-icon">&#xe640;</i>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="page">
            <el-pagination
                    background
                    @current-change="handleCurrentChange"
                    layout="prev, pager, next"
                    :page-size="page_size"
                    :current-page="current_page"
                    :total="total">
            </el-pagination>
        </div>
    </div>
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <script type="text/javascript" src="https://unpkg.com/qiniu-js@2.0/dist/qiniu.min.js"></script>
    <script type="text/javascript" src="{{asset('js/upload.js')}}"></script>
    <script>
        "use strict";
        const start = new Date();
        let token = '';
        const IMAGE_URL = "{{env('QI_NIU_DOMAIN')}}";
        const ZONE = "{{env('QI_NIU_ZONE')}}";
        new Vue({
            el: '#app',
            data: {
                banks:[],
                activities:[],
                categories:[],
                form:[],
                checkedCategory: [],
                checkedBank: [],
                total:0,
                page_size:20,
                current_page:1,
                categoryName:'',
                showBankForm:false,
                showNumberInput:false,
                colleges:[],
                attachments:[],
                notLimit:1,
                date:'',
                imageUrl:IMAGE_URL,
                title:'',
                url:'',
                content:'',
                bankId:'',
                number:0,
                start_date:'',
                end_date:'',
                startLimit:start.getTime(),
                operateType:'create',
                activityId:'',
                filter:''
            },
            created:function () {
                this.getBanks();
                this.getCategories();
                this.getQiNiuToken();
                this.getActivityList();
            },
            methods:{
                search:function () {
                    this.current_page = 1;
                    this.activities = [];
                    this.getActivityList();
                },
                edit:function (id) {
                    this.activityId = id;
                    this.operateType = 'edit';
                    let activity = '';
                    this.activities.map(item=>{
                        if(item.id == id){
                            activity = item;
                        }
                    });

                    let theBankIds = [];
                    activity.banks.map(item=>{
                        theBankIds.push(item.pivot.bank_id);
                    });

                    let theCategoryIds = [];
                    activity.categories.map(item=>{
                        theCategoryIds.push(item.pivot.activity_category_id);
                    });

                    this.title = activity.title;
                    this.attachments = activity.attachments;
                    this.url = activity.url;
                    this.content = activity.content;
                    this.checkedBank = theBankIds;
                    this.checkedCategory = theCategoryIds;
                    this.start_date = activity.start_at;
                    this.end_date = activity.end_at;
                    this.notLimit = activity.limit_type==1?true:false;
                    this.number = activity.number;
                    this.showBankForm = true;

                    if(this.notLimit){
                        this.showNumberInput = true;
                    }else{
                        this.showNumberInput = false;
                    }
                },

                deleteActivity:function (id) {
                    console.log(id);
                    this.$confirm('此操作将永久删除该活动, 是否继续?', '警告', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        axios.delete(`/admin/activity/${id}/delete`,{}).then( response=> {
                            let ResData = response.data;
                            if(ResData.code == 500){
                                layer.msg(ResData.message);
                            }else{
                                layer.msg(ResData.message);
                                let tempActivities = this.activities;
                                this.activities = tempActivities.filter(item=>{
                                    if(item.id !== id){
                                        return item;
                                    }
                                });
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }).catch(() => {
                        this.$message({
                            type: 'info',
                            message: '已取消删除'
                        });
                    });
                },
                handleCheckedBankChange:function (val) {
                    this.checkedBank = val;
                    console.log(val)
                },
                handleCheckedCategoryChange:function (val) {
                    this.checkedCategory = val;
                },
                switchLimit:function () {
                  console.log(this.notLimit);
                    if(this.notLimit){
                      this.showNumberInput = true;
                    }else{
                        this.showNumberInput = false;
                    }
                },
                getActivityList:function () {
                    var url = "{{ asset("admin/activity_list") }}";
                    axios.get(url+"?page_size="+this.page_size+'&page_number='+this.current_page+'&order_by=created_at&sort_by=desc&type=1&filter='+this.filter)
                        .then( response=> {
                            var res = response.data;
                            if(res.code === 0){
                                let tempActivities = this.activities;
                                 res.data.page_data.map(item=>{
                                     tempActivities.push(item);
                                 });
                                 this.activities = tempActivities;
                                this.total = res.data.page.total_items;
                            }else{
                                console.log('error:'+res);
                            }
                        }).catch(function (error) {
                        console.log(error);
                    });
                },
                getBanks:function () {
                    axios.get(`/admin/banks`,{}).then( response=> {
                        let resData = response.data;
                        if(resData.code == 0){
                            this.banks = resData.data;
                        }

                    }).catch(function (error) {
                        console.log(error);
                    });
                },
                getCategories:function () {
                    axios.get(`/admin/categories`,{}).then( response=> {
                        let resData = response.data;
                        if(resData.code == 0){
                            this.categories = resData.data;
                        }

                    }).catch(function (error) {
                        console.log(error);
                    });
                },
                showBankView:function () {
                    this.showBankForm = true;
                    this.operateType = 'create';
                },
                closeBankForm:function () {
                    this.showBankForm = false;
                    this.title = '';
                    this.attachments = [];
                    this.url = '';
                    this.content = '';
                    this.checkedBank = [];
                    this.checkedCategory = [];
                    this.number = 0;
                    this.notLimit = 1;
                    this.start_date = '';
                    this.end_date = '';
                },
                submitActivityInfo:function () {
                    let title = this.title;
                    let attachments = this.attachments;
                    let url = this.url;
                    let content = this.content;
                    let bank = this.checkedBank;
                    let categoryArray = this.checkedCategory;
                    let startDate = this.start_date;
                    let endDate = this.end_date;
                    let limitNumber = this.notLimit;
                    let number = this.number;

                    if(!title){
                        layer.msg("标题不能为空");
                        return false;
                    }

                    if(attachments.length<=0){
                        layer.msg("图片不能为空");
                        return false;
                    }

                    if(!url){
                        layer.msg("链接不能为空");
                        return false;
                    }

                    if(!content){
                        layer.msg("详情不能为空");
                        return false;
                    }

                    if(!bank){
                        layer.msg("银行不能为空");
                        return false;
                    }

                    if(!startDate){
                        layer.msg("开始日期不能为空");
                        return false;
                    }

                    if(!endDate){
                        layer.msg("截止日期不能为空");
                        return false;
                    }

                    if(this.operateType == 'create'){
                        axios.post(`/admin/activity/create`,{
                            title:title,
                            attachments:attachments,
                            url:url,
                            content:content,
                            bank_id:bank,
                            categories:categoryArray,
                            number:number,
                            limit_type:limitNumber,
                            start_at:startDate,
                            end_at:endDate
                        }).then( response=> {
                            let ResData = response.data;
                            if(ResData.code == 500){
                                layer.msg(ResData.message);
                            }else{
                                this.getActivityList();
                                layer.msg(ResData.message);
                                this.categoryName = '';
                                this.showBankForm = false;
                                this.title = '';
                                this.attachments = [];
                                this.url = '';
                                this.content = '';
                                this.checkedBank = [];
                                this.checkedCategory = [];
                                this.number = 0;
                                this.notLimit = 1;
                                this.start_date = '';
                                this.end_date = '';
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }else{
                        axios.post(`/admin/activity/${this.activityId}/edit`,{
                            title:title,
                            attachments:attachments,
                            url:url,
                            content:content,
                            bank_id:bank,
                            categories:categoryArray,
                            number:number,
                            limit_type:limitNumber,
                            start_at:startDate,
                            end_at:endDate
                        }).then( response=> {
                            let ResData = response.data;
                            if(ResData.code == 500){
                                layer.msg(ResData.message);
                            }else{
                                this.getActivityList();
                                layer.msg(ResData.message);
                                this.categoryName = '';
                                this.showBankForm = false;
                                this.title = '';
                                this.attachments = [];
                                this.url = '';
                                this.content = '';
                                this.checkedBank = [];
                                this.checkedCategory = [];
                                this.number = 0;
                                this.notLimit = 1;
                                this.start_date = '';
                                this.end_date = '';
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                },
                /**
                 * 获取七牛token
                 **/
                getQiNiuToken:function () {
                    axios.get(`/admin/qiniu/token`,{}).then( response=> {
                        let resData = response.data;
                        if(resData.code == 0){
                            token = resData.data;
                        }

                    }).catch(function (error) {
                        console.log(error);
                    });
                },
                /**
                 * 选择图片并且上传到七牛
                 *
                 * @param event
                 */
                selectBankImage:function (event) {
                    let file = event.target.files[0];

                    uploadPicture(file,res=> {
                        this.attachments = [this.imageUrl+res.key]
                        console.log(this.attachments)
                    },function (res) {
                        //var total = res.total;
                        console.log(res)
                    },function (res) {
                        layer.msg("添加图片失败");
                    },ZONE);

                },
                /**
                 * 监听分页
                 */
                handleCurrentChange:function (e) {
                    console.log(e);
                    this.current_page = e;
                    this.getPosts();
                }
            }
        })
    </script>

@endsection
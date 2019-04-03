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
        flex-wrap:wrap;
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

    .sale-time-model{
        width: 100%;
        height: 100px;
        display: flex;
        flex-direction: column;
    }

    .sale-time-item{
        width: 100%;
        display: flex;
        flex-direction: row;
    }

    .standard-list{
        width: 100%;
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
        background: #F2F2F2;
        padding: 10px 10px;
    }

    .standard-close{
        position: absolute;
        z-index: 10;
        right: 0px;
        margin-right: 10px;
    }

    .standard-close img{
        width: 30px;
        height: 30px;
    }

    .standard-value-close{
        position: absolute;
        z-index: 100;
        top:0px;
        right: 0px;
        width: 30px;
        height: 30px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }

    .standard-item-input{
        width: 100px;
        margin-right: 10px;
        margin-bottom: 10px;
        position: relative;
    }

    .goods-form-label{
        width: 100px;
    }

    .goods-image{
        padding: 5px 5px;
        width: 100px;
        height:100px;
    }

    .fake-image{
        width: 100px;
        height:100px;
        position: absolute;
        z-index: 100;
        background: black;
        background:rgba(2,2,2,0.6);
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }

    .goods-image-container{
        display: flex;
        flex-direction: row;
        align-items: center;
        width: 900px;
    }

    .goods-image-item{
        padding: 5px 5px;
        width: 100px;
        height:100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
    }

    .goods-image-icon{
        padding: 5px 5px;
        width: 100px;
        height:100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-style:solid;
        border-width:1px;
        border-color: #EEEEEE;
        margin-left: 10px;
    }

    .delete-image{
        width: 30px;
        height:30px;
    }

    .goods-close-button{
        width: 30px;
        height:30px;
        padding: 5px 5px;
    }

</style>
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="{{asset('css/shop.css')}}">
    <link rel="stylesheet" href="{{asset('css/goods.css')}}">
    <!-- 引入样式 -->
    <link rel="stylesheet" href="https://unpkg.com/vue-easytable/umd/css/index.css">
    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a >首页</a>
        <a >商品</a>
        <a>
          <cite>商品列表</cite></a>
      </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
            <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body" id="app" v-cloak>

        <div class="layui-row">
            <div class="layui-form layui-col-md12 x-so">
                <input type="text" v-model="filter" name="username"  placeholder="请输入商品名" autocomplete="off" class="layui-input">
                <button class="layui-btn"  lay-submit="" lay-filter="sreach" v-on:click="search"><i class="layui-icon">&#xe615;</i></button>
            </div>
        </div>

        <xblock>
            <button class="layui-btn" v-on:click="showBankView"><i class="layui-icon"></i>新建</button>
            <span class="x-right" style="line-height:40px">共有数据：@{{total}} 条</span>
        </xblock>

        <!-- 新建商品的页面 -->
        <div class="add_activity" style="margin-top: -50px;" v-show="showBankForm">
            <div class="activity-form" id="createGoods" style="width: 80%;margin-top: -100px;overflow: scroll;margin-bottom: 100px">
                <div class="close-view">
                    <img class="goods-close-button" v-on:click="closeBankForm" src="{{asset('images/close.png')}}" alt="">
                </div>
                <div class="activity-form-left">
                    <div class="layui-form-item">
                        <label for="username" class="layui-form-label goods-form-label" style="width: 100px">
                            <span class="x-red">*</span>商品名
                        </label>
                        <div class="layui-input-inline">
                            <input type="text" v-model="goodsName" required="" lay-verify="required"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="phone" class="layui-form-label goods-form-label" style="width: 100px">
                            商品卖点
                        </label>
                        <div class="layui-input-inline">
                            <textarea placeholder="请输入内容" v-model="goodsDescribe" class="layui-textarea"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="L_email" class="layui-form-label goods-form-label" style="width: 100px">
                            分享描述
                        </label>
                        <div class="layui-input-inline">
                            <textarea placeholder="请输入内容" v-model="goodsShareDescribe" class="layui-textarea"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="username" class="layui-form-label goods-form-label" style="width: 100px">
                            <span class="x-red">*</span>商品图片
                        </label>
                        <div class="goods-image-container">
                            <div class="goods-image-item" v-for="attachment in attachments" @mouseenter="enterImage(attachment.name)" @mouseleave="leaveImage(attachment.name)">
                                <img class="goods-image" v-bind:src="attachment.url" alt="">
                               <div class="fake-image" v-if="attachment.show==true" v-on:click="removeImage(attachment.name)">
                                   <img class="delete-image" src="{{ asset('images/remove-img.png') }}">
                               </div>
                            </div>
                            <div class="goods-image-icon" onclick="javascript:$('#cover-picture').click()" v-if="showAddImageIcon==true">
                                <img class="bank-image" src="{{asset('images/upload_img.png')}}" alt="">
                            </div>
                        </div>
                        <input type="file" id="cover-picture" style="display: none" class="layui-input" @change="selectBankImage($event)"/>
                    </div>
                    <div class="">
                        <label class="layui-form-label goods-form-label" style="width: 100px"><span class="x-red">*</span>商品类目</label>
                        <div class="layui-input-block">
                            <el-checkbox-group v-model="checkedCategory" @change="handleCheckedCategoryChange">
                            <el-checkbox v-for="category in categories" :label="category.id" :key="category.id">@{{category.name}}</el-checkbox>
                            </el-checkbox-group>
                        </div>
                    </div>

                    <div class="" style="width: 100%;">
                        <label for="L_pass" class="layui-form-label goods-form-label" style="width: 100px">商品规格</label>
                        <div class="layui-input-inline standard-container">
                            <div class="standard" v-if="showStandardAddButton">
                                <div class="standard-list" v-for="(standardItem,index) in standardItems">
                                    <div class="standard-item">
                                        <div class="standard-title">规格名：</div>
                                        <input type="text"
                                               v-model="standardItem.name"
                                               required=""
                                               lay-verify="required"
                                               v-on:input="watchInputName(standardItem.name,index)"
                                               autocomplete="off"
                                               placeholder="规格名"
                                               style="width: 100px"
                                               class="layui-input">
                                    </div>
                                    <div class="standard-item">
                                        <div class="standard-title">规格值：</div>
                                        <div class="standard-item-input"
                                             v-for="(value,i) in standardItem.values">
                                            <input type="text"
                                                   v-model="value"
                                                   style="width: 100%;"
                                                   required=""
                                                   lay-verify="required"
                                                   placeholder="规格值"
                                                   v-on:input="watchInputValue(value,index,i)"
                                                   autocomplete="off"
                                                   class="layui-input">
                                            <div class="standard-value-close">
                                                <img src="{{asset('/images/cancel.png')}}" alt="" v-on:click="closeStandardValue(index,i)">
                                            </div>
                                        </div>
                                        <div class="add-world" style="cursor:pointer" v-on:click="addStandardValue(index)">添加规格值</div>
                                    </div>
                                    <div class="standard-close" v-on:click="closeStandard(index)">
                                        <img src="{{asset('/images/cancel.png')}}" alt="">
                                    </div>
                                </div>
                                <div class="add-standard-button" style="cursor:pointer" v-on:click="addStandardItem()">添加规格项</div>
                                <small class="standard-tips">如有颜色、尺码等多种规格，请添加商品规格</small>
                            </div>

                            <div class="add-standard-button"
                                 style="cursor:pointer"
                                 v-on:click="showStandardAdd"
                                 v-if="!showStandardAddButton">
                                添加规格项
                            </div>

                        </div>
                    </div>

                    <div class="" style="width: 100%;" v-if="showStandard">
                        <label for="L_pass" class="layui-form-label goods-form-label" style="width: 100px">
                            <span class="x-red">*</span>商品明细
                        </label>
                        <div class="layui-input-inline standard-container-table">
                            <table class="layui-table standard-detail">
                                <thead>
                                <tr>
                                    <th v-for="standardItem in standardItems"><small>@{{ standardItem.name }}</small></th>
                                    <th><small>售格</small></th>
                                    <th><small>成本格</small></th>
                                    <th><small>库存</small></th>
                                </thead>
                                <tbody>
                                <tr v-for="(standard,index) in standardArray">
                                    <td :rowspan="Math.ceil(standardArray.length/standardItems[0].values.length)" v-if="(index)%(standardArray.length/standardItems[0].values.length)==0 || index==0">@{{ standard.levels[0].value }}</td>
                                    <td :rowspan="Math.ceil(standardArray.length/standardItems[1].values.length/standardItems[0].values.length)" v-if="standard.levels[1] && ((index)%(standardArray.length/standardItems[1].values.length/standardItems[0].values.length)==0 || index==0)">@{{ standard.levels[1].value }}</td>
                                    <td rowspan="1" v-if="standard.levels[2]">@{{ standard.levels[2].value }}</td>
                                    <td>
                                        <input type="text"
                                               class="standard-input"
                                               v-model="standard.price"
                                               required=""
                                               lay-verify="required"
                                               autocomplete="off" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="standard-input"
                                               v-model="standard.cost_price"
                                               required=""
                                               lay-verify="required"
                                               autocomplete="off" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="standard-input"
                                               v-model="standard.stock"
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
                        <label for="username" class="layui-form-label goods-form-label" style="width: 100px">
                            <span class="x-red">*</span>价格
                        </label>
                        <div class="layui-input-inline">
                            <input type="text" v-model="goodsPrice" required="" lay-verify="required"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label for="username" class="layui-form-label goods-form-label" style="width: 100px">划线价</label>
                        <div class="layui-input-inline">
                            <input type="text" v-model="chalkLinePrice" required="" lay-verify="required"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label for="username" class="layui-form-label goods-form-label" style="width: 100px">
                            <span class="x-red">*</span>库存
                        </label>
                        <div class="layui-input-inline">
                            <input type="text" v-model="goodsStock" required="" lay-verify="required"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label goods-form-label" style="width: 100px"><span class="x-red">*</span>上架时间</label>
                        <div class="layui-input-block sale-time-model">
                            <el-radio v-model="saleStartModel" @change="saleStartModelChange" label="1">立即上架售卖</el-radio>
                            <div>
                                <el-radio v-model="saleStartModel" @change="saleStartModelChange" label="2">自定义上架时间</el-radio>
                                <el-date-picker
                                        v-model="startSaleTime"
                                        @change="startSaleTimeChange"
                                        type="datetime"
                                        value-format="yyyy-MM-dd HH:mm:ss"
                                        placeholder="选择日期时间">
                                </el-date-picker>
                            </div>
                            <el-radio v-model="saleStartModel" @change="saleStartModelChange" label="3">暂不售卖，放入仓库</el-radio>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label goods-form-label" style="width: 100px"><span class="x-red">*</span>下架时间</label>
                        <div class="layui-input-block sale-time-model">
                            <el-radio v-model="saleStopModel" @change="saleStopModelChange" label="1">售完即可下架</el-radio>
                            <div>
                                <el-radio v-model="saleStopModel" @change="saleStopModelChange" label="2">自定义下架时间</el-radio>
                                <el-date-picker
                                        v-model="stopSaleTime"
                                        type="datetime"
                                        @change="stopSaleTimeChange"
                                        value-format="yyyy-MM-dd HH:mm:ss"
                                        placeholder="选择日期时间">
                                </el-date-picker>
                            </div>
                            <el-radio v-model="saleStopModel" @change="saleStopModelChange" label="3">售完不下架</el-radio>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label goods-form-label" style="width: 100px"><span class="x-red">*</span>限购</label>
                        <div class="layui-input-block sale-time-model">
                            <el-radio v-model="limitSaleModel" @change="limitSaleModelChange" label="1">无限购买</el-radio>
                            <div style="display: flex;flex-direction: row;align-items: center;margin-top: 10px">
                                <el-radio v-model="limitSaleModel" @change="limitSaleModelChange" label="2">限制数量</el-radio>
                                <input type="text"  v-model="limitSaleModelValue" style="width: 100px" v-if="limitSaleModel==2" class="layui-input">
                            </div>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label goods-form-label" style="width: 100px"><span class="x-red">*</span>配送方式</label>
                        <div class="layui-input-block" style="margin-top: 10px">
                            <el-radio v-model="postType" label="1">快递发货</el-radio>
                            <el-radio v-model="postType" label="2">同城配送</el-radio>
                            <el-radio v-model="postType" label="3">到店自取</el-radio>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label goods-form-label" style="width: 100px"><span class="x-red">*</span>快递运费</label>
                        <div class="layui-input-block">
                            <div style="display: flex;flex-direction: row;align-items: center;margin-top: 5px">
                                <el-radio v-model="postCostType" label="1">统一邮费</el-radio>
                                <input type="text" v-model="postCost" style="width: 100px" value="0" class="layui-input">
                            </div>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label for="L_repass" class="layui-form-label goods-form-label" style="width: 100px">
                        </label>
                        <div  class="layui-btn" lay-filter="add" lay-submit="" v-on:click="submitGoodsInfo" style="margin-top: 20px">
                            保存
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="goods-list">
            <div class="list-content">

                <div class="list-item" v-for="goods in goodsList">
                    <div class="item-left">
                        <img v-bind:src="goods.images_attachments[0]" alt="">
                    </div>
                    <div class="item-middle">
                        <div class="middle-goods-info">
                            <h2 class="goodsname">@{{ goods.name }}</h2>
                            <span class="price-info">￥@{{ goods.sku[0].price }}</span>
                            <span>@{{ goods.describe }}</span>
                        </div>
                        <div class="middle-goods-other">
                            <span class="selling">状态：在售</span>
                            <span class="other-color">限购：@{{ goods.limit_purchase_num }}</span>
                            <span class="other-color">邮费：@{{ goods.postage_cost }}</span>
                            <span class="other-color">发布日期：@{{ goods.created_at }}</span>
                        </div>
                        <div class="middle-goods-stock">
                            <span>库存：100</span>
                            <span>总销量：88</span>
                            <span>访客数：88</span>
                            <span>浏览量：88</span>
                        </div>
                    </div>
                    <div class="item-right">
                        <div class="operate-button goods-up">上架</div>
                        <div class="operate-button goods-down">下架</div>
                        <div class="operate-button goods-eidt">编辑</div>
                        <div class="operate-button goods-delete">删除</div>
                    </div>
                </div>

            </div>
        </div>

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
    <script src="https://unpkg.com/vue-easytable/umd/js/index.js"></script>
    <script>
        "use strict";
        const start = new Date();
        const MAX_UPLOAD_IMAGE = 9;
        let token = '';
        const IMAGE_URL = "{{env('QI_NIU_DOMAIN')}}";
        const ZONE = "{{env('QI_NIU_ZONE')}}";

        $("#createGoods").height($(document.body).height()-120);

        new Vue({
            el: '#app',
            data: {
                goodsList:[],

                showStandard:false,
                showStandardAddButton:false,
                showBankForm:false,
                categories:[],
                total:0,
                page_size:10,
                page_number:1,
                current_page:1,
                categoryName:'',
                imageUrl:IMAGE_URL,
                startLimit:start.getTime(),
                filter:'',
                standardItems:[],
                tableStandards:[],
                attachments:[],
                standardArray:[],
                standardTable:[],

                goodsName:'',
                goodsDescribe:'',
                goodsShareDescribe:'',
                checkedCategory: [],
                goodsPrice:'',
                chalkLinePrice:'',
                goodsStock:'',
                saleStartModel:'1',
                startSaleTime:'',
                saleStopModel:'1',
                stopSaleTime:'',
                limitSaleModel:'1',
                limitSaleModelValue:'',
                postType:'1',
                postCostType:'1',
                postCost:0,
                showAddImageIcon:true
            },
            created:function () {
                this.getGoods();
                this.getCategories();
                this.getQiNiuToken();
            },
            methods:{
                /**
                 * 获取商品列表
                 **/
                getGoods:function () {
                    axios.get(`/admin/goods?page_size=${this.page_size}&page_number=${this.page_number}`,{}).then( response=> {
                        let resData = response.data;
                        if(resData.code == 0){
                            console.log(resData.data.page_data);
                            let goodsList = [];
                            resData.data.page_data.map(goods=>{
                                goodsList.push(goods);
                            });
                            this.goodsList = goodsList;
                            this.page_number += 1;
                            this.total = resData.data.page.total_items;
                        }

                    }).catch(function (error) {
                        console.log(error);
                    });
                },

                /**
                 * 进入图片
                 **/
                enterImage:function (value) {
                    this.attachments.map(item=>{
                        if(item.name == value){
                            item.show = true;
                        }
                        return item;
                    });
                },

                /**
                 * 鼠标移除图片
                 **/
                leaveImage:function (value) {
                    this.attachments.map(item=>{
                        item.show = false;
                        return item;
                    });
                },

                /**
                 * 删除图片
                 **/
                removeImage:function (value) {
                    this.attachments = this.attachments.filter(item=>{
                        if(item.name != value){
                            return item;
                        }
                    });
                    if(this.attachments.length < MAX_UPLOAD_IMAGE){
                        this.showAddImageIcon = true;
                    }
                },
                /**
                 * 新建商品
                 **/
                submitGoodsInfo:function () {
                    if(this.checkedCategory.length <= 0){
                        layer.msg("商品分类不能为空");
                        return false;
                    }

                    let saleStartType = parseInt(this.saleStartModel);
                    if(saleStartType == 2 && this.startSaleTime == ''){
                        layer.msg("上架日期不能为空");
                        return false;
                    }

                    let saleStopType = parseInt(this.saleStopModel);
                    if(saleStopType == 2 && this.stopSaleTime == ''){
                        layer.msg("下架日期不能为空");
                        return false;
                    }

                    let limitSaleModel = this.limitSaleModel;
                    let limitSaleModelValue = this.limitSaleModelValue;
                    if(parseInt(limitSaleModel) == 2 && (limitSaleModelValue == '' || limitSaleModelValue == 0)){
                        layer.msg("限购数量不能为空或者为0");
                        return false;
                    }

                    let postCostType = this.postCostType;
                    let postCost = this.postCostType;
                    if(parseInt(postCostType) == 1 && (postCost == '')){
                        layer.msg("邮费不能为空");
                        return false;
                    }

                    if(this.attachments.length <= 0){
                        layer.msg("商品图片不能为空");
                        return false;
                    }

                    let images = this.attachments.map(item=>{
                        return item.url;
                    });

                    axios.post(`/admin/goods/create`,{
                        goods_name:this.goodsName,
                        goods_describe:this.goodsDescribe,
                        goods_share_describe:this.goodsShareDescribe,
                        attachments:images,
                        checked_category:this.checkedCategory,
                        standard_items:this.standardItems,
                        sku_data:this.standardArray,
                        goods_price:this.goodsPrice,
                        chalk_line_price:this.chalkLinePrice,
                        goods_stock:this.goodsStock,
                        sale_start_type:saleStartType,
                        start_sale_time:this.startSaleTime,
                        sale_stop_type:saleStopType,
                        stop_sale_time:this.stopSaleTime,
                        limit_sale_model:this.limitSaleModel,
                        limit_sale_model_value:this.limitSaleModelValue,
                        post_type:this.postType,
                        post_cost_type : this.postCostType,
                        post_cost : this.postCost
                    }).then( response=> {
                        let ResData = response.data;
                        if(ResData.code == 500){
                            layer.msg(ResData.message);
                        }else{
                            //清空表单
                            this.page_number = 1;
                            this.getGoods();
                        }

                    }).catch(function (error) {
                        console.log(error);
                    });
                },

                /**
                 * 监听上架日期的变更
                 * */
                startSaleTimeChange:function (value) {
                    this.saleStartModel = '2';
                },

                /**
                 * 监听上架模式的值变换
                 * */
                saleStartModelChange:function (value) {
                    if(parseInt(value) != 2){
                        this.startSaleTime = '';
                    }
                    this.saleStartModel = value;
                },

                /**
                 * 监听下架日期的变更
                 * */
                stopSaleTimeChange:function (value) {
                    this.saleStopModel = '2';
                },

                /**
                 * 监听下架模式的值变换
                 * */
                saleStopModelChange:function (value) {
                    if(parseInt(value) != 2){
                        this.stopSaleTime = '';
                    }
                    this.saleStopModel = value;
                },

                /**
                 * 监听限制购买数量的勾选类型
                 * */
                limitSaleModelChange:function (value) {
                    if(parseInt(this.limitSaleModel) != 2){
                        this.limitSaleModelValue = '';
                    }
                },

                /**
                 * 监听数据输入
                 **/
                watchInputValue:function(value,index,i){
                    this.showStandard = true;
                    this.standardItems.map((itemValue,itemValueIndex)=>{
                        if(itemValueIndex == index){
                            itemValue.values[i] = value;
                        }
                        return itemValue;
                    });

                    let temp = this.standardArray.map(item=>{
                        if(item.levels[index].index == i){
                            item.levels[index].value = value;
                        }

                        return item;
                    });

                    this.standardArray = temp;
                },

                watchInputName:function (value,index) {
                    this.standardArray.map(item=>{
                        item.levels[index].name = value;
                    });
                },

                resetStandardArray:function () {
                    this.standardArray = [];

                    switch (this.standardItems.length){
                        case 1:
                            this.standardItems[0].values.map((one,one_index)=>{
                                this.standardArray.push({
                                    levels:[{name:this.standardItems[0].name,value:one,index:one_index}],
                                    price:'',
                                    cost_price:'',
                                    stock:''
                                });
                                return one;
                            });
                            break;
                        case 2:
                            this.standardItems[0].values.map((one,one_index)=>{
                                this.standardItems[1].values.map((two,two_index)=>{
                                    this.standardArray.push({
                                        levels:[
                                            {name:this.standardItems[0].name,value:one,index:one_index},
                                            {name:this.standardItems[1].name,value:two,index:two_index}
                                        ],
                                        price:'',
                                        cost_price:'',
                                        stock:''
                                    });
                                    return two;
                                });
                                return one;
                            });
                            break;
                        case 3:
                            this.standardItems[0].values.map((one,one_index)=>{
                                this.standardItems[1].values.map((two,two_index)=>{
                                    this.standardItems[2].values.map((three,three_index)=>{
                                        this.standardArray.push({
                                            levels:[
                                                {name:this.standardItems[0].name,value:one,index:one_index},
                                                {name:this.standardItems[1].name,value:two,index:two_index},
                                                {name:this.standardItems[1].name,value:three,index:three_index}
                                            ],
                                            price:'',
                                            cost_price:'',
                                            stock:''
                                        });
                                    });
                                    return two;
                                });
                                return one;
                            });
                            break;
                    }
                },

                /**
                 * 点击添加规格项
                 **/
                showStandardAdd:function () {
                    this.showStandardAddButton = true;
                    this.standardItems.push({level:1,name:'',values:['']});
                    this.standardArray.push({
                        levels:[],
                        price:'',
                        cost_price:'',
                        stock:''
                    });
                    this.standardArray[0].levels.push({name:this.standardItems[0].name,value:'',index:0});
                },

                /**
                 * 添加规格值
                 **/
                addStandardValue:function (index) {
                    this.standardItems.map((item,i)=>{
                        if(i == index){
                            item.values.push('');
                        }
                        return item;
                    });

                    let theIndex = this.standardItems[index].values.length - 1;

                    switch (index){
                        case 0:
                            switch (this.standardItems.length){
                                case 1:
                                    this.standardArray.push({
                                        levels:[{name:this.standardItems[0].name,value:this.standardItems[0].values[theIndex],index:theIndex}],
                                        price:'',
                                        cost_price:'',
                                        stock:''
                                    });
                                    break;
                                case 2:
                                    this.standardItems[1].values.map((two,two_index)=>{
                                        this.standardArray.push({
                                            levels:[
                                                {name:this.standardItems[0].name,value:this.standardItems[0].values[theIndex],index:theIndex},
                                                {name:this.standardItems[1].name,value:this.standardItems[1].values[two_index],index:two_index}
                                            ],
                                            price:'',
                                            cost_price:'',
                                            stock:''
                                        });
                                        return two;
                                    });
                                    break;
                                case 3:
                                    this.standardItems[1].values.map((two,two_index)=>{
                                        this.standardItems[2].values.map((three,three_index)=>{
                                            this.standardArray.push({
                                                levels:[
                                                    {name:this.standardItems[0].name,value:this.standardItems[0].values[theIndex],index:theIndex},
                                                    {name:this.standardItems[1].name,value:this.standardItems[1].values[two_index],index:two_index},
                                                    {name:this.standardItems[2].name,value:this.standardItems[2].values[three_index],index:three_index}
                                                ],
                                                price:'',
                                                cost_price:'',
                                                stock:''
                                            });
                                        });
                                        return two;
                                    });
                                    break;
                            }
                            break;
                        case 1:
                            if(this.standardItems.length == 3){
                                this.standardItems[0].values.map((one,one_index)=>{
                                    this.standardItems[2].values.map((three,three_index)=>{
                                        this.standardArray.push({
                                            levels:[
                                                {name:this.standardItems[0].name,value:this.standardItems[0].values[one_index],index:one_index},
                                                {name:this.standardItems[1].name,value:this.standardItems[1].values[theIndex],index:theIndex},
                                                {name:this.standardItems[2].name,value:this.standardItems[2].values[three_index],index:three_index}
                                            ],
                                            price:'',
                                            cost_price:'',
                                            stock:''
                                        });
                                        return one;
                                    });
                                });
                            }else{
                                this.standardItems[0].values.map((one,one_index)=>{
                                    this.standardArray.push({
                                        levels:[
                                            {name:this.standardItems[0].name,value:this.standardItems[0].values[one_index],index:one_index},
                                            {name:this.standardItems[1].name,value:this.standardItems[1].values[theIndex],index:theIndex}
                                        ],
                                        price:'',
                                        cost_price:'',
                                        stock:''
                                    });
                                    return one;
                                });
                            }
                            break;
                        case 2:
                            this.standardItems[0].values.map((one,one_index)=>{
                                this.standardItems[1].values.map((two,two_index)=>{
                                    this.standardArray.push({
                                        levels:[
                                            {name:this.standardItems[0].name,value:this.standardItems[0].values[one_index],index:one_index},
                                            {name:this.standardItems[1].name,value:this.standardItems[1].values[two_index],index:two_index},
                                            {name:this.standardItems[2].name,value:this.standardItems[1].values[theIndex],index:theIndex}
                                        ],
                                        price:'',
                                        cost_price:'',
                                        stock:''
                                    });
                                    return two;
                                });
                                return one;
                            });
                            break;
                    }

                    if(this.standardItems.length == 2){
                        this.standardArray = this.sort(this.standardArray,0);
                    }else if(this.standardItems.length == 3){
                        this.standardArray = this.sort(this.standardArray,1);
                        this.standardArray = this.sort(this.standardArray,0);
                    }

                    console.log(this.standardArray);
                },

                /**
                 * 排序
                 */
                sort: function (arr, key) {
                    for (let i = 0; i < arr.length; i++) {
                        for (let j = arr.length - 1; i < j; j--) {
                            if (arr[j].levels[key].index < arr[j - 1].levels[key].index) {
                                let temp = arr[j - 1];
                                arr[j - 1] = arr[j];
                                arr[j] = temp;
                            }
                        }
                    }
                    return arr;
                },

                /**
                 * 继续添加规格项
                 **/
                addStandardItem:function () {

                    if(this.standardItems.length >= 3){
                        layer.msg("只能添加三个规格项");
                        return false;
                    }

                    this.standardItems.push({name:'',values:['']});

                    this.standardItems[0].values.map((one,one_index)=>{
                        if(this.standardItems.length >= 2){
                            this.standardItems[1].values.map((two,two_index)=>{
                                if(this.standardItems.length >= 3){
                                    this.standardItems[2].values.map((three,three_index)=>{
                                        this.standardArray.map(item=>{
                                            item.levels[2] = {name:this.standardItems[2].name,value:three,index:three_index};
                                            return item;
                                        });
                                    });
                                }else{
                                    this.standardArray.map(item=>{
                                        item.levels[1] = {name:this.standardItems[1].name,value:two,index:two_index};
                                        return item;
                                    });
                                }
                            });
                        }else{
                            this.standardArray.map(item=>{
                                item.levels[0] = {name:this.standardItems[1].name,value:one,index:one_index};
                                return item;
                            });
                        }
                    });

                    let length = this.tableStandards.length-1;
                },

                /**
                 * 取消添加当前规格项
                 **/
                closeStandard:function (index) {
                    let standardLength = this.standardItems.length;
                    let tempStandards = this.standardItems.filter((item,i)=>{
                        if(i!=index){
                            return item;
                        }
                    });

                    this.standardItems = tempStandards;
                    if(tempStandards.length == 0){
                        this.showStandardAddButton = false;
                        this.showStandard = false;
                    }

                    this.resetStandardArray();
                },

                /**
                 * 删除规格值
                 **/
                closeStandardValue:function (index,i) {
                    this.standardItems = this.standardItems.filter((item,j)=>{
                        if(index == j){
                            item.values = item.values.filter((subItem,k)=>{
                                if(k != i){
                                    return subItem;
                                }
                            });
                        }
                        if(item.values.length > 0){
                            return item;
                        }
                    });


                    this.standardArray = this.standardArray.filter(item=>{
                        let result = false;
                        item.levels = item.levels.filter((sub,sub_index)=>{
                            if(sub_index == index && sub.index == i){
                                result = true;
                            }else{
                                return sub;
                            }
                        });

                        if(!result){
                            return item;
                        }else{
                            if(this.standardArray.length == 1 && this.standardItems.length > 0){
                                return item;
                            }
                        }
                    });

                    if(this.standardArray.length == 0){
                        this.showStandard = false;
                    }

                    console.log(this.standardArray);
                    console.log(this.standardItems);

                },
                search:function () {
                    this.current_page = 1;
                    this.activities = [];
                },
                edit:function (id) {

                },

                handleCheckedBankChange:function (val) {
                    this.checkedBank = val;
                    console.log(val)
                },
                handleCheckedCategoryChange:function (val) {
                    this.checkedCategory = val;
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
                        this.attachments.push({url:this.imageUrl+res.key,name:res.key,show:false});
                        if(this.attachments.length >= MAX_UPLOAD_IMAGE){
                            this.showAddImageIcon = false;
                        }
                    },function (res) {
                        //var total = res.total;
                    },function (res) {
                        console.log(res);
                        layer.msg("添加图片失败");
                    },ZONE);
                },
                /**
                 * 监听分页
                 */
                handleCurrentChange:function (e) {
                    console.log(e);
                    this.current_page = e;
                    this.getGoods();
                }
            }
        })
    </script>

@endsection
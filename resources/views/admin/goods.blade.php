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

</style>
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="{{asset('css/shop.css')}}">
    <!-- 引入样式 -->
    <link rel="stylesheet" href="https://unpkg.com/vue-easytable/umd/css/index.css">
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
            <form class="activity-form" style="width: 80%;margin-top: -100px;overflow: scroll;height: 900px">
                <div class="close-view">
                    <img class="close-button" v-on:click="closeBankForm" src="{{asset('images/close.png')}}" alt="">
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
                            <span class="x-red">*</span>商品买点
                        </label>
                        <div class="layui-input-inline">
                            <textarea placeholder="请输入内容" v-model="goodsDescribe" class="layui-textarea"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="L_email" class="layui-form-label goods-form-label" style="width: 100px">
                            <span class="x-red">*</span>分享描述
                        </label>
                        <div class="layui-input-inline">
                            <textarea placeholder="请输入内容" v-model="goodsShareDescribe" class="layui-textarea"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="username" class="layui-form-label goods-form-label" style="width: 100px">
                            <span class="x-red">*</span>商品图片
                        </label>
                        <div class="layui-input-inline">
                            <img class="bank-image" v-bind:src="attachments[0]" v-if="attachments.length>=1" alt="">
                            <img class="upload-buttong" src="{{asset('images/upload_img.png')}}" onclick="javascript:$('#cover-picture').click()" alt="">
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
                        <label for="L_pass" class="layui-form-label goods-form-label" style="width: 100px">
                            <span class="x-red">*</span>商品规格
                        </label>
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
                                                   v-on:input="watchInputValue(value,index,i)"
                                                   autocomplete="off"
                                                   class="layui-input">
                                            <div class="standard-value-close" v-on:click="closeStandardValue(index,i)">
                                                <img src="{{asset('/images/cancel.png')}}" alt="">
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
                                    <td :rowspan="Math.ceil(standardArray.length/standardItems[0].values.length)" v-if="(index)%(standardArray.length/standardItems[0].values.length)==0 || index==0">@{{ standard.level_one.value }}</td>
                                    <td :rowspan="Math.ceil(standardArray.length/standardItems[1].values.length/standardItems[0].values.length)" v-if="standard.level_two && ((index)%(standardArray.length/standardItems[1].values.length/standardItems[0].values.length)==0 || index==0)">@{{ standard.level_two.value }}</td>
                                    <td rowspan="1" v-if="standard.level_three">@{{ standard.level_three.value }}</td>
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
                        <label for="username" class="layui-form-label goods-form-label" style="width: 100px">
                            <span class="x-red">*</span>划线价
                        </label>
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
                        <div class="layui-input-block layui-form sale-time-model">
                            <input type="radio" name="saleStartModel" lay-skin="primary" title="立即上架售卖" checked="">
                            <div class="sale-time-item">
                                <input type="radio" name="saleStartModel" lay-skin="primary" title="自定义上架时间">
                                <el-date-picker
                                        v-model="startSaleDate"
                                        type="datetime"
                                        value-format="yyyy-MM-dd HH:mm:ss"
                                        placeholder="选择日期时间">
                                </el-date-picker>
                            </div>
                            <input type="radio" name="saleStartModel" lay-skin="primary" title="暂不售卖，放入仓库" >
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label goods-form-label" style="width: 100px"><span class="x-red">*</span>下架时间</label>
                        <div class="layui-input-block layui-form sale-time-model">
                            <input type="radio" name="stopStartModel" lay-skin="primary" title="售完即可下架" checked="">
                            <div class="sale-time-item">
                                <input type="radio" name="stopStartModel" lay-skin="primary" title="自定义下架时间">
                                <el-date-picker
                                        v-model="stopSaleDate"
                                        type="datetime"
                                        value-format="yyyy-MM-dd HH:mm:ss"
                                        placeholder="选择日期时间">
                                </el-date-picker>
                            </div>
                            <input type="radio" name="stopStartModel" lay-skin="primary" title="售完不下架" >
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label goods-form-label" style="width: 100px"><span class="x-red">*</span>限购</label>
                        <div class="layui-input-block layui-form sale-time-model">
                            <input type="radio" name="limitSaleModel" lay-skin="primary" title="无限购买" checked="">
                            <div class="sale-time-item">
                                <input type="radio" name="limitSaleModel" lay-skin="primary" title="限制数量">
                                <input type="text" required="" style="width: 100px" lay-verify="required"
                                       autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label goods-form-label" style="width: 100px"><span class="x-red">*</span>配送方式</label>
                        <div class="layui-input-block layui-form">
                            <input type="radio" name="postType" lay-skin="primary" title="快递发货" checked="">
                            <input type="radio" name="postType" lay-skin="primary" title="同城配送" checked="">
                            <input type="radio" name="postType" lay-skin="primary" title="到点自取" checked="">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label goods-form-label" style="width: 100px"><span class="x-red">*</span>快递运费</label>
                        <div class="layui-input-block layui-form">
                            <div class="sale-time-item">
                                <input type="radio" name="postCost" lay-skin="primary" title="统一邮费">
                                <input type="text" required="" style="width: 100px" value="0" lay-verify="required"
                                       autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label for="L_repass" class="layui-form-label goods-form-label" style="width: 100px">
                        </label>
                        <div  class="layui-btn" lay-filter="add" lay-submit="" v-on:click="submitActivityInfo" style="margin-top: 20px">
                            保存
                        </div>
                    </div>
                </div>
            </form>

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
        let token = '';
        const IMAGE_URL = "{{env('QI_NIU_DOMAIN')}}";
        const ZONE = "{{env('QI_NIU_ZONE')}}";
        new Vue({
            el: '#app',
            data: {
                categories:[],
                checkedCategory: [],
                total:0,
                page_size:20,
                current_page:1,
                categoryName:'',
                showBankForm:false,
                imageUrl:IMAGE_URL,
                startLimit:start.getTime(),
                filter:'',

                showStandard:false,
                showStandardAddButton:false,
                standardItems:[],
                tableStandards:[],

                goodsName:'',
                goodsDescribe:'',
                goodsShareDescribe:'',
                attachments:[],
                goodsNumber:0,
                goodsPrice:0,
                chalkLinePrice:0,
                goodsStock:0,
                startSaleDate:'',
                saleStartModel:1,
                stopStartModel:1,
                stopSaleDate:'',
                limitSaleModel:1,
                postType:1,
                postCost:0,

                standardArray:[],
                standardTable:[]
            },
            created:function () {
                this.getCategories();
                this.getQiNiuToken();
            },
            methods:{
                watchInputValue:function(value,index,i){
                    this.showStandard = true;

                    this.standardItems.map((itemValue,itemValueIndex)=>{
                        if(itemValueIndex == index){
                            itemValue.values[i] = value;
                        }
                        return itemValue;
                    });

                    this.standardArray.map(item=>{
                        switch (index){
                            case 0:
                                if(item.level_one.index == i){
                                    item.level_one.value = value;
                                }
                                break;
                            case 1:
                                if(item.level_two.index == i){
                                    console.log("第二个");
                                    item.level_two.value = value;
                                }
                                break;
                            case 2:
                                if(item.level_three.index == i){
                                    item.level_three.value = value;
                                }
                                break;
                        }
                        return item;
                    });

                    console.log(this.standardArray);
                },

                watchInputName:function (value,index) {
                    this.standardArray.map(item=>{
                        switch (index){
                            case 0:
                                item.level_one.name = value;
                                break;
                            case 1:
                                item.level_two.name = value;
                                break;
                            case 2:
                                item.level_three.name = value;
                                break;
                        }
                    });
                },

                resetStandardArray:function () {
                    this.standardArray = [];

                    switch (this.standardItems.length){
                        case 1:
                            this.standardItems[0].values.map(one=>{
                                this.standardArray.push({
                                    level_one:{name:this.standardItems[0].name,value:one},
                                    level_two:'',
                                    level_three:'',
                                    price:1,
                                    cost_price:1,
                                    stock:100
                                });
                                return one;
                            });
                            break;
                        case 2:
                            this.standardItems[0].values.map(one=>{
                                this.standardItems[1].values.map(two=>{
                                    this.standardArray.push({
                                        level_one:{name:this.standardItems[0].name,value:one},
                                        level_two:{name:this.standardItems[1].name,value:two},
                                        level_three:'',
                                        price:1,
                                        cost_price:1,
                                        stock:100
                                    });
                                    return two;
                                });
                                return one;
                            });
                            break;
                        case 3:
                            this.standardItems[0].values.map(one=>{
                                this.standardItems[1].values.map(two=>{
                                    this.standardItems[2].values.map(three=>{
                                        console.log("three");
                                        this.standardArray.push({
                                            level_one:{name:this.standardItems[0].name,value:one},
                                            level_two:{name:this.standardItems[1].name,value:two},
                                            level_three:{name:this.standardItems[2].name,value:three},
                                            price:1,
                                            cost_price:1,
                                            stock:100
                                        });
                                    });
                                    return two;
                                });
                                return one;
                            });
                            break;
                    }

                     console.log(this.standardArray);
                },

                /**
                 * 点击添加规格项
                 **/
                showStandardAdd:function () {
                    this.showStandardAddButton = true;
                    this.standardItems.push({level:1,name:'一级规格',values:['']});
                    this.standardArray.push({
                        level_one:{name:this.standardItems[0].name,value:'',index:0},
                        level_two:'',
                        level_three:'',
                        price:1,
                        cost_price:1,
                        stock:100
                    });
                    //this.resetStandardArray();
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
                                        level_one:{name:this.standardItems[0].name,value:'',index:theIndex},
                                        level_two:'',
                                        level_three:'',
                                        price:1,
                                        cost_price:1,
                                        stock:100
                                    });
                                    break;
                                case 2:
                                    this.standardItems[1].values.map((two,two_index)=>{
                                        this.standardArray.push({
                                            level_one:{name:this.standardItems[0].name,value:'',index:theIndex},
                                            level_two:{name:this.standardItems[1].name,value:'',index:two_index},
                                            level_three:'',
                                            price:1,
                                            cost_price:1,
                                            stock:100
                                        });
                                        return two;
                                    });
                                    break;
                                case 3:
                                    this.standardItems[1].values.map((two,two_index)=>{
                                        this.standardItems[2].values.map((three,three_index)=>{
                                            this.standardArray.push({
                                                level_one:{name:this.standardItems[0].name,value:'',index:theIndex},
                                                level_two:{name:this.standardItems[1].name,value:'',index:two_index},
                                                level_three:{name:this.standardItems[2].name,value:'',index:three_index},
                                                price:1,
                                                cost_price:1,
                                                stock:100
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
                                            level_one:{name:this.standardItems[0].name,value:'',index:one_index},
                                            level_two:{name:this.standardItems[1].name,value:'',index:theIndex},
                                            level_three:{name:this.standardItems[2].name,value:'',index:three_index},
                                            price:1,
                                            cost_price:1,
                                            stock:100
                                        });
                                        return one;
                                    });
                                });
                            }else{
                                this.standardItems[0].values.map((one,one_index)=>{
                                    this.standardArray.push({
                                        level_one:{name:this.standardItems[0].name,value:'',index:one_index},
                                        level_two:{name:this.standardItems[1].name,value:'',index:theIndex},
                                        level_three:'',
                                        price:1,
                                        cost_price:1,
                                        stock:100
                                    });
                                    return one;
                                });
                            }
                            break;
                        case 2:
                            this.standardItems[0].values.map((one,one_index)=>{
                                this.standardItems[1].values.map((two,two_index)=>{
                                    this.standardArray.push({
                                        level_one:{name:this.standardItems[0].name,value:'',index:one_index},
                                        level_two:{name:this.standardItems[1].name,value:'',index:two_index},
                                        level_three:{name:this.standardItems[2].name,value:'',index:theIndex},
                                        price:1,
                                        cost_price:1,
                                        stock:100
                                    });
                                    return two;
                                });
                                return one;
                            });
                            break;
                    }

                    console.log("数据排序");

                    this.standardArray = this.sort(this.standardArray,'level_one');

                    if(this.standardItems.length == 2){
                       // this.standardArray = this.sort(this.standardArray,'level_one');
                    }else if(this.standardItems.length == 3){
                      //  this.standardArray = this.sort(this.standardArray,'level_one');
                    }
                    console.log(this.standardArray);

                    //this.resetStandardArray();
                },

                /**
                 * 排序
                 */
                sort: function (arr, key) {
                    for (let i = 0; i < arr.length; i++) {
                        for (let j = arr.length - 1; i < j; j--) {
                            if (arr[j][key].index < arr[j - 1][key].index) {
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
                    this.standardItems.push({name:'次级规格项',values:['']});
                    //this.resetStandardArray();

                    this.standardItems[0].values.map((one,one_index)=>{
                        if(this.standardItems.length >= 2){
                            this.standardItems[1].values.map((two,two_index)=>{
                                if(this.standardItems.length >= 3){
                                    this.standardItems[2].values.map((three,three_index)=>{
                                        this.standardArray.map(item=>{
                                            item.level_three = {name:this.standardItems[2].name,value:three,index:three_index};
                                            return item;
                                        });
                                    });
                                }else{
                                    this.standardArray.map(item=>{
                                        item.level_two = {name:this.standardItems[1].name,value:two,index:two_index};
                                        return item;
                                    });
                                }
                            });
                        }else{
                            this.standardArray.map(item=>{
                                item.level_one = {name:this.standardItems[1].name,value:one,index:one_index};
                                return item;
                            });
                        }
                    });

                    console.log(this.standardArray);

                    let length = this.tableStandards.length-1;
                },

                /**
                 * 取消添加当前规格项
                 **/
                closeStandard:function (index) {
                    let tempStandards = this.standardItems.filter((item,i)=>{
                        if(i!=index){
                           return item;
                        }
                    });
                    this.standardItems = tempStandards;
                    if(tempStandards.length == 0){
                        this.showStandardAddButton = false;
                    }
                },

                /**
                 * 删除规格值
                 **/
                closeStandardValue:function (index,i) {
                    let tempData = this.standardItems.map((item,j)=>{
                        if(index == j){
                            let tempValues = [];
                            item.values.map((subItem,k)=>{
                                if(k != i){
                                    tempValues.push(subItem);
                                }
                            });
                            item.values = tempValues;
                        }
                        return item;
                    });
                    this.standardItems = tempData;
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
                submitActivityInfo:function () {
                    if(true){
                        layer.msg("标题不能为空");
                        return false;
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
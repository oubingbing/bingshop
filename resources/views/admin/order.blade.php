@extends('layouts/admin')
<style>
    [v-cloak] {
        display: none;
    }

</style>
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="{{asset('css/order.css')}}">
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
    <div class="x-body" id="app" v-cloak v-loading.fullscreen.lock="fullscreenLoading">

        <div class="layui-row">
            <div class="layui-form layui-col-md12 x-so">
                <input type="text" name="username"  placeholder="请输入商品名" autocomplete="off" class="layui-input">
                <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            </div>
        </div>

        <xblock>
            <button class="layui-btn"><i class="layui-icon"></i>新建</button>
            <span class="x-right" style="line-height:40px">共有数据：@{{total}} 条</span>
        </xblock>

        <div class="order-container">
            <div class="order-content">

                <div class="content-item" v-for="order in orderList">
                    <div class="order-header">
                        <div class="header-item order_number">
                            <span>订单号：</span>
                            <span>@{{ order.order_number }}</span>
                        </div>
                        <div class="header-item create_time">
                            <span>下单时间：</span>
                            <span>@{{ order.created_at }}</span>
                        </div>
                        <div class="header-item order-type">
                            <span>微信-普通订单</span>
                        </div>
                    </div>
                    <div class="order-body">
                        <div class="user-info">
                            <div class="avatar">
                                <img src="https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIRSkNHofic4wB9oyZrFBybUGjozW4DKtGJYWTWORATffxUtFLt1Cm3ibP0YVtfRicERVhsSickhgZic2w/132" alt="">
                            </div>
                            <div class="name">
                                <span>区志彬</span>
                                <span>微信昵称：叶子</span>
                                <span>13425144866</span>
                            </div>
                        </div>
                        <div class="order-detail">
                            <span class="goods_number">商品数：2</span>
                            <span class="total_amount">总金额：￥@{{ order.amount }}</span>
                            <span class="real_amount">实付金额：￥@{{order.actual_amount}}</span>
                            <span class="express">配送方式：顺丰快递</span>
                        </div>
                        <div class="other-info">
                            <div v-if="order.status==1" class="status-wait">状态：待支付</div>
                            <div v-if="order.status==2" class="status-paid">状态：已支付</div>
                            <div v-if="order.status==3">状态：支付失败</div>
                            <div v-if="order.status==4">状态：待发货</div>
                            <div v-if="order.status==5">状态：配送中</div>
                            <div v-if="order.status==6">状态：退款中</div>
                            <div v-if="order.status==7">状态：已完成</div>
                        </div>
                        <div class="order-operate">
                            <div class="dispatch-button">发货</div>
                            <div class="detail-button">详情</div>
                        </div>
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
        const IMAGE_URL = "{{env('QI_NIU_DOMAIN')}}";
        const ZONE = "{{env('QI_NIU_ZONE')}}";
        let token = '';

        new Vue({
            el: '#app',
            data: {
                total:0,
                page_size:10,
                page_number:1,
                current_page:1,
                fullscreenLoading:true,
                orderList:[]
            },
            created:function () {
                this.getOrder();
                this.getQiNiuToken();
            },
            methods:{
                /**
                 * 获取商品列表
                 **/
                getOrder:function () {
                    axios.get(`/admin/orders?page_size=${this.page_size}&page_number=${this.page_number}`,{}).then( response=> {
                        let resData = response.data;
                        this.fullscreenLoading = false;
                        if(resData.code == 0){
                            let orderList = [];
                            let orderData = resData.data.page_data;
                            console.log(resData);
                            if(orderData.length > 0){
                                console.log("测试");
                                orderData.map(goods=>{
                                    orderList.push(goods);
                                });
                                this.orderList = orderList;
                                this.page_number = this.current_page;
                                this.total = resData.data.page.total_items;
                            }
                        }

                    }).catch(function (error) {
                        console.log(error);
                    });
                },

                search:function () {
                    this.current_page = 1;
                    this.activities = [];
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
                    this.page_number = e;
                    this.orderList = [];
                    this.fullscreenLoading = true;
                    this.getOrder();
                }
            }
        })
    </script>

@endsection
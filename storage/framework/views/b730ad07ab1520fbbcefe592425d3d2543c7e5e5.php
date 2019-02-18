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

</style>
<?php $__env->startSection('content'); ?>
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/shop.css')); ?>">
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
            <span class="x-right" style="line-height:40px">共有数据：{{total}} 条</span>
        </xblock>

        <!-- 添加银行的页面 -->
        <div class="add_activity" style="margin-top: -50px" v-show="showBankForm">
            <form class="activity-form" style="width: 70%;margin-top: -100px;overflow: scroll;height: 600px">
                <div class="close-view">
                    <img class="close-button" v-on:click="closeBankForm" src="<?php echo e(asset('images/close.png')); ?>" alt="">
                </div>
                <div class="activity-form-left">
                    <div class="layui-form-item">
                        <label for="username" class="layui-form-label">
                            <span class="x-red">*</span>商品名
                        </label>
                        <div class="layui-input-inline">
                            <input type="text" v-model="goodsName" required="" lay-verify="required"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="phone" class="layui-form-label">
                            <span class="x-red">*</span>商品买点
                        </label>
                        <div class="layui-input-inline">
                            <textarea placeholder="请输入内容" v-model="goodsDescribe" class="layui-textarea"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="L_email" class="layui-form-label">
                            <span class="x-red">*</span>分享描述
                        </label>
                        <div class="layui-input-inline">
                            <textarea placeholder="请输入内容" v-model="goodsShareDescribe" class="layui-textarea"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="username" class="layui-form-label">
                            <span class="x-red">*</span>商品图片
                        </label>
                        <div class="layui-input-inline">
                            <img class="bank-image" v-bind:src="attachments[0]" v-if="attachments.length>=1" alt="">
                            <img class="upload-buttong" src="<?php echo e(asset('images/upload_img.png')); ?>" onclick="javascript:$('#cover-picture').click()" alt="">
                        </div>
                        <input type="file" id="cover-picture" style="display: none" class="layui-input" @change="selectBankImage($event)"/>
                    </div>
                    <div class="">
                        <label class="layui-form-label"><span class="x-red">*</span>商品类目</label>
                        <div class="layui-input-block">
                            <el-checkbox-group v-model="checkedCategory" @change="handleCheckedCategoryChange">
                            <el-checkbox v-for="category in categories" :label="category.id" :key="category.id">{{category.name}}</el-checkbox>
                            </el-checkbox-group>
                        </div>
                    </div>

                    <div class="" style="width: 100%;">
                        <label for="L_pass" class="layui-form-label">
                            <span class="x-red">*</span>商品规格
                        </label>
                        <div class="layui-input-inline standard-container">
                            <div class="standard" v-if="showStandardAddButton">
                                <div class="standard-list">
                                    <div class="standard-item">
                                        <div class="standard-title">规格名：</div>
                                        <input type="text"
                                               v-model="standards"
                                               required=""
                                               lay-verify="required"
                                               autocomplete="off"
                                               style="width: 100px"
                                               class="layui-input">
                                    </div>
                                    <div class="standard-item">
                                        <div class="standard-title">规格值：</div>
                                        <input type="text"
                                               v-model="standards"
                                               required=""
                                               lay-verify="required"
                                               autocomplete="off"
                                               style="width: 100px"
                                               class="layui-input">
                                        <div class="add-world">添加规格值</div>
                                    </div>
                                    <div class="standard-close">
                                        <img src="<?php echo e(asset('/images/cancel.png')); ?>" alt="">
                                    </div>
                                </div>
                                <div class="standard-list">
                                    <div class="standard-item">
                                        <div class="standard-title">规格名：</div>
                                        <input type="text"
                                               v-model="standards"
                                               required=""
                                               lay-verify="required"
                                               autocomplete="off"
                                               style="width: 100px"
                                               class="layui-input">
                                    </div>
                                    <div class="standard-item">
                                        <div class="standard-title">规格值：</div>
                                        <input type="text"
                                               v-model="standards"
                                               required=""
                                               lay-verify="required"
                                               autocomplete="off"
                                               style="width: 100px"
                                               class="layui-input">
                                        <div class="add-world">添加规格值</div>
                                    </div>
                                    <div class="standard-close">
                                        <img src="<?php echo e(asset('/images/cancel.png')); ?>" alt="">
                                    </div>
                                </div>
                                <div class="add-standard-button">添加规格项</div>
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
                        <label for="L_pass" class="layui-form-label">
                            <span class="x-red">*</span>商品明细
                        </label>
                        <div class="layui-input-inline standard-container-table">
                            <!-- https://blog.csdn.net/qq_35415600/article/details/70237433 -->
                            <table class="layui-table standard-detail">
                                <thead>
                                <tr>
                                    <th><small>颜色</small></th>
                                    <th><small>尺寸</small></th>
                                    <th><small>大小</small></th>
                                    <th><small>售格</small></th>
                                    <th><small>成本格</small></th>
                                    <th><small>库存</small></th>
                                </thead>
                                <tbody>

                                <tr>
                                    <td rowspan="4">红色</td>
                                    <td rowspan="2">1寸</td>
                                    <td rowspan="1">大</td>
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
                                    <td> 小</td>
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
                                    <td rowspan="2"> 2寸</td>
                                    <td> 小</td>
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
                                    <td> 小</td>
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
                        <label for="username" class="layui-form-label">
                            <span class="x-red">*</span>价格
                        </label>
                        <div class="layui-input-inline">
                            <input type="text" v-model="goodsPrice" required="" lay-verify="required"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label for="username" class="layui-form-label">
                            <span class="x-red">*</span>划线价
                        </label>
                        <div class="layui-input-inline">
                            <input type="text" v-model="chalkLinePrice" required="" lay-verify="required"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label for="username" class="layui-form-label">
                            <span class="x-red">*</span>库存
                        </label>
                        <div class="layui-input-inline">
                            <input type="text" v-model="goodsStock" required="" lay-verify="required"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>上架时间</label>
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
                        <label class="layui-form-label"><span class="x-red">*</span>下架时间</label>
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
                        <label class="layui-form-label"><span class="x-red">*</span>限购</label>
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
                        <label class="layui-form-label"><span class="x-red">*</span>配送方式</label>
                        <div class="layui-input-block layui-form">
                            <input type="radio" name="postType" lay-skin="primary" title="快递发货" checked="">
                            <input type="radio" name="postType" lay-skin="primary" title="同城配送" checked="">
                            <input type="radio" name="postType" lay-skin="primary" title="到点自取" checked="">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>快递运费</label>
                        <div class="layui-input-block layui-form">
                            <div class="sale-time-item">
                                <input type="radio" name="postCost" lay-skin="primary" title="统一邮费">
                                <input type="text" required="" style="width: 100px" value="0" lay-verify="required"
                                       autocomplete="off" class="layui-input">
                            </div>
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
    <script type="text/javascript" src="<?php echo e(asset('js/upload.js')); ?>"></script>
    <script>
        "use strict";
        const start = new Date();
        let token = '';
        const IMAGE_URL = "<?php echo e(env('QI_NIU_DOMAIN')); ?>";
        const ZONE = "<?php echo e(env('QI_NIU_ZONE')); ?>";
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

                goodsName:'',
                goodsDescribe:'',
                goodsShareDescribe:'',
                attachments:[],
                standards:[],
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
                postCost:0
            },
            created:function () {
                this.getCategories();
                this.getQiNiuToken();
            },
            methods:{
                showStandardAdd:function () {
                    this.showStandardAddButton = true;
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
                 * @param  event
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts/admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
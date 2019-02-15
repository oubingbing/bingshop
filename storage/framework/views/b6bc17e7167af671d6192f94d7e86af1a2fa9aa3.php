<style>
    [v-cloak] {
        display: none;
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
        <xblock>
            <button class="layui-btn" v-on:click="showBankView"><i class="layui-icon"></i>添加</button>
            <span class="x-right" style="line-height:40px">共有数据：88 条</span>
        </xblock>

        <!-- 添加银行的页面 -->
        <div class="add_bank" v-if="showBankForm" style="background: white;width: 30%">
            <form class="layui-form form-bank">
                <div class="close-view">
                    <img class="close-button" v-on:click="closeBankForm" src="<?php echo e(asset('images/close.png')); ?>" alt="">
                </div>
                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>类型名称
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" v-model="categoryName" name="username" required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>排序
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" v-model="sort" name="sort" required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                    </label>
                    <div  class="layui-btn" lay-filter="add" v-on:click="submitCategoryInfo">
                        保存
                    </div>
                </div>
            </form>

        </div>

        <table class="layui-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>排序</th>
                <th>创建时间</th>
                <th>操作</th>
            </thead>
            <tbody>
            <tr v-for="category in categories">
                <td>{{ category.id }}</td>
                <td>{{ category.name }}</td>
                <td>{{ category.sort }}</td>
                <td>{{ category.created_at }}</td>
                <td>
                    <a title="编辑" href="javascript:;" v-on:click="editCategory(category.id,category.name,category.sort)">
                        <i class="layui-icon">&#xe642;</i>
                    </a>
                    <a title="删除" v-on:click="deleteCategory(category.id)" href="javascript:;">
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
    <script>
        "use strict";
        new Vue({
            el: '#app',
            data: {
                categories:[],
                total:0,
                page_size:20,
                current_page:1,
                categoryName:'',
                showBankForm:false,
                operateType:'create',
                categoryId:'',
                sort:1
            },
            created:function () {
                this.getCategories();
            },
            methods:{
                editCategory:function (id,name,sort) {
                    this.showBankForm = true;
                    this.operateType = 'edit';
                    this.categoryId = id;
                    this.categoryName = name;
                    this.sort = sort;
                },
                deleteCategory:function (id) {
                    this.$confirm('此操作将永久删除该类型, 是否继续?', '警告', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        axios.delete(`/admin/category/${id}/delete`,{}).then( response=> {
                            let ResData = response.data;
                            if(ResData.code == 500){
                                layer.msg(ResData.message);
                            }else{
                                layer.msg(ResData.message);
                                this.categoryName = '';
                                this.showBankForm = false;
                                let tempArray = this.categories;
                                this.categories = tempArray.filter(item=>{
                                    if(item.id != id){
                                        return item;
                                    }
                                })
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
                showBankView:function () {
                    this.showBankForm = true;
                },
                closeBankForm:function () {
                    this.showBankForm = false;
                    this.categoryName = '';
                    this.sort = 1;
                },
                /**
                 * 新建类型
                 **/
                submitCategoryInfo:function () {
                    let name = this.categoryName;
                    let sort = this.sort;
                    if(!name){
                        layer.msg("类型名称不能为空");
                        return false;
                    }

                    if(this.operateType == 'create'){
                        axios.post(`/admin/category/create`,{
                            name:name,
                            sort:sort
                        }).then( response=> {
                            console.log(response);
                            let ResData = response.data;
                            if(ResData.code == 500){
                                layer.msg(ResData.message);
                            }else{
                                this.getCategories();
                                this.current_page = 1;
                                layer.msg(ResData.message);
                                this.categoryName = '';
                                this.sort = 1;
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }else{
                        axios.patch(`/admin/category/edit`,{
                            id:this.categoryId,
                            name:name,
                            sort:sort
                        }).then( response=> {
                            console.log(response);
                            let ResData = response.data;
                            if(ResData.code == 500){
                                layer.msg(ResData.message);
                            }else{
                                layer.msg(ResData.message);
                                this.categoryName = '';
                                this.showBankForm = false;
                                this.sort = 1;
                                let tempArray = this.categories;
                                this.categories = tempArray.map(item=>{
                                    if(item.id == this.categoryId){
                                        item.name = name;
                                        item.sort = sort
                                    }
                                    return item;
                                })
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }

                },
                getCategories:function () {
                    var url = "<?php echo e(asset("admin/category_list")); ?>";
                    axios.get(url+"?page_size="+this.page_size+'&page_number='+this.current_page+'&order_by=created_at&sort_by=desc')
                        .then( response=> {
                            var res = response.data;
                            if(res.code === 0){
                                this.categories = res.data.page_data;
                                this.total = res.data.page.total_items;
                            }else{
                                console.log('error:'+res);
                            }
                        }).catch(function (error) {
                        console.log(error);
                    });
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
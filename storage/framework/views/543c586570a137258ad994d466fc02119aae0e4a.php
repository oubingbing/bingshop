<style>
    [v-cloak] {
        display: none;
    }
</style>
<?php $__env->startSection('content'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <div class="x-body layui-anim layui-anim-up" id="app" v-cloak>
        <blockquote class="layui-elem-quote">你好：<?php echo e($user->nickname); ?>，欢迎使用bingshop后台管理系统</blockquote>
        <fieldset class="layui-elem-field">
            <legend>数据统计</legend>
            <div class="layui-field-box">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body">
                            <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
                                <div carousel-item="">
                                    <ul class="layui-row layui-col-space10 layui-this">
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>用户数</h3>
                                                <p>
                                                    <cite>{{all_user}}</cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>今日新增用户</h3>
                                                <p>
                                                    <cite>{{all_user}}</cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>昨日新增用户</h3>
                                                <p>
                                                    <cite>{{ yesterday_user }}</cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>官方活动</h3>
                                                <p>
                                                    <cite>{{ activity }}</cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>共享活动</h3>
                                                <p>
                                                    <cite>{{ share_activity }}</cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>攻略文章</h3>
                                                <p>
                                                    <cite>{{ article }}</cite></p>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

    </div>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/axios/0.17.1/axios.min.js"></script>
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <script>
        "use strict";
        var app = new Vue({
            el: '#app',
            data: {
                new_user:'-',
                yesterday_user:'-',
                all_user:'-',
                activity:'_',
                share_activity:'_',
                article:'_'
            },
            created:function () {
                this.statics();
            },
            methods:{
                statics:function () {
                    axios.get(`/admin/statics`,{}).then( response=> {
                        let resData = response.data;
                        if(resData.code == 0){
                            let data = resData.data;
                            this.new_user = data.new_user;
                            this.all_user = data.all_user;
                            this.yesterday_user = data.yesterday_user;
                            this.activity = data.activity;
                            this.share_activity = data.share_activity;
                            this.article = data.article;
                        }

                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts/admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
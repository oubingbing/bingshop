@extends('layouts/admin')
<style>
    [v-cloak] {
        display: none;
    }
</style>
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <style>
    </style>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">用户</a>
        <a>
          <cite>用户列表</cite></a>
      </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
            <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body" id="app" v-cloak v-loading.fullscreen.lock="fullscreenLoading">
        <div class="layui-row">
            <div class="layui-form layui-col-md12 x-so">
                <input type="text" v-model="username" name="username"  placeholder="请输入用户名" autocomplete="off" class="layui-input">
                <button class="layui-btn"  lay-submit="" lay-filter="sreach" v-on:click="search"><i class="layui-icon">&#xe615;</i></button>
            </div>
        </div>
        <blockquote class="layui-elem-quote">共有数据：@{{total}} 条</blockquote>
        <table class="layui-table">
            <thead>
            <tr>
                <th>头像</th>
                <th>微信昵称</th>
                <th>性别</th>
                <th>国家</th>
                <th>省</th>
                <th>市</th>
                <th>创建时间</th>
            </thead>
            <tbody>
            <tr v-for="user in users">
                <td><img v-bind:src=user.avatar style="width: 40px;width: 40px"/></td>
                <td>@{{ user.nickname }}</td>
                <td>@{{ user.gender }}</td>
                <td>@{{ user.country }}</td>
                <td>@{{ user.province }}</td>
                <td>@{{ user.city }}</td>
                <td>@{{ user.created_at }}</td>
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
    <script>
        "use strict";
        new Vue({
            el: '#app',
            data: {
                users:[],
                total:0,
                page_size:20,
                current_page:1,
                username:'',
                fullscreenLoading:true
            },
            created:function () {
                this.getUsers();
            },
            methods:{
                search:function () {
                    this.current_page = 1;
                    this.users = [];
                    this.getUsers();
                },
                handleCurrentChange:function (e) {
                    this.current_page = e;
                    this.getUsers();
                },
                getUsers:function () {
                    var url = "{{ asset("admin/users") }}";
                    axios.get(url+"?page_size="+this.page_size+'&page_number='+this.current_page+'&order_by=created_at&sort_by=desc&username='+this.username)
                        .then( response=> {
                            this.fullscreenLoading = false;
                            var res = response.data;
                            if(res.code === 0){
                                this.users = res.data.page_data;
                                this.total = res.data.page.total_items;
                                this.page_number += 1;
                            }else{
                                console.log('error:'+res);
                            }
                        }).catch(function (error) {
                        console.log(error);
                    });
                }
            }
        })
    </script>

@endsection
@extends('layouts/admin')

@section('content')
    <body>
    <!-- 顶部开始 -->
    <div class="container">
        <div class="logo"><a href="./index.html">bingshop - 后台管理</a></div>
        <div class="left_open">
            <i title="展开左侧栏" class="iconfont">&#xe699;</i>
        </div>
        <ul class="layui-nav right" lay-filter="">
            <li class="layui-nav-item">
                <a href="javascript:;">{{$user->nickname}}</a>
                <dl class="layui-nav-child"> <!-- 二级菜单 -->
                    <dd><a href="{{asset('logout')}}">退出</a></dd>
                </dl>
            </li>
        </ul>

    </div>
    <!-- 顶部结束 -->
    <!-- 中部开始 -->
    <!-- 左侧菜单开始 -->
    <div class="left-nav">
        <div id="side-nav">
            <ul id="nav">
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe6b8;</i>
                        <cite>用户</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="{{ asset('admin/user/index') }}">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>用户列表</cite>

                            </a>
                        </li >
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe6b8;</i>
                        <cite>商品</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="{{ asset('admin/category/index') }}">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>类目</cite>
                            </a>
                        </li >
                        <li>
                            <a _href="{{ asset('admin/goods/index') }}">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>商品</cite>
                            </a>
                        </li >
                        <li>
                            <a _href="{{ asset('admin/activity/share') }}">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>共享活动</cite>
                            </a>
                        </li >
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe6b8;</i>
                        <cite>订单</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a _href="{{ asset('admin/strategy/official') }}">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>订单列表</cite>

                            </a>
                        </li >
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- <div class="x-slide_left"></div> -->
    <!-- 左侧菜单结束 -->
    <!-- 右侧主体开始 -->
    <div class="page-content">
        <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
            <ul class="layui-tab-title">
                <li class="home"><i class="layui-icon">&#xe68e;</i>我的桌面</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <iframe src='{{ asset('admin/dashboard') }}' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content-bg"></div>
    <!-- 右侧主体结束 -->
    <!-- 中部结束 -->
    <!-- 底部开始 -->
    <div class="footer">
        <div class="copyright">@2018-2019 bingshop | 粤ICP备16004706号-1</div>
    </div>
    <!-- 底部结束 -->
    </body>
    <script>
        "use strict";
    </script>
@stop
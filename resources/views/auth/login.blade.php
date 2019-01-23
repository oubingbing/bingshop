@extends('layouts/admin')

@section('content')
    <body class="login-bg">

    <div class="login layui-anim layui-anim-up">
        <div class="message"><a href="{{ asset('/') }}" style="color: white;">灯塔笔记 - 后台登录</a></div>
        <div id="darkbannerwrap"></div>

        <form method="post" class="layui-form">
            {{ csrf_field() }}
            <input name="phone" placeholder="手机"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20" >
        </form>
        <div><a href="{{ asset('register') }}">没有账号？快去注册吧</a></div>
    </div>

    <script>
        $(function  () {
            layui.use('form', function(){
                var form = layui.form;

                form.on('submit(login)', function(data){
                    var fields = data.field;
                    $.post("{{asset('login')}}",fields,function(res){
                        if(res.code === 500){
                            layer.msg(res.message)
                        }else{
                            layer.msg("登录成功");
                            setTimeout(function () {
                                window.location.href = "/admin";
                            },1500)
                        }
                    });

                    return false;
                });
            });
        })


    </script>
    </body>
@stop
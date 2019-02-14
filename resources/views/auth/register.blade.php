@extends('layouts/admin')
<style>
    .phone-div{
        width: 100%;
        display: flex;
        flex-direction: row;
    }
    .email{
        width: 70%;
    }

    .send-button{
        width: 30%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }

    .button{
        border-radius: 5px;
        border:0;
        background: gainsboro;
        padding: 5px 15px;
        cursor:pointer;
    }

    .wait-second{
        background: gainsboro;
        padding: 5px 15px;
    }
</style>
@section('content')
    <body class="login-bg">

    <div class="login layui-anim layui-anim-up">
        <div class="message"><a href="{{ asset('/') }}" style="color: white;">控制台 - 注册用户</a></div>
        <div id="darkbannerwrap"></div>

        <form method="POST" class="layui-form">
            {{ csrf_field() }}
            <input name="nickname" placeholder="昵称"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <div class="phone-div">
                <input id="email" name="email" placeholder="邮箱"  type="text" lay-verify="required" class="layui-input email" >
            </div>
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
            <hr class="hr15">
            <input name="password_confirmation" lay-verify="required" placeholder="确认密码"  type="password" class="layui-input">

            <hr class="hr20" >
            <input value="注册" lay-submit lay-filter="login" style="width:100%;" type="submit">
        </form>
        <hr class="hr20" >
        <div><a href="{{asset('login')}}">已有账号？快去登录吧</a></div>
    </div>
    <script>
        $(function  () {
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

            layui.use('form', function(){
                var form = layui.form;
                //监听提交
                form.on('submit(login)', function(data){
                    var fields = data.field;

                    if(fields.password_confirmation !== fields.password){
                        layer.msg('两次输入密码不一致！');
                        return false;
                    }

                    $.post("{{asset('register')}}",fields,function(res){
                        console.log(res)
                        if(res.error_code != 0){
                            layer.msg(res.error_message)
                        }else{
                            layer.msg("注册成功");
                        }
                    });

                    return false;
                });
            });
        })


    </script>
    </body>
@endsection

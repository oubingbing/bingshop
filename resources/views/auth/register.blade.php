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
        <div class="message"><a href="{{ asset('/') }}" style="color: white;">灯塔笔记 - 注册</a></div>
        <div id="darkbannerwrap"></div>

        <form method="POST" class="layui-form">
            {{ csrf_field() }}
            <input name="nickname" placeholder="昵称"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
            <hr class="hr15">
            <input name="password_confirmation" lay-verify="required" placeholder="确认密码"  type="password" class="layui-input">
            <hr class="hr15">
            <div class="phone-div">
                <input id="phone" name="phone" placeholder="手机"  type="text" lay-verify="required" class="layui-input email" >
                <div class="send-button">
                    <span class="button" id="send-message">发送</span>
                    <span class="wait-second" id="waiting" style="display: none;">90s</span>
                </div>
            </div>
            <hr class="hr15">
            <input name="code" lay-verify="required" placeholder="验证码"  type="text" class="layui-input">
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

                    if(fields.code == ''){
                        layer.msg('验证码不能为空');
                        return false;
                    }

                    $.post("{{asset('register')}}",fields,function(res){
                        if(res.code === 500){
                            layer.msg(res.message)
                        }else{
                            layer.msg("注册成功");
                            setTimeout(function () {
                                window.location.href = "{{asset('login')}}";
                            },1500)
                        }
                    });

                    return false;
                 });
            });

            $("#send-message").on("click",function () {
                var phone = $("#phone").val();
                if(phone == ''){
                    layer.msg('手机号不能为空');
                    return false;
                }

                $.post("{{asset('send_message')}}",{phone:phone},function(res){
                    if(res.code === 500){
                        layer.msg(res.message)
                    }else{
                        $("#waiting").css("display","");
                        $("#send-message").css("display","none");
                        var time = 90;
                        var int=self.setInterval(function () {
                            $("#waiting").html((time--)+"s");
                            if(time < 0){
                                window.clearInterval(int);
                                $("#waiting").css("display","none");
                                $("#send-message").css("display","");
                                $("#waiting").html("90s");
                            }
                            console.log(time)
                        },1000);
                        layer.msg("发送成功");
                    }
                });
            })
        })


    </script>
    </body>
@endsection

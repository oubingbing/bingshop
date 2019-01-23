@extends('layouts/gateway')
<style>
    .wechat-image{
        width: 150px;
        height: 150px;
    }
</style>
@section('content')
    <div class="container">
        <div class="jumbotron">
            <div>
                <image class="wechat-image" src="{{asset('images/qrcode.jpg')}}"></image>
            </div>
            <h3>灯塔笔记</h3>
            <p>灯塔笔记，我自己的笔记本</p>
        </div>
    </div>
@stop
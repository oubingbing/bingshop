@extends('layouts/admin')
<link rel="stylesheet" href="{{asset('css/markdown/style.css')}}" />
<link rel="stylesheet" href="{{asset('css/markdown/editormd.css')}}" />
<link rel="shortcut icon" href="https://pandao.github.io/editor.md/favicon.ico" type="image/x-icon" />
<style>
    .create-container{
        width: 100%;
        display: flex;
        flex-direction: column;
    }

    ::-webkit-scrollbar {
        width: 0;   /* 滚动条宽度为0 */
        height: 0; /* 滚动条高度为0 */
        display: none; /* 滚动条隐藏 */
    }

    .create-header{
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        padding-top: 10px;
        padding-bottom: 10px;
        border-bottom-style:solid;
        border-width:1px;
        border-color: #E1E1E1;
        cursor:pointer;
    }

    .create-header img{
        width: 35px;
        height: 35px;
    }

    .create-header .create-title{
        padding-left: 5px;
    }

    .create-container .my-dir{
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: white;
        padding-bottom: 750px;
    }

    .my-dir .title{
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        padding-top: 10px;
        padding-bottom: 10px;
        background: #009688;
        color: white;
    }

    .title .title-label{
        width: 80%;
    }

    .my-dir .note{
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        margin-top: 5px;
    }

    .note .note-item{
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
    }

    .note-item img{
        width: 20px;
        height:20px;
        padding-right: 5px;
    }

    .note-item .note-title{
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
        cursor:pointer;
        padding: 10px;
        height: 50px;
    }

    .note-title .title-left,.rename-div{
        width: 80%;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
    }

    .note-title .title-right{
        width: 20%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }

    .selectTitle{
        background: red;
    }

    .note-title .title-label{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .note-item .note-content{
        width: 100%;
        display: flex;
        flex-direction: column;
    }

    .note-content .content-title{
        padding: 10px;
        color: gray;
        cursor:pointer;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
        border-top-style:solid;
        border-width:1px;
        border-color: gainsboro;
    }

    .note-content .title-border{
        height: 60px;
    }

    .content-title img{
        width: 20px;
        height: 20px;
    }

    .note-content .add-icon{
        display: flex;
        flex-direction: column;
        border-style:dashed;
        border-width:1px;
        border-color: #E1E1E1;
        margin-top:20px;
        padding-top: 5px;
        padding-bottom: 5px;
        align-items: center;
        justify-content: center;
        border-radius: 2px;
        margin-bottom: 10px;
    }

    .note-content .enter
    {
        background-color:#F2F2F2;
    }

    [v-cloak] {
        display: none !important;
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity .4s;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

    .tap{
        background:#009688;
    }
    .titleColor{
        color: white;
    }

    .create-category{
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        padding: 5px;
    }

    .create-category button{
        margin-left: 3px;
    }

    .moveCategory{
        background: #F0F8FF;
    }

    .page-content .content-header{
        width: 100%;
        height: 70px;
        background: white;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        border-bottom-style:solid;
        border-width:1px;
        border-color: #E1E1E1;
    }

    .content-header .header-left{
        width: 50%;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
    }

    .header-left .title{
        margin-left: 30px;
    }

    .page-content .header-right{
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: center;
        position: fixed;
        z-index: 100;
        right: 50px;
        top: 40%;
    }

    .header-right .operate-button{
        display: flex;
        flex-direction: column;
    }

    .operate-button img{
        width: 50px;
        height:50px;
        margin-bottom: 10px;
        margin-top: 10px;
        background: white;
        border-radius: 25px;
    }


    .image-container{
        width: 100%;
        height: 150px;
        background: white;
        display: flex;
        flex-direction: row;
        align-items: center;
        border-bottom-style:solid;
        border-width:1px;
        border-color: #E1E1E1;
    }

    .image-container .label{
        margin-left: 30px;
        width: 10%;
    }

    .image-container .image-content{
        width: 75%;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
    }

    .image-content img{
        width: 100px;
        height:100px;
    }

    .image-content .cover-container{
        margin-right: 10px;
    }

    .delete-container{
        width: 100px;
        height: 100px;
        z-index: 330;
        position: absolute;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background:rgba(2,2,2,0.6);
        border-radius: 5px;
        border-radius: 5px;
        color: #009688;
    }

    .delete-container img{
        width: 30px;
        height: 30px;
    }

    .note-div{

    }

    .note-div,.renameNote-div{
        float: right;
        width: 100%;
        display: flex;
        flex-direction: row;
        overflow: hidden;
        text-overflow:ellipsis;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        justify-content: flex-start;
        align-items: center;
        text-align: left;
        display: -webkit-box;
    }

    .edit-note{
        width: 10%;
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        align-items: center;
    }

    .setting-container{
        width: 50%;
        height: 50%;
        display: flex;
        flex-direction: column;
        position: fixed;
        z-index: 300;
        background: white;
        top: 20%;
        left: 25%;
        box-shadow: darkgrey 10px 10px 30px 5px ;
    }

    .setting-container .setting-content{
        width: 100%;
        display: flex;
        flex-direction: column;
        padding: 10px 10px;
    }

    .setting-content .setting-donation{
        width: 100%;
        display: flex;
        flex-direction: row;
        height: 150px;
    }

    .setting-donation .donation-label{
        width: 10%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .setting-donation .donation-code{
        width: 90%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
    }

    .donation-code img{
        width: 100px;
        height: 100px;
        margin-right: 10px;
    }

    .code-container{
        width: 100%;
        display: flex;
        flex-direction: row;
    }

    .setting-content .header{
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
    }

    .header img{
        width: 20px;
        height: 20px;
    }

    .content-md{
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .content-md .viewMd{
        width: 80%;
    }

    .md-container{
        padding-bottom: 100px;
    }

</style>
@section('content')
<body>
<div id="app">

    <div class="setting-container" v-if="showSetting" v-cloak>
        <div class="setting-content" v-cloak>
            <div class="header">
                <img src="{{asset('images/close.png')}}" alt="" v-on:click="hiddenSetting()">
            </div>
            <div class="setting-donation">
                <div class="donation-label">
                    <div>赞赏码</div>
                </div>
                <div class="donation-code">
                    <div class="code-container">
                        <img  v-bind:src="donationQrCode"
                              alt="" v-if="donationQrCode" onclick="javascript:$('#cover-picture').click()" v-if="showSelectQrCode">
                        <img src="{{asset('images/select-image.png')}}" alt="" onclick="javascript:$('#cover-picture').click()" v-if="showSelectQrCode">
                    </div>
                    <input type="file" id="cover-picture" style="display: none" class="layui-input" @change="selectDonationQrCode($event)"/>
                </div>
            </div>
        </div>
    </div>

    <!-- 顶部开始 -->
    <div class="container" style="background: #009688">
        <div class="logo"><a href="./index.html">灯塔笔记</a></div>
        <div class="left_open">
            <i title="展开左侧栏" class="iconfont">&#xe699;</i>
        </div>
        <ul class="layui-nav right" lay-filter="">
            <li class="layui-nav-item to-index"><a href="/">前台首页</a></li>
          <li class="layui-nav-item">
            <a href="javascript:;">{{$nickname}}</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
              <dd><a href="{{asset('logout')}}">退出</a></dd>
            </dl>
          </li>
            <li class="layui-nav-item to-index" v-on:click="showSettingDiv"><a href="javascript:;">设置</a></li>
        </ul>

    </div>
    <!-- 顶部结束 -->
    <!-- 中部开始 -->
     <!-- 左侧菜单开始 -->
    <div class="left-nav">
      <div id="side-nav">
          <div class="create-container" v-cloak>
              <div class="create-header" v-on:click="createDir()">
                  <img src="{{asset('images/file.png')}}" alt="">
                  <span class="create-title">新建笔记本</span>
              </div>
              <div class="my-dir">
                  <div class="title"><span class="title-label">我的笔记本</span></div>
                  <div class="note">
                      <div class="note-item" v-for="category in noteCategories">
                          <div class="note-title"
                               v-on:click="showNoteList(category.id)"
                                @mouseenter="enterNoteCategory(category.id)"
                                @mouseleave="leaveNoteCategory()"
                                v-bind:class="{moveCategory:category.showBackgroud}">
                              <div class="title-left" v-show="!category.showRenameCategory">
                                  <div class="title-div">
                                      <img src="{{asset('images/book.png')}}" alt="">
                                      <span class="title-label">@{{ category.name }}</span>
                                  </div>
                              </div>
                              <div class="rename-div"
                                   v-show="category.showRenameCategory"
                                   @mouseenter="enterRenameCategory(category.name)"
                                   @mouseleave="leaveRenameCategory(category.id,category.use_type)">
                                  <input type="text"
                                         v-model="renameCategoryValue"
                                         v-show="category.showRenameCategory"
                                         id="rename_category"
                                         autocomplete="off"
                                         class="layui-input">
                              </div>
                              <div class="title-right" v-if="category.showOperate == true && !category.showRenameCategory">
                                  <img src="{{asset('images/edit-category.png')}}" style="width: 25px;height: 20px" alt="" v-on:click="renameCategory(category.id)">
                                  <img src="{{asset('images/delete-category.png')}}" style="width: 25px;height: 20px" alt="" v-on:click="deleteCategory(category.id)">
                              </div>
                          </div>
                          <transition name="fade">
                              <div class="note-content" v-if="category.showNotes == true">
                                  <div style="" class="content-title title-border"
                                        @mouseenter="enterNote(category.id,note.id)"
                                        @mouseleave="leaveNote()"
                                        v-bind:class="{tap:note.tap,enter:note.enter}"
                                        v-on:click="openNote(category.id,note.id)"
                                        v-for="note in category.notes">
                                      <div class="note-div" v-show="!note.showRenameNote">
                                          <span v-bind:class="{titleColor:note.tap}">@{{ note.title }}</span>
                                      </div>
                                      <div class="renameNote-div" v-show="note.showRenameNote" @mouseleave="leaveNoteInput(note.id,category.id,note.title)">
                                          <input type="text"
                                                 v-model="renameNoteValue"
                                                 id="rename_category"
                                                 autocomplete="off"
                                                 class="layui-input">
                                      </div>
                                      <div class="edit-note" v-show="note.showEdit">
                                          <img src="{{asset('images/edit-category.png')}}" style="width: 25px;height: 20px" alt="" v-on:click="renameNote(note.id)">
                                      </div>
                                  </div>

                                  <div class="create-dialog create-note" v-if="category.showCreateNote">
                                      <div class="create-category">
                                          <div class="layui-input-inline">
                                              <input type="text"
                                                     id="name_name"
                                                     required=""
                                                     lay-verify="required"
                                                     autocomplete="off"
                                                     v-model="noteName"
                                                     class="layui-input">
                                          </div>
                                          <div class="layui-form-mid layui-word-aux">
                                              <button  class="layui-btn" lay-filter="add" lay-submit="" v-on:click="createNote(category.id)">
                                                  提交
                                              </button>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="content-title add-icon" v-on:click="showCreateNoteButton(category.id)">
                                      <img src="{{asset('images/add-note.png')}}" alt="">
                                  </div>

                              </div>
                          </transition>
                      </div>
                  </div>
              <div class="create-dialog" v-if="showCreateCategory">
                  <div class="create-category">
                      <div class="layui-input-inline">
                          <input type="text"
                                 id="title"
                                 required=""
                                 lay-verify="required"
                                 autocomplete="off"
                                 v-model="categoryName"
                                 placeholder="笔记簿"
                                 class="layui-input">
                      </div>
                      <div class="layui-form-mid layui-word-aux">
                          <button  class="layui-btn" lay-filter="add" lay-submit="" v-on:click="createCategory">
                              提交
                          </button>
                      </div>
                  </div>
              </div>

              </div>
          </div>

      </div>
    </div>

    <!-- <div class="x-slide_left"></div> -->
    <!-- 左侧菜单结束 -->
    <!-- 右侧主体开始 -->
    <div class="page-content" v-cloak >
        <div class="header-right" v-show="showDelete">
            <div class="operate-button">
                <img src="{{asset('images/save.png')}}" alt="" v-on:click="saveEdit()" v-show="showSave">
                <img src="{{asset('images/edit.png')}}" alt="" v-on:click="showEditMd()" v-show="showEdit">
                <img src="{{asset('images/delete-icon.png')}}" alt="" v-show="showDelete" v-on:click="deleteNote()">
            </div>
        </div>
        <div class="layui-tab tab md-container" lay-filter="xbs_tab" lay-allowclose="false">
            <div class="content-body content-md">
                <div id="editormd" v-show="showMd">
                    <textarea style="display:none;"></textarea>
                </div>
                <div id="viewMd" style="height: 900px" class="viewMd" v-show="!showMd">
                    <textarea style="display:none;"></textarea>
                </div>
            </div>

        </div>
    </div>
    <div class="page-content-bg"></div>
    <!-- 右侧主体结束 -->

    <!-- 底部开始 -->
    <div class="footer">
        <div class="copyright">@2016-2018 大学灯塔 | 粤ICP备16004706号-1</div>
    </div>
    <!-- 底部结束 -->
</div>
<script type="text/javascript" src="https://unpkg.com/qiniu-js@2.0/dist/qiniu.min.js"></script>
<script type="text/javascript" src="{{asset('js/upload.js')}}"></script>
<script src="{{asset('js/markdown/editormd.js')}}"></script>
<script src="{{asset('lib/marked.min.js')}}"></script>
<script src="{{asset('lib/prettify.min.js')}}"></script>
<script src="{{asset('lib/raphael.min.js')}}"></script>
<script src="{{asset('lib/underscore.min.js')}}"></script>
<script src="{{asset('lib/sequence-diagram.min.js')}}"></script>
<script src="{{asset('lib/flowchart.min.js')}}"></script>
<script src="{{asset('lib/jquery.flowchart.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/note-index.js?v=1.0.0')}}"></script>
<script type="text/javascript">
    'use strict';
    const token = "{{$token}}";
    const IMAGE_URL = "{{env('QI_NIU_DOMAIN')}}";
    const ZONE = "z2";
    let editorMd = '';
    let viewMd = '';
    let windowHeight = document.documentElement.scrollHeight;

    $(function() {

        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        editorMd = editormd("editormd", {
            width: "100%",
            height: (windowHeight*0.9),
            markdown : "",
            path : "/lib/",
            imageUpload : true,
            imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
            imageUploadURL : "{{asset('/admin/note/image_upload')}}",
        });

    });
</script>
</body>
@stop
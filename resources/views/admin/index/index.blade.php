<!-- 更多资源下载请加QQ群：304104682 -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>易证后台管理</title>
    <link rel="stylesheet" type="text/css" href="/static/admin/layui/css/layui.css"/>
    <link rel="stylesheet" type="text/css" href="/static/admin/css/admin.css"/>

</head>
<body>
<div class="main-layout" id='main-layout'>
    <!--侧边栏-->
    <div class="main-layout-side">
        <div class="m-logo">
        </div>
        <ul class="layui-nav layui-nav-tree" lay-filter="leftNav">
            <li class="layui-nav-item layui-nav-itemed">
                <a href="javascript:;"><i class="iconfont">&#xe607;</i>商户管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;" data-url="{{url('admin/merchant/index')}}" data-id='1' data-text="商户列表" id="merchant"><span class="l-line"></span>商户列表</a></dd>
                    <dd><a href="javascript:;" data-url="{{url('admin/merchant/add')}}" data-id='2' data-text="添加商户"><span class="l-line"></span>添加商户</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;"><i class="iconfont">&#xe608;</i>会员管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;" data-url="{{url('admin/user/index')}}" data-id='3' data-text="会员列表"><span class="l-line"></span>会员列表</a></dd>
                    <dd><a href="javascript:;" data-url="{{url('admin/user/add')}}" data-id='4' data-text="新增会员"><span class="l-line"></span>新增会员</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;"><i class="iconfont">&#xe608;</i>服务产品</a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;" data-url="{{url('admin/goods/index')}}" data-id='5' data-text="产品列表"><span class="l-line"></span>产品列表</a></dd>
                    <dd><a href="javascript:;" data-url="{{url('admin/goods/add')}}" data-id='6' data-text="添加产品"><span class="l-line"></span>添加产品</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;"><i class="iconfont">&#xe608;</i>服务项目</a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;" data-url="{{url('admin/template/index')}}" data-id='7' data-text="模板列表"><span class="l-line"></span>模板列表</a></dd>
                    <dd><a href="javascript:;" data-url="{{url('admin/template/add')}}" data-id='8' data-text="添加模板"><span class="l-line"></span>添加模板</a></dd>
                    <dd><a href="javascript:;" data-url="{{url('admin/template/field_index')}}" data-id='9' data-text="模板字段列表"><span class="l-line"></span>模板字段列表</a></dd>
                    <dd><a href="javascript:;" data-url="{{url('admin/template/field_add')}}" data-id='10' data-text="添加模板字段"><span class="l-line"></span>添加模板字段</a></dd>
                    <dd><a href="javascript:;" data-url="{{url('admin/server/index')}}" data-id='11' data-text="服务列表"><span class="l-line"></span>服务列表</a></dd>
                    <dd><a href="javascript:;" data-url="{{url('admin/server/add')}}" data-id='12' data-text="添加服务"><span class="l-line"></span>添加服务</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;"><i class="iconfont">&#xe608;</i>订单管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;" data-url="{{url('admin/order/index')}}" data-id='13' data-text="订单列表"><span class="l-line"></span>订单列表</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;"><i class="iconfont">&#xe608;</i>系统管理</a>
                <dl class="layui-nav-child">
                    {{--<dd><a href="javascript:;" data-url="" data-id='3' data-text="会员列表"><span class="l-line"></span>用户管理</a></dd>--}}
                    <dd><a href="javascript:;" data-url="{{url('admin/group/index')}}" data-id='20' data-text="组织列表"><span class="l-line"></span>组织列表</a></dd>
                    <dd><a href="javascript:;" data-url="{{url('admin/role/index')}}" data-id='17' data-text="角色列表"><span class="l-line"></span>角色管理</a></dd>
                    <dd><a href="javascript:;" data-url="{{url('admin/role/add')}}" data-id='16' data-text="添加角色"><span class="l-line"></span>添加角色</a></dd>
                    <dd><a href="javascript:;" data-url="{{url('admin/permission/index')}}" data-id='14' data-text="权限列表"><span class="l-line"></span>权限管理</a></dd>
                    <dd><a href="javascript:;" data-url="{{url('admin/permission/add')}}" data-id='15' data-text="添加权限"><span class="l-line"></span>添加权限</a></dd>
                    {{--<dd><a href="javascript:;" data-url="" data-id='3' data-text="会员列表"><span class="l-line"></span>组织管理</a></dd>--}}
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;"><i class="iconfont">&#xe608;</i>热搜管理</a>
                <dl class="layui-nav-child">
                    {{--<dd><a href="javascript:;" data-url="" data-id='3' data-text="会员列表"><span class="l-line"></span>用户管理</a></dd>--}}
                    <dd><a href="javascript:;" data-url="{{url('admin/hots/index')}}" data-id='18' data-text="热搜榜单"><span class="l-line"></span>热搜榜单</a></dd>
                    {{--<dd><a href="javascript:;" data-url="{{url('admin/hots/index')}}" data-id='18' data-text="热词榜单"><span class="l-line"></span>热搜城市</a></dd>--}}
                    {{--<dd><a href="javascript:;" data-url="{{url('admin/hots/add')}}" data-id='19' data-text="添加热词"><span class="l-line"></span>添加热词</a></dd>--}}
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;"><i class="iconfont">&#xe608;</i>广告管理</a>
                <dl class="layui-nav-child">
                    {{--<dd><a href="javascript:;" data-url="" data-id='3' data-text="会员列表"><span class="l-line"></span>用户管理</a></dd>--}}
                    <dd><a href="javascript:;" data-url="{{url('admin/adsense/index')}}" data-id='19' data-text="广告位列表"><span class="l-line"></span>广告位列表</a></dd>
                    {{--<dd><a href="javascript:;" data-url="{{url('admin/hots/add')}}" data-id='19' data-text="添加热词"><span class="l-line"></span>添加热词</a></dd>--}}
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;"><i class="iconfont">&#xe608;</i>消息通知</a>
                <dl class="layui-nav-child">
                    {{--<dd><a href="javascript:;" data-url="" data-id='3' data-text="会员列表"><span class="l-line"></span>用户管理</a></dd>--}}
                    <dd><a href="javascript:;" data-url="{{url('admin/news/index')}}" data-id='21' data-text="消息通知列表"><span class="l-line"></span>消息通知列表</a></dd>
                    <dd><a href="javascript:;" data-url="{{url('admin/news/add')}}" data-id='22' data-text="创建消息通知"><span class="l-line"></span>创建消息</a></dd>
                    {{--<dd><a href="javascript:;" data-url="{{url('admin/hots/add')}}" data-id='19' data-text="添加热词"><span class="l-line"></span>添加热词</a></dd>--}}
                </dl>
            </li>

            <li class="layui-nav-item">
                <a href="javascript:;"><i class="iconfont">&#xe608;</i>分润机制</a>
                <dl class="layui-nav-child">
                    {{--<dd><a href="javascript:;" data-url="" data-id='3' data-text="会员列表"><span class="l-line"></span>用户管理</a></dd>--}}
                    <dd><a href="javascript:;" data-url="{{url('admin/bonus/index')}}" data-id='23' data-text="分润机制列表"><span class="l-line"></span>分润机制列表</a></dd>
                    <dd><a href="javascript:;" data-url="{{url('admin/bonus/add')}}" data-id='24' data-text="创建分润机制"><span class="l-line"></span>创建分润机制</a></dd>
                    {{--<dd><a href="javascript:;" data-url="{{url('admin/hots/add')}}" data-id='19' data-text="添加热词"><span class="l-line"></span>添加热词</a></dd>--}}
                </dl>
            </li>
        </ul>
    </div>
    <!--右侧内容-->
    <div class="main-layout-container">
        <!--头部-->
        <div class="main-layout-header">
            <div class="menu-btn" id="hideBtn">
                <a href="javascript:;">
                    <span class="iconfont">&#xe60e;</span>
                </a>
            </div>
            <ul class="layui-nav" lay-filter="rightNav">
                <li class="layui-nav-item"><a href="javascript:;" data-url="email.html" data-id='4' data-text="邮件系统"><i class="iconfont">&#xe603;</i></a></li>
                <li class="layui-nav-item">
                    <a href="javascript:;" id="info_me">{{request()->session()->get('user_info')->merchant_name}}</a>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;" id="password">修改密码</a>
                </li>
                <li class="layui-nav-item"><a href="{{url('admin/login/logout')}}">退出</a></li>
            </ul>
        </div>
        <!--主体内容-->
        <div class="main-layout-body">
            <!--tab 切换-->
            <div class="layui-tab layui-tab-brief main-layout-tab" lay-filter="tab" lay-allowClose="true">
                <ul class="layui-tab-title">
                    <li class="layui-this welcome">后台主页</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show" style="background: #f5f5f5;" id="merchant">


                         <!--1-->
                        <iframe src="" width="100%" height="100%" name="iframe" scrolling="auto" class="iframe" framborder="0">

                        </iframe>
                        <!--1end-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--遮罩-->
    <div class="main-mask">

    </div>
</div>

<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

        var scope={
            link:'./welcome.html'
        }

        {{--$(document).on('click','#info_me',function () {--}}
            {{--layer.open({--}}

                {{--type:2,--}}

                {{--title:"修改个人信息",--}}

                {{--area: ['800px', '600px'],--}}

                {{--closeBtn: 0,--}}

                {{--shadeClose: true,--}}

                {{--content: "{{url('admin/index/information')}}"--}}

            {{--});--}}
        {{--});--}}

        $(document).on('click','#password',function () {
             index =layer.open({

                type:1,

                title:"修改密码",

                area: ['500px','300px'],

                closeBtn: 0,

                shadeClose: true,

                content: '<form action="{{url('admin/index/password')}}" class="layui-form" method = "post" >{{csrf_field()}}<div class="layui-form-item"><label class="layui-form-label">用户名</label><div class="layui-input-block"><input type="text" name="username" required  lay-verify="required" value="{{request()->session()->get('user_info')->merchant_name}}" autocomplete="off" class="layui-input" readonly style="width: 300px"></div></div><div class="layui-form-item"><label class="layui-form-label">旧密码</label><div class="layui-input-block"><input type="password" name="old_password" required  lay-verify="required" autocomplete="off" class="layui-input" style="width: 300px"></div></div><div class="layui-form-item"><label class="layui-form-label">新密码</label><div class="layui-input-block"><input type="password" name="password" required  lay-verify="required" autocomplete="off" class="layui-input" style="width: 300px"></div></div><div class="layui-form-item"> <div class="layui-input-block"> <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button> <button type="button" class="layui-btn layui-btn-primary" >重置</button> </div> </div></form>'

            });
        });
</script>
</body>
</html>

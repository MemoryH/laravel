<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from condorthemes.com/cleanzone/ by HTTrack Website Copier/3.x [XR&CO'2013], Mon, 31 Mar 2014 14:31:31 GMT -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/static/images/favicon.png">

    <title>Clean Zone</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:100' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>


    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" type="text/css" href="http://www.jq22.com/jquery/bootstrap-3.3.4.css">
    <link rel="stylesheet" type="text/css" href="http://www.jq22.com/jquery/font-awesome.4.6.0.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="/static/js/jquery.gritter/css/jquery.gritter.css" />

    {{--<link rel="stylesheet" type="text/css" href="/static/js/jquery.nanoscroller/nanoscroller.css" />--}}
    {{--<link rel="stylesheet" type="text/css" href="/static/js/jquery.easypiechart/jquery.easy-pie-chart.css" />--}}
    {{--<link rel="stylesheet" type="text/css" href="/static/js/bootstrap.switch/bootstrap-switch.css" />--}}
    {{--<link rel="stylesheet" type="text/css" href="/static/js/bootstrap.datetimepicker/css/bootstrap-datetimepicker.min.css" />--}}
    {{--<link rel="stylesheet" type="text/css" href="/static/js/jquery.select2/select2.css" />--}}
    {{--<link rel="stylesheet" type="text/css" href="/static/js/bootstrap.slider/css/slider.css" />--}}
    {{--<link rel="stylesheet" type="text/css" href="/static/js/intro.js/introjs.css" />--}}
    <!-- Custom styles for this template -->
    <link href="/static/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="/static/webuploader/webuploader.css">

</head>
<body>

<!-- Fixed navbar -->
<div id="head-nav" class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="fa fa-gear"></span>
            </button>
            <a class="navbar-brand" href="#"><span>后台管理系统</span></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="{{url('admin/index/index')}}">首页</a></li>
                <li><a href="{{url('admin/login/register')}}" id="add_user">添加企业用户</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right user-nav">
                <li class="dropdown profile_menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img alt="Avatar" src="/static/images/avatar2.jpg" /><span>{{request()->session()->get('user_info')->name}}</span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">

                        <li><a href="{{url('admin/login/logout')}}">退出登录</a></li>
                    </ul>
                </li>
            </ul>

        </div><!--/.nav-collapse animate-collapse -->
    </div>
</div>

<div id="cl-wrapper" class="fixed-menu">
    <div class="cl-sidebar" data-position="right" data-step="1" data-intro="<strong>Fixed Sidebar</strong> <br/> It adjust to your needs." >
        <div class="cl-toggle"><i class="fa fa-bars"></i></div>
        <div class="cl-navblock">
            <div class="menu-space">
                <div class="content">
                    {{--个人信息--}}
                    <div class="side-user">
                        <div class="avatar"><img src="/static/images/avatar1_50.jpg" alt="Avatar" /></div>
                        <div class="info">
                            <a href="#">{{request()->session()->get('user_info')->name}}</a>
                            <img src="/static/images/state_online.png" alt="Status" /> <span>Online</span>
                        </div>
                    </div>

                    <ul class="cl-vnavigation">
                        <li><a href="#"><i class="fa fa-home"></i><span>订单管理</span></a>
                            <ul class="sub-menu">
                                <li class="active"><a href="javascript:;" id="order">订单列表</a></li>
                                {{--<li><a href="dashboard2.html"><span class="label label-primary pull-right">New</span> Version 2</a></li>--}}
                            </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-home"></i><span>商户管理</span></a>
                            <ul class="sub-menu">
                                <li class=""><a href="javascript:;" id="merchant">商户列表</a></li>
                                <li class=""><a href="javascript:;" id="add_merchant">新增商户</a></li>
                                {{--<li><a href="dashboard2.html"><span class="label label-primary pull-right">New</span> Version 2</a></li>--}}
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>

        </div>
    </div>
    {{--内容部分--}}
    <div class="container-fluid" id="pcont">
        <div class="cl-mcont">

            <form action="{{url('admin/merchant/add')}}" style="margin-bottom: 0px ;" class="form-horizontal" parsley-validate novalidate id="merchant_form">
                <div class="form-group"><label for="inputEmail3" class="col-sm-2 control-label">商户名称</label>
                    <div class="col-sm-10"><input type="text" class="col-sm-6" name="merchant_name" placeholder="merchant_name">
                    </div>
                </div>
                <div class="form-group"><label for="inputPassword3" class="col-sm-2 control-label">行业类型</label>
                    <div class="col-sm-10">个人:<input type="radio" name="type" value=0> 企业:<input type="radio" name="type" value=1></div>
                </div>
                <div class="form-group"><label for="inputPassword3" class="col-sm-2 control-label">所属区域</label>
                    <div class="col-sm-10"><input type="text" class="col-sm-6" name="regio"
                                                  placeholder="regio"></div>
                </div>
                <div class="form-group"><label for="inputPassword3" class="col-sm-2 control-label">联系人</label>
                    <div class="col-sm-10"><input type="text" class="col-sm-6" name="contacts" placeholder="contacts">
                    </div>
                </div>
                <div class="form-group"><label for="inputPassword3" class="col-sm-2 control-label">联系电话</label>
                    <div class="col-sm-10"><input type="number" class="col-sm-6" name="contacts_number"
                                                  placeholder="contacts_number"></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button class="btn btn-primary" data-dismiss="modal" type="submit" id="add">新增</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="http://www.jq22.com/jquery/jquery-1.10.2.js"></script>
<script src="/static/webuploader/webuploader.js"></script>
<script type="text/javascript" src="/static/js/jquery.gritter/js/jquery.gritter.js"></script>

<script type="text/javascript" src="/static/js/jquery.nanoscroller/jquery.nanoscroller.js"></script>
<script type="text/javascript" src="/static/js/behaviour/general.js"></script>
<script src="/static/js/jquery.ui/jquery-ui.js" type="text/javascript"></script>
<script type="text/javascript" src="/static/js/jquery.sparkline/jquery.sparkline.min.js"></script>
<script type="text/javascript" src="/static/js/jquery.easypiechart/jquery.easy-pie-chart.js"></script>
<script type="text/javascript" src="/static/js/jquery.nestable/jquery.nestable.js"></script>
<script type="text/javascript" src="/static/js/bootstrap.switch/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="/static/js/bootstrap.datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="/static/js/jquery.select2/select2.min.js" type="text/javascript"></script>
<script src="/static/js/skycons/skycons.js" type="text/javascript"></script>
<script src="/static/js/bootstrap.slider/js/bootstrap-slider.js" type="text/javascript"></script>
<script src="/static/js/intro.js/intro.js" type="text/javascript"></script>



<script type="text/javascript">

    var uploader = WebUploader.create({

        // 选完文件后，是否自动上传。
        auto: true,

        // swf文件路径
        swf: '/static/Uploader.swf',

        // 文件接收服务端。
        server: 'http://webuploader.duapp.com/server/fileupload.php',

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#filePicker',

        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        }
    });


    $(document).ready(function(){
        //initialize the javascript
        App.init();
        App.dashBoard();
//          introJs().setOption('showBullets', false).start();
    });

    //生成订单页面
    $('#order').click(function () {
        $.get('{{url('admin/order/index')}}','',function (res) {
            html = '';
            $.each(res,function (i,v) {
                html = '<tr class="odd gradeX"><td>'+v.id+'</td><td>'+v.order_sn+'</td><td>'+v.order_status+'</td><td class="center">'+v.total_money+'</td><td class="center">'+v.pay_time+'</td><td class="center">'+v.pay_name+'</td><td class="center"><a href="{{url('admin/order/intro?id='."v.id")}}">查看</a></td></tr>'
            })

            $('#pcont').html('<table class="table table-bordered" id="datatable" ><thead><tr><th>id</th><th>订单号</th><th>订单状态</th><th>订单金额</th><th>支付时间</th><th>支付方式</th><th>操作</th></tr></thead><tbody>'+html+'</tbody></table>')
        },'json')
    })

    //生成商户列表页面
    $(document).on('click','#merchant',function () {

        $.get('{{url('admin/merchant/index')}}','',function (res) {
            html='';

            $.each(res,function (i,v) {

                html = '<tr class="odd gradeX"><td>'+v.merchant_name+'</td><td class="center">'+v.regio+'</td><td class="center">'+v.contacts+'</td><td class="center">'+v.contacts_number+'</td><td class="center">'+v.charges+'</td><td class="center">'+v.update_time+'</td><td class="center">'+v.counts+'</td><td class="center"><a href="{{url('admin/order/intro?id='."v.id")}}">查看员工</a> <a href="{{url('admin/order/intro?id='."v.id")}}">编辑</a> <a href="{{url('admin/order/intro?id='."v.id")}}">删除</a></td></tr>'
            })

            $('#pcont').html('<table class="table table-bordered" id="datatable" ><thead><tr><th>商户名称</th><th>所属地区</th><th>联系人</th><th>联系电话</th><th>管理员</th><th>操作时间</th><th>员工人数</th><th>操作</th></tr></thead><tbody>'+html+'</tbody></table>')
        },'json')
    })


</script>

<!-- Bootstrap core JavaScript
  ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script src="/static/js/behaviour/voice-commands.js"></script>
<script src="/static/js/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/static/js/jquery.flot/jquery.flot.js"></script>
<script type="text/javascript" src="/static/js/jquery.flot/jquery.flot.pie.js"></script>
<script type="text/javascript" src="/static/js/jquery.flot/jquery.flot.resize.js"></script>
<script type="text/javascript" src="/static/js/jquery.flot/jquery.flot.labels.js"></script>
</body>

<!-- Mirrored from condorthemes.com/cleanzone/ by HTTrack Website Copier/3.x [XR&CO'2013], Mon, 31 Mar 2014 14:32:27 GMT -->
</html>

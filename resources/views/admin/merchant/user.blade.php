<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>网站后台管理模版</title>
    <link rel="stylesheet" type="text/css" href="/static/admin/layui/css/layui.css"/>
    <link rel="stylesheet" type="text/css" href="/static/admin/css/admin.css"/>

</head>
<body>
<form action="">
<table class="layui-table">
    <colgroup>
        <col width="150">
        <col width="200">
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>真实姓名</th>
        <th>会员类型</th>
        <th>联系电话</th>
        <th>身份证号</th>
        <th>电子邮件</th>
        <th>紧急联系人</th>
        <th>紧急联系人电话</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr user_id="{{$user->id}}" merchant_id="{{$merchant_id}}">
            <td>{{$user->true_name}}</td>
            <td>{{$user->type==1?'个人':($user->type==2?'企业':'代理')}}</td>
            <td>{{$user->phone}}</td>
            <td>{{$user->id_card}}</td>
            <td>{{$user->e_mail}}</td>
            <td>{{$user->em_contact}}</td>
            <td>{{$user->em_phone}}</td>
            <td>
                @if(empty($relationship))
                <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn user">绑定</a>
                    @else
                    <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn layui-btn-disabled">已绑定</a>
                @endif

            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</form>
<div class="main-mask">

</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

$(document).on('click','.user',function () {
    var user_id = $(this).closest('tr').attr('user_id');
    var merchant_id = $(this).closest('tr').attr('merchant_id');
    $.post('{{url('admin/merchant/user')}}',{user_id:user_id,merchant_id:merchant_id},function (res) {
        location.reload(true)
    })

})

</script>
</body>
</html>
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
<form action="" class="layui-form">
    <div class="layui-form-item">
        <div class="" style="width: 200px;float: left">
            <input type="text" name="phone" placeholder="请输入会员账号" value="{{!empty(request()->get('phone'))?request()->get('phone'):''}}" autocomplete="off" class="layui-input">
        </div>
        <div class="" style="width: 200px;float: left">
            <input type="text" name="nick_name" placeholder="请输入会员昵称" autocomplete="off" value="{{!empty(request()->get('nick_name'))?request()->get('nick_name'):''}}" class="layui-input">
        </div>
        <div class="" style="width: 200px;float: left">
            <input type="text" name="e_mail" placeholder="请输入会员邮箱" autocomplete="off" value="{{!empty(request()->get('e_mail'))?request()->get('e_mail'):''}}" class="layui-input">
        </div>
        <div class="" style="width: 200px;float: left">
            <button class="layui-btn layui-btn-normal go-btn">搜索</button>
        </div>
    </div>


</form>
<table class="layui-table">
    <colgroup>
        <col width="150">
        <col width="200">
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>会员账号</th>
        <th>真实姓名</th>
        <th>会员类型</th>
        <th>身份证号</th>
        <th>电子邮件</th>
        <th>紧急联系人</th>
        <th>紧急联系人电话</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr user_id="{{$user->id}}">
            <td>{{$user->phone}}</td>
            <td>{{$user->true_name}}</td>
            <td>{{$user->type==1?'个人':($user->type==2?'企业':'代理')}}</td>
            <td>{{$user->id_card}}</td>
            <td>{{$user->e_mail}}</td>
            <td>{{$user->em_contact}}</td>
            <td>{{$user->em_phone}}</td>
            <td>
                <a href="javascript:;" id="user_edit" class="layui-btn layui-btn-small layui-btn-normal go-btn"><i class="layui-icon"></i>编辑</a>
                <a href="javascript:;" class="del layui-btn layui-btn-small layui-btn-danger del-btn">删除</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{$users->appends(['phone'=>$users->phone,'nick_name'=>$users->nick_name,'e_mail'=>$users->e_mail])->render()}}
<div class="main-mask">

</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    //    var scope={
    //        link:'./welcome.html'
    //    }
    $(document).on('click','.del',function () {
        var tr = $(this).closest('tr')
        var id = $(this).closest('tr').attr('user_id');
        $data = {
            'id':id
        };
        layer.msg('你确定删除吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/user/del')}}",$data,function (res) {

                    if(res){

                        tr.remove();
                    }
                })
                layer.msg('删除成功', {
                    icon: 6
                    ,btn: ['确定']
                });
            }
        });


    });
    $(document).on('click','#user_edit',function () {
        var user_id = $(this).closest('tr').attr('user_id');
        // console.log(merchant_id);

        index =layer.open({

            type:2,

            title:"修改密码",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },

            area: ['100%','100%'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'edit?id='+user_id,



        });


    });

</script>
</body>
</html>
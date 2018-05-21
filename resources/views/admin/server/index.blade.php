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
<table class="layui-table">
    <colgroup>
        <col width="150">
        <col width="200">
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>创建用户</th>
        <th>服务名称</th>
        <th>隶属服务</th>
        <th>服务简介</th>
        <th>服务状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        @foreach($servers as $server)
            <tr server_id="{{$server->id}}">
                <td>{{$server->user_id}}</td>
                <td>{{$server->server_name}}</td>
                <td>{{$server->server_pid}}</td>
                <td>{{$server->server_desc}}</td>
                <td>{{$server->server_status}}</td>
                <td>
                    <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn server_edit " >编辑</a>
                    <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn bind_template " >绑定模板</a>
                    <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn set_table " >设置表名</a>
                    <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn create_table " >生成数据表</a>
                    <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn ">删除</a>

                </td>
            </tr>
            @endforeach
    </tbody>
</table>
{{$servers->render()}}
<div class="main-mask">

</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    //修改服务
    $(document).on('click','.server_edit',function () {
        var server_id = $(this).closest('tr').attr('server_id');
        // console.log(merchant_id);

        index = layer.open({

            type: 2,

            title: "修改服务",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['100%', '100%'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'edit?server_id='+server_id


        });
    });

    //绑定数据表
    $(document).on('click','.bind_template',function () {
        var server_id = $(this).closest('tr').attr('server_id');
        // console.log(merchant_id);

        index = layer.open({

            type: 2,

            title: "绑定模板",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['100%', '100%'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'bind_template?server_id='+server_id


        });
    });

    //生成数据表
    $(document).on('click','.create_table',function () {
        var server_id = $(this).closest('tr').attr('server_id');
        $data = {
            'server_id':server_id
        };
        layer.msg('你确定创建吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/server/create_table')}}",$data,function (res) {

                })
                layer.msg('创建成功', {
                    icon: 6
                    ,btn: ['确定']
                });
            }
        });


    });
    //设置表名
    $(document).on('click','.set_table',function () {
        var server_id = $(this).closest('tr').attr('server_id');
        // console.log(merchant_id);

        index = layer.open({

            type: 2,

            title: "设置表名",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['600px', '300px'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'set_table?server_id='+server_id


        });
    });
</script>
</body>
</html>
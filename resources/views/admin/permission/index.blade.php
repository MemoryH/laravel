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
<table id="tags-table" class="layui-table">
    <thead>
    <tr>
        <th>权限规则</th>
        <th>权限名称</th>
        <th>权限概述</th>
        <th>权限创建日期</th>
        <th>权限修改日期</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($permissions as $permission)
        <tr permission_id="{{$permission->id}}">
            <td>{{$permission->name}}</td>
            <td>{{$permission->display_name}}</td>
            <td>{{$permission->description}}</td>
            <td>{{$permission->created_at}}</td>
            <td>{{$permission->updated_at}}</td>
            <td>
                <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn permission_edit" >编辑</a>
                <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn permission_del">删除</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{$permissions->render()}}
<div class="main-mask">

</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    //编辑权限
    $(document).on('click','.permission_edit',function () {
        var permission_id = $(this).closest('tr').attr('permission_id');
        // console.log(merchant_id);

        index = layer.open({

            type: 2,

            title: "编辑权限",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['800px', '600px'],

            closeBtn: 0,
            // maxmin:true,

//            shadeClose: true,

            content: 'edit?permission_id='+permission_id


        });
    });
    //删除权限
    $(document).on('click','.permission_del',function () {
        var tr = $(this).closest('tr')
        var id = $(this).closest('tr').attr('permission_id');
        $data = {
            'id':id
        };
        layer.msg('你确定删除吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/permission/del')}}",$data,function (res) {

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
</script>
</body>
</html>
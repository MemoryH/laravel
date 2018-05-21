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
        <th>模型名称</th>
        <th>模型类别</th>
        <th>模型字段名</th>
        <th>模型字段业务别名</th>
        <th>模型字段状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        @foreach($fields as $field)
        <tr field_id="{{$field->id}}">
            <td>{{$field->module_name}}</td>
            <td>{{$field->module_category}}</td>
            <td>{{$field->module_field}}</td>
            <td>{{$field->module_field_alias}}</td>
            <td>{{$field->module_field_status}}</td>
            <td>
                <button href="javascript:;" class="del layui-btn layui-btn-small layui-btn-danger del-btn field_del"><i class="layui-icon"></i>删除</button>
                <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn field_edit"><i class="layui-icon"></i>修改</a>
            </td>
        </tr>
            @endforeach
    </tbody>
</table>
{{$fields->render()}}
<div class="main-mask">

</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    //修改模板
    $(document).on('click','.field_edit',function () {
        var field_id = $(this).closest('tr').attr('field_id');
        // console.log(merchant_id);

        index = layer.open({

            type: 2,

            title: "修改字段模型",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['100%', '100%'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'field_edit?field_id='+field_id


        });
    });
    //删除字段模板
    $(document).on('click','.field_del',function () {
        var tr = $(this).closest('tr')
        var id = $(this).closest('tr').attr('field_id');
        $data = {
            'id':id
        };
        layer.msg('你确定删除吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/template/field_del')}}",$data,function (res) {

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
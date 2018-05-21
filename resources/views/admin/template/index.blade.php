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
        <th>模板名称</th>
        <th>模板js内容</th>
        <th>模板HTML</th>
        <th>模板状态</th>
        <th>模板备注</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($templates as $template)
        <tr template_id="{{$template->id}}">
            <td>{{$template->template_name}}</td>
            <td>{{$template->template_js}}</td>
            <td>{{$template->template_html}}</td>
            <td>{{$template->template_status}}</td>
            <td>{{$template->template_remark}}</td>
            <td>
                <button href="javascript:;" class="del layui-btn layui-btn-small layui-btn-danger del-btn template_del"><i class="layui-icon"></i>删除</button>
                <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn template_edit"><i class="layui-icon"></i>修改</a>
                <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn bind_field">绑定字段</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{$templates->render()}}
<div class="main-mask">

</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
//修改模板
$(document).on('click','.template_edit',function () {
        var template_id = $(this).closest('tr').attr('template_id');
        // console.log(merchant_id);

        index = layer.open({

            type: 2,

            title: "修改模板",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['100%', '100%'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'edit?template_id='+template_id


        });
    });
//绑定字段
$(document).on('click','.bind_field',function () {
    var template_id = $(this).closest('tr').attr('template_id');
    // console.log(merchant_id);

    index = layer.open({

        type: 2,

        title: "绑定字段",
        btn:['关闭'],
        yes:function(index,layero){
            layer.closeAll('iframe')
        },
        area: ['100%', '100%'],

        closeBtn: 0,
        // maxmin:true,

        shadeClose: true,

        content: 'bind_field?template_id='+template_id


    });
});
//删除模板
$(document).on('click','.template_del',function () {
    var tr = $(this).closest('tr')
    var id = $(this).closest('tr').attr('template_id');
    $data = {
        'id':id
    };
    layer.msg('你确定删除吗？', {
        time: 0 //不自动关闭
        ,btn: ['确认', '取消']
        ,yes: function(index){
            layer.close(index);
            $.get("{{url('admin/template/del')}}",$data,function (res) {

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
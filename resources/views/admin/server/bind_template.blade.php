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
        <tr template_id="{{$template->id}}" server_id="{{request()->get('server_id')}}">
            <td>{{$template->template_name}}</td>
            <td>{{$template->template_js}}</td>
            <td>{{$template->template_html}}</td>
            <td>{{$template->template_status}}</td>
            <td>{{$template->template_remark}}</td>
            <td>
                @if(!in_array($template->id,$template_ids))
                    <button href="javascript:;" class="del layui-btn layui-btn-small layui-btn-danger del-btn bind_template">绑定</button>
                @else
                    <button href="javascript:;" class="del layui-btn layui-btn-small layui-btn-danger del-btn layui-btn-disabled">已绑定</button>
                    @endif
                {{--<a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn template_edit"><i class="layui-icon"></i>修改</a>--}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{$templates->appends(['server_id'=>request()->get('server_id')])->render()}}
<div class="main-mask">

</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $(document).on('click','.bind_template',function () {
        var template_id = $(this).closest('tr').attr('template_id');
        var server_id = $(this).closest('tr').attr('server_id');

        index =layer.open({

            type:1,

            title:"绑定选项",

            area: ['500px','300px'],

            closeBtn: 0,

            shadeClose: true,

            content: '<form action="{{url('admin/server/bind_template')}}" class="layui-form" method = "post" >{{csrf_field()}}<div class="layui-form-item"><label class="layui-form-label">模板排序序号</label><div class="layui-input-block"><input type="text" name="template_order" required  lay-verify="required" autocomplete="off" class="layui-input" style="width: 300px"></div></div><div class="layui-form-item"><label class="layui-form-label">模板组名称</label><div class="layui-input-block"><input type="text" name="template_group_name" required  lay-verify="required" autocomplete="off" class="layui-input" style="width: 300px"></div></div><div class="layui-form-item"><label class="layui-form-label">模板组序号</label><div class="layui-input-block"><input type="hidden" name="server_id" value="'+server_id+'"><input type="hidden" name="template_id" value="'+template_id+'"><input type="text" name="template_group_order" required  lay-verify="required" autocomplete="off" class="layui-input" style="width: 300px"></div></div><div class="layui-form-item"> <div class="layui-input-block"> <button class="layui-btn" lay-submit lay-filter="formDemo">绑定</button> </div> </div></form>'

        });
    });
//绑定

</script>
</body>
</html>
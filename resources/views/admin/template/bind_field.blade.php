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
        <tr field_id="{{$field->id}}" template_id="{{request()->get('template_id')}}">
            <td>{{$field->module_name}}</td>
            <td>{{$field->module_category}}</td>
            <td>{{$field->module_field}}</td>
            <td>{{$field->module_field_alias}}</td>
            <td>{{$field->module_field_status}}</td>
            <td>

                @if(!in_array($field->id,$fields_ids))
                    <button href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn bind_field">绑定</button>
                @else
                    <button href="javascript:;" class="del layui-btn layui-btn-small layui-btn-danger del-btn layui-btn-disabled">已绑定</button>
                @endif
            </td>
        </tr>
            @endforeach
    </tbody>
</table>
{{$fields->appends(['template_id'=>request()->get('template_id')])->render()}}
<div class="main-mask">

</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $(document).on('click','.bind_field',function () {
        var field_id = $(this).closest('tr').attr('field_id');
        var template_id = $(this).closest('tr').attr('template_id');
        console.log(template_id);
        $.post('{{url('admin/template/bind_field')}}',{template_id:template_id,field_id:field_id},function (res) {
            location.reload(true)
        })

    })
</script>
</body>
</html>
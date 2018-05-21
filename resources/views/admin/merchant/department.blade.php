<!-- 更多资源下载请加QQ群：304104682 -->
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
@if (session('status'))

    <div class="tools-alert tools-alert-green">
        {{ session('status') }}
    </div>
@endif
<a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn department_add" merchant_id="{{request()->get('merchant_id')}}">添加部门</a>
<table class="layui-table">
    <colgroup>
        <col width="150">
        <col width="200">
        <col>
    </colgroup>
    <thead>
    <tr>

        <th>部门id</th>
        <th>部门名称</th>
        <th>部门描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($departments as $department)
    <tr department_id="{{$department->id}}">
        <td>{{$department->id}}</td>
        <td>{{$department->name}}</td>
        <td>{{$department->intro}}</td>
        <td>
            <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn department_show" merchant_id="{{request()->get('merchant_id')}}">查看员工</a>
            <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn department_edit" merchant_id="{{request()->get('merchant_id')}}">编辑</a>
            <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn del" merchant_id="{{request()->get('merchant_id')}}">删除</a>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>

<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
{{--<script src="/js/data.js" type="text/javascript" charset="utf-8"></script>--}}
<script src="/js/province.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    //添加部门
    $(document).on('click','.department_add',function () {
        // console.log(merchant_id);
        var merchant_id = $(this).attr('merchant_id');
        index =layer.open({

            type:2,

            title:"添加部门",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['100%','100%'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'department_add?merchant_id='+merchant_id


        });


    });
    //删除部门
    $(document).on('click','.del',function () {
        var tr = $(this).closest('tr')
        var id = $(this).closest('tr').attr('department_id');
        $data = {
            'id':id
        };
        layer.msg('你确定删除吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/merchant/department_del')}}",$data,function (res) {

                    if(res =='success'){

                        tr.remove();
                        layer.msg('删除成功', {
                            icon: 6
                            ,btn: ['确定']
                        });
                    }else {
//                        alert('部门下有子部门无法删除');
                        layer.msg('部门下有子部门无法删除', {
                            icon: 6
                            ,btn: ['确定']
                        });

                    }
                })

            }
        });


    });
    //修改部门信息
    $(document).on('click','.department_edit',function () {
        // console.log(merchant_id);
        var merchant_id = $(this).attr('merchant_id');
        var department_id = $(this).closest('tr').attr('department_id');

        index =layer.open({

            type:2,

            title:"修改部门信息",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['100%','100%'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'department_edit?merchant_id='+merchant_id+'&department_id='+department_id


        });


    });
    //查看员工
    $(document).on('click','.department_show',function () {
        // console.log(merchant_id);
        var merchant_id = $(this).attr('merchant_id');
        var department_id = $(this).closest('tr').attr('department_id')
        index =layer.open({

            type:2,

            title:"部门员工",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['100%','100%'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'department_show?merchant_id='+merchant_id+'&department_id='+department_id


        });


    });

</script>
</body>
</html>

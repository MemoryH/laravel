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
<a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn add_group">添加组</a>
<table class="layui-table">
    <colgroup>
        <col width="150">
        <col width="200">
        <col>
    </colgroup>
    <thead>
    <tr>

        <th>组id</th>
        <th>组名称</th>
        <th>组描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($groups as $group)
    <tr group_id="{{$group->id}}">
        <td>{{$group->id}}</td>
        <td>{{$group->name}}</td>
        <td>{{$group->intro}}</td>
        <td>
            <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn group_edit">编辑</a>
            <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn del">删除</a>
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
    //添加组
    $(document).on('click','.add_group',function () {
        index =layer.open({

            type:2,

            title:"添加组",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['100%','100%'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'add'


        });


    });
    //删除组
    $(document).on('click','.del',function () {
        var tr = $(this).closest('tr')
        var id = $(this).closest('tr').attr('group_id');
        $data = {
            'id':id
        };
        layer.msg('你确定删除吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/group/del')}}",$data,function (res) {

                    if(res =='success'){

                        tr.remove();
                        layer.msg('删除成功', {
                            icon: 6
                            ,btn: ['确定']
                        });
                    }else {
//                        alert('部门下有子部门无法删除');
                        layer.msg('组下面有子组或已是根目录无法删除', {
                            icon: 6
                            ,btn: ['确定']
                        });

                    }
                })

            }
        });


    });
    //修改组信息
    $(document).on('click','.group_edit',function () {
        // console.log(merchant_id);
        var group_id = $(this).closest('tr').attr('group_id');

        index =layer.open({

            type:2,

            title:"修改组信息",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['100%','100%'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'edit?group_id='+group_id


        });


    });


</script>
</body>
</html>

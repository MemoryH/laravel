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
        <th>公司名称</th>
        <th>机制名称</th>
        <th>所属区域</th>
        <th>分润金额/比例</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        @foreach($bonuses as $bonus)
        <tr bonus_id="{{$bonus->id}}">
            <td>{{$bonus->merchant_id}}</td>
            <td>{{$bonus->bonus_name}}</td>
            <td>{{$bonus->province .$bonus->city}}</td>
            <td>{{$bonus->proportion}}</td>
            <td>
                <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn bonus_edit" >编辑</a>
                <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn bonus_del">删除</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="main-mask">

</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    //编辑权限
    $(document).on('click','.bonus_edit',function () {
        var bonus_id  = $(this).closest('tr').attr('bonus_id');
        // console.log(merchant_id);

        index = layer.open({

            type: 2,

            title: "编辑权限",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['1000px', '800px'],

            closeBtn: 0,
            // maxmin:true,

//            shadeClose: true,

            content: 'edit?bonus_id='+bonus_id


        });
    });

    //删除
    $(document).on('click','.bonus_del',function () {
        var tr = $(this).closest('tr');
        var bonus_id = $(this).closest('tr').attr('bonus_id');
        $data = {
            'bonus_id':bonus_id
        };
        layer.msg('你确定删除吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/bonus/del')}}",$data,function (res) {

                    if(res=='success'){
                        tr.remove();
                        layer.msg('删除成功', {
                            icon: 6
                            ,btn: ['确定']
                        });
                    }else {
                        layer.msg('删除失败', {
                            icon: 6
                            ,btn: ['确定']
                        });
                    }
                })

            }
        });


    });
</script>
</body>
</html>
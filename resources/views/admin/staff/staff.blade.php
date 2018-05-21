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
<a href="{{url('admin/staff/add?merchant_id='.$merchant_id)}}" id="staff_add" class="layui-btn">新增员工</a>
<table class="layui-table">
    <colgroup>
        <col width="150">
        <col width="200">
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>用户名</th>
        <th>所属区域</th>
        <th>所属组织</th>
        <th>所属角色</th>
        <th>联系电话</th>
        <th>分润比例</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($staffs as $staff)
        <tr class="odd gradeX" staff_id="{{$staff->id}}">
            <td>{{$staff->staff_name}}</td>
            <td>{{$staff->city}}</td>
            <td>{{$merchant_name}}</td>
            <td>{{$staff->position}}</td>
            <td>{{$staff->contacts_number}}</td>
            <td>{{$staff->bonus}}</td>
            <td class="center">
                {{--<a href="{{url("admin/Staff/Staff?id=$re->id")}}">查看详细信息</a>--}}
                <button href="javascript:;" class="del layui-btn layui-btn-small layui-btn-danger del-btn"><i class="layui-icon"></i>删除</button>
                <a href="javascript:;" id="staff_edit" class="layui-btn layui-btn-small layui-btn-normal go-btn"><i class="layui-icon"></i>修改</a>
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


    //    var scope={
    //        link:'./welcome.html'
    //    }
    $(document).on('click','.del',function () {
        var tr = $(this).closest('tr')
        var id = $(this).closest('tr').attr('staff_id');
        $data = {
            'id':id
        };
        layer.msg('你确定删除吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/staff/del')}}",$data,function (res) {

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

    $(document).on('click','#staff_edit',function () {
        var staff_id = $(this).closest('tr').attr('staff_id');
        var merchant_id = '{{$merchant_id}}'
        // console.log(merchant_id);

        index = layer.open({

            type: 2,

            title: "编辑员工信息",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['100%', '100%'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'edit?staff_id=' + staff_id+'&merchant_id='+merchant_id


        });
    });

    $(document).on('click','#staff_add',function () {
        var merchant_id = '{{$merchant_id}}';
        // console.log(merchant_id);

        index = layer.open({

            type: 2,

            title: "添加员工",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['100%', '100%'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'add?merchant_id=' + merchant_id


        });
    });

</script>
</body>
</html>
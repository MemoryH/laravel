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
<a  class="layui-btn layui-btn-small layui-btn-normal go-btn add_adsense">添加广告位</a>
<table id="tags-table" class="layui-table">
    <thead>
    <tr>
        <th>广告位ID</th>
        <th>广告位名称</th>
        <th>广告位标签</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($adsenses as $adsense)
    <tr adsense_id="{{$adsense->id}}">
        <td>{{$adsense->id}}</td>
        <td>{{$adsense->position_name}}</td>
        <td>{{$adsense->position_label}}</td>
        <td>
            <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn show_ad" >查看置放广告</a>
            <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn edit_adsense" >编辑</a>
            <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn del_adsense">删除</a>
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
    $(document).on('click','.add_adsense',function () {
        // console.log(merchant_id);

        index = layer.open({

            type: 2,

            title: "添加广告位",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['600px', '400px'],

            closeBtn: 0,
            // maxmin:true,

//            shadeClose: true,

            content: 'add'


        });
    });
    $(document).on('click','.edit_adsense',function () {
        // console.log(merchant_id);
var adsense_id=$(this).closest('tr').attr('adsense_id');
        index = layer.open({

            type: 2,

            title: "编辑广告位",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['600px', '400px'],

            closeBtn: 0,
            // maxmin:true,

//            shadeClose: true,

            content: 'edit?adsense_id='+adsense_id


        });
    });
    //删除广告位
    $(document).on('click','.del_adsense',function () {
        var tr = $(this).closest('tr')
        var id = $(this).closest('tr').attr('adsense_id');
        $data = {
            'id':id
        };
        layer.msg('你确定删除吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/adsense/del')}}",$data,function (res) {

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
    //查看广告
    $(document).on('click','.show_ad',function () {
        // console.log(merchant_id);
        var adsense_id=$(this).closest('tr').attr('adsense_id');
        index = layer.open({

            type: 2,

            title: "广告列表",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['100%', '100%'],

            closeBtn: 0,
            // maxmin:true,

//            shadeClose: true,

            content: 'show_ad?adsense_id='+adsense_id


        });
    });
</script>
</body>
</html>
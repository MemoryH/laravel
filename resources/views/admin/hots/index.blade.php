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
<div style="padding-left: 100px; width: 600px;float: left">
    <table class="layui-table">

        <thead>
        <tr>
            <th colspan="4" style="text-align: center;background-color: #00a0e9">后台热词</th>
        </tr>
        <tr>
            <th>热词级别</th>
            <th>热词名称</th>
            <th>热词类型</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($hots_words as $hots_word)
            <tr hots_id="{{$hots_word->id}}">
                <td>{{$hots_word->level}}</td>
                <td>{{$hots_word->name}}</td>
                <td>{{$hots_word->type}}</td>
                <td>
                    <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn edit_hots">编辑</a>
                    <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn del">删除</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <a  class="layui-btn layui-btn-small layui-btn-normal go-btn add_hots">添加热词</a>
</div>

<div style="padding-left: 100px; width: 600px;float: left">
    <table class="layui-table">
        <thead>
        <tr>
            <th colspan="3" style="text-align: center;background-color: #00a0e9">用户热搜词</th>
        </tr>
        <tr>
            <th>热词名称</th>
            <th>搜索次数</th>
            <th>热词类型</th>
        </tr>
        </thead>
        <tbody>
        @foreach($searchs as $search)
            <tr hots_id="{{$search->id}}">
                <td>{{$search->keyword}}</td>
                <td>{{$search->times}}</td>
                <td>{{$search->type}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


<div style="padding-left: 100px; width: 600px;float: left">
    <table class="layui-table">
        <thead>
        <tr>
            <th colspan="4" style="text-align: center;background-color: #00a0e9">热搜城市</th>
        </tr>
        <tr>
            <th>城市名称</th>
            <th>所属省</th>
            <th>热搜等级</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($citys as $city)
            <tr city_id="{{$city->id}}">
                <td>{{$city->city_name}}</td>
                <td>{{$city->pid_name}}</td>
                <td>{{$city->level}}</td>
                <td>
                    <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn edit_city">编辑</a>
                    <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn del_city">删除</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <a  class="layui-btn layui-btn-small layui-btn-normal go-btn add_city">添加城市</a>
</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $(document).on('click','.add_hots',function () {
        // console.log(merchant_id);

        index = layer.open({

            type: 2,

            title: "添加热词",
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

    $(document).on('click','.edit_hots',function () {
        // console.log(merchant_id);
        var hots_id=$(this).closest('tr').attr('hots_id')
        index = layer.open({

            type: 2,

            title: "编辑热词",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['600px', '400px'],

            closeBtn: 0,
            // maxmin:true,

//            shadeClose: true,

            content: 'edit?id='+hots_id


        });
    });

    //删除热词
    $(document).on('click','.del',function () {
        var tr = $(this).closest('tr')
        var id = $(this).closest('tr').attr('hots_id');
        $data = {
            'id':id
        };
        layer.msg('你确定删除吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/hots/del')}}",$data,function (res) {

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

    //添加热搜城市
    $(document).on('click','.add_city',function () {
        // console.log(merchant_id);

        index = layer.open({

            type: 2,

            title: "添加热词",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['600px', '400px'],

            closeBtn: 0,
            // maxmin:true,

//            shadeClose: true,

            content: 'add_city'


        });
    });
    //编辑热搜城市
    $(document).on('click','.edit_city',function () {
        // console.log(merchant_id);
var id = $(this).closest('tr').attr('city_id');
        index = layer.open({

            type: 2,

            title: "添加热词",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['600px', '400px'],

            closeBtn: 0,
            // maxmin:true,

//            shadeClose: true,

            content: 'edit_city?id='+id


        });
    });

    //删除热搜城市
    $(document).on('click','.del_city',function () {
        var tr = $(this).closest('tr')
        var id = $(this).closest('tr').attr('city_id');
        $data = {
            'id':id
        };
        layer.msg('你确定删除吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/hots/del_city')}}",$data,function (res) {

                    if(res =='success'){

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
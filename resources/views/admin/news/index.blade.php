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
        <th>消息ID</th>
        <th>消息标题</th>
        <th>消息内容</th>
        <th>消息类型</th>
        <th>操作</th>
    </tr>
    </thead>
    @foreach($news as $new)
        <tr new_id="{{$new->id}}" content="{{$new->content}}" title="{{$new->title}}">
            <td>{{$new->id}}</td>
            <td>{{$new->title}}</td>
            <td>{{$new->content}}</td>
            <td>{{$new->type==1?'系统消息':'个人消息'}}</td>
            <td>
                <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn push" >推送消息</a>
                <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn new_del">删除</a>
            </td>
        </tr>
    @endforeach
    <tbody>

    </tbody>
</table>

<div class="main-mask">

</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $(document).on('click','.new_del',function () {
    var tr = $(this).closest('tr')
    var id = $(this).closest('tr').attr('new_id');
    $data = {
        'id':id
    };
    layer.msg('你确定删除吗？', {
        time: 0 //不自动关闭
        ,btn: ['确认', '取消']
        ,yes: function(index){
            layer.close(index);
            $.get("{{url('admin/news/del')}}",$data,function (res) {

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

    $(document).on('click','.push',function () {
        console.log(111);
        var tr = $(this).closest('tr')
        var content = $(this).closest('tr').attr('content');
        var title = $(this).closest('tr').attr('title');
        $data = {
            'content':content,
            'title':title
        };
        layer.msg('你确定推送吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/news/send')}}",$data,function (res) {

                    if(res=='success'){
                        layer.msg('推送成功', {
                            icon: 6
                            ,btn: ['确定']
                        });
                    }else {
                        layer.msg('推送失败', {
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
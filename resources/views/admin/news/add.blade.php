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
    <link rel="stylesheet" href="/ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">

</head>
<body>
<form class="layui-form" action="{{empty($results)?url('admin/news/add'):url('admin/news/edit')}}" method="post" style="width: 90%;padding-top: 20px;">
    {{csrf_field()}}
    <div class="layui-form-item">
        <label class="layui-form-label">消息标题</label>
        <div class="layui-input-block">
            <input type="text" name="title" required  lay-verify="required" value="{{!empty($results)?$results->title:''}}" placeholder="请输入消息标题" autocomplete="off" class="layui-input">
            <input type="hidden" name="news_id" value="{{request()->get('news_id')}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">消息内容</label>
        <div class="layui-input-block">
            <textarea name="content" id="" cols="30" rows="10" required class="layui-textarea"></textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">消息类型</label>
        <div class="layui-input-block">
            <input type="radio" name="type" value="1" title="系统消息" checked>
            <input type="radio" name="type" value="2" title="个人消息">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
{{--<script src="/js/data.js" type="text/javascript" charset="utf-8"></script>--}}
{{--<script src="/js/province.js" type="text/javascript" charset="utf-8"></script>--}}
{{--<script type="text/javascript" src="/ztree/js/jquery.ztree.core.js"></script>--}}


<script>

</script>

</body>
</html>



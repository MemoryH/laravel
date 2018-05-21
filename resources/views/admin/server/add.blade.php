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
<form class="layui-form" action="{{empty($results)?url('admin/server/add'):url('admin/server/edit')}}" method="post" style="width: 90%;padding-top: 20px;">
    {{csrf_field()}}
    <div class="layui-form-item">
        <label class="layui-form-label">服务名称</label>
        <div class="layui-input-block">
            <input type="text" name="server_name" required  lay-verify="required" value="{{!empty($results)?$results->server_name:''}}" placeholder="请输入服务名称" autocomplete="off" class="layui-input">
            <input type="hidden" name="server_id" value="{{request()->get('server_id')}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">创建者</label>
        <div class="layui-input-block">
            <select name="user_id" lay-verify="required">
                <option value="">==选择商户==</option>
                @foreach($merchants as $merchant)
                <option value="{{$merchant->id}}" {{(!empty($results)&&$results->user_id==$merchant->id)?'selected':''}}>{{$merchant->merchant_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">隶属服务</label>
        <div class="layui-input-block">
            <select name="server_pid" lay-verify="required">
                <option value="0">==选择服务==</option>
                @foreach($servers as $server)
                <option value="{{$server->id}}" {{(!empty($results)&&$results->server_pid==$server->id)?'selected':''}}>{{$server->server_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">服务状态</label>
        <div class="layui-input-block">
            <input type="radio" name="server_status" value="1" title="正常" {{!empty($results)&&$results->server_status==1?'checked':''}}>
            <input type="radio" name="server_status" value="2" title="暂停" {{!empty($results)&&$results->server_status==2?'checked':''}}>
            <input type="radio" name="server_status" value="3" title="无效" {{!empty($results)&&$results->server_status==3?'checked':''}}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">服务简介</label>
        <div class="layui-input-block">
            <textarea name="server_desc" id="" cols="30" rows="10" class="layui-textarea">{{!empty($results)?$results->server_desc:''}}</textarea>
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



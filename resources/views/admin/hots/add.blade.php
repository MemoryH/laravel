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
<form class="layui-form" action="{{empty($hots_word)?url('admin/hots/add'):url('admin/hots/edit')}}" method="post" style="width: 90%;padding-top: 20px;">
    {{csrf_field()}}
    <div class="layui-form-item">
        <label class="layui-form-label">热词名称</label>
        <div class="layui-input-block">
            <input type="text" name="name" required  lay-verify="required" value="{{!empty($hots_word)?$hots_word->name:''}}" placeholder="请输入热词名称" autocomplete="off" class="layui-input">
            <input type="hidden" name="id" value="{{request()->get('id')}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">热词等级</label>
        <input type="hidden" name="id" value="{{request()->get('id')}}">
        <div class="layui-input-block">
            <select name="level" lay-verify="required">
                <option value="0" >==请选择热度等级==</option>
                <option value="1" {{!empty($hots_word)?($hots_word->level==1?'selected':''):''}}>一级热度</option>
                <option value="2" {{!empty($hots_word)?($hots_word->level==2?'selected':''):''}}>二级热度</option>
                <option value="3" {{!empty($hots_word)?($hots_word->level==3?'selected':''):''}}>三级热度</option>
                <option value="4" {{!empty($hots_word)?($hots_word->level==4?'selected':''):''}}>四级热度</option>
                <option value="5" {{!empty($hots_word)?($hots_word->level==5?'selected':''):''}}>五级热度</option>
            </select>
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
<script src="/js/province.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="/ztree/js/jquery.ztree.core.js"></script>


<script>

</script>

</body>
</html>



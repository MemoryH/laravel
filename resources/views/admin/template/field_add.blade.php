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
<form class="layui-form" action="{{empty($results)?url('admin/template/field_add'):url('admin/template/field_edit')}}" method="post" style="width: 90%;padding-top: 20px;">
    {{csrf_field()}}
    <div class="layui-form-item">
        <label class="layui-form-label">模型名称</label>
        <div class="layui-input-block">
            <input type="text" name="module_name" required  lay-verify="required" value="{{!empty($results)?$results->module_name:''}}" placeholder="请输入标题" autocomplete="off" class="layui-input">
            <input type="hidden" name="template_id" value="{{request()->get('field_id')}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">模型类型</label>
        <div class="layui-input-block">
            <input type="radio" name="module_category" value="1" title="个人" {{!empty($results)&&$results->module_category==1?'checked':''}}>
            <input type="radio" name="module_category" value="2" title="企业" {{!empty($results)&&$results->module_category==2?'checked':''}}>
            <input type="radio" name="module_category" value="3" title="通用" {{!empty($results)&&$results->module_category==3?'checked':''}}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">模型字段名</label>
        <div class="layui-input-block">
            <input type="text" name="module_field" required  lay-verify="required" value="{{!empty($results)?$results->module_field:''}}" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">模型字段别名</label>
        <div class="layui-input-block">
            <input type="text" name="module_field_alias" required  lay-verify="required" value="{{!empty($results)?$results->module_field_alias:''}}" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">模型状态</label>
        <div class="layui-input-block">
            <input type="radio" name="module_field_status" value="1" title="正常" {{!empty($results)&&$results->module_field_status==1?'checked':''}}>
            <input type="radio" name="module_field_status" value="2" title="无效" {{!empty($results)&&$results->module_field_status==2?'checked':''}}>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">数据类型</label>
        <div class="layui-input-block">
            <select name="type" id="" class="form-control">
                <option value="">==请选择字段类型==</option>
                <option {{!empty($type)?($type->type =="bigInteger"?'selected':''):''}} value="bigInteger">bigInteger</option>
                <option {{!empty($type)?($type->type =="binary"?'selected':''):''}} value="binary" >binary</option>
                <option {{!empty($type)?($type->type =="boolean"?'selected':''):''}} value="boolean" >boolean</option>
                <option {{!empty($type)?($type->type =="date"?'selected':''):''}} value="date" >date</option>
                <option {{!empty($type)?($type->type =="dateTime"?'selected':''):''}} value="dateTime" >dateTime</option>
                <option {{!empty($type)?($type->type =="dateTimeTz"?'selected':''):''}} value="dateTimeTz" >dateTimeTz</option>
                <option {{!empty($type)?($type->type =="float"?'selected':''):''}} value="float" >float</option>
                <option {{!empty($type)?($type->type =="integer"?'selected':''):''}} value="integer" >integer</option>
                <option {{!empty($type)?($type->type =="longText"?'selected':''):''}} value="longText" >longText</option>
                <option {{!empty($type)?($type->type =="mediumInteger"?'selected':''):''}} value="mediumInteger" >mediumInteger</option>
                <option {{!empty($type)?($type->type =="mediumText"?'selected':''):''}} value="mediumText" >mediumText</option>
                <option {{!empty($type)?($type->type =="smallInteger"?'selected':''):''}} value="smallInteger" >smallInteger</option>
                <option {{!empty($type)?($type->type =="text"?'selected':''):''}} value="text" >text</option>
                <option {{!empty($type)?($type->type =="time"?'selected':''):''}} value="time" >time</option>
                <option {{!empty($type)?($type->type =="timeTz"?'selected':''):''}} value="timeTz" >timeTz</option>
                <option {{!empty($type)?($type->type =="tinyInteger"?'selected':''):''}} value="tinyInteger" >tinyInteger</option>
                <option {{!empty($type)?($type->type =="timestamp"?'selected':''):''}} value="timestamp" >timestamp</option>
                <option {{!empty($type)?($type->type =="timestampTz"?'selected':''):''}} value="timestampTz" >timestampTz</option>
                <option {{!empty($type)?($type->type =="uuid"?'selected':''):''}} value="uuid" >uuid</option>
                <option {{!empty($type)?($type->type =="char"?'selected':''):''}} value="char" >char</option>
                <option {{!empty($type)?($type->type =="enum"?'selected':''):''}} value="enum" >enum</option>
                <option {{!empty($type)?($type->type =="string"?'selected':''):''}} value="string" >string</option>
                <option {{!empty($type)?($type->type =="decimal"?'selected':''):''}} value="decimal" >decimal</option>
                <option {{!empty($type)?($type->type =="double"?'selected':''):''}} value="double" >double</option>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">长度</label>
        <div class="layui-input-block">
            <input type="text" name="len" value="{{!empty($type)?$type->len:''}}" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">小数点</label>
        <div class="layui-input-block">
            <input type="number" name="decimal" required  lay-verify="required" value="{{!empty($type)?$type->decimal:''}}" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">默认值</label>
        <div class="layui-input-block">
            <input type="text" name="default" required  lay-verify="required" value="{{!empty($type)?$type->default:''}}" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">索引类型</label>
        <div class="layui-input-block">
            <input type="radio" name="index" value="1" title="普通索引" {{!empty($type->index)&&$type->index==1?'checked':''}}>
            <input type="radio" name="index" value="2" title="唯一索引" {{!empty($type->index)&&$type->index==2?'checked':''}}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">复合索引</label>
        <div class="layui-input-block">
            <input type="text" name="indexn" value="{{!empty($type->indexn)?$type->indexn:''}}" placeholder="请输入标题" autocomplete="off" class="layui-input">
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



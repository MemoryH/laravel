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
<form class="layui-form" action="{{empty($bonus)?url('admin/bonus/add'):url('admin/bonus/edit')}}" method="post" style="width: 90%;padding-top: 20px;">
    {{csrf_field()}}
    <div class="layui-form-item">
        <label class="layui-form-label">机制名称</label>
        <div class="layui-input-block">
            <input type="text" name="bonus_name" required  lay-verify="required" value="{{!empty($bonus)?$bonus->bonus_name:''}}" placeholder="请输入机制名称" autocomplete="off" class="layui-input">
            <input type="hidden" name="bonus_id" value="{{request()->get('bonus_id')}}">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-form-item">
            <div class="layui-form-item">
                <label class="layui-form-label">选择地区</label>
                <div class="layui-input-inline">
                    <select name="provid" id="provid" lay-filter="provid">
                        <option value="">请选择省</option>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <select name="cityid" id="cityid" lay-filter="cityid">
                        <option value="">请选择市</option>
                    </select>
                </div>
                <div class="layui-input-inline" style="display: none">
                    <select name="areaid" id="areaid" lay-filter="areaid">
                        <option value="">请选择县/区</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">所属公司</label>
        <div class="layui-input-block">
            <select name="merchant_id" lay-verify="required">
                <option value="">==选择公司==</option>
                @foreach($merchants as $merchant)
                    <option value="{{$merchant->id}}" {{!empty($bonus->merchant_id)?($bonus->merchant_id==$merchant->id?'selected':''):''}}>{{$merchant->merchant_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">分润类型</label>
        <div class="layui-input-block">
            <input type="radio" name="type" value="1" title="百分比" {{!empty($bonus->type)?($bonus->type==1?'checked':''):''}}>
            <input type="radio" name="type" value="2" title="金额" {{!empty($bonus->type)?($bonus->type==2?'checked':''):''}}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">金额/比例</label>
        <div class="layui-input-block">
            <input type="text" name="proportion" required  lay-verify="required" value="{{!empty($bonus->proportion)?$bonus->proportion:''}}" placeholder="请输入分润比例或金额" autocomplete="off" class="layui-input">
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
{{--<script type="text/javascript" src="/ztree/js/jquery.ztree.core.js"></script>--}}


<script>

</script>

</body>
</html>



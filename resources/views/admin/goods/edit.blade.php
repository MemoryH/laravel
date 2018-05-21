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
    <link href="/uploadfile/css/iconfont.css" rel="stylesheet" type="text/css"/>
    <link href="/uploadfile/css/fileUpload.css" rel="stylesheet" type="text/css">

</head>
<body>


<form class="layui-form" action="{{url('admin/goods/edit')}}" method="post" style="width: 90%;padding-top: 20px;">
    {{csrf_field()}}
    <div class="layui-form-item">
        <label class="layui-form-label">所属公司</label>
        <input type="hidden" name="id" value="{{request()->get('id')}}">
        <div class="layui-input-block">
            <select name="merchant_id" lay-verify="required">
                @foreach($merchants as $merchant)
                    <option value="{{$merchant->id}}" {{($goods->merchant_id ==$merchant->id)?'selected':''}} >{{$merchant->merchant_name}}</option>
                    @endforeach
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">商品名称</label>
        <div class="layui-input-block">
            <input type="text" name="goods_name" value="{{$goods->goods_name}}" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">商品分类</label>
        <div class="layui-input-block">
            <select name="goods_category_id" lay-verify="required">
                @foreach($servers as $server)
                    <option value="{{$server->id}}" {{$goods->goods_category_id==$server->id?'selected':''}}>{{$server->server_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">计量单位</label>
        <div class="layui-input-block">
            <input type="radio" name="unit" value="1" {{$goods->unit==1?'checked':''}} title="套">
            <input type="radio" name="unit" value="2" {{$goods->unit==2?'checked':''}} title="个">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">价格</label>
        <div class="layui-input-block">
            <input type="text" name="goods_price" value="{{$goods->goods_price}}" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">标签</label>
        <div class="layui-input-block">
            @foreach($labels as $label)
                <input type="checkbox" name="label[]" value="{{$label->label_name}}" {{in_array($label->label_name,$label_arr)?'checked':''}} title="{{$label->label_name}}">
            @endforeach
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">简介</label>
        <div class="layui-input-block">
            <textarea name="goods_intro" id="" cols="30" rows="10" class="layui-textarea">{{$goods->goods_intro}}</textarea>
        </div>
    </div>



    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
        </div>
    </div>
</form>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
{{--<script src="/js/data.js" type="text/javascript" charset="utf-8"></script>--}}
<script src="/js/province.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="/uploadfile/js/fileUpload.js"></script>

<script>


</script>

</body>
</html>



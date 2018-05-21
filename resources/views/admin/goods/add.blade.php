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


<form class="layui-form" action="{{empty($goods)?url('admin/goods/add'):url('admin/goods/edit')}}" method="post" style="width: 90%;padding-top: 20px;">
    {{csrf_field()}}
    <div class="layui-form-item">
        <label class="layui-form-label">所属公司</label>
        <input type="hidden" name="id" value="{{request()->get('id')}}">
        <div class="layui-input-block">
            <select name="merchant_id" lay-verify="required">
                <option value="0">==请选择公司==</option>
                @foreach($merchants as $merchant)
                    <option value="{{$merchant->id}}" {{!empty($goods->merchant_id)?($goods->merchant_id ==$merchant->id?'selected':''):''}}>{{$merchant->merchant_name}}</option>
                    @endforeach
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">服务名称</label>
        <div class="layui-input-block">
            <input type="text" name="goods_name" value="{{!empty($goods->goods_name)?$goods->goods_name:''}}" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">服务分类</label>
        <div class="layui-input-block">
            <select name="goods_category_id" lay-verify="required">
                @foreach($servers as $server)
                <option value="{{$server->id.':'.$server->server_name}}">{{$server->server_name}}</option>
                    @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">计量单位</label>
        <div class="layui-input-block">
            <input type="radio" name="unit" {{!empty($goods->unit)?($goods->unit==1?'checked':''):''}} value="1" title="套">
            <input type="radio" name="unit" {{!empty($goods->unit)?($goods->unit==2?'checked':''):''}} value="2" title="个">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-form-item">
            <div class="layui-form-item">
                <label class="layui-form-label">服务地区</label>
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
                <div class="layui-input-inline">
                    <select name="areaid" id="areaid" lay-filter="areaid">
                        <option value="">请选择县/区</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">价格</label>
        <div class="layui-input-block">
            <input type="text" name="goods_price" value="{{!empty($goods->goods_price)?$goods->goods_price:''}}" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
@if(empty($goods))
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="1" title="未上架">
            <input type="radio" name="status" value="2" title="已上架" checked>
        </div>
    </div>
    @endif
    <div class="layui-form-item">
        <label class="layui-form-label">标签</label>
        <div class="layui-input-block">
            @foreach($labels as $label)
            <input type="checkbox" name="label[]" value="{{$label->label_name}}" {{!empty($label_arr)?(in_array($label->label_name,$label_arr)?'checked':''):''}} title="{{$label->label_name}}">
                @endforeach
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">简介</label>
        <div class="layui-input-block">
            <textarea name="goods_intro" id="" cols="30" rows="10" class="layui-textarea">{{!empty($goods->goods_intro)?$goods->goods_intro:''}}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">商品图片</label>
        <div id="fileUploadContent" class="fileUploadContent"></div>
        @if(!empty($goods->goods_images))

        @foreach(json_decode($goods->goods_images) as $good)
        <img src="{{$good}}" alt="" width="200px" class="business_license">
        @endforeach
        @endif
        <input type="hidden" name="goods_images" id="business_license"  value="" >
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

    $("#fileUploadContent").initUpload({
        "uploadUrl":"{{url('admin/goods/upload')}}",//上传文件信息地址
        //"size":350,//文件大小限制，单位kb,默认不限制
        //"maxFileNumber":3,//文件个数限制，为整数
        //"filelSavePath":"",//文件上传地址，后台设置的根目录
        "beforeUpload":beforeUploadFun,//在上传前执行的函数
        "onUpload":onUploadFun,//在上传后执行的函数
        autoCommit:false,//文件是否自动上传
        "fileType":['png','jpg','docx','doc']//文件类型限制，默认不限制，注意写的是文件后缀
    });
    function beforeUploadFun(opt){
        opt.otherData =[{"name":"name","value":"zxm"}];
    }
    var business_license = $('#business_license')
    function onUploadFun(opt,data){
        alert('营业执照上传成功');
        business_license.attr('value',data);
        uploadTools.uploadError(opt);//显示上传错误
        uploadTools.uploadSuccess(opt);//显示上传成功
    }
    function testUpload(){
        var opt = uploadTools.getOpt("fileUploadContent");
        uploadEvent.uploadFileEvent(opt);
    }
</script>

</body>
</html>



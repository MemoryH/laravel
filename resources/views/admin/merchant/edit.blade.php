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


<form class="layui-form" action="{{url('admin/merchant/edit')}}" method="post" style="width: 90%;padding-top: 20px;">
    {{csrf_field()}}
    <div class="layui-form-item">
        <label class="layui-form-label">商户名称</label>
        <div class="layui-input-block">
            <input type="hidden" name="id" value="{{$results[0]->id}}">
            <input type="text" name="merchant_name" value="{{$results[0]->merchant_name}}" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">行业类型</label>
        <div class="layui-input-block">
            <select name="category_id" lay-verify="required">
                <option value="">==选择行业==</option>
                @foreach($categorys as $category)
                <option value="{{$category->id}}" {{$results[0]->category_id==$category->id?'selected':''}}>{{$category->category}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">公司类型</label>
        <div class="layui-input-block">
            <input type="radio" name="type" value="0" title="个人" {{$results[0]->type?'':'checked'}}>
            <input type="radio" name="type" value="1" title="企业" {{$results[0]->type?'checked':''}}>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-form-item">
            <label class="layui-form-label">选择地区</label>
            <div class="layui-input-inline">
                <select name="provid" id="provid" lay-filter="provid" address="{{$results[0]->provid}}">
                    <option value="">请选择省</option>
                </select>
            </div>
            <div class="layui-input-inline">
                <select name="cityid" id="cityid" lay-filter="cityid" address="{{$results[0]->cityid}}">
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

    <div class="layui-form-item">
        <label class="layui-form-label">联系人</label>
        <div class="layui-input-block">
            <input type="text" name="contacts" value="{{$results[0]->contacts}}" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">联系电话</label>
        <div class="layui-input-block">
            <input type="text" name="contacts_number" value="{{$results[0]->contacts_number}}" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">管理员</label>
        <div class="layui-input-block">
            <input type="text" name="charges" value="{{$results[0]->charges}}" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">身份证号</label>
        <div class="layui-input-block">
            <input type="text" name="identity_id" required  lay-verify="required" value="{{$results[0]->identity_id}}" placeholder="请身份证号" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">经营范围</label>
        <div class="layui-input-block">
            <input type="text" name="scope" required  lay-verify="required" placeholder="请输入经营范围" value="{{$results[0]->scope}}" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">注册资金</label>
        <div class="layui-input-block">
            <input type="text" name="capital" required  lay-verify="required" placeholder="请输入注册资金" value="{{$results[0]->capital}}" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">角色</label>
        <div class="layui-input-block">
            @foreach($roles as $role)
                <input type="checkbox" name="roles[]" value="{{$role->id}}" {{!empty($select_role)?(in_array($role->id,$select_role)?'checked':''):''}} title="{{$role->display_name}}">
            @endforeach
        </div>
    </div>
    <div class="layui-form-item path" path="{{url('admin/index/enclosure?enclosure='.$results[0]->business_license)}}">
        <label class="layui-form-label">营业执照</label>
        <div id="fileUploadContent" class="fileUploadContent"></div>
        <input type="hidden" name="business_license" id="business_license" value="{{!empty($results[0])?$results[0]->business_license:''}}" >
        <img src="{{url('admin/index/enclosure?enclosure='.$results[0]->business_license)}}" alt="" width="200px" class="business_license">
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">组织代码</label>
        <div id="fileUploadContent1" class="fileUploadContent"></div>
        <img src="{{url('admin/index/enclosure?enclosure='.$results[0]->institutional_code)}}" alt="" width="200px" class="institutional_code">
        <input type="hidden" name="institutional_code" id="institutional_code" value="{{!empty($results[0])?$results[0]->institutional_code:''}}">
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">税务登记</label>
        <div id="fileUploadContent2" class="fileUploadContent"></div>
        <img src="{{url('admin/index/enclosure?enclosure='.$results[0]->tax_registration)}}" alt="" width="200px" class="tax_registration">
        <input type="hidden" name="tax_registration" id="tax_registration" value="{{!empty($results[0])?$results[0]->tax_registration:''}}">
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
<script type="text/javascript" src="/uploadfile/js/fileUpload.js"></script>

<script>
    var province = $('#provid').attr('address');
    console.log(province);
    var city = $('#cityid').attr('address');
    var defaults = {
        s1: 'provid',
        s2: 'cityid',
        s3: 'areaid',
        v1: province?province:null,
        v2: city?city:null,
        v3: null
    };

    $(document).on('click','#password',function () {
        $.get('{{url('admin/merchant/password')}}','',function (res) {
                $('#password_value').attr('value',res);
        })
    })


    //营业执照
    $("#fileUploadContent").initUpload({
        "uploadUrl":"{{url('admin/merchant/upload')}}",//上传文件信息地址
        //"size":350,//文件大小限制，单位kb,默认不限制
        //"maxFileNumber":3,//文件个数限制，为整数
        //"filelSavePath":"",//文件上传地址，后台设置的根目录
        "beforeUpload":beforeUploadFun,//在上传前执行的函数
        "onUpload":onUploadFun,//在上传后执行的函数
        autoCommit:false,//文件是否自动上传

        "fileType":['png','jpg','docx','doc']//文件类型限制，默认不限制，注意写的是文件后缀

    });
    var path = $('.path').attr('path');
//    console.log(path);
//    console.log(uploadTools.getShowFileType(true,'','',path,''))
    function beforeUploadFun(opt){
        $('.business_license').hide();
        opt.otherData =[{"name":"name","value":"zxm"}];
    }
    var business_license = $('#business_license')
    function onUploadFun(opt,data){
        alert('营业执照上传成功');
        business_license.attr('value',data);
        uploadTools.uploadError(opt);//显示上传错误
        uploadTools.uploadSuccess(opt);//显示上传成功
    }

    //组织机构代码
    $("#fileUploadContent1").initUpload({
        "uploadUrl":"{{url('admin/merchant/upload')}}",//上传文件信息地址
        //"size":350,//文件大小限制，单位kb,默认不限制
        //"maxFileNumber":3,//文件个数限制，为整数
        //"filelSavePath":"",//文件上传地址，后台设置的根目录
        "beforeUpload":beforeUploadFun1,//在上传前执行的函数
        "onUpload":onUploadFun1,//在上传后执行的函数
        autoCommit:false,//文件是否自动上传
        "fileType":['png','jpg','docx','doc']//文件类型限制，默认不限制，注意写的是文件后缀
    });
    function beforeUploadFun1(opt){
        $('.institutional_code').hide();
        opt.otherData =[{"name":"name","value":"zxm"}];
    }
    var institutional_code = $('#institutional_code')
    function onUploadFun1(opt,data){
        alert('组织机构代码上传成功');
        institutional_code.attr('value',data);
        uploadTools.uploadError(opt);//显示上传错误
        uploadTools.uploadSuccess(opt);//显示上传成功
    }
    //税务登记
    $("#fileUploadContent2").initUpload({
        "uploadUrl":"{{url('admin/merchant/upload')}}",//上传文件信息地址
        //"size":350,//文件大小限制，单位kb,默认不限制
        //"maxFileNumber":3,//文件个数限制，为整数
        //"filelSavePath":"",//文件上传地址，后台设置的根目录
        "beforeUpload":beforeUploadFun2,//在上传前执行的函数
        "onUpload":onUploadFun2,//在上传后执行的函数
        autoCommit:false,//文件是否自动上传
        "fileType":['png','jpg']//文件类型限制，默认不限制，注意写的是文件后缀
    });
    function beforeUploadFun2(opt){
        $('.tax_registration').hide();
        opt.otherData =[{"name":"name","value":"zxm"}];
    }
    var tax_registration = $('#tax_registration')
    function onUploadFun2(opt,data){
        alert('税务登记上传成功');
        tax_registration.attr('value',data);
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



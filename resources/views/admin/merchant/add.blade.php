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
    <style type="text/css">
        body, html,#allmap {width: 100%;height: 500px;margin:0;font-family:"微软雅黑";}
    </style>
</head>
<body>
<form class="layui-form" action="{{empty($result)?url('admin/merchant/add'):url('admin/merchant/edit')}}" method="post" style="width: 90%;padding-top: 20px;">
    {{csrf_field()}}
    <div class="layui-form-item">
        <label class="layui-form-label">商户名称</label>
        <div class="layui-input-block">
            <input type="hidden" name="merchant_id" value="{{!empty($result)?$result->merchant_id:''}}">
            <input type="text" name="merchant_name" required value="{{!empty($result)?$result->merchant_name:''}}"  lay-verify="required" placeholder="请输入商户标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    @if(empty($result))
    <div class="layui-form-item">
        <label class="layui-form-label">密码框</label>
        <div class="layui-input-inline">
            <input type="text" name="password" id="password_value" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">

        </div>
        <button type="button" id="password" class="layui-btn layui-btn-sm">随机</button>
        <div class="layui-form-mid layui-word-aux">密码必须包含数字跟英文且长度最少为8位</div>
    </div>
        @endif
    <div class="layui-form-item">
        <label class="layui-form-label">单位类型</label>
        <div class="layui-input-block">
            <select name="category_id" lay-verify="required">
                <option value="">==选择单位==</option>
                <option value="1" {{!empty($result)?$result->category_id==1?'selected':'':''}}>企业</option>
                <option value="2" {{!empty($result)?$result->category_id==2?'selected':'':''}}>事业单位</option>
                <option value="3" {{!empty($result)?$result->category_id==3?'selected':'':''}}>民办非企业单位</option>
                <option value="4" {{!empty($result)?$result->category_id==4?'selected':'':''}}>个体工商户</option>
                <option value="5" {{!empty($result)?$result->category_id==5?'selected':'':''}}>社会团体</option>
                <option value="6" {{!empty($result)?$result->category_id==6?'selected':'':''}}>党政及国家机关</option>
            </select>
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
                <div class="layui-input-inline">
                    <select name="areaid" id="areaid" lay-filter="areaid">
                        <option value="">请选择县/区</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">详细地址</label>
        <div class="layui-input-block">
            <input type="text" name="address" id="address" value="{{!empty($result)?$result->address:''}}"  required  lay-verify="required" placeholder="请输入详细地址" autocomplete="off" class="layui-input">
        </div>
    </div>

            <input type="hidden" name="lng" id="lng" value="{{!empty($result)?$result->lng:''}}"  required lay-verify="required" class="layui-input">

            <input type="hidden" name="lat" id="lat" value=""  required lay-verify="required"  class="layui-input">

    <div id="allmap">

    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">联系人</label>
        <div class="layui-input-block">
            <input type="text" name="contacts" required value="{{!empty($result)?$result->contacts:''}}"   lay-verify="required" placeholder="联系人姓名" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">联系电话</label>
        <div class="layui-input-block">
            <input type="number" name="contacts_number" id="contacts_number" required value="{{!empty($result)?$result->contacts_number:''}}"   lay-verify="required" placeholder="联系电话/登录账号" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">经营范围</label>
        <div class="layui-input-block">
            <input type="text" name="scope" value="{{!empty($result)?$result->scope:''}}"  required  lay-verify="required" placeholder="请输入经营范围" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">注册号</label>
        <div class="layui-input-block">
            <input type="number" name="registration_number" value="{{!empty($result)?$result->registration_number:''}}" required  lay-verify="required" placeholder="请输入注册号" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">角色</label>
        <div class="layui-input-block">
            @foreach($roles as $role)
                <input type="checkbox" name="roles[]" {{!empty($select_role)?(in_array($role->id,$select_role)?'checked':''):''}} lay-verify="required" value="{{$role->id}}" title="{{$role->display_name}}">
            @endforeach
        </div>
    </div>
    @if(empty($result))
    <div class="layui-form-item">
        <label class="layui-form-label">审核状态</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="0" title="待审核">
            <input type="radio" name="status" value="1" title="审核通过">
            <input type="radio" name="status" value="2" title="审核未通过">
        </div>
    </div>
    @endif
    <div class="layui-form-item">
        <label class="layui-form-label">营业执照</label>
        <div id="fileUploadContent" class="fileUploadContent"></div>
        <input type="hidden" name="business_license" id="business_license" required  lay-verify="required" value="{{!empty($result)?$result->business_license:''}}" >
        <img src="{{!empty($result)?url('admin/index/enclosure?enclosure='.$result->business_license):''}}" alt="" width="200px" class="business_license">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">营业期限</label>
        <div class="layui-input-block">
            <input type="date" name="business_time" value="{{!empty($result)?date('Ymd',$result->business_time):''}}" required  lay-verify="required" placeholder="请输入身份证有效期" autocomplete="off" class="layui-input">
        </div>
    </div>
    <hr/>
    <div class="layui-form-item">
        <label class="layui-form-label">公司法人姓名</label>
        <div class="layui-input-block">
            <input type="text" name="legal_name" value="{{!empty($legal)?$legal->legal_name:''}}" required  lay-verify="required" placeholder="请输入公司法人姓名" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">身份证号</label>
        <div class="layui-input-block">
            <input type="text" name="legal_identity" value="{{!empty($legal)?$legal->legal_identity:''}}" id="identity_id" required  lay-verify="required" placeholder="请输入公司法人身份证号" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">身份证有效期</label>
        <div class="layui-input-block">
            <input type="date" name="legal_identity_end_time" value="{{!empty($legal)?date('Ymd',$legal->legal_identity_end_time):''}}" required  lay-verify="required" placeholder="请输入身份证有效期" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">户籍所在地</label>
        <div class="layui-input-block">
            <input type="text" name="legal_address" required value="{{!empty($legal)?$legal->legal_address:''}}"  lay-verify="required" placeholder="请输入公司法人户籍所在地" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">身份证正面照</label>
        <div id="fileUploadContent1" class="fileUploadContent"></div>
        <input type="hidden" name="legal_identity_photo_just" id="identity_photo" required  lay-verify="required" value="{{!empty($legal)?$legal->legal_identity_photo_just:''}}">
        <img src="{{!empty($legal)?url('admin/index/enclosure?enclosure='.$legal->legal_identity_photo_just):''}}" alt="" width="200px" class="business_license">
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">身份证反面照</label>
        <div id="fileUploadContent2" class="fileUploadContent"></div>
        <input type="hidden" name="legal_identity_photo_back" id="legal_identity_photo_back" required  lay-verify="required" value="{{!empty($legal)?$legal->legal_identity_photo_back:''}}">
        <img src="{{!empty($legal)?url('admin/index/enclosure?enclosure='.$legal->legal_identity_photo_back):''}}" alt="" width="200px" class="business_license">
    </div>

    <hr>
    <div class="layui-form-item">
        <label class="layui-form-label">银行卡开户名</label>
        <div class="layui-input-block">
            <input type="text" name="username" value="{{!empty($bank)?$bank->username:''}}" required  lay-verify="required" placeholder="请输入银行卡开户人" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">银行卡账户</label>
        <div class="layui-input-block">
            <input type="text" name="bank_account" value="{{!empty($bank)?$bank->bank_account:''}}" required  lay-verify="required" placeholder="请输入银行卡账户号码" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">开户行所在地</label>
        <div class="layui-input-block">
            <input type="text" name="bank_address" value="{{!empty($bank)?$bank->bank_address:''}}" required  lay-verify="required" placeholder="请输入开户行所在地" autocomplete="off" class="layui-input">
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
<script type="text/javascript" src="/uploadfile/js/fileUpload.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=mPvOjf1adE0MhtxfMZoVFkPZaldU1mw5"></script>

<script>
    var defaults = {
        s1: 'provid',
        s2: 'cityid',
        s3: 'areaid',
        v1: null,
        v2: null,
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
        opt.otherData =[{"name":"name","value":"zxm"}];
    }
    var identity_photo = $('#identity_photo')
    function onUploadFun1(opt,data){
        alert('身份证正面上传成功');
        identity_photo.attr('value',data);
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
        opt.otherData =[{"name":"name","value":"zxm"}];
    }
    var legal_identity_photo_back = $('#legal_identity_photo_back')
    function onUploadFun2(opt,data){
        alert('税务登记上传成功');
        legal_identity_photo_back.attr('value',data);
        uploadTools.uploadError(opt);//显示上传错误
        uploadTools.uploadSuccess(opt);//显示上传成功
    }
//=========================地图===========================================//
    // 百度地图API功能

    
    var map = new BMap.Map("allmap");
    var point = new BMap.Point(116.331398,39.897445);
    map.centerAndZoom(point,14);

    $('#address').blur(function () {
        var address =$('#address').val();
        var city = $('#cityid').val();
        // 创建地址解析器实例
        var myGeo = new BMap.Geocoder();
        // 将地址解析结果显示在地图上,并调整地图视野
        myGeo.getPoint(address, function(point){
            if (point) {
                map.centerAndZoom(point, 16);
                map.addOverlay(new BMap.Marker(point));
                var localSearch = new BMap.LocalSearch(map);
                localSearch.enableAutoViewport(); //允许自动调节窗体大小
                    map.clearOverlays();//清空原来的标注
                    var keyword = document.getElementById("address").value;
                    localSearch.setSearchCompleteCallback(function (searchResult) {
                        var poi = searchResult.getPoi(0);
                        document.getElementById("lng").value = poi.point.lng;//这里是追加到对应的input文本框里，可以根据自己的需要来修改
                        document.getElementById("lat").value = poi.point.lat;//这里是追加到对应的input文本框里，可以根据自己的需要来修改
                        map.centerAndZoom(poi.point, 16);
                        var marker = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));  // 创建标注，为要查询的地方对应的经纬度
                        map.addOverlay(marker);
//                        var content = document.getElementById("address").value + "<br/><br/>经度：" + poi.point.lng + "<br/>纬度：" + poi.point.lat;
//                        var infoWindow = new BMap.InfoWindow("<p style='font-size:14px;'>" + content + "</p>");
//                        marker.addEventListener("click", function () { this.openInfoWindow(infoWindow); });
                        // marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
                    });
                    localSearch.search(keyword);


            }else{
                alert("您选择地址没有解析到结果!");
            }
        }, city);
    });


    // 添加带有定位的导航控件
    var navigationControl = new BMap.NavigationControl({
        // 靠左上角位置
        anchor: BMAP_ANCHOR_TOP_LEFT,
        // LARGE类型
        type: BMAP_NAVIGATION_CONTROL_LARGE,
        // 启用显示定位
        enableGeolocation: true
    });
    map.addControl(navigationControl);
    // 添加定位控件
    var geolocationControl = new BMap.GeolocationControl();
    geolocationControl.addEventListener("locationSuccess", function(e){
        // 定位成功事件
        var address = '';
        address += e.addressComponent.province;
        address += e.addressComponent.city;
        address += e.addressComponent.district;
        address += e.addressComponent.street;
        address += e.addressComponent.streetNumber;
        alert("当前定位地址为：" + address);
    });
    geolocationControl.addEventListener("locationError",function(e){
        // 定位失败事件
        alert(e.message);
    });
    map.addControl(geolocationControl);
    map.enableScrollWheelZoom();   //启用滚轮放大缩小，默认禁用
    map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
//    var geolocation = new BMap.Geolocation();
//    geolocation.getCurrentPosition(function(r){
//        if(this.getStatus() == BMAP_STATUS_SUCCESS){
//            var mk = new BMap.Marker(r.point);
//            map.addOverlay(mk);
//            map.panTo(r.point);
//            alert('您的位置：'+r.point.lng+','+r.point.lat);
//        }
//        else {
//            alert('failed'+this.getStatus());
//        }
//    },{enableHighAccuracy: true})
//=========================地图===========================================//

    function testUpload(){
        var opt = uploadTools.getOpt("fileUploadContent");
        uploadEvent.uploadFileEvent(opt);
    }

    //判断电话号码
    $('#contacts_number').blur(function () {
        var sMobile = $("#contacts_number").val();
        if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(sMobile))) {
            alert("不是完整的11位手机号或者正确的手机号前七位");
            $("#contacts_number").val('');

            return false;
        }
        })
    //判断身份证号码
    $("#identity_id").blur(function () {
        var code = $("#identity_id").val();
        var city={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江 ",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北 ",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏 ",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外 "};
        var row={
            'pass':true,
            'msg':'验证成功'
        };
        if(!code || !/^\d{6}(18|19|20)?\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|[xX])$/.test(code)){
            row={
                'pass':false,
                'msg':'身份证号格式错误'
            };
        }else if(!city[code.substr(0,2)]){
            row={
                'pass':false,
                'msg':'身份证号地址编码错误'
            };
        }else{
            //18位身份证需要验证最后一位校验位
            if(code.length == 18){
                code = code.split('');
                //∑(ai×Wi)(mod 11)
                //加权因子
                var factor = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2 ];
                //校验位
                var parity = [ 1, 0, 'X', 9, 8, 7, 6, 5, 4, 3, 2 ];
                var sum = 0;
                var ai = 0;
                var wi = 0;
                for (var i = 0; i < 17; i++)
                {
                    ai = code[i];
                    wi = factor[i];
                    sum += ai * wi;
                }
                if(parity[sum % 11] != code[17].toUpperCase()){
                    row={
                        'pass':false,
                        'msg':'身份证号校验位错误'
                    };
                }
            }
        }
//        console.log(row.pass);
        if (row.pass ===false){
            alert(row.msg);
            $("#identity_id").val('');
        }
    })
    //判断密码格式
    $("#password_value").blur(function () {
        var sMobile = $("#password_value").val();
        if(!(/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,16}$/.test(sMobile))) {
            alert("密码不符合规则");
            $("#password_value").val('');

            return false;
        }
    })
</script>

</body>
</html>



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


<form class="layui-form" action="{{url('admin/user/add')}}" method="post" style="width: 90%;padding-top: 20px;">
    {{csrf_field()}}
    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-block">

            <input type="text" name="nick_name" value="" required  lay-verify="required" placeholder="请输入用户名" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">密码框</label>
        <div class="layui-input-inline">
            <input type="password" name="password" id="password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">密码必须包含数字跟英文切长度最少为8位</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">会员类型</label>
        <div class="layui-input-block">
            <input type="radio" name="type" value="1" title="个人" checked >
            <input type="radio" name="type" value="2" title="企业" >
            <input type="radio" name="type" value="3" title="代理" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">真实姓名</label>
        <div class="layui-input-block">
            <input type="text" name="true_name" value="" required  lay-verify="required" placeholder="请输入真实姓名" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">联系电话</label>
        <div class="layui-input-block">
            <input type="text" name="phone" value="" required  lay-verify="required" placeholder="请输入联系电话" autocomplete="off" class="layui-input phone">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">身份证号</label>
        <div class="layui-input-block">
            <input type="text" name="id_card" id="identity_id" required  lay-verify="required" placeholder="请输入身份证号码" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">电子邮件</label>
        <div class="layui-input-block">
            <input type="text" name="e_mail" id="e_mail" required  lay-verify="required" placeholder="请输入电子邮箱" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">紧急联系人</label>
        <div class="layui-input-block">
            <input type="text" name="em_contact" value="" required  lay-verify="required" placeholder="请输入紧急联系人" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">紧急联系人电话</label>
        <div class="layui-input-block">
            <input type="text" name="em_phone" value="" required  lay-verify="required" placeholder="紧急联系人电话" autocomplete="off" class="layui-input phone">
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
{{--<script type="text/javascript" src="/uploadfile/js/fileUpload.js"></script>--}}

<script>

    //判断电话号码
    $('.phone').blur(function () {
        var sMobile = $(".phone").val();
        if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(sMobile))) {
            alert("不是完整的11位手机号或者正确的手机号前七位");
            $(".phone").val('');

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

    //判断电子邮箱
    $("#e_mail").blur(function () {
        var sMobile = $("#e_mail").val();
        if(!(/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/.test(sMobile))) {
            alert("邮箱格式不正确");
            $("#e_mail").val('');

            return false;
        }
    })

    //判断密码格式
    $("#password").blur(function () {
        var sMobile = $("#password").val();
        if(!(/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,16}$/.test(sMobile))) {
            alert("密码不符合规则");
            $("#password").val('');

            return false;
        }
    })


</script>

</body>
</html>



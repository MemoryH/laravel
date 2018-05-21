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

</head>
<body>

<fieldset class="layui-elem-field">
    <legend>商户明细</legend>
    <div class="layui-field-box">
        @if(isset($merchant))
            <legend>商户基本信息</legend>
            <hr>
                <p>商户名称: {{$merchant->merchant_name}}</p>
                <p>联系人: {{$merchant->contacts}}</p>
                <p>联系电话: {{$merchant->contacts_number}}</p>
                <p>营业执照: <img src="{{!empty($merchant->business_license)?url('admin/index/enclosure?enclosure='.$merchant->business_license):''}}" alt="" width="200px" class="business_license"></p>
                <p>商户号: {{$merchant->merchant_sn}}</p>
                <p>事业单位: {{$merchant->category_id}}</p>
                <p>经营范围: {{$merchant->scope}}</p>
                <p>商户来源: {{$merchant->source}}</p>
                <p>公司地址: {{$merchant->province.$merchant->city.$merchant->area.$merchant->address}}</p>
                <p>营业期限: {{$merchant->business_time}}</p>
                <p>公司简介: {{$merchant->introduction}}</p>
        @endif
        @if(isset($legal))
                <legend>商户法人信息</legend>
                <hr>
                <p>法人名称: {{$legal->legal_name}}</p>
                <p>所属地址: {{$legal->legal_address}}</p>
                <p>身份证号码: {{$legal->legal_identity}}</p>
                <p>有效期: {{$legal->legal_identity_end_time}}</p>
                <p>身份证正面: <img src="{{!empty($legal->legal_identity_photo_just)?url('admin/index/enclosure?enclosure='.$legal->legal_identity_photo_just):''}}" alt="" width="200px" class="business_license"></p>
                <p>身份证反面: <img src="{{!empty($legal->legal_identity_photo_back)?url('admin/index/enclosure?enclosure='.$legal->legal_identity_photo_back):''}}" alt="" width="200px" class="business_license"></p>
            @endif
        @if(isset($user_info))
                <legend>个人商户信息</legend>
                <hr>
                <p>真实姓名: {{$user_info->true_name}}</p>
                <p>电子邮箱: {{$user_info->e_mail}}</p>
                <p>个人信息认证通过</p>
            @endif
        @if(isset($bank))
                <legend>银行信息</legend>
                <hr>
                <p>银行开户名: {{$bank->username}}</p>
                <p>银行账户: {{$bank->bank_account}}</p>
                <p>开户行地址: {{$bank->bank_address}}</p>
            @endif


    </div>

</fieldset>
<span id="merchant_id" merchant_id="{{request()->get('merchant_id')}}"></span>
<a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn yes" status="1">通过</a>
<a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn no" status="1">不通过</a>

<div class="main-mask">

</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script>
    //审核通过
    $(document).on('click','.yes',function () {
        var id=$('#merchant_id').attr('merchant_id');
        var status=1;
        $data = {
            'id':id,
            'status':status
        };
        layer.msg('你确定审核通过吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/merchant/status')}}",$data,function (res) {
                    if(res=='success'){
                        layer.msg('审核成功', {
                            icon: 6
                            ,btn: ['确定']
                        });
                    }else {
                        layer.msg('审核失败', {
                            icon: 6
                            ,btn: ['确定']
                        });
                    }

                });

            }
        });


    });

    $(document).on('click','.no',function () {
        var id=$('#merchant_id').attr('merchant_id');
        index =layer.open({

            type:1,

            title:"审核失败原因",

            area: ['500px','300px'],

            closeBtn: 0,

            shadeClose: true,

            content: '<form action="{{url('admin/merchant/status')}}" class="layui-form" method = "post" >{{csrf_field()}}<div class="layui-form-item"> <label class="layui-form-label">失败原因</label> <input type="hidden" name="status" value="2"> <input type="hidden" name="id" value="'+id+'"><div class="layui-input-block"> <textarea name="audit_hints" id="" cols="20" rows="8" class="layui-textarea"></textarea> </div> </div><div class="layui-form-item"> <div class="layui-input-block"> <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button> <button type="button" class="layui-btn layui-btn-primary" >重置</button> </div> </div></form>'

        });
    });
</script>
</body>
</html>
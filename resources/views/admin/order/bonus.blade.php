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
<form class="layui-form" action="{{url('admin/order/bonus')}}" method="post" style="width: 90%;padding-top: 20px;">
    {{csrf_field()}}
    <div class="layui-form-item">
        <label class="layui-form-label">公司名称/发起人</label>
        <div class="layui-input-block">
            <input type="text" name="merchant_name" readonly required  lay-verify="required" value="{{$order->merchant_name}}" autocomplete="off" class="layui-input">
            <input type="hidden" name="form_merchant_id" value="{{$order->merchant_id}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">支付方/用户</label>
        <div class="layui-input-block">
            <input type="text" required  lay-verify="required" readonly value="{{$user->true_name}}"autocomplete="off" class="layui-input">
            <input type="hidden" name="user_id" value="{{$order->user_id}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">订单金额</label>
        <div class="layui-input-block">
            <input type="text" required name="order_money"  lay-verify="required" readonly value="{{$order->pay_money}}" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">分润员工</label>
        <div class="layui-input-block">
            <select name="bonus_staff_id" lay-verify="required">
                <option value="">==选择员工==</option>
                @foreach($staffs as $staff)
                    <option value="{{$staff->id}}" >{{$staff->staff_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">分润机制</label>
        <div class="layui-input-block">
            <select id="bonus" name="bonus_id">
                <option value="">==选择机制==</option>
                @foreach($bonuses as $bonus)
                    <option value="{{$bonus->id}}" >{{$bonus->bonus_name}}</option>
                @endforeach
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
{{--<script src="/js/province.js" type="text/javascript" charset="utf-8"></script>--}}
{{--<script type="text/javascript" src="/ztree/js/jquery.ztree.core.js"></script>--}}


<script>

</script>

</body>
</html>



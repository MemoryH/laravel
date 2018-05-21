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
    <legend>订单明细</legend>
    <div class="layui-field-box">
        <p>下单方: {{$order->merchant_name}}</p>
        <p>支付方: {{$user->true_name}}</p>
        <p>订单交易流水号: {{$order->transaction_id}}</p>
        <p>订单名称: {{$order->ext_data}}</p>
        <p>订单号: {{$order->order_sn}}</p>
        <p>订单总价: ￥{{$order->total_money}}</p>
        <p>实际支付: ￥{{$order->pay_money}}</p>
        <p>订单发起时间: {{date('Y-m-d H:i:s',$order->add_time)}}</p>
        <p>订单支付时间: {{date('Y-m-d H:i:s',$order->pay_time)}}</p>
        <p>支付方式: {{$order->pay_name}}</p>
    </div>
</fieldset>


<div class="main-mask">

</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>
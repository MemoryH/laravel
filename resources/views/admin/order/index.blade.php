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
<form action="" class="layui-form">
    <div class="layui-form-item">
        <div class="" style="width: 200px;float: left">
            <input type="text" value="{{!empty(request()->get('order_sn')?request()->get('order_sn'):'')}}" name="order_sn" placeholder="请输入订单号码" autocomplete="off" class="layui-input">
        </div>
        <div class="" style="width: 200px;float: left">
            <input type="text" value="{{!empty(request()->get('merchant_name')?request()->get('merchant_name'):'')}}" name="merchant_name" placeholder="请输入商户名称" autocomplete="off" class="layui-input">
        </div>
        <div class="" style="width: 200px;float: left">
            <select name="order_status" id="" class="form-control">
                <option value="">==订单状态==</option>
                <option value="0">待支付</option>
                <option value="1">已支付</option>

            </select>
        </div>
        <div class="" style="width: 200px;float: left">
            <select name="provid" id="provid" lay-filter="provid" position="{{request()->get('provid')}}">
                <option value="">请选择省</option>
            </select>
        </div>
        <div class="" style="width: 200px;float: left">
            <select name="cityid" id="cityid" lay-filter="cityid" position="{{request()->get('cityid')}}">
                <option value="">请选择市</option>
            </select>
        </div>
        <div class="" style="width: 200px;float: left;">
            <select name="areaid" id="areaid" lay-filter="areaid">
                <option value="">请选择县/区</option>
            </select>
        </div>
        <div class="" style="width: 200px;float: left">
            <button class="layui-btn layui-btn-normal go-btn">搜索</button>
        </div>
    </div>


</form>
<table class="layui-table">
    <colgroup>
        <col width="150">
        <col width="200">
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>订单号</th>
        <th>订单时间</th>
        <th>订单总额</th>
        <th>订单标题</th>
        <th>商家名称</th>
        <th>所属地区</th>
        <th>实付金额</th>
        <th>支付类型</th>
        <th>订单状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
@foreach($orders as $order)
    <tr order_id="{{$order->id}}" order_status="{{$order->order_status}}">
        <td>{{$order->order_sn}}</td>
        <td>{{date('Y-m-d H:i:s',$order->add_time)}}</td>
        <td>{{$order->total_money}}</td>
        <td>{{$order->title}}</td>
        <td>{{$order->merchant_name}}</td>
        <td>{{$order->province.$order->city.$order->area}}</td>
        <td>{{$order->pay_money}}</td>
        <td>{{$order->pay_name}}</td>
        <td>{{$order->order_status==0?'待支付':($order->order_status==1?'已支付':($order->order_status==2?'待确认':($order->order_status==3?'已确认':'')))}}</td>
        <td>
            <a href="javascript:;"  class="layui-btn layui-btn-small layui-btn-normal go-btn order_show">详细信息</a>
            <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn bonus">分润</a>
            {{--<button class="layui-btn layui-btn-small layui-btn-normal go-btn order_status {{$order->order_status>=5?'layui-btn-disabled':''}}"><i class="layui-icon"></i>修改状态</button>--}}
        </td>
    </tr>
    @endforeach
    </tbody>
</table>

<div class="main-mask">

</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/province.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

    $(document).on('click','.order_show',function () {
        var order_id = $(this).closest('tr').attr('order_id');
        // console.log(merchant_id);

        index = layer.open({

            type: 2,

            title: "订单详情",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['800px', '600px'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'show?order_id='+order_id


        });
    });
//分润
    $(document).on('click','.bonus',function () {
        var order_id = $(this).closest('tr').attr('order_id');
        // console.log(merchant_id);

        index = layer.open({

            type: 2,

            title: "分润",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['800px', '600px'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'bonus?order_id='+order_id


        });
    });
    $(document).on('click','.order_status',function () {
        var tr = $(this).closest('tr')
        var status = $(this).closest('tr').attr('order_status');
        var order_id = $(this).closest('tr').attr('order_id');
        $data = {
            'status':status,
            'order_id':order_id
        };
        layer.msg('你确定修改状态吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/order/status')}}",$data,function (res) {

                    if(res){
                        location.reload(true)
                    }
                });

            }
        });


    });
</script>
</body>
</html>
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

</head>
<body>
<form action="" class="layui-form">
    <div class="layui-form-item">
        <div class="" style="width: 200px;float: left">
            <input type="text" name="goods_name" value="{{!empty(request()->get('goods_name'))?request()->get('goods_name'):''}}" placeholder="请输入产品名称" autocomplete="off" class="layui-input">
        </div>
        <div class="" style="width: 200px;float: left">
            <select name="merchant_id" id="" class="form-control">

                <option value="0">==公司名称==</option>
                @foreach($merchants as $merchant)
                    <option value="{{$merchant->id}}" {{(!empty(request()->get('merchant_id'))&&request()->get('merchant_id')==$merchant->id)?'selected':''}}>{{$merchant->merchant_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="" style="width: 200px;float: left">
            <input type="text" name="goods_sn" value="{{!empty(request()->get('goods_sn'))?request()->get('goods_sn'):''}}" placeholder="请输入产品编码" autocomplete="off" class="layui-input">
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
        <th>公司名称</th>
        <th>商品名称</th>
        <th>商品编码</th>
        <th>分类</th>
        <th>创建时间</th>
        <th>价格</th>
        <th>上架时间</th>
        <th>下架时间</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($goods as $good)

        <tr goods_id="{{$good->id}}">
            <td>{{$good->merchant_id}}</td>
            <td>{{$good->goods_name}}</td>
            <td>{{$good->goods_sn}}</td>
            <td>{{$good->goods_category_id}}</td>
            <td>{{date('Y-m-d H:i:s',$good->create_time)}}</td>
            <td>{{$good->goods_price}}</td>
            <td>{{date('Y-m-d H:i:s',$good->start_time)}}</td>
            <td>{{date('Y-m-d H:i:s',$good->end_time)}}</td>
            <td>{{$good->status==1?'未上架':($good->status==2?'已上架':'已下架')}}</td>
            <td>
                <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn goods_edit"><i class="layui-icon"></i>编辑</a>
                @if($good->status==1 or $good->status==3)
                    <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn goods_status" status="2">上架</a>
                @endif
                @if($good->status==2)
                    <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-danger del-btn goods_status" status="3">下架</a>
                @endif
                <a href="javascript:;" class="del layui-btn layui-btn-small layui-btn-danger del-btn">删除</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{$goods->appends(['goods_name'=>$goods->goods_name,'merchant_id'=>$goods->merchant_id,'goods_sn'=>$goods->goods_sn])->render()}}
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
{{--<script src="/js/data.js" type="text/javascript" charset="utf-8"></script>--}}
<script src="/js/province.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $(document).on('click','.goods_status',function () {
        var goods_id = $(this).closest('tr').attr('goods_id');
        var status = $(this).attr('status');
        $.get('{{url('admin/goods/status')}}',{id:goods_id,status:status},function (res) {
            if (res){
                location.reload(true)
            }
        })
    });

    //删除商品
    $(document).on('click','.del',function () {
        var tr = $(this).closest('tr')
        var id = $(this).closest('tr').attr('goods_id');
        $data = {
            'id':id
        };
        layer.msg('你确定删除吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/goods/del')}}",$data,function (res) {

                    if(res){

                        tr.remove();
                    }
                })
                layer.msg('删除成功', {
                    icon: 6
                    ,btn: ['确定']
                });
            }
        });


    });

    //修改商品
    $(document).on('click','.goods_edit',function () {
        var goods_id = $(this).closest('tr').attr('goods_id');
        // console.log(merchant_id);

        index =layer.open({

            type:2,

            title:"修改商品",
            btn:['关闭'],
            yes:function(index,layero){
                layer.closeAll('iframe')
            },
            area: ['100%','100%'],

            closeBtn: 0,
            // maxmin:true,

            shadeClose: true,

            content: 'edit?id='+goods_id


        });


    });
</script>
</body>
</html>

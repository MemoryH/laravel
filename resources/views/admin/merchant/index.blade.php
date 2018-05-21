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
            <input type="text" name="merchant_sn" placeholder="请输入商户编码" autocomplete="off" value="{{!empty(request()->get('merchant_sn'))?request()->get("merchant_sn"):''}}" class="layui-input">
        </div>

            <div class="" style="width: 200px;float: left">
                <select name="category_id" class="form-control" lay-verify="required">
                    <option value="">==选择单位==</option>
                    <option value="1" {{!empty($result)?$result->category_id==1?'selected':'':''}}>企业</option>
                    <option value="2" {{!empty($result)?$result->category_id==2?'selected':'':''}}>事业单位</option>
                    <option value="3" {{!empty($result)?$result->category_id==3?'selected':'':''}}>民办非企业单位</option>
                    <option value="4" {{!empty($result)?$result->category_id==4?'selected':'':''}}>个体工商户</option>
                    <option value="5" {{!empty($result)?$result->category_id==5?'selected':'':''}}>社会团体</option>
                    <option value="6" {{!empty($result)?$result->category_id==6?'selected':'':''}}>党政及国家机关</option>
                </select>
            </div>

        <div class="" style="width: 200px;float: left">
            <select name="type" id="" class="form-control">
                <option value="">==请选择企业类型==</option>
                <option value="0" {{(request()->get('type')==='0')?'selected':''}}>个人</option>
                <option value="1" {{request()->get('type')==1?'selected':''}}>企业</option>
            </select>
        </div>
        <div class="" style="width: 200px;float: left">
            <select name="status" id="" class="form-control">
                <option value="">==审核状态==</option>
                <option value="0" {{(request()->get('status')=='0')?'selected':''}}>待审核</option>
                <option value="1" {{request()->get('status')==1?'selected':''}}>通过</option>
                <option value="2" {{request()->get('status')==2?'selected':''}}>未通过</option>
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
        <div class="" style="width: 200px;float: left;display: none">
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
        <th>商户编码</th>
        <th>商户名称</th>
        <th>所属地区</th>
        <th>联系人</th>
        <th>联系电话</th>
        <th>操作时间</th>
        <th>员工人数</th>
        <th>商户状态</th>
        <th>商户来源</th>
        <th>商户标识</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($res as $re)
        @if(!empty($re->merchant_id))
            <?php
            $id = $re->merchant_id;
            $company = \Illuminate\Support\Facades\DB::table('company')->where('id',$id)->first();
            ?>

            @endif
    <tr class="" merchant_id='{{!empty($company)?$company->id:''}}' phone="{{$re->contacts_number}}">
        <td>{{$re->merchant_sn}}</td>
        <td>{{$re->merchant_name}}</td>
        <td class="">{{$re->province.'&nbsp;'.$re->city}}</td>
        <td class="center">{{$re->contacts}}</td>
        <td class="center">{{$re->contacts_number}}</td>
        <td class="center">{{date('Y-m-d H:i:s',!empty($company)?$company->update_time:0)}}</td>
        <td class="center">{{$re->counts}}</td>
        <td class="center" class="examine">{{!empty($company)?($company->status==0?'待审核':($company->status==1?'审核通过':'审核未通过')):''}}</td>
        <td class="center">{{$re->source==1?'平台':'APP'}}</td>
        <td class="center">{{!empty($company)?$company->identification:''}}</td>
        <td class="center">
            <a class="layui-btn layui-btn-small layui-btn-normal go-btn show_staff">查看员工</a>
            <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn merchant_edit"><i class="layui-icon"></i>编辑</a>
            <a href="javascript:;" class="del layui-btn layui-btn-small layui-btn-danger del-btn">删除</a>
            @if(!empty($company))
                @if($company->status !=1)
                <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn show_merchant" status="1">审核</a>
                @endif
                    @if($company->status ==2)
                        <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn layui-btn-disabled" status="1">审核未通过</a>
                    @endif
            @endif
            <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn merchant_user" status="1">绑定会员</a>
            <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal go-btn department" status="1">查看部门</a>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
{{$res->appends(['category_id'=>$res->category_id,'merchant_sn'=>$res->merchant_sn,'type'=>$res->type,'status'=>$res->status])->render()}}
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
{{--<script src="/js/data.js" type="text/javascript" charset="utf-8"></script>--}}
<script src="/js/province.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
//三级联动
    var province = $('#provid').attr('position');
    var city = $('#cityid').attr('position')
    var defaults = {
        s1: 'provid',
        s2: 'cityid',
        s3: 'areaid',
        v1: province?province:null,
        v2: city?city:null,
        v3: null
    };
//修改商户状态
$(document).on('click','.show_merchant',function () {
    var merchant_id = $(this).closest('tr').attr('merchant_id');
    // console.log(merchant_id);

    index =layer.open({

        type:2,

        title:"审核",
        btn:['关闭'],
        yes:function(index,layero){
            layer.closeAll('iframe')
        },
        area: ['100%','100%'],

        closeBtn: 0,
        // maxmin:true,

        shadeClose: true,

        content: 'show_merchant?merchant_id='+merchant_id


    });


});
    //删除
    $(document).on('click','.del',function () {
        var tr = $(this).closest('tr')
        var id = $(this).closest('tr').attr('merchant_id');
        $data = {
            'id':id
        };
        layer.msg('你确定删除吗？', {
            time: 0 //不自动关闭
            ,btn: ['确认', '取消']
            ,yes: function(index){
                layer.close(index);
                $.get("{{url('admin/merchant/del')}}",$data,function (res) {

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

//修改商户
    $(document).on('click','.merchant_edit',function () {
        var merchant_id = $(this).closest('tr').attr('merchant_id');
        // console.log(merchant_id);

            index =layer.open({

                type:2,

                title:"编辑商户",
                btn:['关闭'],
                yes:function(index,layero){
                    layer.closeAll('iframe')
                },
                area: ['100%','100%'],

                closeBtn: 0,
                // maxmin:true,

                shadeClose: true,

                content: 'edit?id='+merchant_id


        });


    });
//绑定会员
    $(document).on('click','.merchant_user',function () {
    var phone = $(this).closest('tr').attr('phone');
    var merchant_id = $(this).closest('tr').attr('merchant_id');
    // console.log(merchant_id);

    index =layer.open({

        type:2,

        title:"绑定会员",
        btn:['关闭'],
        yes:function(index,layero){
            layer.closeAll('iframe')
        },
        area: ['1100px','600px'],

        closeBtn: 0,
        // maxmin:true,

        shadeClose: true,

        content: 'user?phone='+phone+'&merchant_id='+merchant_id


    });


});

    //查看部门
$(document).on('click','.department',function () {
    var merchant_id = $(this).closest('tr').attr('merchant_id');
    // console.log(merchant_id);

    index =layer.open({

        type:2,

        title:"部门列表",
        btn:['关闭'],
        yes:function(index,layero){
            layer.closeAll('iframe')
        },
        area: ['100%','100%'],

        closeBtn: 0,
        // maxmin:true,

        shadeClose: true,

        content: 'department?merchant_id='+merchant_id


    });


});

//查看员工
$(document).on('click','.show_staff',function () {
    var merchant_id = $(this).closest('tr').attr('merchant_id');
    // console.log(merchant_id);

    index =layer.open({

        type:2,

        title:"查看员工",
        btn:['关闭'],
        yes:function(index,layero){
            layer.closeAll('iframe')
        },
        area: ['100%','100%'],

        closeBtn: 0,
        // maxmin:true,

        shadeClose: true,

        content: '{{url('admin/staff/staff')}}?id='+merchant_id


    });


});
</script>
</body>
</html>

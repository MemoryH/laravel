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
<form class="layui-form" action="{{empty($results)?url('admin/staff/add'):url('admin/staff/edit')}}" method="post" style="width: 90%;padding-top: 20px;">
    {{csrf_field()}}
    <div class="layui-form-item">
        <label class="layui-form-label">员工名称</label>
        <div class="layui-input-block">
            <input type="text" name="staff_name" required  lay-verify="required" value="{{!empty($results)?$results[0]->staff_name:''}}" placeholder="请输入标题" autocomplete="off" class="layui-input">
            <input type="hidden" name="staff_id" required  value="{{!empty($results)?$results[0]->id:''}}" placeholder="请输入员工名称" autocomplete="off" class="layui-input">
            <input type="hidden" name="merchant_id" required  value="{{!empty($results)?$results[0]->merchant_id:$merchant_id}}" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <input type="hidden" id="department_id" name="department_id">
    <span id="res" category="{{$nodes}}"></span>
    <div class="layui-form-item">
        <label class="layui-form-label">所属部门</label>
        <div class="layui-input-block">
            <div>
                <ul id="treeDemo" class="ztree"></ul>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">所属角色</label>
        <div class="layui-input-block">
            <input type="radio" name="type" value="0" title="普通员工" {{(!empty($department_id)&&$department_id->type==0)?'checked':''}}>
            <input type="radio" name="type" value="1" title="部门经理" {{!empty($department_id)&&$department_id->type==1?'checked':''}}>
        </div>
    </div>

    <div >
        <div class="layui-form-item">
            <label class="layui-form-label">选择地区</label>
            <div class="layui-input-inline">
                <select name="provid" id="provid" lay-filter="provid" address="{{!empty($results)?$results[0]->provid:''}}">
                    <option value="">请选择省</option>
                </select>
            </div>
            <div class="layui-input-inline">
                <select name="cityid" id="cityid" lay-filter="cityid" address="{{!empty($results)?$results[0]->cityid:''}}">
                    <option value="">请选择市</option>
                </select>
            </div>
            <div class="layui-input-inline" style="display: none">
                <select name="areaid" id="areaid" lay-filter="areaid" >
                    <option value="">请选择县/区</option>
                </select>
            </div>
        </div>

    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">联系电话</label>
        <div class="layui-input-block">
            <input type="text" name="contacts_number" id="contacts_number" value="{{!empty($results)?$results[0]->contacts_number:''}}" required  lay-verify="required" placeholder="请输入电话号码" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">分润比例</label>
        <div class="layui-input-block">
            <input type="text" name="bonus" required  lay-verify="required" value="{{!empty($results)?$results[0]->bonus:''}}" placeholder="请输入分润比例" autocomplete="off" class="layui-input">
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
<script type="text/javascript" src="/ztree/js/jquery.ztree.core.js"></script>


<script>

    var province = $('#provid').attr('address');
    var city = $('#cityid').attr('address');
    var defaults = {
        s1: 'provid',
        s2: 'cityid',
        s3: 'areaid',
        v1: province?province:null,
        v2: city?city:null,
        v3: null
    };

    var zTreeObj;
    // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
    var setting = {
        data:{
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        },
        callback: {
            onClick: function(event, treeId, treeNode) {
                $('#department_id').val(treeNode.id)
            }
        },
        check: {
            enable: true,
            chkStyle: "radio",
            radioType: "all"
        }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes =JSON.parse($('#res').attr('category'));


//    console.log(zNodes);

    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    zTreeObj.expandAll(true);
    zTreeObj.selectNode(zTreeObj.getNodeByParam("id", "{{!empty($default)?$default->id:''}}", null));

    $('#contacts_number').blur(function () {
        var sMobile = $("#contacts_number").val();
        if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(sMobile))) {
            alert("不是完整的11位手机号或者正确的手机号前七位");
            $("#contacts_number").val('');

            return false;
        }
    })
</script>

</body>
</html>



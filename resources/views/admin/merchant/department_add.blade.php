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
    <link rel="stylesheet" href="/ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">

</head>
<body>


<form class="layui-form" action="{{url('admin/merchant/department_add')}}" method="post" style="width: 90%;padding-top: 20px;">
    {{csrf_field()}}
<span id="res" category="{{$nodes}}"></span>
    <div class="layui-form-item">
        <label class="layui-form-label">部门名称</label>
        <div class="layui-input-block">
            <input type="text" name="name" value="" required  lay-verify="required" placeholder="请输入部门名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">所属部门</label>
        <div class="layui-input-block">
            <div>
                <ul id="treeDemo" class="ztree"></ul>
            </div>
        </div>
    </div>
    {{--<input type="hidden" id="parent_id" required lav-verify="required" name="parent_id">--}}
    <input type="hidden" id="parent_id" name="parent_id" value="" required  lay-verify="required" placeholder="请输入部门名称" autocomplete="off" class="layui-input">
    <input type="hidden" id="" name="merchant_id" value="{{request()->get('merchant_id')}}">
    <div class="layui-form-item">
        <label class="layui-form-label">简介</label>
        <div class="layui-input-block">
            <textarea name="intro" id="" cols="30" rows="10" class="layui-textarea"></textarea>
        </div>
    </div>



    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
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
<script type="text/javascript" src="/ztree/js/jquery.ztree.core.js"></script>

<script>
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
                $('#parent_id').val(treeNode.id)
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
//console.log(zNodes);
//    var zNodes = [
//        {'id':0,'parent_id':0,'name':'顶级分类'},
//        {'id':1,'parent_id':0,'name':'顶级分类'},
//        {'id':2,'parent_id':1,'name':'顶级分类'}
//    ]
//    console.log(zNodes);

    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    zTreeObj.expandAll(true);
//    zTreeObj.selectNode(zTreeObj.getNodeByParam("id", "1", null));

</script>

</body>
</html>



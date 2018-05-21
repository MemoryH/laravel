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
    <link rel="stylesheet" href="/ztree/zTreeStyle.css" type="text/css">

</head>
<body>


<form class="layui-form" action="{{url('admin/department/add')}}" method="post" style="width: 90%;padding-top: 20px;">
    {{csrf_field()}}


    <div class="layui-form-item">
        <label class="layui-form-label">所属部门</label>
        <div class="layui-input-block">
            <div>
                <ul id="treeDemo" class="ztree"></ul>
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">简介</label>
        <div class="layui-input-block">
            <textarea name="goods_intro" id="" cols="30" rows="10" class="layui-textarea"></textarea>
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
<script type="text/javascript" src="/ztree/jquery.ztree.core.js"></script>

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
                $('#goodscategory-parent_id').val(treeNode.id)
            }
        }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes =[
        {id: 1, pId: 0, name: "[core] 基本功能 演示", open: true},
        {id: 101, pId: 1, name: "最简单的树 --  标准 JSON 数据", file: "core/standardData"},
        {id: 102, pId: 1, name: "最简单的树 --  简单 JSON 数据", file: "core/simpleData"},
        {id: 103, pId: 1, name: "不显示 连接线", file: "core/noline"},
        {id: 104, pId: 1, name: "不显示 节点 图标", file: "core/noicon"},
        {id: 105, pId: 1, name: "自定义图标 --  icon 属性", file: "core/custom_icon"},
        {id: 106, pId: 1, name: "自定义图标 --  iconSkin 属性", file: "core/custom_iconSkin"},
        {id: 107, pId: 1, name: "自定义字体", file: "core/custom_font"},
        {id: 115, pId: 1, name: "超链接演示", file: "core/url"},
        {id: 108, pId: 1, name: "异步加载 节点数据", file: "core/async"},
        {id: 109, pId: 1, name: "用 zTree 方法 异步加载 节点数据", file: "core/async_fun"},
        {id: 110, pId: 1, name: "用 zTree 方法 更新 节点数据", file: "core/update_fun"},
        {id: 111, pId: 1, name: "单击 节点 控制", file: "core/click"},
        {id: 112, pId: 1, name: "展开 / 折叠 父节点 控制", file: "core/expand"},
        {id: 113, pId: 1, name: "根据 参数 查找 节点", file: "core/searchNodes"},
        {id: 114, pId: 1, name: "其他 鼠标 事件监听", file: "core/otherMouse"}
    ] ;

    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    zTreeObj.expandAll(true);
    zTreeObj.selectNode(zTreeObj.getNodeByParam("id", "{$model->parent_id}", null));

</script>

</body>
</html>



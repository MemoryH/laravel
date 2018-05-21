var defaults = {
    s1: 'provid',
    s2: 'cityid',
    s3: 'areaid',
    v1: null,
    v2: null,
    v3: null
};
$.get("/admin/index/test",'',function (res) {

    var $form;
    var form;
    var $;
    layui.define(['jquery', 'form'], function () {
        $ = layui.jquery;
        form = layui.form();
        $form = $('form');
        treeSelect(defaults);
    });

// console.log(JSON.stringify(t));

    function treeSelect(config) {
        config.v1 = config.v1 ? config.v1 : 110000;
        config.v2 = config.v2 ? config.v2 : 110100;
        config.v3 = config.v3 ? config.v3 : 110101;
        $.each(res, function (k, v) {
            appendOptionTo($form.find('select[name=' + config.s1 + ']'), k, v.val+':'+k, config.v1);
        });
        form.render();
// if (config.v1==110000){
//
// }else {
//     cityEvent(config);
//     areaEvent(config);
// }

        if (config.v1=110000){
            // console.log(config.v1);
            form.on('select(' + config.s1 + ')', function (data) {
                cityEvent(data);
                form.on('select(' + config.s2 + ')', function (data) {
                    areaEvent(data);
                });
            });
        }


        function cityEvent(data) {
            $form.find('select[name=' + config.s2 + ']').html("");

            config.v1 = data.value ? data.value : config.v1;
// console.log(config.v1);
            $.each(res, function (k, v) {
                // console.log(config.v1);
                if (config.v1 ==110000){
                    if (v.val == config.v1) {

                        if (v.items) {

                            $.each(v.items, function (kt, vt) {

                                appendOptionTo($form.find('select[name=' + config.s2 + ']'), kt, vt.val+':'+kt, config.v2);
                            });
                        }
                    }
                }else {
                    if (v.val+':'+k == config.v1) {

                        if (v.items) {

                            $.each(v.items, function (kt, vt) {

                                appendOptionTo($form.find('select[name=' + config.s2 + ']'), kt, vt.val+':'+kt, config.v2);
                            });
                        }
                    }
                }

            });
            form.render();
            config.v2 = $('select[name=' + config.s2 + ']').val();

            areaEvent(config);
        }
        function areaEvent(data) {
            $form.find('select[name=' + config.s3 + ']').html("");
            config.v2 = data.value ? data.value : config.v2;

            $.each(res, function (k, v) {
                // console.log(config.v2)

                    if (v.val+':'+k == config.v1) {
                        if (v.items) {
                            $.each(v.items, function (kt, vt) {
                                if (vt.val+':'+kt == config.v2) {
                                    $.each(vt.items, function (ka, va) {
                                        appendOptionTo($form.find('select[name=' + config.s3 + ']'), ka, va+':'+ka, config.v3);
                                    });
                                }
                            });
                        }
                    }




            });
            form.render();
            form.on('select(' + config.s3 + ')', function (data) { });
        }
        function appendOptionTo($o, k, v, d) {
            var $opt = $("<option>").text(k).val(v);
            if (v == d) { $opt.attr("selected", "selected") }
            $opt.appendTo($o);
        }
    }
},'json')

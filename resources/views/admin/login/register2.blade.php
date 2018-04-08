<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from condorthemes.com/cleanzone/pages-sign-up.html by HTTrack Website Copier/3.x [XR&CO'2013], Mon, 31 Mar 2014 14:37:32 GMT -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/static/images/favicon.png">


    <title>易证后台管理系统</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:300,200,100' rel='stylesheet' type='text/css'>

    <!-- Bootstrap core CSS -->
    <link href="/static/js/bootstrap/dist/css/bootstrap.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="http://www.jq22.com/jquery/font-awesome.4.6.0.css">


    <!-- Custom styles for this template -->
    <link href="/static/css/style.css" rel="stylesheet" />

</head>

<body class="texture">

<div id="cl-wrapper" class="sign-up-container">

    <div class="middle-sign-up">
        <div class="block-flat">
            <div class="header">
                <h3 class="text-center"><img class="logo-img" src="/static/images/logo.png" alt="logo"/>易证后台管理系统</h3>
            </div>
            <div>
                <form style="margin-bottom: 0px ;" method="post" class="form-horizontal" action="" parsley-validate novalidate>
                    {{ csrf_field() }}
                    <div class="content">
                        <h5 class="title text-center"><strong>公司信息</strong></h5>
                        <hr/>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-hand-right"></i></span>
                                    <input type="text" name="enterprise_name" parsley-trigger="change" parsley-error-container="#nick-error" required placeholder="企业用户名" class="form-control">
                                </div>
                                <div id="nick-error"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-hand-right"></i></span>
                                    <input type="number" name="merchant" parsley-trigger="change" parsley-error-container="#email-error" required placeholder="商户名称" class="form-control">
                                </div>
                                <div id="email-error"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-hand-right"></i></span>
                                    <input type="number" name="Organization_code" parsley-trigger="change" parsley-error-container="#email-error" required placeholder="组织机构代码" class="form-control">
                                </div>
                                <div id="email-error"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-hand-right"></i></span>
                                    <input type="number" name="Identification_number" parsley-trigger="change" parsley-error-container="#email-error" required placeholder="身份识别号" class="form-control">
                                </div>
                                <div id="email-error"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-hand-right"></i></span>
                                    <input type="number" name="register_address" parsley-trigger="change" parsley-error-container="#email-error" required placeholder="注册所在地" class="form-control">
                                </div>
                                <div id="email-error"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-hand-right"></i></span>
                                    <input type="number" name="scope" parsley-trigger="change" parsley-error-container="#email-error" required placeholder="企业经营范围" class="form-control">
                                </div>
                                <div id="email-error"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-hand-right"></i></span>
                                    <input type="number" name="capital" parsley-trigger="change" parsley-error-container="#email-error" required placeholder="企业注册资金" class="form-control">
                                </div>
                                <div id="email-error"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-hand-right"></i></span>
                                    <input type="number" name="validity_time" parsley-trigger="change" parsley-error-container="#email-error" required placeholder="证件有效期" class="form-control">
                                </div>
                                <div id="email-error"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-hand-right"></i></span>
                                    <input type="number" name="address" parsley-trigger="change" parsley-error-container="#email-error" required placeholder="地址" class="form-control">
                                </div>
                                <div id="email-error"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-hand-right"></i></span>
                                    <input type="number" name="telephone" parsley-trigger="change" parsley-error-container="#email-error" required placeholder="联系电话" class="form-control">
                                </div>
                                <div id="email-error"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-hand-right"></i></span>
                                    <input type="number" name="official_seal" parsley-trigger="change" parsley-error-container="#email-error" required placeholder="公章扫描" class="form-control">
                                </div>
                                <div id="email-error"></div>
                            </div>
                        </div>


                        <div class="foot">
                            <button href="" class="btn btn-default" data-dismiss="modal" type="button" onclick="history.back();">上一步</button>
                            <button class="btn btn-primary" data-dismiss="modal" type="submit">下一步</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <div class="text-center out-links"><a href="#">&copy; 2014 Your Company</a></div>
    </div>

</div>


<script src="/static/js/jquery.js"></script>
<script src="/static/js/jquery.parsley/dist/parsley.js" type="text/javascript"></script>
<script src="/static/js/behaviour/general.js" type="text/javascript"></script>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="/static/js/behaviour/voice-commands.js"></script>
<script src="/static/js/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/static/js/jquery.flot/jquery.flot.js"></script>
<script type="text/javascript" src="/static/js/jquery.flot/jquery.flot.pie.js"></script>
<script type="text/javascript" src="/static/js/jquery.flot/jquery.flot.resize.js"></script>
<script type="text/javascript" src="/static/js/jquery.flot/jquery.flot.labels.js"></script>
</body>

<!-- Mirrored from condorthemes.com/cleanzone/pages-sign-up.html by HTTrack Website Copier/3.x [XR&CO'2013], Mon, 31 Mar 2014 14:37:32 GMT -->
</html>

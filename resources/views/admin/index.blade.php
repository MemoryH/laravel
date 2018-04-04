<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>瑞安云智能OA系统</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="Access-Control-Allow-Origin" content="*">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	<meta name="scrf_token" content="{{ csrf_token() }}">
	<link rel="icon" href="favicon.ico">
	<link rel="stylesheet" href="/layui//css/layui.css" media="all">
	<!--<link rel="stylesheet" href="//at.alicdn.com/t/font_tnyc012u2rlwstt9.css" media="all">-->
	<link rel="stylesheet" href="/css/main.css" media="all">
</head>
<body class="main_body">
	<div class="layui-layout layui-layout-admin">
		<!-- 顶部 -->
		<div class="layui-header header">
			<div class="layui-main">
				<img src="/images/oa_logo.png" class="logo" alt="瑞安云智能OA系统" style="margin: 11px 0 0 0;">
				{{--<a href="#" class="logo">瑞安云智能OA系统</a>--}}
				<!-- 显示/隐藏菜单 -->
				<a href="javascript:;" class="iconfont hideMenu icon-menu1"></a>
			    <!-- 天气信息 -->
			    <div class="weather" pc style="width: 100px;">
			    	<div id="tp-weather-widget"></div>
					<script>(function(T,h,i,n,k,P,a,g,e){g=function(){P=h.createElement(i);a=h.getElementsByTagName(i)[0];P.src=k;P.charset="utf-8";P.async=1;a.parentNode.insertBefore(P,a)};T["ThinkPageWeatherWidgetObject"]=n;T[n]||(T[n]=function(){(T[n].q=T[n].q||[]).push(arguments)});T[n].l=+new Date();if(T.attachEvent){T.attachEvent("onload",g)}else{T.addEventListener("load",g,false)}}(window,document,"script","tpwidget","//widget.seniverse.com/widget/chameleon.js"))</script>
					<script>tpwidget("init", {
					    "flavor": "slim",
					    "location": "WX4FBXXFKE4F",
					    "geolocation": "enabled",
					    "language": "zh-chs",
					    "unit": "c",
					    "theme": "chameleon",
					    "container": "tp-weather-widget",
					    "bubble": "disabled",
					    "alarmType": "badge",
					    "color": "#FFFFFF",
					    "uid": "U9EC08A15F",
					    "hash": "039da28f5581f4bcb5c799fb4cdfb673"
					});
					tpwidget("show");</script>
			    </div>
				
                {{-- 顶部循环公告 --}}
				@if(!empty($bulletin))
					<div class="bulletin" id="bulletin">
						<div class="bulletin-main">
							@foreach($bulletin as $key => $val)
								<div style="display:inline-block;">{{ $val['title'] }}</div><div style="width: 40px;height: 100%;"></div>
							@endforeach
						</div>
					</div>
				@endif


			    <!-- 顶部右侧菜单 -->
			    <ul class="layui-nav top_menu">
			    	<li class="layui-nav-item showNotice" id="showNotice" pc>
						<a href="javascript:;" data-url="/{{ $domain }}/site/bulletin"><i class="iconfont icon-gonggao"></i><cite>系统公告</cite></a>
					</li>
			    	<li class="layui-nav-item" mobile>
			    		<a href="javascript:;" class="mobileAddTab" data-url="/page/user/changePwd.html"><cite>设置</cite></a>
			    	</li>
			    	<li class="layui-nav-item" mobile>
			    		<a href="javascript:;" class="signOut"><i class="iconfont icon-loginout"></i>退出</a>
			    	</li>
					<li class="layui-nav-item" pc>
						<a href="javascript:;">
							<img src="/images/face.jpg" class="layui-circle" width="35" height="35">
							<cite>{{$userinfo->username}} ({{$userinfo->name}})</cite>
						</a>
						<dl class="layui-nav-child">
							<dd><a href="javascript:;" data-url="/admin/user/user_info"><cite>个人信息</cite></a></dd>
							<dd><a href="javascript:;" data-url="/admin/user/modify_pwd"><cite>修改密码</cite></a></dd>
							<dd><a href="javascript:;" lay-submit lay-filter="logout" class="signOut"><cite>退出</cite></a></dd>
						</dl>
					</li>
				</ul>
			</div>
		</div>
		<!-- 左侧导航 -->
		<div class="layui-side layui-bg-black">
			<div class="user-photo">
				<a class="img" title="我的头像" ><img src="/images/face.jpg"></a>
				<p><span class="userName">{{$userinfo->name}}</span></p>
			</div>
			<?php
				// print_r($menus);
			?>
			<div class="navBar layui-side-scroll">
				<div class="left-nav">
					<div id="side-nav">
						<ul id="nav">
							<li>
								<a href="javascript:;" data-url="/{{$domain}}/index/index">
									<i class="iconfont 	icon-computer" data-icon="icon-computer"></i>
									<cite>首页</cite>
								</a>
							</li>
							@foreach ($menus as $index => $menu)
							<li>
								@if ($menu['is_link'] == 1) 
									<a href="{{$menu['link_addr']}}" data-url="" target="_blank" >
									<i class="iconfont {{$menu['icon']}}"></i>
									<cite>{{$menu['name']}}</cite>
								@else
									@if (!empty($menu['_childs'])) 
										<a href="javascript:;" >
									@else
										<a href="javascript:;" data-url="/{{$domain}}/{{$menu['controller']}}/{{$menu['method']}}?m={{MD5($menu['id'].'mid')}}@if(!empty($menu['params']))&{{$menu['params']}}@endif">
									@endif
										<i class="iconfont {{$menu['icon']}}" ></i>
										<cite>{{$menu['name']}}</cite>
									@if (!empty($menu['_childs'])) 
										<i class="iconfont nav_right">&#xe697;</i>
									@endif
									</a>
									@if (!empty($menu['_childs'])) 
										<ul class="sub-menu">
										@foreach ($menu['_childs'] as $index => $child)
											<li>
												@if ($child['is_link'] == 1) 
													<a href="{{$child['link_addr']}}" data-url="" target="_blank" >
													<i class="iconfont {{$child['icon']}}"></i>
													<cite>{{$child['name']}}</cite>
												@else
													@if (!empty($child['_childs'])) 
														<a href="javascript:;" >
													@else
														<a href="javascript:;" data-url="/{{$domain}}/{{$child['controller']}}/{{$child['method']}}?m={{MD5($child['id'].'mid')}}@if(!empty($child['params']))&{{$child['params']}}@endif">
													@endif
													<i class="iconfont">&#xe6a7;</i>
													<cite>{{$child['name']}}</cite>
													@if (!empty($child['_childs'])) 
														<i class="iconfont nav_right">&#xe697;</i>
													@endif
													</a>
													@if (!empty($child['_childs'])) 
														<ul class="sub-menu">
														@foreach ($child['_childs'] as $index2 => $child2)
															<li>
																@if ($child2['is_link'] == 1) 
																	<a href="{{$child2['link_addr']}}" data-url="" target="_blank" >
																	<i class="iconfont {{$child2['icon']}}"></i>
																	<cite>{{$child2['name']}}</cite>
																@else
																	@if (!empty($child2['_childs'])) 
																		<a href="javascript:;" >
																	@else
																		<a href="javascript:;" data-url="/{{$domain}}/{{$child2['controller']}}/{{$child2['method']}}?m={{MD5($child2['id'].'mid')}}@if(!empty($child2['params']))&{{$child2['params']}}@endif">
																	@endif
																	<i class="iconfont">&#xe6a7;</i>
																	<cite>{{$child2['name']}}</cite>
																	@if (!empty($child2['_childs'])) 
																		<i class="iconfont nav_right">&#xe697;</i>
																	@endif
																</a>
																@endif
															</li>
														@endforeach
														</ul>
													@endif
												@endif
											</li>
										@endforeach
										</ul>
									@endif
								@endif
							</li>
							@endforeach
							<!-- <li>
								<a href="javascript:;">
									<i class="iconfont 	icon-text"></i>
									<cite>公文管理</cite>
									<i class="iconfont nav_right">&#xe697;</i>
								</a>
								<ul class="sub-menu">
									<li>
										<a href="javascript:;" data-url="/page/404.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>待阅公文</cite>
										</a>
										<ul class="sub-menu">
											<a href="javascript:;" data-url="/page/404.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>待阅公文2</cite>
										</a>
										</ul>
									</li>
									<li>
										<a href="javascript:;" data-url="/page/404.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>已阅公文</cite>
										</a>
									</li>
									<li>
										<a href="javascript:;" data-url="/page/404.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>发送公文</cite>
										</a>
									</li>
									<li>
										<a href="javascript:;" data-url="/page/404.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>公文类型</cite>
										</a>
									</li>
									<li>
										<a href="javascript:;" data-url="/page/404.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>公文管理</cite>
										</a>
									</li>
								</ul>
							</li>
							<li>
								<a href="javascript:;">
									<i class="iconfont icon-dongtaifensishu"></i>
									<cite>公共信息</cite>
									<i class="iconfont nav_right">&#xe697;</i>
								</a>
								<ul class="sub-menu">
									<li>
										<a href="javascript:;" data-url="/page/404.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>通讯录</cite>
										</a>
									</li >
									<li>
										<a href="javascript:;" data-url="/page/404.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>政策法规</cite>
										</a>
									</li>
								</ul>
							</li>
							<li>
								<a href="javascript:;">
									<i class="iconfont icon-shezhi1" data-icon="icon-shezhi1"></i>
									<cite>系统管理</cite>
									<i class="iconfont nav_right">&#xe697;</i>
								</a>
								<ul class="sub-menu">
									<li>
										<a href="javascript:;" data-url="/page/404.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>群组管理</cite>
										</a>
									</li >
									<li>
										<a href="javascript:;" data-url="/page/404.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>角色管理</cite>
										</a>
									</li>
									<li>
										<a href="javascript:;" data-url="/page/404.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>用户管理</cite>
										</a>
									</li>
								</ul>
							</li>
							<li>
								<a href="javascript:;" data-url="/page/404.html">
									<i class="iconfont 	icon-computer"></i>
									<cite>权限管理</cite>
								</a>
							</li>
							<li>
								<a href="javascript:;">
									<i class="iconfont icon-zhanghu"></i>
									<cite>个人中心</cite>
									<i class="iconfont nav_right">&#xe697;</i>
								</a>
								<ul class="sub-menu">
									<li>
										<a href="javascript:;" data-url="/page/user/userInfo.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>个人信息</cite>
										</a>
									</li >
									<li>
										<a href="javascript:;" data-url="/page/user/changePwd.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>修改密码</cite>
										</a>
									</li>
								</ul>
							</li>
							<li>
								<a href="javascript:;">
									<i class="iconfont icon-gonggao"></i>
									<cite>消息管理</cite>
									<i class="iconfont nav_right">&#xe697;</i>
								</a>
								<ul class="sub-menu">
									<li>
										<a href="javascript:;" data-url="/page/user/userInfo.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>公文消息</cite>
										</a>
									</li>
									<li>
										<a href="javascript:;" data-url="/page/user/userInfo.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>邮件消息</cite>
										</a>
									</li>
									<li>
										<a href="javascript:;" data-url="/page/user/changePwd.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>文件消息</cite>
										</a>
									</li>
									<li>
										<a href="javascript:;" data-url="/page/user/changePwd.html">
											<i class="iconfont">&#xe6a7;</i>
											<cite>系统消息</cite>
										</a>
									</li>
								</ul>
							</li> -->
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- 右侧内容 -->
		<div class="layui-body layui-form">
			<div class="layui-tab marg0" lay-filter="bodyTab" id="top_tabs_box">
				<ul class="layui-tab-title top_tab" id="top_tabs">
					<li class="layui-this" lay-id=""><i class="iconfont icon-computer"></i> <cite>首页</cite></li>
				</ul>
				<ul class="layui-nav closeBox">
				  <li class="layui-nav-item">
				    <a href="javascript:;"><i class="iconfont icon-caozuo"></i> 页面操作</a>
				    <dl class="layui-nav-child">
					  <dd><a href="javascript:;" class="refresh refreshThis"><i class="layui-icon">&#x1002;</i> 刷新当前</a></dd>
				      <dd><a href="javascript:;" class="closePageOther"><i class="iconfont icon-prohibit"></i> 关闭其他</a></dd>
				      <dd><a href="javascript:;" class="closePageAll"><i class="iconfont icon-guanbi"></i> 关闭全部</a></dd>
				    </dl>
				  </li>
				</ul>
				<div class="layui-tab-content clildFrame">
					<div class="layui-tab-item layui-show">
						<iframe src="/{{$domain}}/index/main"></iframe>
					</div>
				</div>
			</div>
		</div>
		<!-- 底部 -->
		<div class="layui-footer footer">
			<p>copyright @2017 瑞安云智能OA系统
				<!--　　<a onclick="donation()" class="layui-btn layui-btn-danger layui-btn-small">捐赠作者</a>-->
			</p>
		</div>
	</div>
	
	<!-- 移动导航 -->
	<div class="site-tree-mobile layui-hide"><i class="layui-icon">&#xe602;</i></div>
	<div class="site-mobile-shade"></div>	
	<script>
		var notice_flag = @if (!empty($maintain) && $maintain[0] === true) true @else false @endif ;
		var notice_str = "{{base64_decode($maintain[1])}}";
	</script>
	<script type="text/javascript" src="/layui/layui.js"></script>
	<script type="text/javascript" src="/js/leftNav.js"></script>
	<script type="text/javascript" src="/js/index.js"></script>
</body>
</html>
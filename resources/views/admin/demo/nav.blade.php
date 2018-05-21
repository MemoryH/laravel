<div class="cl-sidebar" data-position="right" data-step="1" data-intro="<strong>Fixed Sidebar</strong> <br/> It adjust to your needs." >
    <div class="cl-toggle"><i class="fa fa-bars"></i></div>
    <div class="cl-navblock">
        <div class="menu-space">
            <div class="content">
                {{--个人信息--}}
                <div class="side-user">
                    <div class="avatar"><img src="/static/images/avatar1_50.jpg" alt="Avatar" /></div>
                    <div class="info">
                        <a href="#">{{request()->session()->get('user_info')->name}}</a>
                        <img src="/static/images/state_online.png" alt="Status" /> <span>Online</span>
                    </div>
                </div>

                <ul class="cl-vnavigation">
                    <li><a href="#"><i class="fa fa-home"></i><span>订单管理</span></a>
                        <ul class="sub-menu">
                            <li class="active"><a href="{{url('admin/order/index')}}">订单列表</a></li>
                            {{--<li><a href="dashboard2.html"><span class="label label-primary pull-right">New</span> Version 2</a></li>--}}
                        </ul>
                    </li>
                    <li><a href="#"><i class="fa fa-home"></i><span>商户管理</span></a>
                        <ul class="sub-menu">
                            <li class=""><a href="#merchant" id="merchant">商户列表</a></li>
                            <li class=""><a href="{{url('admin/merchant/add')}}" id="add_merchant">新增商户</a></li>
                            {{--<li class=""><a href="{{url('Staff')}}" id="staff">商户员工列表</a></li>--}}
                            {{--<li class=""><a href="{{url('Staff')}}" id="staff_add">新增员工</a></li>--}}
                            {{--<li><a href="dashboard2.html"><span class="label label-primary pull-right">New</span> Version 2</a></li>--}}
                        </ul>
                    </li>

                </ul>
            </div>
        </div>

    </div>
</div>
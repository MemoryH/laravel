<?php
namespace App\Http\Controllers\Order;

use App\Http\Controllers\BaseController;
use App\Http\Model\Order\OrderModel;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = [];

    /* 跳过方法权限验证 */
    protected $skip_func = true;
    public function __construct()
    {
        if(request()->session()->get('user_info')){
//            var_dump(request()->session()->get('user_info'));exit;

            $roles =DB::table('company_merchant')->where(['merchant_id'=>request()->session()->get('user_info')->merchant_id])->join('role_user','company_merchant.merchant_id','=','role_user.user_id')->select('role_id')->get();
            foreach ($roles as $role){
                $role_id[] = get_object_vars($role);

            }
            foreach ($role_id as $ro){
                $data[] = $ro['role_id'];
            }
            $permissions = DB::table('permission_role')->whereIn('role_id',$data)->join('permissions','permission_role.permission_id','=','permissions.id')->get();
            foreach ($permissions as $permission){
                $permission_name[] = get_object_vars($permission);

            }
            $permission_name = array_unique(array_column($permission_name,'name'));
            foreach ($permission_name as $name){
                $this->skip_auth[]=$name;
            }
        }else{
//            echo 111;exit;
//            var_dump(request()->session()->get('user_info'));exit;
            echo redirect('admin/login/index');

        }


    }

    protected function index(){
        $data = $this->formatData(['order_sn','order_status','merchant_name'],$this->request->input());
        $citys = $this->formatData(['provid','cityid','areaid'],$this->request->input());
        if (!empty($citys)){
            $provid = explode(':',$citys['provid']);
            $data['province_id']=$provid[0];
            $city = explode(':',$citys['cityid']);
            $data['city_id']=$city[0];
            $area = explode(':',$citys['areaid']);
            $data['area_id']=$area[0];

        }

        $where = [];
        if (!empty($data['order_sn'])){
            $where['order_sn']=$data['order_sn'];
        }elseif(!empty($data['order_status'])){
            $where['order_status'] =$data['order_status'];
        }elseif (!empty($data['merchant_name'])){
            $where['merchant_name'] = $data['merchant_name'];
        }elseif (!empty($data['province_id'])){
            $where['province_id'] = $data['province_id'];
            $where['city_id'] = $data['city_id'];
            $where['area_id'] = $data['area_id'];
        }

        $orders = DB::table('order')->where($where)->get();
//        var_dump($orders);exit;
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['orders'=>$orders,]);
    }

    //详情页面
    protected function show(){
        //接收数据
        $id = request()->get('order_id');
        $order = DB::table('order')->where(['id'=>$id])->first();
        $user = DB::table('user_info')->where(['id'=>$order->user_id])->first();
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['order'=>$order,'user'=>$user]);
    }

    protected function status(){
        $status= request()->get('status');
        $order_id = request()->get('order_id');

//        var_dump($status);exit;
        if ($status==0){
            DB::table('order')->where(['id'=>$order_id])->update(['order_status'=>1]);
        }elseif ($status==1){
            DB::table('order')->where(['id'=>$order_id])->update(['order_status'=>2]);
        }elseif ($status==2){
            DB::table('order')->where(['id'=>$order_id])->update(['order_status'=>3]);
        }elseif ($status==3){
            DB::table('order')->where(['id'=>$order_id])->update(['order_status'=>4]);
        }elseif ($status==4){
            DB::table('order')->where(['id'=>$order_id])->update(['order_status'=>5]);
        }
        echo 'success';

    }

    //订单分润
    protected function bonus(){

        if (request()->isMethod('POST')){
            //接收数据
            $data = $this->formatData(['form_merchant_id','user_id','bonus_staff_id','bonus_id','order_money'],$this->request->input());


            if (!empty($data['bonus_id'])){
                $bonus = DB::table('bonus')->where(['id'=>$data['bonus_id']])->first();

                if ($bonus->type ==1){
                    $data['bonus_proportion'] = $data['order_money']*(($bonus->proportion)/100);
                }else{
                    $data['bonus_proportion'] = $bonus->proportion;
                }
            }

            $num = DB::table('bonus_order')->insert($data);
            if ($num){
                return redirect($this->domain.'/'.$this->controller.'/'.'index');
            }else{
                echo '设置失败';
            }

        }else{

            $order_id = request()->get('order_id');
            $order = DB::table('order')->where(['id'=>$order_id])->first();
            $user = DB::table('user_info')->where(['id'=>$order->user_id])->first();
            $staffs = DB::table('merchant_staff')->where(['merchant_id'=>$order->merchant_id])->get();
            $bonuses = DB::table('bonus')->where(['merchant_id'=>$order->merchant_id])->get();
            //加载视图
            return view($this->domain.'/'.$this->controller.'/'.$this->method,['order'=>$order,'user'=>$user,'staffs'=>$staffs,'bonuses'=>$bonuses]);
        }
    }
}
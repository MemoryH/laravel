<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\BaseController;
use App\Http\Model\Staff\StaffModel;
use Illuminate\Support\Facades\DB;

class StaffController extends BaseController{
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

    //展示商户对应员工
    protected function staff(){
        $id = request()->get('id');
        $res = DB::select("select * from merchant_staff WHERE `merchant_id`='{$id}'");
        $merchant = DB::table('company_merchant')->where(['company_id'=>$id])->first();
        $merchant_name='';
        if (!empty($merchant->merchant_name)){
            $merchant_name = $merchant->merchant_name;
        }

//        var_dump($res);exit;
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['staffs'=>$res,'merchant_name'=>$merchant_name,'merchant_id'=>$id]);
//        $results = [$merchant_name,$res];
//        echo json_encode($results);
    }


    //删除员工
    protected function del(){
        $id = request()->get('id');

        $res = DB::delete("delete from merchant_staff WHERE `id`=?",[$id]);
//        var_dump($res);exit;
//        return redirect($this->domain.'/'.$this->controller.'/'.'index');
        echo $res;
    }

    //新增员工
    protected function add(){
        //判断请求方式
        if (request()->isMethod('POST')){
            //实例化模型
            $staff = new StaffModel();
            //接受数据
            $departments = $this->formatData(['type','merchant_id','department_id'],$this->request->input());
            $data = $this->formatData(['staff_name','contacts_number','merchant_id','provid','cityid','bonus'],$this->request->input());
            $provid = explode(':',$data['provid']);
            $data['provid']=$provid[0];
            $data['province']=$provid[1];
            $city = explode(':',$data['cityid']);
            $data['cityid']=$city[0];
            $data['city']=$city[1];
            if ($departments['type']) {
                $data['position'] = '部门经理';
            }else{
                $data['position']='普通员工';
            }
//            var_dump($data);exit;
            //开启事务
            $staff->StartTransaction();
            $num = $staff->add($data);
//            var_dump($num);exit;

            $departments['staff_id']=$num;
            DB::table('staff_and_department')->insert([$departments]);

            if ($staff->TransactionCommit()){
                return redirect($this->domain.'/'.'staff'.'/'.'staff?id='.$data['merchant_id']);
            }else{
                $staff->TransactionRollback();
                echo '添加失败';
            }



        }else{
            $merchant_id = request()->get('merchant_id');
//var_dump($merchant_id);exit;
            $nodes = DB::table('staff_department')->where(['merchant_id'=>$merchant_id])->select(['id','parent_id','name'])->get();
//            $nodes = get_object_vars($nodes);
            $departments =[];
            foreach ($nodes as $node){
                $departments[]=get_object_vars($node);
            }
//            $departments =
//            var_dump($departments);exit;
            $parent = (array)DB::table('staff_department')->where(['parent_id'=>0])->select(['id','parent_id','name'])->first();
            $departments[] = $parent;
            return view($this->domain.'/'.$this->controller.'/'.$this->method,['merchant_id'=>$merchant_id,'nodes'=>json_encode($departments)]);
        }
    }
    //修改员工
    protected function edit(){
        if (request()->isMethod('POST')){
            //实例化模型
            $staff = new StaffModel();
            //接受数据
            $data = $this->formatData(['staff_name','contacts_number','merchant_id','province','position','provid','cityid','bonus'],$this->request->input());
            $departments = $this->formatData(['type','merchant_id','department_id'],$this->request->input());
            $provid = explode(':',$data['provid']);
            $data['provid']=$provid[0];
            $data['province']=$provid[1];
            $city = explode(':',$data['cityid']);
            $data['cityid']=$city[0];
            $data['city']=$city[1];
            //判断员工角色
            if ($departments['type']) {
                $data['position'] = '部门经理';
            }else{
                $data['position']='普通员工';
            }
//
            $id = request()->post('staff_id');
//            var_dump($data);
            $merchant_id = request()->post('merchant_id');
//            var_dump($merchant_id);exit;
            $num = DB::table('merchant_staff')
                ->where('id',$id)
                ->update($data);
            DB::table('staff_and_department')->where(['staff_id'=>$id,'merchant_id'=>$merchant_id])->update(['department_id'=>$departments['department_id']]);
            return redirect($this->domain.'/'.'staff'.'/'.'staff?id='.$merchant_id);

        }else{

            $staff_id = request()->get('staff_id');
            $id = request()->get('department_id');
            $nodes = DB::table('staff_department')->where(['merchant_id'=>request()->get('merchant_id')])->select(['id','parent_id','name'])->get();
//            $nodes = get_object_vars($nodes);
            $departments =[];
            //遍历数据拼数组
            foreach ($nodes as $node){
                $departments[]=get_object_vars($node);
            }
            $departments_id = DB::table('staff_and_department')->where(['staff_id'=>$staff_id,'merchant_id'=>request()->get('merchant_id')])->first();
//            var_dump($departments_id);exit;
            $default = DB::table('staff_department')->where(['id'=>$departments_id->department_id])->first();
//var_dump(request()->get('merchant_id'));exit;
            $parent = (array)DB::table('staff_department')->where(['parent_id'=>0])->select(['id','parent_id','name'])->first();
            $departments[] = $parent;

            $results = DB::select("select * from merchant_staff WHERE id='{$staff_id}'");
//            var_dump($results);exit;
            return view($this->domain.'/'.$this->controller.'/'.'add',['results'=>$results,'nodes'=>json_encode($departments),'default'=>$default,'department_id'=>$departments_id]);
        }
    }

}
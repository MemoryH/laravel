<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\BaseController;
use App\Http\Controllers\Rsa\AuthApiController;
use App\Http\Model\User\UserModel;
use App\Http\Model\UserPassword\UserPasswordModel;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
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

    //前台会员展示列表

    protected function index(){
        $date = $this->formatData(['phone','nick_name','e_mail'],$this->request->input());
        $where = [];
        $phone = '';
        if(!empty($date['phone'])){
            $where['phone']=$date['phone'];
            $phone=$date['phone'];
        }
        $nick_name='';
        if(!empty($date['nick_name'])){
            $where['nick_name']=$date['nick_name'];
            $nick_name = $date['nick_name'];
        }
        $e_mail = '';
        if(!empty($date['e_mail'])){
            $where['e_mail']=$date['e_mail'];
            $e_mail=$date['e_mail'];
        }
//        var_dump($where);
        $users = DB::table('user_info')->where($where)->paginate(15);
        $users->phone = $phone;
        $users->nick_name=$nick_name;
        $users->e_mail = $e_mail;
        //加载视图
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['users'=>$users]);
    }

    //会员修改
    protected function edit(){

        //通过提交方式判断
        if (request()->isMethod('POST')){
            $id = request()->post('id');

            $data = $this->formatData(['nick_name','type','true_name','phone','id_card','e_mail','em_contact','em_phone'],$this->request->input());
            $num = DB::table('user_info')
                ->where('id',$id)
                ->update($data);
            return redirect($this->domain.'/'.$this->controller.'/'.'index');
        }else{
            $id = request()->get('id');
//            var_dump($id);exit;
            $result = DB::table('user_info')->where(['id'=>$id])->get();

            return view($this->domain.'/'.$this->controller.'/'.$this->method,['result'=>$result]);
        }
    }


    //会员的添加
    protected function add(){
        //判断提交方式
        if (request()->isMethod('POST')){
            //实例化模型
            $user = new UserPasswordModel();
            $user_info = new UserModel();
            $data = $this->formatData(['nick_name','type','true_name','phone','id_card','e_mail','em_contact','em_phone'],$this->request->input());
            $user_password = $this->formatData(['password'],$this->request->input());
            //处理密码token
            $input = str_random(6);
            $password['salt'] = AuthApiController::rsaEncrypt($input,public_path().'/rsa/rsa_public_key.pem');
            //加密
            $password['psd'] = md5($user_password['password'].$input);
            $password['phone'] = $data['phone'];

//var_dump($password);exit;

            $user->StartTransaction();
            $num = $user->add($password);
//            var_dump($num);exit;
            $data['user_id']=$num;
            $user_info->add($data);
            if ($user->TransactionCommit()){
                return redirect($this->domain.'/'.$this->controller.'/'.'index');
            }else{
                $user->TransactionRollback();
                echo '添加失败';
            }
        }else{
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
        }
    }

    //会员删除
    protected function del(){
//        $id = request()->get('id');
//        $num = DB::table('user_info')
//            ->where('id',$id)
//            ->update(['status'=>1]);
//        echo $num;
    }
}
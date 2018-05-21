<?php
namespace App\Http\Controllers\Hots;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;

class HotsController extends BaseController
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

    //热词榜单
    protected function index(){

        //查询热词
        $hots_words = DB::table('hots_word')->orderBy('level','desc')->get();
        //查询搜索热词
        $searchs = DB::table('search_log')->orderBy('times','desc')->get();
        //查询热搜城市
        $citys = DB::table('hots_city')->orderBy('level','desc')->get();

        //加载页面
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['hots_words'=>$hots_words,'searchs'=>$searchs,'citys'=>$citys]);
    }
    //添加热词
    protected function add(){

        //判断传输方式
        if(request()->isMethod('POST')){
            //接收数据
            $data = $this->formatData(['name','level'],$this->request->input());
            DB::table('hots_word')->insert($data);
            return redirect($this->domain.'/'.$this->controller.'/'.'index');
        }else{
            //显示页面
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
        }
    }
    /**
     * 修改热词
     */
    protected function edit(){

        //判断提交方式
        if(request()->isMethod('POST')){
            $id = request()->post('id');
            $data = $this->formatData(['name','level'],$this->request->input());
            DB::table('hots_word')->where(['id'=>$id])->update($data);
            return redirect($this->domain.'/'.$this->controller.'/'.'index');
        }else{
            //接收数据
            $id= request()->get('id');
//            var_dump($id);exit;
            $hots_word = DB::table('hots_word')->where(['id'=>$id])->first();
            //加载页面
            return view($this->domain.'/'.$this->controller.'/'.'add',['hots_word'=>$hots_word]);
        }
    }

    /**
     * 删除热词
     */
    protected function del(){

        $id = request()->get('id');
        $num = DB::table('hots_word')->where(['id'=>$id])->delete();
       echo $num;
    }
    /*
     * 添加热搜城市
     */
    protected function add_city(){
        if (request()->isMethod('POST')){
            $res = $this->formatData(['provid','cityid','level'],$this->request->input());
            $provid = explode(':',$res['provid']);
            $data['pid']=$provid[0];
            $data['pid_name']=$provid[1];
            $city = explode(':',$res['cityid']);
            $data['city_id']=$city[0];
            $data['city_name']=$city[1];
            $data['level']=$res['level'];
            $num  = DB::table('hots_city')->insert($data);
            if ($num){
                return redirect($this->domain.'/'.$this->controller.'/'.'index');
            }else{
                echo '添加失败';
            }

        }else{

            //加载视图
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
        }
    }

    //编辑热搜城市
    protected function edit_city(){

        if (request()->isMethod('POST')){
            $id = request()->post('id');
            //接收数据
            $res = $this->formatData(['provid','cityid','level'],$this->request->input());
            $provid = explode(':',$res['provid']);
            $data['pid']=$provid[0];
            $data['pid_name']=$provid[1];
            $city = explode(':',$res['cityid']);
            $data['city_id']=$city[0];
            $data['city_name']=$city[1];
            $data['level']=$res['level'];
            $num  = DB::table('hots_city')->where(['id'=>$id])->update($data);
            if ($num){
                return redirect($this->domain.'/'.$this->controller.'/'.'index');
            }else{
                echo '添加失败';
            }
        }else{
            $id = request()->get('id');
            $hots_city = DB::table('hots_city')->where(['id'=>$id])->first();
            //加载页面
//            var_dump($hots_city);exit;
            return view($this->domain.'/'.$this->controller.'/'.'add_city',['hots_city'=>$hots_city]);
        }

    }

    //删除热搜功能
    protected function del_city(){
        $id =request()->get('id');
        $num = DB::table('hots_city')->where(['id'=>$id])->delete();
        if ($num){
            echo 'success';
        }else{
            echo 'fail';
        }
    }
}
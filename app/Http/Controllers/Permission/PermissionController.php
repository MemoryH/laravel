<?php
namespace App\Http\Controllers\Permission;


use App\Http\Controllers\BaseController;

use App\Http\Model\Rbac\Permission;
use Illuminate\Support\Facades\DB;

class PermissionController extends BaseController{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = true;

    /* 跳过方法权限验证 */
    protected $skip_func = true;

    //权限列表页面
    protected function index(){
        $permissions = DB::table('permissions')->paginate(10);
//        var_dump($permissions);exit;
        //加载视图
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['permissions'=>$permissions]);
    }

    //添加权限
    protected function add(){

        //判断提交方式
        if(request()->isMethod('POST')){
            $permission = new Permission();
            $permission->name = request()->post('name');
            $permission->display_name = request()->post('display_name');
            $permission->description = request()->post('description');
            $res = $permission->save();
            if ($res){
                return redirect($this->domain.'/'.$this->controller.'/'.'index')->withSuccess('添加成功');
            }else{
                return redirect($this->domain.'/'.$this->controller.'/'.$this->method)->withErrors('添加失败');
            }
        }else{

            //加载视图页面
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
        }
    }

    //修改权限
    protected function edit(){

        //判断请求方式
        if (request()->isMethod('POST')){
            //接收数据
            $id = request()->post('permission_id');
//            var_dump($id);exit;
            //实例化模型
            $permission = new Permission();
            $data = $this->formatData(['name','display_name','description'],$this->request->input());
            $res = DB::table('permissions')->where(['id'=>$id])->update($data);
//            var_dump($res);exit;
//            var_dump($data);exit;
//            $res = $permission->update(['id'=>$id],$data);
//            var_dump($res);exit;
            return redirect($this->domain.'/'.$this->controller.'/'.'index');
        }else{
            //查询权限数据
            $id = request()->get('permission_id');
            $results = DB::table('permissions')->where(['id'=>$id])->first();
            //加载页面
            return view($this->domain.'/'.$this->controller.'/'.'add',['results'=>$results]);
        }
    }

    //删除权限
    protected function del(){
        $permission_id = request()->get('id');
        DB::table('permissions')->where(['id'=>$permission_id])->delete();
        DB::table('permission_role')->where(['permission_id'=>$permission_id])->delete();


        echo 'success';
    }
}
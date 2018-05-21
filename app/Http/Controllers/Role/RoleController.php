<?php
 namespace App\Http\Controllers\Role;

 use App\Http\Controllers\BaseController;
 use App\Http\Model\Rbac\RoleModel;
 use Illuminate\Support\Facades\DB;

 class RoleController extends BaseController
 {
     /* 跳过登录验证 */
     protected $skip_login = true;

     /* 跳过权限验证 */
     protected $skip_auth = true;

     /* 跳过方法权限验证 */
     protected $skip_func = true;

     protected function index(){

         $roles = DB::table('roles')->get();
         //加载页面
         return view($this->domain.'/'.$this->controller.'/'.$this->method,['roles'=>$roles]);
     }

     //添加角色
     protected function add(){

         //判断提交方式
         if (request()->isMethod('POST')){
             $permissions = request()->get('permissions');
//             var_dump($permissions);exit;
             //实例化模型
             $role = new RoleModel();
             //接收数据
             $data = $this->formatData(['name','display_name','description'],$this->request->input());
             $data['created_at']= date('Y-m-d H:i:s',time());
             $data['updated_at']= date('Y-m-d H:i:s',time());
             $res = DB::table('roles')->insertGetId($data);
                $add =[];
             foreach ($permissions as $permission){
                $add[]=['role_id'=>$res,'permission_id'=>$permission];
             }
             DB::table('permission_role')->insert($add);

             return redirect($this->domain.'/'.$this->controller.'/'.'index');
         }else{
             //查询权限列表
             $permissions = DB::table('permissions')->get();
             //加载页面
             return view($this->domain.'/'.$this->controller.'/'.$this->method,['permissions'=>$permissions]);
         }
     }

     //编辑角色
     protected function edit(){

         //判断提交方式
         if(request()->isMethod('POST')){
             $id = request()->post('role_id');
             //接收数据
             $data = $this->formatData(['name','display_name','description'],$this->request->input());
             $data['updated_at']= date('Y-m-d H:i:s',time());
             $res = DB::table('roles')->where(['id'=>$id])->update($data);
             //删除之前的数据
             DB::table('permission_role')->where(['role_id'=>$id])->delete();
             $permissions = request()->get('permissions');
             $add = [];
             foreach ($permissions as $permission){
                $add[] = ['role_id'=>$id,'permission_id'=>$permission];
             }

             DB::table('permission_role')->insert($add);
             return redirect($this->domain.'/'.$this->controller.'/'.'index');

         }else{
             //接收数据
             $id = request()->get('role_id');
             $results = DB::table('roles')->where(['id'=>$id])->first();
             $permissions_id = DB::table('permission_role')->where(['role_id'=>$id])->select(['permission_id'])->get();
//             var_dump($permissions_id);exit;
             foreach ($permissions_id as $permission_id){
                 $select_permission[] = get_object_vars($permission_id)['permission_id'];
             }
//             var_dump($select_permission);exit;
             //查询权限列表
             $permissions = DB::table('permissions')->get();
             //加载页面
             return view($this->domain.'/'.$this->controller.'/'.'add',['permissions'=>$permissions,'results'=>$results,'select_permission'=>$select_permission]);
         }
     }

     //删除角色
     protected function del(){

         $id = request()->get('id');
        
         DB::table('roles')->where(['id'=>$id])->delete();
         DB::table('permission_role')->where(['role_id'=>$id])->delete();


         echo 'success';
     }

 }
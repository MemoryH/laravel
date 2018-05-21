<?php
namespace App\Http\Controllers\Group;

use App\Http\Controllers\BaseController;
use App\Http\Model\Group\GroupModel;
use App\Http\Model\Nested\NestedModel;
use Illuminate\Support\Facades\DB;

class GroupController extends BaseController
{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = true;

    /* 跳过方法权限验证 */
    protected $skip_func = true;

    //权限组列表
    protected function index(){
        $groups = DB::table('permission_group')->select(['id','name','intro'])->get();
        //加载页面
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['groups'=>$groups]);
    }

    //添加组
    protected function add(){
        if (request()->isMethod('POST')){
            $permissions = request()->get('permissions');
            $models = new GroupModel();
            //创建根节点
            $models->parent_id = $this->request->input('parent_id');
            $models->name = $this->request->input('name');
            $models->intro = $this->request->input('intro');
            $models->save();
            $id = $models->id;
//            var_dump($id);exit;
            $add =[];
            foreach ($permissions as $permission){
                $add[]=['permission_group_id'=>$id,'permission_id'=>$permission];
            }
            DB::table('permission_group_permission')->insert($add);
            return redirect($this->domain.'/'.$this->controller.'/'.'index');
        }else{

            $nodes = DB::table('permission_group')->select(['id','parent_id','name'])->get();
            $groups =[];
            foreach ($nodes as $node){
                $groups[]=get_object_vars($node);
            }
            return view($this->domain.'/'.$this->controller.'/'.$this->method,['nodes'=>json_encode($groups)]);
        }
    }
    //修改组
    protected function edit(){
        //提交方式判断
        if (request()->isMethod('POST')){
            //接受数据
            $id = request()->post('id');
            if (!empty($this->request->input('parent_id'))){
                $rses = GroupModel::where(['id'=>$id])->first()->makeChildOf($this->request->input("parent_id"));
            }
            $data = $this->formatData(['name','intro'],$this->request->input());
            DB::table('permission_group')->where(['id'=>$id])->update($data);
            //删除之前的数据
            DB::table('permission_group_permission')->where(['permission_group_id'=>$id])->delete();
            $permissions = request()->get('permissions');
            $add = [];
            foreach ($permissions as $permission){
                $add[] = ['permission_group_id'=>$id,'permission_id'=>$permission];
            }

            DB::table('permission_group_permission')->insert($add);
            return redirect($this->domain.'/'.$this->controller.'/'.'index')->with('status','编辑成功!!!');
        }else{
            $id = request()->get('group_id');
//            var_dump($id);exit;
            $nodes = DB::table('permission_group')->select(['id','parent_id','name'])->get();
//            $nodes = get_object_vars($nodes);
            $group =[];
            //遍历数据拼数组
            foreach ($nodes as $node){
                $group[]=get_object_vars($node);
            }
            $group_view = DB::table('permission_group')->where(['id'=>$id])->first();

            $permissions_id = DB::table('permission_group_permission')->where(['permission_group_id'=>$id])->select(['permission_id'])->get();
//             var_dump($permissions_id);exit;
            $select_permission=[];
            foreach ($permissions_id as $permission_id){
                $select_permission[] = get_object_vars($permission_id)['permission_id'];
            }
//             var_dump($select_permission);exit;
            //查询权限列表
            $permissions = DB::table('permissions')->get();

            return view($this->domain.'/'.$this->controller.'/'.'add',['nodes'=>json_encode($group),'group_view'=>$group_view,'permissions'=>$permissions,'select_permission'=>$select_permission]);
        }
    }

    //删除组
    protected function del(){
        $id = request()->get('id');
        if ($id==1){
            echo 'fail';
        }else{
            $res = DB::table('permission_group')->where(['parent_id'=>$id])->first();
            if (!empty($res)){
                echo 'fail';
            }else{
                GroupModel::find($id)->delete();
                echo 'success';
            }
        }

    }

    //获取权限
    protected function get_permission(){
        $permission_group_id = request()->get('id');

        if ($permission_group_id==1){
            $permissions = DB::table('permissions')->get();
        }else{
            $permissions_id = DB::table('permission_group_permission')->where(['permission_group_id'=>$permission_group_id])->get();
            $select_permission=[];
            foreach ($permissions_id as $permission_id){
                $select_permission[] = get_object_vars($permission_id)['permission_id'];
            }
            $permissions = DB::table('permissions')->whereIn('id',$select_permission)->get();
        }

        echo json_encode($permissions);
    }
    //修改时获取权限
    protected function edit_permission(){
        $permission_group_id = request()->get('parent_id');
        if ($permission_group_id==0){
            $permissions = DB::table('permissions')->get();
        }else{
            $permissions_id = DB::table('permission_group_permission')->where(['permission_group_id'=>$permission_group_id])->get();
            $select_permission=[];
            foreach ($permissions_id as $permission_id){
                $select_permission[] = get_object_vars($permission_id)['permission_id'];
            }
            $permissions = DB::table('permissions')->whereIn('id',$select_permission)->get();
        }

        echo json_encode($permissions);
    }

}
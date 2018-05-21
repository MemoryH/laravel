<?php
namespace App\Http\Controllers\Server;


use App\Http\Controllers\BaseController;
use App\Http\Model\Model\ModelModel;
use App\Http\Model\Server\ServerModel;
use Illuminate\Support\Facades\DB;

class ServerController extends BaseController{
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

    //服务列表页面
    protected function index(){
        $model_server = new ServerModel();

        $servers =$model_server->acquireServerList();
//        var_dump($servers);exit;
        //展示页面
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['servers'=>$servers]);
    }

    //添加服务
    protected function add(){

        if (request()->isMethod('POST')){
            //接收数据
            $data  = $this->formatData(['server_name','user_id','server_pid','server_desc','server_status'],$this->request->input());
            //实例化模型
            $model_server = new ServerModel();
            $model_server->appendServer($data);
            return redirect($this->domain.'/'.$this->controller.'/'.'index');

        }else{

            $merchants = DB::table('company_merchant')->select(['id','merchant_name'])->get();
            $servers = DB::table('server')->get();
            //加载页面
            return view($this->domain.'/'.$this->controller.'/'.$this->method,['merchants'=>$merchants,'servers'=>$servers]);
        }

    }

    //编辑服务
    protected function edit(){

        if (request()->isMethod('POST')){
            $id = request()->post('server_id');
            $data  = $this->formatData(['server_name','user_id','server_pid','server_desc','server_status'],$this->request->input());
            //实例化模型
            $model_server = new ServerModel();
            $model_server->modifyServer($data,['id'=>$id]);
            return redirect($this->domain.'/'.$this->controller.'/'.'index');

        }else{

            //接收数据
            $id = request()->get('server_id');
            //查询一条数据
            $results = DB::table('server')->where(['id'=>$id])->first();
            //查询下拉数据
            $merchants = DB::table('company_merchant')->select(['id','merchant_name'])->get();
            $servers = DB::table('server')->get();
            //加载视图
            return view($this->domain.'/'.$this->controller.'/'.'add',['merchants'=>$merchants,'servers'=>$servers,'results'=>$results]);
        }

    }

    //绑定模板
    protected function bind_template(){

        if (request()->isMethod('POST')){
            //接收数据
            $template_id = request()->post('template_id');
            $server_id = request()->post('server_id');
            $template_order = request()->post('template_order');
            $template_group_name = request()->post('template_group_name');
            $template_group_order = request()->post('template_group_order');
            //实例化模型

            $model_server = new ServerModel();
            $model_server->bindServerTemplate($server_id,$template_id,$template_order,['name'=>$template_group_name,'order'=>$template_group_order]);
            //加载视图
            return redirect($this->domain.'/'.$this->controller.'/'.'bind_template');
        }else{
//实例化模型
            $model = new ModelModel();
            $templates = $model->acquireTemplateList(['template_isdel'=>1]);
            $server_template = DB::table('server_template_relate')->where(['server_id'=>request()->get('server_id')])->get();

                foreach ($server_template as $server){
                    $servers[]=get_object_vars($server);
                }
                $template_ids=[];
                if (!empty($servers)){
                    $template_ids  = array_unique(array_column($servers, 'template_id'));
                }



//            var_dump($template_ids);exit;
            //加载视图
            return view($this->domain.'/'.$this->controller.'/'.$this->method,['templates'=>$templates,'template_ids'=>$template_ids]);

        }
    }
    //生成数据表
    protected function create_table(){
        //实例化模型
        $id = request()->get('server_id');
        $model_server = new ServerModel();

        $res = $model_server->createServerTable($id);
        echo $res;
    }
    //设置表名
    protected function set_table(){
        if (request()->isMethod('POST')){
            //接收数据
            $id = request()->post('server_id');
            $type = request()->post('type');

            $table_name = request()->post('table_name');



            $res = DB::table('server_system')->where(['server_id'=>$id])->update(['table_name'=>$table_name]);
            return redirect($this->domain.'/'.$this->controller.'/'.'index');
        }else{
            $id = request()->get('server_id');
            $table_name = DB::table('server_system')->select(['table_name'])->where(['server_id'=>$id])->first();
//            var_dump($table_name);exit;
            //加载页面

//            var_dump($table_name);exit;
            return view($this->domain.'/'.$this->controller.'/'.$this->method,['table_name'=>$table_name]);
        }


    }

}
<?php
namespace App\Http\Controllers\Template;

use App\Http\Controllers\BaseController;
use App\Http\Model\Model\ModelModel;
use Illuminate\Support\Facades\DB;

class TemplateController extends BaseController
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

    //展示模板列表
    protected function index(){
        $model = new ModelModel();
       $templates = $model->acquireTemplateList(['template_isdel'=>1]);
//       var_dump($templates);exit;
       //加载视图
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['templates'=>$templates]);
    }
    //添加模板
    protected function add(){

        //判断提交方式
        if (request()->isMethod('POST')){
            //实例化模型
            $model = new ModelModel();
            //接收数据
            $data = $this->formatData(['template_name','template_html','template_js','template_remark','template_status'],$this->request->input());
            $model->appendTemplate($data);
            //跳转
            return redirect($this->domain.'/'.$this->controller.'/'.'index');
        }else{

            //加载页面
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
        }
    }

    //修改模板
    protected function edit(){

        if(request()->isMethod('POST')){
            //实例化模型
            $model = new ModelModel();
            $id = request()->post('template_id');
//            var_dump($id);exit;
            //接收数据
            $data = $this->formatData(['template_name','template_html','template_js','template_remark','template_status'],$this->request->input());
            $res = $model->modifyTemplate($data,['id'=>$id]);

        }else{
            //获取id
            $id = request()->get('template_id');
            //实例化模型
            $model = new ModelModel();
            $results = $model->acquireTemplate(['id'=>$id]);
            //加载页面
            return view($this->domain.'/'.$this->controller.'/'.'add',['results'=>$results]);
        }
    }
    //删除模板
    protected function del(){
        $id = request()->get('id');
//        var_dump($id);exit;
        DB::table('module_field_template')->where(['id'=>$id])->update(['template_isdel'=>2]);
        echo 'success';
    }

    //模板字段展示列表
    protected function field_index(){
        //实例化模型
        $model = new ModelModel();
        $fields = $model->acquireFieldList(['module_isdel'=>1]);
        //加载视图
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['fields'=>$fields]);

    }

    //添加模板字段
    protected function field_add(){

        if (request()->isMethod('POST')){
            //实例化model模型
            $model = new ModelModel();
            $data = $this->formatData(['module_name','module_field','module_field_alias','module_category','module_field_status'],$this->request->input());
            $type = $this->formatData(['type','len','decimal','default','index','indexn'],$this->request->input());
            $data['module_field_attrs'] = json_encode($type);
//            var_dump($data);exit;
            $data['module_isdel']=1;
            $model->appendField($data);
            //跳转页面
            return redirect($this->domain.'/'.$this->controller.'/'.'field_index');
        }else{

            //加载视图
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
        }

    }
    //修改模块儿字段
    protected function field_edit(){

        if (request()->isMethod('POST')){
            //实例化模型
            $model = new ModelModel();
            //接收数据
            $data = $this->formatData(['module_name','module_field','module_field_alias','module_category','module_field_status'],$this->request->input());
            $id = request()->post('field_id');
            $model->modifyField($data,['id'=>$id]);
            return redirect($this->domain.'/'.$this->controller.'/'.'field_index');

        }else{
            $id = request()->get('field_id');
            //实例化模型
            $model = new ModelModel();
            $results = $model->acquireField(['id'=>$id]);
            $type = json_decode($results->module_field_attrs);
//            var_dump($type->type);exit;
            //加载试图
            return view($this->domain.'/'.$this->controller.'/'.'field_add',['results'=>$results,'type'=>$type]);
        }
    }

    //绑定模型字段
    protected function bind_field(){
        //实例化模型
        $model = new ModelModel();

        if (request()->isMethod('POST')){
            $template_id = request()->get('template_id');
            $field_id[] = request()->get('field_id');
//            var_dump($template_id);exit;
            $model->bindTemplateField($template_id,$field_id);
            echo 'success';

        }else{
            $fields = DB::table('module_field_template_relate')->where(['template_id'=>request()->get('template_id')])->get();
//            var_dump($fields);exit;
            foreach ($fields as $field){
                $template_field[]=get_object_vars($field);
            }
            $fields_ids=[];
            if (!empty($template_field)){
                $fields_ids  = array_unique(array_column($template_field, 'field_id'));
            }
            $fields = $model->acquireFieldList(['module_isdel'=>1]);
            return view($this->domain.'/'.$this->controller.'/'.$this->method,['fields'=>$fields,'fields_ids'=>$fields_ids]);
        }
    }
    //删除模板字段
    protected function field_del(){

        //接收数据
        $id = request()->get('id');
        DB::table('module_field_structure')->where(['id'=>$id])->update(['module_isdel'=>2]);
        echo 'success';
    }
}
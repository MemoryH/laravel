<?php
namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\BaseController;
use App\Http\Model\Company\CompanyModel;
use App\Http\Model\Enclosure\EnclosuresModel;
use App\Http\Model\Merchant\MerchantModel;
use App\Http\Model\Nested\NestedModel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class MerchantController extends BaseController
{

    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = ['admin/merchant/upload','admin/merchant/password'];

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

    //测试
    protected function test(){

        $roles =DB::table('company_merchant')->where(['id'=>request()->session()->get('user_info')->id])->join('role_user','company_merchant.id','=','role_user.user_id')->select('role_id')->get();
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
        var_dump($permission_name);
    }
    //展示商户列表页面
    protected function index(){
        $merchant = new MerchantModel();
        $date = $this->formatData(['category_id','type','cityid','provid','merchant_sn','status'],$this->request->input());
        $where = [];
        $category_id = '';
        if(!empty($date['category_id'])&&$date['category_id']!=0){
            $where['category_id']=$date['category_id'];
            $category_id = $date['category_id'];
        }
        $merchant_sn='';
        if(!empty($date['merchant_sn'])){
            $where['merchant_sn']=$date['merchant_sn'];
            $merchant_sn=$date['merchant_sn'];
        }
        $type = '';
        if(!empty($date['type'])&&$date['type']>=0){
            $where['type']=$date['type'];
            $type = $date['type'];
        }
//        var_dump());
        $status = '';
        if(isset($date['status'])){

            $where['status']=$date['status'];
            $status = $date['status'];
        }
        $provid='';
        $cityid='';
        if(!empty($date['provid'])&&$date['provid']!='请选择省份'){
            $provid = explode(':',$date['provid']);
            $date['provid']=$provid[0];
            $city = explode(':',$date['cityid']);
            $date['cityid']=$city[0];
            $where['provid']=$date['provid'];
            $provid=$date['provid'];
            $where['cityid']=$date['cityid'];
            $cityid=$date['cityid'];
        }



//        var_dump($where);
        $res = DB::table('company_merchant')->where($where)->paginate(15);
        $res->category_id=$category_id;
        $res->merchant_sn=$merchant_sn;
        $res->type=$type;
        $res->status=$status;
        $res->provid=$provid;
        $res->cityid=$cityid;


        return view($this->domain.'/'.$this->controller.'/'.$this->method,['res'=>$res]);
//        echo json_encode($resulets);
    }
    //删除商户
    protected function del(){

        $id = request()->get('id');

        $res = DB::table('company_merchant')->where(['merchant_id'=>$id])->delete();
        DB::table('bank')->where(['relation_id'=>$id])->delete();
        DB::table('company_legal')->where(['merchant_id'=>$id])->delete();
//        var_dump($res);exit;
//        return redirect($this->domain.'/'.$this->controller.'/'.'index');
        if ($res){
            echo 'success';
        }else{
            echo 'fail';
        }
    }
    //商户修改
    protected function edit(){
        //判断提交方式
        if (request()->isMethod('POST')){
            $merchant = new MerchantModel();
            $data = $this->formatData(['merchant_name','contacts', 'registration_number','contacts_number', 'cityid', 'provid','areaid', 'address','lng','lat','category_id', 'scope'], $this->request->input());
            $data['source']=1;
            $provid = explode(':',$data['provid']);
            $data['provid']=$provid[0];
            $data['province']=$provid[1];
            $city = explode(':',$data['cityid']);
            $data['cityid']=$city[0];
            $data['city']=$city[1];
            $city = explode(':',$data['areaid']);
            $data['areaid']=$city[0];
            $data['area']=$city[1];
            $id = request()->post('merchant_id');
            $roles = request()->post('roles');
            $enclosures = $this->formatData(['business_license'],$this->request->input());
            //查询商户的附件信息
            $results = DB::table('company_merchant')->select(['business_license'])->where(['merchant_id'=>$id])->first();
            //判断是否修改营业执照
            if (!empty($results->business_license)){
                if ($enclosures['business_license'] !=$results->business_license) {
                    DB::table('enclosure')->where(['token' => $results->business_license])->update(['path' => $enclosures['business_license']]);
                }
            }else{

                    $token = md5(microtime());
                    $res['path'] = $enclosures['business_license'];
                    $res['token'] = $token;
//                var_dump($res);exit;
                    DB::table('enclosure')->insert($res);
                    $data['business_license'] = $token;
            }
            //接收法人信息
            $legal= $this->formatData(['legal_name','legal_identity','legal_identity_end_time','legal_address'],$this->request->input());
            $legal['legal_identity_end_time']=strtotime($legal['legal_identity_end_time']);
            $legal_identity = $this->formatData(['legal_identity_photo_just','legal_identity_photo_back'],$this->request->input());
            $legal_identity_photo = DB::table('company_legal')->select(['legal_identity_photo_just','legal_identity_photo_back'])->where(['merchant_id'=>$id])->first();
            //判断是否修改身份证件
            if (!empty($legal_identity_photo->legal_identity_photo_just)){
                if ($legal_identity['legal_identity_photo_just'] !=$legal_identity_photo->legal_identity_photo_just) {
                    DB::table('enclosure')->where(['token' => $legal_identity_photo->legal_identity_photo_just])->update(['path' => $legal_identity['legal_identity_photo_just']]);
                }elseif ($legal_identity['legal_identity_photo_back'] !=$legal_identity_photo->legal_identity_photo_back){
                    DB::table('enclosure')->where(['token' => $legal_identity_photo->legal_identity_photo_back])->update(['path' => $legal_identity['legal_identity_photo_back']]);
                }
            }else{

                if (!empty($legal_identity)){

                    foreach ($legal_identity as $key =>$identity){
                        $ident['path'] = $identity;
                        $ident['token'] = md5(microtime());
                        DB::table('enclosure')->insert($ident);
                        $legal[$key] = $ident['token'];
                    }
                }
            }
            //接收银行信息
            $bank = $this->formatData(['username','bank_account','bank_address'],$this->request->input());
            //修改公共表
            $company['update_time']= time();
            //开启事务
            $merchant->StartTransaction();
            $num = DB::table('company_merchant')
                ->where('merchant_id',$id)
                ->update($data);
            //修改法人信息
            DB::table('company_legal')->where(['merchant_id'=>$id])->update($legal);
            DB::table('bank')->where(['relation_id'=>$id])->update($bank);
            DB::table('company')->where(['id'=>$id])->update($company);
            //修改银行信息
            if (!empty($roles)){
                DB::table('role_user')->where(['user_id'=>$id])->delete();
                foreach ($roles as $role){
                    DB::table('role_user')->insert(['user_id'=>$id,'role_id'=>$role]);
                }
            }


            if ($merchant->TransactionCommit()) {

                return redirect($this->domain . '/' . $this->controller . '/' . 'index');
            } else {
                $merchant->TransactionRollback();
                echo '添加失败';
            }

//            var_dump($num);exit;
            return redirect($this->domain.'/'.$this->controller.'/'.'index');
        }else{
            $roles = DB::table('roles')->get();

            $id = request()->get('id');
            $roles_id = DB::table('role_user')->where(['user_id'=>$id])->select(['role_id'])->get();
            $select_role=[];
            foreach ($roles_id as $role_id){
                $select_role[] = get_object_vars($role_id)['role_id'];
            }
            $legal = DB::table('company_legal')->where(['merchant_id'=>$id])->first();
            $result = DB::table('company_merchant')->where(['merchant_id'=>$id])->first();
            $bank = DB::table('bank')->where(['type'=>2,'relation_id'=>$id])->first();
//            var_dump($result);exit;
//            var_dump($id);exit;
            return view($this->domain.'/'.$this->controller.'/'.'add',['result'=>$result,'roles'=>$roles,'legal'=>$legal,'select_role'=>$select_role,'bank'=>$bank]);
        }
    }

    //新增商户
    protected function add()
    {
        if (request()->isMethod('POST')) {
            $merchant = new MerchantModel();
            $company = new CompanyModel();
            //商户基本信息
            $enclosure_path = new EnclosuresModel();
            $a = mt_rand(10000000, 99999999);
            $b = mt_rand(10000000, 99999999);
            $c = $a . $b;

            $time = date('Ymd', time());
            $merchant_sn = $time . $c;
            //自动生成商户标识
            $identification = md5(microtime());
            $identification = substr($identification,0,8);
            $res = (array)DB::table('company')->where(['identification'=>$identification])->first();
            if (!empty($res)){
                $identification = md5(md5($identification));
                $identification = substr($identification,0,8);

            }
//            var_dump($merchant_sn);exit;
            $merchant_user = $this->formatData(['password', 'contacts_number'], $this->request->input());
            //接收company_merchant表数据
            $data = $this->formatData(['merchant_name','contacts', 'registration_number','contacts_number', 'cityid', 'provid','areaid', 'address','lng','lat','category_id','business_time','scope'], $this->request->input());
            $data['business_time']=strtotime($data['business_time']);
            $enclosures = $this->formatData(['business_license'], $this->request->input());
            //处理文本输入歧义
            $data['merchant_name'] = addslashes($data['merchant_name']);
            $data['contacts'] = addslashes($data['contacts']);
            $data['scope'] = addslashes($data['scope']);
            //地址处理
            $provid = explode(':',$data['provid']);
            $data['provid']=$provid[0];
            $data['province']=$provid[1];
            $city = explode(':',$data['cityid']);
            $data['cityid']=$city[0];
            $data['city']=$city[1];
            $city = explode(':',$data['areaid']);
            $data['areaid']=$city[0];
            $data['area']=$city[1];
//            var_dump($provid);exit;

            $roles = request()->post('roles');
            if (!empty($enclosures)) {
                $token = md5(microtime());
                $res['path'] = $enclosures['business_license'];
                $res['token'] = $token;
//                var_dump($res);exit;
                $enclosure_path->add($res);
                $data['business_license'] = $token;

            }

            $data['merchant_sn'] = $merchant_sn;

            $data['source']=1;

            $merchant_user['password'] = password_hash($merchant_user['password'], PASSWORD_BCRYPT);
            //商户法人信息
            $legal = $this->formatData(['legal_name','legal_identity','legal_address','legal_identity_end_time'],$this->request->input());
            $legal['legal_identity_end_time']=strtotime($legal['legal_identity_end_time']);
            $legal_identity = $this->formatData(['legal_identity_photo_just','legal_identity_photo_back'],$this->request->input());
            if (!empty($legal_identity)){

                foreach ($legal_identity as $key =>$identity){
                    $ident['path'] = $identity;
                    $ident['token'] = md5(microtime());
                    $enclosure_path->add($ident);
                    $legal[$key] = $ident['token'];
                }
            }
            //银行信息
            $bank = $this->formatData(['username','bank_account','bank_address'],$this->request->input());
            $bank['type'] = 2;
            //个人 商户 公共信息
            $common =$this->formatData(['status'],$this->request->input());
            $common['identification']=$identification;
            $common['update_time'] = time();
            $common['type'] =0;

            //开启事务
            $merchant->StartTransaction();
            $num = DB::table('company')->insertGetId($common);
            $data['merchant_id']=$num;
            $merchant->add($data);
//            var_dump($num);exit;
            $merchant_user['merchant_id'] = $num;
            $legal['merchant_id']=$num;
            $bank['relation_id'] = $num;
            DB::table('company_legal')->insert($legal);
            DB::table('bank')->insert($bank);
            $company->add($merchant_user);
            if (!empty($roles)) {
                foreach ($roles as $role) {
                    DB::table('role_user')->insert(['user_id' => $num, 'role_id' => $role]);
                }
            }

            if ($merchant->TransactionCommit()) {

                return redirect($this->domain . '/' . $this->controller . '/' . 'index');
            } else {
                $merchant->TransactionRollback();
                echo '添加失败';
            }


        } else {
            $roles = DB::table('roles')->get();

            return view($this->domain . '/' . $this->controller . '/' . $this->method, ['roles' => $roles]);
        }
    }



    //图片处理
    protected function upload(){
//        var_dump($_FILES);exit;
        //实例化上传文件类
        $info = request()->file('file');
        $extension = $info->getClientOriginalExtension();
        $fileName = str_random(10).'.'.$extension;
        $dir = 'certificates/'.date('Ymd').'/';
        if (!is_dir($dir)){
            mkdir($dir,777,true);
        }
        $res = $info->move($dir, $fileName);
        $file = public_path().'/'.$dir.$fileName;

        if ($res){
            //文件保存成功 返回文件路径
            $accessKey ="TxMyeDQ095vC5DtBNrUmE_PqD-Ds6I1mz3i__KJk";
            $secretKey = "M69Cr5LgZCbmJrdmxqRfDl2qvdPYiZB8nZ6P9F7g";

            $bucket = "laravel";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = $file;
            // 上传到七牛后保存的文件名
            $key = $fileName;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if ($err !== null) {
                var_dump($err);
            } else {
                echo "http://p6y8pw5fr.bkt.clouddn.com/{$key}";
            }
//            return json_encode([
//                'url'=>$file
//            ]);
        }
    }

    //生成随机密码
    protected function password()
    {
        $str = md5(microtime());
        $password = substr($str,0,8);
        echo $password;
    }

    //改变商户状态值
    protected function status(){
        $id = request()->get('id');
        $status = request()->get('status');
        $audit_hints = request()->get('audit_hints');
        $time = time();
        if ($status==1){
            DB::table('company')->where(['id'=>$id])->update(['status'=>$status,'audit_time'=>$time]);
            echo 'success';
        }else{
            DB::table('company')->where(['id'=>$id])->update(['status'=>$status,'audit_hints'=>$audit_hints]);
            return redirect($this->domain.'/'.$this->controller.'/'.'index');
        }



    }
    protected function show_merchant(){
        //接收数据
        $merchant_id = request()->get('merchant_id');
        //查询公司类型
        $company = DB::table('company')->where(['id'=>$merchant_id])->first();
//        var_dump($merchant_id);exit;
        if ($company->type){
            //查询商户信息
            $merchant = DB::table('company_merchant')->where(['merchant_id'=>$merchant_id])->first();
            //查询商户法人信息
            $legal = DB::table('company_legal')->where(['merchant_id'=>$merchant_id])->first();
            //查询商户银行信息
            $bank = DB::table('bank')->where(['relation_id'=>$merchant_id,'type'=>2])->first();
            //加载视图
            return view($this->domain.'/'.$this->controller.'/'.$this->method,['merchant'=>$merchant,'legal'=>$legal,'bank'=>$bank]);
        }else{
            //查询个人认证信息
            $user = DB::table('user_and_merchant')->where(['merchant_id'=>$merchant_id])->select(['user_id'])->first();
            $error = '';
            if (!empty($user)){
                $user_identity = DB::table('user_identity')->where(['user_id'=>$user->user_id])->select(['personal_status'])->first();
                if ($user_identity ==1){
                    $user_info = DB::table('user_info')->where(['user_id'=>$user->user_id])->first();
                    $bank = DB::table('bank')->where(['relation_id'=>$merchant_id,'type'=>1])->first();
                    return view($this->domain.'/'.$this->controller.'/'.$this->method,['user_info'=>$user_info,'user'=>$user,'bank'=>$bank]);
                }else{
                    $error = '您的个人信息认证未通过';
                    return view($this->domain.'/'.$this->controller.'/'.$this->method,['error'=>$error]);
                }
            }else{
                $error = '您还没有认证请先认证个人信息';
                return view($this->domain.'/'.$this->controller.'/'.$this->method,['error'=>$error]);
            }
        }

    }
//关联会员
    protected function user(){

        if(request()->isMethod('POST')){
            $user_id  = request()->post('user_id');
            $merchant_id  = request()->post('merchant_id');
            $num = DB::table('user_and_merchant')->insert(['user_id'=>$user_id,'merchant_id'=>$merchant_id]);
//            var_dump($num);
        }else{
            $merchant_id = request()->get('merchant_id');
            $phone = request()->get('phone');
            $num = DB::table('user_info')->where(['phone'=>$phone])->get();
//            var_dump($num);exit;
            if (!empty($num[0])){

                $user_id = $num[0]->id;
                $relationship = DB::table('user_and_merchant')->where(['user_id'=>$user_id,'merchant_id'=>$merchant_id])->first();
            }


            return view($this->domain.'/'.$this->controller.'/'.$this->method,['users'=>$num,'merchant_id'=>$merchant_id,'relationship'=>$relationship??'']);
        }

}

//部门列表
    protected function department(){
        $department = DB::table('staff_department')->select(['id','name','intro'])->where(['merchant_id'=>request()->get('merchant_id')])->get();
        //加载页面
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['departments'=>$department]);
    }

    //添加部门
    protected function department_add(){

        if (request()->isMethod('POST')){
            $models = new NestedModel();
            //创建根节点
            $models->parent_id = $this->request->input('parent_id');
            $models->name = $this->request->input('name');
            $models->merchant_id = $this->request->input('merchant_id');
            $models->intro = $this->request->input('intro');
            $models->save();
            return redirect($this->domain.'/'.$this->controller.'/'.'index');
        }else{

            $nodes = DB::table('staff_department')->where(['merchant_id'=>request()->get('merchant_id')])->select(['id','parent_id','name'])->get();
            $parent  = (array)DB::table('staff_department')->where(['parent_id'=>0])->select(['id','parent_id','name'])->first();

//            $nodes = get_object_vars($nodes);
            $departments =[];
            foreach ($nodes as $node){
                $departments[]=get_object_vars($node);
            }

//            $departments =
//            var_dump($departments);exit;
            $departments[] = $parent;



            return view($this->domain.'/'.$this->controller.'/'.$this->method,['nodes'=>json_encode($departments)]);
        }
    }

    //删除部门
    protected function department_del(){
        $id = request()->get('id');
        $department = new NestedModel();
        $res = DB::table('staff_department')->where(['parent_id'=>$id])->first();
        if (!empty($res)){

            echo 'fail';
        }else{
            NestedModel::find($id)->delete();
            echo 'success';
        }
    }
    //修改部门
    protected function department_edit(){

        //提交方式判断
        if (request()->isMethod('POST')){
            //接受数据
            $id = request()->post('id');
            $merchant_id = request()->post('merchant_id');
            if (!empty($this->request->input('parent_id'))){
//                echo 11;exit;
                //修改节点
                $rses = NestedModel::where(['id'=>$id,'merchant_id'=>$merchant_id])->first()->makeChildOf($this->request->input("parent_id"));
            }
            $data = $this->formatData(['name','intro'],$this->request->input());
            DB::table('staff_department')->where(['id'=>$id])->update($data);
//            var_dump($rses);exit;
            return redirect($this->domain.'/'.$this->controller.'/'.'index')->with('status','编辑成功!!!');
        }else{
            $id = request()->get('department_id');
            $nodes = DB::table('staff_department')->where(['merchant_id'=>request()->get('merchant_id')])->select(['id','parent_id','name'])->get();
//            $nodes = get_object_vars($nodes);
            $departments =[];
            //遍历数据拼数组
            foreach ($nodes as $node){
                $departments[]=get_object_vars($node);
            }
            $default = DB::table('staff_department')->where(['id'=>$id])->first();
//var_dump(request()->get('merchant_id'));exit;
            $parent  = (array)DB::table('staff_department')->where(['parent_id'=>0])->select(['id','parent_id','name'])->first();
            $departments[] = $parent;
            return view($this->domain.'/'.$this->controller.'/'.$this->method,['nodes'=>json_encode($departments),'default'=>$default]);
        }
    }
    //查看部门员工
    protected function department_show(){
        //接收数据
        $department_id = request()->get('department_id');
        $merchant_id = request()->get('merchant_id');
        $res = DB::table('staff_and_department')->where(['department_id'=>$department_id,'merchant_id'=>$merchant_id])->get();

        if (isset($res[0])){
            foreach ($res as $re){
                $staff_id[] = $re->staff_id;
            }
            $departments_staff = DB::table('merchant_staff')->whereIn('id',$staff_id)->get();
            return view($this->domain.'/'.$this->controller.'/'.$this->method,['departments_staff'=>$departments_staff]);
        }else{
            return view($this->domain.'/'.$this->controller.'/'.$this->method,['departments_staff'=>[]]);
        }

    }


}
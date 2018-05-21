<?php
namespace App\Http\Controllers\Adsense;

use App\Http\Controllers\BaseController;
use App\Http\Model\Enclosure\EnclosuresModel;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class AdsenseController extends BaseController
{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = ['admin/adsense/upload'];

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
    //广告位首页
    protected function index(){
        //查询数据库
        $adsenses = DB::table('ad_position')->get();
        //加载页面
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['adsenses'=>$adsenses]);
    }
    //添加广告位
    protected function add(){
        //判断提交方式
        if (request()->isMethod('POST')){
            //接收数据
            $data = $this->formatData(['position_name','position_label'],$this->request->input());
            $num = DB::table('ad_position')->insert($data);
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
    //编辑广告位
    protected function edit(){

        //判断接收数据
        if (request()->isMethod('POST')){
            //接收数据
            $adsense_id = request()->post('adsense_id');
            $data = $this->formatData(['position_name','position_label'],$this->request->input());
            $re = DB::table('ad_position')->where(['id'=>$adsense_id])->update($data);
            if ($re){
                return redirect($this->domain.'/'.$this->controller.'/'.'index');
            }else{
                echo '修改失败';
            }

        }else{
            //接收id
            $id = request()->get('adsense_id');
            $adsense = DB::table('ad_position')->where(['id'=>$id])->first();
            //加载页面
            return view($this->domain.'/'.$this->controller.'/'.'add',['adsense'=>$adsense]);
        }
    }
    //删除广告位
    protected function del(){
        //接收数据
        $id = request()->post('id');
        $re = DB::table('ad_position')->where(['id'=>$id])->delete();
        if ($re){
            echo 'success';

        }else{
            echo 'fail';
        }
    }
    //查看广告
    protected function show_ad(){
        //接收数据
        $adsense_id = request()->get('adsense_id');
        //查询数据
        $ads = DB::table('ad')->where(['pid'=>$adsense_id])->get();
        //加载页面
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['ads'=>$ads]);
    }
    //添加广告
    protected function add_ad(){

        //判断提交方式
        if (request()->isMethod('POST')){
            $enclosure_path = new EnclosuresModel();
            //接收数据
            $data = $this->formatData(['ad_name','pid','ad_link'],$this->request->input());
            $enclosures = request()->post('ad_code');
            $token = md5(microtime());
            $res['token'] = $token;
            $res['path']=$enclosures;
            $data['ad_code'] =$token;
//var_dump($data['adsense_id']);exit;
            $enclosure_path->StartTransaction();
            $num = $enclosure_path->add($res);
            DB::table('ad')->insert($data);


            if ($enclosure_path->TransactionCommit()) {

                return redirect($this->domain . '/' . $this->controller . '/' . 'show_ad');
            } else {
                $enclosure_path->TransactionRollback();
                echo '添加失败';
            }

        }else{

            //加载页面
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
        }
    }

    //编辑广告
    protected function edit_ad(){
        //判断提交方式
        if (request()->isMethod('POST')){
            //接收数据
            $data = $this->formatData(['ad_name','ad_link'],$this->request->input());
//            var_dump(strlen(request()->post('ad_code')));exit;
            $id = request()->post('ad_id');

            if (strlen(request()->post('ad_code')) !=32){
                $re = DB::table('ad')->where(['ad_id'=>$id])->select(['ad_code'])->first();
//                var_dump($re);exit;
                $path = request()->post('ad_code');
                $results = DB::table('enclosure')->where(['token'=>$re->ad_code])->update(['path'=>$path]);
            }
            $num = DB::table('ad')->where(['ad_id'=>$id])->update($data);
            if ($num ||$results){
                return redirect($this->domain.'/'.$this->controller.'/'.'index');
            }else{
                echo '修改失败';
            }
        }else{
            //接收数据
            $ad_id = request()->get('ad_id');
            $ad = DB::table('ad')->where(['ad_id'=>$ad_id])->first();
            //加载视图
            return view($this->domain.'/'.$this->controller.'/'.'add_ad',['ad'=>$ad]);
        }
    }
    //删除广告
    protected function del_ad(){
        $id = request()->post('id');
        $re = DB::table('ad')->where(['ad_id'=>$id])->delete();
        if ($re){
            echo 'success';
        }else{
            echo 'fail';
        }
    }

    //图片处理
    protected function upload(){

//        var_dump($_FILES);exit;
        //实例化上传文件类
        $info = request()->file('file');
        $extension = $info->getClientOriginalExtension();
        $fileName = str_random(10).'.'.$extension;
        $dir = 'advertisement/'.date('Ymd').'/';
        if (!is_dir($dir)){
            mkdir($dir,777,true);
        }
        $res = $info->move($dir, $fileName);
        $file = public_path().'/'.$dir.$fileName;

        if ($res){
            //文件保存成功 返回文件路径
            $accessKey ="TxMyeDQ095vC5DtBNrUmE_PqD-Ds6I1mz3i__KJk";
            $secretKey = "M69Cr5LgZCbmJrdmxqRfDl2qvdPYiZB8nZ6P9F7g";

            $bucket = "advertisement";
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

                echo "http://p8eeonveo.bkt.clouddn.com/{$key}";
            }
//            return json_encode([
//                'url'=>$file
//            ]);
        }
    }


}
<?php
namespace App\Http\Controllers\Index;


use App\Http\Controllers\BaseController;
use App\Http\Model\Server\ServerModel;
use Illuminate\Support\Facades\DB;

class IndexController extends BaseController
{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = true;

    /* 跳过方法权限验证 */
    protected $skip_func = true;

//    protected function insert(){
////        DB::table('service_ceshi1')->insert();
//        $model_server = new ServerModel();
//        print_r($model_server->appendServerData(10, ['create_date'=>'2018-10-10','update_date'=>'2018-10-11','tindex'=>1,'user_id'=>1,'state'=>1,'isdel'=>1,'title'=>1,'introduction'=>1,'name'=>1]));
//    }

    //返回json三级联动地址数据
    protected function test(){
        $json =  file_get_contents('js/data.js');

        echo $json;
    }

    //地图demo
    protected function address(){
        return view($this->domain.'/'.$this->controller.'/'.$this->method);
    }
    protected function index(){
//        var_dump($this->is_login);exit;
//        var_dump($_COOKIE);exit;
//        return view($this->domain.'/'.$this->controller.'/'.$this->method);
        if(request()->session()->get('user_info')){
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
        }else{
//            var_dump(request()->session()->get('user_info'));exit;
            return redirect($this->domain.'/login/index');

        }

    }


    //获取附件资源
    protected function enclosure(){
        $token = request()->get('enclosure');
        $path = DB::table('enclosure')->select('path')->where(['token'=>$token])->first();
        $info = getimagesize($path->path);

        $imgExt = image_type_to_extension($info[2], false); //获取文件后缀

        $fun = "imagecreatefrom{$imgExt}";
        $imgInfo = $fun($path->path);         //1.由文件或 URL 创建一个新图象。如:imagecreatefrompng ( string $filename )

        //$mime = $info['mime'];
//        $mime = image_type_to_mime_type(exif_imagetype($path->path)); //获取图片的 MIME 类型
//        var_dump($info['mime']);exit;
        $mime = $info['mime'];
        header('Content-Type:'.$mime);
        $quality = 100;
        if($imgExt == 'png') $quality = 9;   //输出质量,JPEG格式(0-100),PNG格式(0-9)
        $getImgInfo = "image{$imgExt}";
        $getImgInfo($imgInfo, null, $quality); //2.将图像输出到浏览器或文件。如: imagepng ( resource $image )
        imagedestroy($imgInfo);
    }


//    //修改个人信息
//    protected function information(){
//        if (request()->isMethod('post')){
//
//        }else{
////            var_dump(request()->session()->get('user_info'));exit;
//            return view($this->domain.'/'.$this->controller.'/'.$this->method);
//        }
//    }

    //修改密码
    protected function password(){
        if (request()->isMethod('post')){

            $data = $this->formatData(['username','old_password','password'],$this->request->input());
            $res = DB::table('company_user')->where(['merchant_id'=>request()->session()->get('user_info')->id])->first();
            //验证旧密码
            if(password_verify($data['old_password'],$res->password)){
                $password = password_hash($data['password'],PASSWORD_BCRYPT);
                DB::table('company_user')->where(['merchant_id'=>request()->session()->get('user_info')->id])->update(['password'=>$password]);
                request()->session()->remove('user_info');
                //跳转到登陆页面
                return redirect('admin/index/index');
            }else{
                return redirect($this->domain.'/'.$this->controller.'/'.'index');
            }
        }else{
//            var_dump(request()->session()->get('user_info'));exit;
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
        }
    }




}
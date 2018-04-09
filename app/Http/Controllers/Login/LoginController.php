<?php
namespace App\Http\Controllers\Login;


use App\Http\Controllers\BaseController;
use App\Http\Model\Login\UserModel;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class LoginController extends BaseController
{
    protected $redirectPath = '/admin/index/index';

    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = true;

    /* 跳过方法权限验证 */
    protected $skip_func = true;

    //用户验证

    protected $rules = [
        'index' => [
            'messages' => [
                'username.required' => '需要输入用户名',
                'username.max' => '用户名最多为:max位字符',
                'username.min' => '用户名最少为:min位字符',
                'password.required' => '需要输入登录密码',
                'password.max' => '登录密码最多为:max位字符',
                'code.max' => '请输入正确验证码',
                'code.min' => '请输入正确验证码'
            ],
            'rules' => [
                'username' => 'required|max:16|min:1',
                'password' => 'required|max:600',
                'code'     => 'min:5|max:5'
            ],
        ],
        'register' => [
            'messages' => [
                'username.required' => '需要输入用户名',
                'username.max' => '用户名最多为:max位字符',
                'username.min' => '用户名最少为:min位字符',
                'password.required' => '需要输入登录密码',
                'password.max' => '登录密码最多为:max位字符',
                'password.min' => '登录密码最少为:min位字符',
                'code.max' => '请输入正确验证码',
                'code.min' => '请输入正确验证码'
            ],
            'rules' => [
                'username' => 'required|max:16|min:1',
                'password' => 'required|max:16|min:3',
                'code'     => 'min:5|max:5'
            ],
        ]
    ];
//用户登录页面
    protected function index(){
        if (request()->isMethod('post')){
            $result = $this->InitVerify('index');
            if ($result !== true) {
                $content = [
                    'code' => 417,
                    'msg'  => implode(',', $result)
                ];
                return view($this->domain.'/'.$this->controller.'/'.$this->method);

            }else{
                $username = $this->request->input('username');
//                var_dump($username);exit;
                $password = $this->request->input('password');
                $code     = $this->request->input('code');

                //验证码
                $success_code = request()->session()->get('code');
                //检测是否存在
                $success_code = isset($success_code)?$success_code:null;
                request()->session()->remove('code');

//
                if (empty($code) || strtolower($code) != strtolower($success_code)) {
                    $content =  json_encode([
                        'code' => 401,
                        'msg'  => '验证码输入错误'
                    ]);

                    return view($this->domain.'/'.$this->controller.'/'.$this->method);
                }
                $user = DB::select("select * from `user` WHERE `name`={$username}");
                if (!empty($user)){
                    if (password_verify($password,$user[0]->password)){

                        request()->session()->put('user_info',$user[0]);
                        $success_user = request()->session()->get('user_info');
//                        var_dump($success_user->id);exit;
                        return redirect('admin/index/index');
                    }else{

                    }
                }
                return view($this->domain.'/'.$this->controller.'/'.$this->method);
//                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
//
//
//                var_dump($passwordHash);
//                if (password_verify($password,$passwordHash)){
//                    echo 111;exit;
//                }


            }

        }else{
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
        }

    }
//用户注册页面
    protected function register(){
        if (request()->isMethod('post')){
//            var_dump(request()->post());exit;

            $data = $this->formatData(['telephone', 'name', 'position', 'identity_id','password'], $this->request->input());
//            var_dump($data);exit;
            if ($data){
                $user_data = [
                    'name'  => $data['name'],
                    'telephone'  => $data['telephone'],
                    'password'  => $data['password'],
                    'position'  => $data['position'],
                    'identity_id'  => $data['identity_id'],
                    'creat_time'  => time(),

                ];
                $user_data = json_encode($user_data);

                setcookie('user',$user_data,time()+3600);
                echo 1;

            }
        }

    }

    //公司信息页面
    protected function register2(){
        //公司信息页面保存



        //加载银行信息页面

        return view($this->domain.'/'.$this->controller.'/'.'register3');

    }
    //银行信息页面保存
    protected function register3(){

        return redirect('admin/login/index');
    }
    //处理文件上传
    protected function logo(){
        //实例化上传文件类
        $info = request()->file('file');
        $extension = $info->getClientOriginalExtension();
        $fileName = str_random(10).'.'.$extension;
        $dir = 'file/'.date('Ymd').'/';
        if (!is_dir($dir)){
            mkdir($dir,777,true);

        }

        $res = $info->move($dir, $fileName);
        $file = asset($dir.$fileName);





//        var_dump($result->getSaveName());exit;
//        var_dump($result);exit;
//        $result = $uploadFile->saveAs(\Yii::getAlias('@webroot') .'/'. $file, 0);
//        var_dump($result->getFilename());exit;

        if ($res){
            //文件保存成功 返回文件路径
            $accessKey ="TxMyeDQ095vC5DtBNrUmE_PqD-Ds6I1mz3i__KJk";
            $secretKey = "M69Cr5LgZCbmJrdmxqRfDl2qvdPYiZB8nZ6P9F7g";
            $bucket = "property";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = $file;
            // 上传到七牛后保存的文件名
            $key = '/'.$fileName;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//        echo "\n====> putFile result: \n";
            if ($err == null) {
                return json_encode([
                    'url'=>"http://p61el74ia.bkt.clouddn.com/{$key}"
                ]);
            } else {
                var_dump($err);
            }
//            return json_encode([
//                'url'=>$file
//            ]);
        }


    }

    //退出登陆
    protected function logout(){

        //跳转到登陆页面
        return redirect('admin/login/index');
    }
}
<?php
namespace App\Http\Controllers\Login;


use App\Http\Controllers\BaseController;
use App\Http\Model\Login\BankModel;
use App\Http\Model\Login\CompanyModel;
use App\Http\Model\Login\UserModel;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
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
                return view($this->domain.'/'.$this->controller.'/'.$this->method)->withErrors($content['msg']);

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

                    return redirect($this->domain.'/'.$this->controller.'/'.$this->method)->withErrors(['验证码错误']);
                }
                $user = DB::select("select * from `company_user` WHERE `contacts_number`={$username}");
//var_dump($user[0]);exit;
                if (!empty($user)){
                    //判断是否为平台账户
                    if (!empty(DB::table('company_merchant')->where(['contacts_number'=>$username,'source'=>2])->first())){
                        return redirect($this->domain.'/'.$this->controller.'/'.$this->method)->withErrors(['非平台账户']);
                    }
                    if (password_verify($password,$user[0]->password)){

                        $ip = $_SERVER['REMOTE_ADDR'];
                        $time = time();
                        $ip = ip2long($ip);

                        DB::update("update `company_user` SET last_login_time={$time},last_login_ip={$ip} WHERE id={$user[0]->id}");
                        $res = DB::table('company_merchant')->where(['merchant_id'=>$user[0]->merchant_id])->get();

                        request()->session()->put('user_info',$res[0]);
                        $success_user = request()->session()->get('user_info');
//                        var_dump($success_user);exit;
                        return redirect('admin/index/index');
                    }else{
                        return redirect($this->domain.'/'.$this->controller.'/'.$this->method)->withErrors(['用户名或密码错误']);
                    }
                }
                return view($this->domain.'/'.$this->controller.'/'.$this->method)->withErrors(['用户名或密码错误']);
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

            $user = $this->formatData(['telephone', 'name', 'position', 'identity_id','password'], $this->request->input());
            $company = $this->formatData(['company_name','merchant','organization_code','register_address','identification_number','scope','capital','validity_time','address','company_tel'],$this->request->input());
            $bank = $this->formatData(['username','bank_account','bank_name','bank_address','branch'],$this->request->input());
//            //            var_dump($data);exit;
//            var_dump($user,$company,$bank);
            if ($user){
                $password = password_hash($user['password'],PASSWORD_BCRYPT);
                $user_data = [
                    'name'  => $user['name'],
                    'telephone'  => $user['telephone'],
                    'password'  => $password,
                    'position'  => $user['position'],
                    'identity_id'  => $user['identity_id'],
                    'creat_time'  => time(),

                ];
                //实例化模型
                $user_model = new UserModel();
                $company_model = new CompanyModel();
                $bank_model = new BankModel();
                //开启事务
                $user_model->StartTransaction();
                $user_model->add($user_data);
                $company_model->add($company);
                $bank_model->add($bank);
                if ($user_model->TransactionCommit()){
                    echo '注册成功';
                }else{
                    $user_model->TransactionRollback();
                    echo '注册失败';
                }


            }
        }else{
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
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

            $bucket = "laravel";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = $file;
            // 上传到七牛后保存的文件名
            $key = 'my-php-logo.png';
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if ($err !== null) {
                var_dump($err);
            } else {
                var_dump($ret);
            }
//            return json_encode([
//                'url'=>$file
//            ]);
        }


    }

    //退出登陆
    protected function logout(){
        request()->session()->remove('user_info');
        //跳转到登陆页面
        return redirect('admin/login/index');
    }
}
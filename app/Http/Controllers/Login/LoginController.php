<?php
namespace App\Http\Controllers\Login;


use App\Http\Controllers\BaseController;

class LoginController extends BaseController
{
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

            }else{
                $username = $this->request->input('username');
//                var_dump($username);exit;

                $code     = $this->request->input('code');

                //验证码
                var_dump($_SESSION);exit;
                $success_code = isset($this->session['code'])?$this->session['code']:null;
                unset($this->session['code']);
                $this->request->session()->put($this->session_key, $this->session);

                if (empty($code) || strtolower($code) != strtolower($success_code)) {
                    return [
                        'code' => 401,
                        'msg'  => '验证码输入错误'
                    ];
                }
            }

        }else{
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
        }

    }
//用户注册页面
    protected function register(){
        if (request()->isMethod('post')){
//            var_dump(request()->post());exit;
            return view($this->domain.'/'.$this->controller.'/'.'register2');
        }else{
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
        }

    }
}
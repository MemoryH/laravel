<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\BaseController;
use App\Http\Model\Demo\DemoModel;
use App\Http\Services\Image;
use Illuminate\Support\Facades\DB;

//DEMO 演示
class DemoController extends BaseController
{
	
	//opensll配置文件名
	private $openssl_path = 'openssl.cnf';
	
	protected $skip_maintain = [
		'api_login', 'api_logout'
	];
	
	protected $skip_auth = [
		'index', 'api_code', 'api_login', 'api_register', 'api_logout','test'
	];
	
	protected $skip_login = [
		'index', 'api_code', 'api_login', 'api_register','test'
	];
	
	/* 验证格式消息及规则 */
	protected $rules = [
		'login' => [
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
				'code'     => 'min:4|max:4'
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
				'code'     => 'min:4|max:4'
			],
		]
	];
	
	/* DEBUG函数 */
	protected function debug_index () {
		
		//debug权限标记
		if (isset($this->session['debug']) && $this->session['debug'] === true) {
			print_r( $this->session_debug);
			print_r( $this->session);
		}
		
	}
	
	//展示页面
    protected function index () {

		if ($this->is_login) {
			return redirect($this->domain.'/index/index');
		} else {
			return view($this->domain.'/'.$this->controller.'/'.$this->method)->with('rsa', preg_replace("/[\r\n]+/i", '', $this->rsa()));
		}
	}

	//测试方法
    protected function test(){
//     $res = DB::select('select * from USER ');
//        var_dump($res);
     $tool = DB::insert('insert into USER (`name`,`age`)VALUE (?,?)',['222','11']);
     var_dump($tool);

//
    }
	
	/* 验证码函数 */
	protected function api_code () {
		
		$this->original = true;
		
		if ($this->request->isMethod('GET')) {
			
			$image = new Image();
			
			$strcode = '';
			for ($i = 0; $i < 4; $i++) {
				$strcode .= dechex(rand(0,15));
			}
			
			$this->SetDomainSession('code', strtolower($strcode));
			
			ob_start();
			
			//输出验证码图片
			$image->createVerifyImg(146, 50, strtoupper($strcode), [
				//字体颜色
				'fcolor'  => [rand(0,100), rand(0,100), rand(0,100)],
				//干扰线
				'line'    => 10,
				//倾斜度（正负）
				'rotate'  => 30,
				'size'    => 24
			]);
			
			$image_content = ob_get_clean();
			
			return response($image_content, 200, [
				'Content-Type' => 'image/jpg',
			]);
		
		}
		
		return null;
		
	}
	
	/* 登录验证 */
	protected function api_login () {
		
		if ($this->request->isMethod('POST') && isset($this->session[MD5('PRIVATE_KEY_SIGN')]) && isset($this->session[MD5('PUBLIC_KEY_SIGN')]) && !empty($this->session[MD5('PRIVATE_KEY_SIGN')]) && !empty($this->session[MD5('PUBLIC_KEY_SIGN')])) {
			
			$result = $this->InitVerify('login');
			
			if ($result !== true) {
				return [
					'code' => 417,
					'msg'  => implode(',', $result)
				];
			} else {
				
				$username = $this->request->input('username');
				
				$code     = $this->request->input('code');
				
				//验证码
				$success_code = isset($this->session['code'])?$this->session['code']:null;
				unset($this->session['code']);
				$this->request->session()->put($this->session_key, $this->session);
				
				if (empty($code) || strtolower($code) != strtolower($success_code)) {
					return [
						'code' => 401,
						'msg'  => '验证码输入错误'
					];
				}
				
				$password = MD5($this->rsa(str_replace(' ', '+', $this->request->input('password'))));
				
				$model = new UserModel();
				
				$user = $model->PowerfulCURD('current_model', 'first', ['where'=>['password', $password], 'where'=>[['password', $password], [function ($query) use ($username) {
					$query->orWhere('username', $username)->orWhere('username2', $username)->orWhere('username3', $username);
				}], 'multi'=>true]], ['id', 'username', 'name', 'reg_time', 'last_login_time', 'status']);
				
				if (!empty($user)) {
					
					if ($user->status != 1) {
						if ($user->status == 4) {
							return [
								'code' => 403,
								'msg'  => '该账号未激活，请激活后重试'
							];
						} else {
							return [
								'code' => 403,
								'msg'  => '该账号状态异常，禁止登陆'
							];
						}
					}
					
					$this->session = [];
					
					$menus_func = [];
					
					$menus = [];
					
					$roles = [];
					
					//超级管理员
					if (in_array($user->username, explode(',', env('SUPER_ADMIN', '')))) {
						
						$roles =  [];
						
						$menus =  $this->Obj2Arr($model->acquireAllCategory());
						
						$menus_func =  false;
						
					} else if (env('MAINTAIN', true) !== true) {
						
						$roles =  $this->Obj2Arr($model->queryUserRole($user->username));
						$role_ids = array_flip(array_flip(array_column($roles, 'id')));
						sort($role_ids);
						
						$menus =  $this->Obj2Arr($model->queryRoleMenu($role_ids));
						
						$menus_func =  $this->Obj2Arr($model->queryMenuFunc(array_column($roles, 'cfid')));
						
					}
					
					$this->session['sign']   = $user->username;
					$this->session['time']   = time();
					$this->session['expire'] = $this->session['time'];
					
					$exist_sign = [];
					
					foreach ($menus as $key => $val) {
						if (in_array($val['id'], $exist_sign)) {
							unset($menus[$key]);
						}
						$exist_sign[] = $val['id'];
					}
					sort($menus);
					
					$this->session[MD5($user->username.$this->session['time'].'info')]      = ['user'=> $user, 'roles' => $roles, 'menus' => $menus, 'menus_func' => $menus_func];
					$this->session[MD5($user->username.$this->session['time'].'verifymd5')] = MD5($this->session['time'].strtoupper(json_encode($this->session[MD5($user->username.$this->session['time'].'info')])));
					
					unset($this->session[MD5('PRIVATE_KEY_SIGN')], $this->session[MD5('PUBLIC_KEY_SIGN')]);
					
					$this->request->session()->put($this->session_key, $this->session);
					
					$user_log = [
						'uid'  => $user->id,
						'name' => $user->name,
						'url'  => urlencode($_SERVER['REQUEST_URI']),
						'ip_addr'      => $_SERVER['REMOTE_ADDR'],
						'record_time'  => date('Y-m-d H:i:s', $this->session['time']),
						'sign' => 4
					];
					
					$model->addLog($user_log);
					
					$user_login_time = [
						'last_login_time'  => date('Y-m-d H:i:s', time())
					];
					
					$model->modify(['id', $user->id], $user_login_time);
					
					return [
						'code' => 200,
						'msg'  => 'success'
					];
					
				}
				
				return [
					'code' => 204,
					'msg'  => '未找到用户信息'
				];
				
			}
			
		} else {
			
			return [
				'code' => 416,
				'msg'  => '无效的请求'
			];
						
		}
		
	}
	
	
	/* 注册账号 */
	protected function api_register () {
		
		if ($this->request->isMethod('POST')) {
			
			$data = $this->formatData(['p1' => 'password', 'p2' => 'password2', 'nickname'=>'name', 'status'], $this->request->input());
			
			$data_info = $this->formatData(['nickname'=>'name', 'realname', 'mobile'=>'mobile_phone', 'phone'=>'fixed_phone', 'address'], $this->request->input());
			
			if (isset($data['password'])) {
				$data['password'] = MD5($data['password']);
			}
			
			if (isset($data['password2'])) {
				$data['password2'] = MD5($data['password2'].'_own');
			}
			
			if ($result !== true) {
				return [
					'code' => 417,
					'msg'  => implode(',', $result)
				];
			} else {
				$username = $this->request->input('username');
				
				$password = MD5($this->request->input('password'));
				
				$code     = $this->request->input('code');
				
				//验证码
				$success_code = isset($this->session['code'])?$this->session['code']:null;
				unset($this->session['code']);
				$this->request->session()->put($this->session_key, $this->session);
				
				if ($code != $success_code) {
					return [
						'code' => 401,
						'msg'  => '验证码输入错误'
					];
				}
				
				$model = new UserModel();
				
				//判断用户名重复
				if ($model->acquireCount(['username', $username]) > 0) {
					return [
						'code' => 409,
						'msg'  => '用户名已经存在'
					];
				}
				
				$user_data = [
					'username'  => $username,
					'password'  => $password,
					'password2' => '',
					'name'      => $username,
					'reg_time'  => date('Y-m-d H:i:s', time()),
					'status'    => 1
				];
				
				//开启事务
				$model->StartTransaction();
				
				//执行注册业务
				if (($id = $model->add($user_data)) !== false) {
					
					$user_info_data = [
						'uid'  => $id,
						'name' => $username
					];
					
					if ($model->addUserInfo($user_info_data) !== false) {
						if ($model->TransactionCommit()) {
							return [
								'code' => 200,
								'msg'  => 'success'
							];
						}
					}
					
				}
				
				$model->TransactionRollback();
				
				return [
					'code' => 202,
					'msg'  => '注册用户信息失败'
				];
				
			}
			
		}
		
		return [
			'code' => 416,
			'msg'  => '无效的请求'
		];
		
	}
	
	/* 登出系统 */
	protected function api_logout () {
		
		if ($this->request->isMethod('GET')) {
			
			if ($this->is_login) {
				
				$this->session = [];
				$this->request->session()->put($this->session_key, $this->session);
				
				return [
					'code' => 200,
					'msg'  => 'success'
				];
				
			}
			
		}
		
		return [
			'code' => 416,
			'msg'  => '无效的请求'
		];
		
	}
	
	/* RSA 加解密 */
	private function rsa ($crypttext='') {
		
		$openssl_path = getcwd().'/'.$this->openssl_path;
		
		if (!file_exists($openssl_path)) {
			return null;
		}
		
		$config = array(
			'config' => $openssl_path
		);
		
		if (empty($crypttext)) {
			
			if (!isset($this->session[MD5('PRIVATE_KEY_SIGN')]) || !isset($this->session[MD5('PUBLIC_KEY_SIGN')]) || empty($this->session[MD5('PRIVATE_KEY_SIGN')]) || empty($this->session[MD5('PUBLIC_KEY_SIGN')])) {
			
				$res = openssl_pkey_new($config);
				
				openssl_pkey_export($res, $pri, null, $config);
				
				$d = openssl_pkey_get_details($res);
				
				$pub = $d['key'];
				
				$this->session[MD5('PRIVATE_KEY_SIGN')] = $pri;
				
				$this->session[MD5('PUBLIC_KEY_SIGN')]  = $pub;
				
				$this->request->session()->put($this->session_key, $this->session);
				
				return $pub;
			
			} else {
				return $this->session[MD5('PUBLIC_KEY_SIGN')];
			}
			
		} else {
			
			$auth_flag = false;
			
			$key_content = file_get_contents($openssl_path);
			
			$prikeyid    = isset($this->session[MD5('PRIVATE_KEY_SIGN')])?$this->session[MD5('PRIVATE_KEY_SIGN')]:'';
			
			$crypttext   = base64_decode($crypttext);
			
			$padding = OPENSSL_NO_PADDING;
			
			if (@openssl_private_decrypt($crypttext, $sourcestr, $prikeyid, $padding))    
			{   
				preg_match_all('/\0[^\0]+$/i', $sourcestr, $real_data);
				$auth_flag = @substr(rtrim($real_data[0][0], "\0"), 1);
			}
			
			return $auth_flag;
			
		}
	}
	
}

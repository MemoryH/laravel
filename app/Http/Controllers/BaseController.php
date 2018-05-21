<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

//基础类
class BaseController extends Controller
{
	
	/* 当前访问系统前缀 */
	protected $domain;
	
	/* 当前访问控制器名 */
	protected $controller;
	
	/* 当前访问方法名 */
	protected $method;
	
	/* SESSION数据 */
	protected $session;
	
	/* DEBUG SESSION数据 */
	protected $session_debug;
	
	/* SESSION存储空间key */
	protected $session_key;
	
	/* 当前用户信息 */
	protected $userinfo;
	
	/* URL请求参数对象 */
	protected $request;
	
	/* 请求参数验证规则 */
	protected $rules;
	
	/* 无结果输出 */
	protected $void = false;
	
	/* 方法结果原样输出 */
	protected $original = false;
	
	/* 跳过登录验证 */
	protected $skip_login = [];
	
	/* 跳过权限验证 */
	protected $skip_auth = [];
	
	/* 跳过方法权限验证 */
	protected $skip_func = [];
	
	/* 跳过维护时方法权限验证 */
	protected $skip_maintain = [];
	
	/* 是否展示相应的方法 */
	protected $display_func = [];
	
	/* 登录标识 */
	protected $is_login = false;
	
	/* 列配置 */
	protected $rows = [];
	
	/* 控制器入口方法 */
	public function _target_debug ($request, $domain, $controller, $method, $method_prefix='') {
		
		if (empty($this->domain)) {
			$this->domain = $domain;
		}
		
		$this->session_key       = MD5($this->domain.env('APP_KEY', ''));
		
		$this->session_debug_key = MD5($this->domain.env('APP_KEY', '').'DEBUG');
		
		$this->controller = $controller;
		
		$tmp_method = $method;
		
		if (!empty($method_prefix)) {
			$method = $method_prefix.'_'.$method;
		}
		
		$this->method     = $method;
		
		$this->request    = $request;
		
		$this->session    = $this->request->session()->get($this->session_key)?:[];
		
		$this->session_debug   = $this->request->session()->get($this->session_debug_key)?:[];
		
		$this->mid        = $this->request->input('m')?:($this->request->header('x-mid')?:-1);
		
		if (method_exists($this, $method)) {
			
			$method_type = new \ReflectionMethod($this, $method);
			
			// 确认方法是受保护的
			if ($method_type->isProtected() === true) {
				
				//是否登录
				if ($this->IsDebugLogin()) {
					
					// 执行方法
					$result = $this->$method();
					
					return $result;
				}
			}
		}
		
		if ($method_prefix == 'debug') {
			return response(json_encode(['code'=>404, 'msg'=>'未找到"'.$tmp_method.'"方法']), 200, [
				'Content-type: application/json',
			]);
		} else {
			return view('error/404')->with('method', $tmp_method);
			
		}
	}
	
	/* 控制器入口方法 */
	public function _target ($request, $domain, $controller, $method, $method_prefix='') {
		
		if (empty($this->domain)) {
			$this->domain = $domain;
		}
		
		$this->session_key = MD5($this->domain.env('APP_KEY', ''));
		
		$this->controller = $controller;
		
		$tmp_method = $method;
		
		if (!empty($method_prefix)) {
			$method = $method_prefix.'_'.$method;
		}
		
		$this->method     = $method;
		
		$this->request    = $request;
		
		$this->session    = $this->request->session()->get($this->session_key)?:[];
		
		$this->mid        = $this->request->input('m')?:($this->request->header('x-mid')?:-1);
		
		if (method_exists($this, $method)) {
			
			$method_type = new \ReflectionMethod($this, $method);
			
			// 确认方法是受保护的
			if ($method_type->isProtected() === true) {
				
				//是否登录
				if ($this->IsLogin()) {
					
					if (!empty($this->session) && isset($this->session['sign']) && !empty($this->session['sign'])) {
						$this->is_login = true;
					}
					
					//是否授权
					if ($this->IsAuth() || $this->IsFuncAuth()) {
						
						if (!empty($this->userinfo) && !in_array($this->userinfo['user']->username, explode(',', env('SUPER_ADMIN', ''))) && env('MAINTAIN', true) === true && !empty($method_prefix) && !in_array($method, $this->skip_maintain)) {
							
							$result = ['code'=>416, 'data'=>'System Maintaining'];
							
						} else {
						
							if (isset($this->userinfo['menus_func']) && $this->userinfo['menus_func']) {
								
								$ids = array_column($this->userinfo['menus'], 'id');
								$mid = 0;
								
								foreach ($ids as $id) {
									if ($this->mid == MD5($id.'mid')) {
										$mid = $id;
										break;
									}
								}
								
								foreach ($this->userinfo['menus_func'] as $func) {
									if ($mid == $func['cid']) {
										$options = !empty($func['options'])?$func['options']:'[]';
										$this->display_func[$func['key']] = json_decode($options, true);
									}
								}
								
							}
							
							// 执行方法
							$result = $this->$method();
							
							if ($result) {
								if ($this->void === true) {
									return null;
								} else if ($method_prefix == 'api' && $this->original === false) {
									$result = response(json_encode(($result?:[])), 200, [
										'Content-type: application/json',
									]);
								} else {
									if ($this->original === false) {
										$result->with('m', $this->mid);
									}
								}
							}
						
						}
						
						return $result;
					} else {
						if ($method_prefix == 'api') {
							return response(json_encode(['code'=>416, 'msg'=>'无效"'.$tmp_method.'"请求']), 200, [
								'Content-type: application/json',
							]);
						} else {
							return view('error/416')->with('method', $tmp_method);
							
						}
					}
				}
			}
		}
		
		if ($method_prefix == 'api') {
			return response(json_encode(['code'=>404, 'msg'=>'未找到"'.$tmp_method.'"方法']), 200, [
				'Content-type: application/json',
			]);
		} else {
			return view('error/404')->with('method', $tmp_method);
			
		}
	}
	
	/* 设置域名SESSION */
	protected function SetDomainSession ($key, $data) {
		
		if ($this->session[MD5('debug')] === true) {
			$this->session_debug[$key] = $data;
			$this->request->session()->put($this->session_debug_key, $this->session_debug);
		} else {
			$this->session[$key] = $data;
			$this->request->session()->put($this->session_key, $this->session);
		}
		
	}
	
	/* 设置用户SESSION */
	protected function SetUserSession ($username, $userinfo) {
		
		$this->session = [];
		
		//配置验证信息
		$this->session['sign']   = $username;
		$this->session['time']   = time();
		$this->session['expire'] = $this->session['time'];
		
		//配置用户信息
		$this->session[MD5($username.$this->session['time'].'info')]      = $userinfo;
		$this->session[MD5($username.$this->session['time'].'verifymd5')] = MD5($this->session['time'].strtoupper(json_encode($this->session[MD5($user->username.$this->session['time'].'info')])));
		
		$this->request->session()->put($this->session_key, $this->session);
		
	}
	
	//初始化参数验证
	protected function InitVerify ($key) {
		if (isset($this->rules[$key]) && isset($this->rules[$key]['rules']) && $this->rules[$key]['messages']) {
			
			//执行验证条件
			$validator = \Validator::make($this->request->all(), $this->rules[$key]['rules'], $this->rules[$key]['messages']);
			
			//判断验证结果
			if ($validator->fails()) {
				return $validator->errors()->all();
			} else {
				return true;
			}
		}
		return null;
	}
	
	/* 按关系组合数组 */
	protected function CombineArray ($datas, $pkey, $parent_id=0, &$arr_role=false, $extra=[]) {
		
		if ($arr_role !== false && count($arr_role) == 0) {
			$arr_role = [];
		}
		
		$array = [];
		
		foreach ($datas as $key=>$val) {
			$val = (array) $val;
			
			if (!is_null($val[$pkey]) && (int)$val[$pkey] === (int)$parent_id) {
				
				$array[] = $val;
				
				if ($arr_role !== false && in_array($val['id'], array_column($arr_role, 'id')) === false) {
					$_tmp = [];
					
					//额外格式数据信息返回
					foreach ($extra as $ekey => $eval) {
						if (is_numeric($ekey)) {
							if (isset($val[$eval])) {
								$_tmp[$eval] = $val[$eval];
							}
						} else {
							if (isset($val[$ekey])) {
								$_tmp[$eval] = $val[$ekey];
							}
						}
					}
					
					$arr_role[] = $_tmp;
				}
				
				if (is_object($datas[$key])) {
					$datas[$key]->$pkey = null;
				} else {
					$datas[$key][$pkey] = null;
				}
				
				$array[count($array)-1]['_childs'] = $this->combineArray($datas, $pkey, $val['id'], $arr_role);
				
			}
		}
		
		return $array;
	}
	
	/* 对象转数组 */
	protected function Obj2Arr ($object) {
		return json_decode( json_encode($object),true);
	}
	
	/* 判断登录Debug权限 */
	protected function IsDebugLogin () {
		
		$flag = false;
		
		if ($this->request->input('debug_name') === env('DEBUG_NAME', false) && $this->request->input('debug_pwd') === env('DEBUG_PWD', false)) {
			
			$this->session[MD5('debug')] = true;
			
			$this->request->session()->put($this->session_key, $this->session);
			
			$flag = true;
			
		}
		
		return $flag;
	}
	
	/* 判断是否登录 */
	protected function IsLogin () {
		
		$flag = false;
		
		$this->userinfo = [];
		
		if (isset($this->session['sign']) && isset($this->session['time']) && (time() - $this->session['time']) <= env('MAX_SESSION_EXPIRE') && (time() - $this->session['expire']) < env('SESSION_EXPIRE')) {
			
			$this->session['expire'] = time();
			
			//内容加密KEY
			$content_verify_key = MD5($this->session['sign'].$this->session['time'].'verifymd5');
			
			//用户信息防窃取KEY
			$user_info_key      = MD5($this->session['sign'].$this->session['time'].'info');
			
			if (isset($this->session[$content_verify_key]) && isset($this->session[$user_info_key])) {
				
				//用户信息加密KEY
				$encry_content_key = MD5($this->session['time'].strtoupper(json_encode($this->session[$user_info_key])));
				
				if ($encry_content_key === $this->session[$content_verify_key]) {
					$this->userinfo = $this->session[$user_info_key];
					$flag  = true;
				}
				
			}
			
		} else {
			
			if ($this->skip_login === true || in_array($this->method, $this->skip_login)) {
				$flag = true;
			}
			
			if (isset($this->session['sign']) && isset($this->session['time'])) { 
				$this->session = [];
			}
			
		}
		
		$this->request->session()->put($this->session_key, $this->session);
		
		return $flag;
	}
	
	/* 判断是否有函数授权（无视任何条件） */
	protected function IsAuth () {
		
		if ($this->IsLogin()) {
			
			if ($this->skip_auth === true || (is_array($this->skip_auth) && in_array($this->domain.'/'.$this->controller.'/'.$this->method, $this->skip_auth))) {
				return true;
			}
			
			if (!empty($this->userinfo)) {
				
				$controllers  = array_column($this->userinfo['menus'], 'controller');
				$methods      = array_column($this->userinfo['menus'], 'method');
				$ids          = array_column($this->userinfo['menus'], 'id');
				
				if (in_array($this->controller, $controllers) && in_array($this->method, $methods)) {
					foreach ($ids as $id) {
						if ($this->mid == MD5($id.'mid')) {
							return true;
						}
					}
				}
				
			}
			
		}
		
		return false;
		
	}
	
	/* 判断是否有函数授权（需要拥有完整登录信息） */
	protected function IsFuncAuth () {
		
		if (!empty($this->userinfo)) {
			
			if ($this->skip_func !== false && isset($this->userinfo['menus_func']) && $this->userinfo['menus_func'] !== false) {
				
				$skip_func = [];
				
				foreach ($this->userinfo['menus_func'] as $func) {
					if ($this->mid == MD5($func['cid'].'mid')) {
						$skip_func[] = $func['bind_method'];
					}
				}
				
				$this->skip_func = array_merge($this->skip_func, $skip_func);
				
			} else {
				$this->skip_func = false;
			}
			
			$controllers = array_column($this->userinfo['menus'], 'controller');
			
			if (in_array($this->controller, $controllers) && ($this->skip_func === false || (is_array($this->skip_func) && in_array($this->method, $this->skip_func)))) {
				return true;
			}
			
		}
		
		return false;
		
	}
	
	/* 列数据格式化 */
	protected function RowDataFormat ($datas) {
		
		$newdata = [];
		
		if (!empty($this->rows)) {
			
			foreach ($datas as $key => $data) {
				$newdata[$key] = [];
				$fields = array_column($this->rows, 'key');
				
				foreach ($fields as $field) {
					if (!empty($field) && !isset($newdata[$key][$field])) {
						$newdata[$key][$field] = isset($data[$field])?$data[$field]:'';
					}
				}
			}
			
			foreach ($newdata as $key => $data) {
				foreach ($this->rows as $attr) {
					
					if (isset($attr['key']) && !empty($data[$attr['key']]) && isset($data[$attr['key']])) {
						
						$field = $attr['key'];
						
						//字段枚举数组
						if (isset($attr['enum']) && !empty($attr['enum'])) {
							if (isset($attr['enum'][$data[$attr['key']]])) {
								$newdata[$key][$field.'_alias'] = $attr['enum'][$data[$attr['key']]];
							} else if (isset($attr['enum']['_else'])) {
								$newdata[$key][$field.'_alias'] = $attr['enum']['_else'];
							}
						}
						
						//字段长度
						if (isset($attr['maxlen']) && $attr['maxlen'] > 0) {
							if (mb_strlen($data[$attr['key']], 'UTF-8') > $attr['maxlen']) {
								$newdata[$key][$field.'_cut'] = mb_substr($data[$attr['key']], 0, $attr['maxlen'], 'UTF-8').'...';
							} else {
								$newdata[$key][$field.'_cut'] = mb_substr($data[$attr['key']], 0, $attr['maxlen'], 'UTF-8');
							}
						}
						
						//将JSON数据转为数组
						if (isset($attr['json']) && !empty($attr['json'])) {
							$json = json_decode($newdata[$key][$field], true)?:[];
							$newdata[$key][$field] = $json;
							foreach ($json as $akey => $aval) {
								$newdata[$key][$field.'.'.$akey] = $aval;
							}
						}
					}
					
				}
			}
			
		} else {
			$newdata = $datas;
		}
		
		return $newdata;
	}
	
	/* 条件格式化 equal(eq),like(like)*/
	protected function IfLikeFormat ($allow_if) {
		
		$where = [];
		
		foreach ($this->request->input() as $key => $val) {
			if (array_key_exists($key, $allow_if)) {
				if ($allow_if[$key]) {
					$where[] = [$key, 'like', $val.'%'];
				} else {
					$where[] = [$key, $val];
				}
			}
		}
		
		$where['multi'] = true;
		
		return $where;
	}
	
	/* 格式化数据 */
	protected function formatData ($format, $datas, $exclude=[]) {
		$result = [];
		
		foreach ($format as $key => $val) {
			if (is_numeric($key)) {
				if (isset($datas[$val]) && !in_array($datas[$val], $exclude)) {
					$result[$val] = $datas[$val];
				}
			} else {
				if (isset($datas[$key]) && !in_array($datas[$key], $exclude)) {
					$result[$val] = $datas[$key];
				}
			}
		}
		return $result;
	}
	
	/*创建目录(没有的目录则自动创建)
	*$dirPath 目录路径
	*$dirpower 目录权限(Linux系统下有效)
	*/
	public function createDir ($dirPath, $dirpower = 0777) {
		if (file_exists($dirPath)) {
			return true;
		}
		$pathArr = explode('/', $dirPath);
		$path = '';
		for ($i = 0; $i < count($pathArr); $i++) {
			$path .= $pathArr[$i] . '/';
			if (!file_exists($path)) {
				if (!mkdir($path, $dirpower)) {
					return false;
				}
			}
		}
		return true;
	}
	
}

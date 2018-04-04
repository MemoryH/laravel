<?php

namespace App\Http\Model;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Model\ImitationModel;

//基础类
abstract class BaseModel {
	
	//扩展方法前缀
	const EX_NAME = 'EX_';
	
	//规范约束类
	use ImitationModel;
	
	//当前模型容器
	protected $current_model;
	
	//扩展模型容器
	private $extend_model;
	
	//查询结果集
	protected $query_result = [];
	
	//是否开启事务
	protected $is_transaction = false;
	
	/* 实例化当前类模型资源 */
	protected function __construct () {
		
		if (isset($this->table_name) && !empty($this->table_name)) {
			$this->current_model = DB::table($this->table_name);
		}
		
		//验证Extend类的方法
		// if (method_exists($this, 'init')) {
			
			// $init = new \ReflectionMethod($this, 'init');
			
			// if ($init->isProtected() === true) {
				// $this->init();
			// } else {
				// exit('"init"方法必须为"protected"类型');
			// }
			
		// } else {
			// exit('请创建模型扩展类，并定义其中的"init"方法');
		// }
		
	}
	
	/* 开启打印SQL */
	protected function StartPrintSql () {
		DB::connection()->enableQueryLog();
	}
	
	/* 打印最近的SQL */
	protected function PrintSql () {
		return DB::getQueryLog();
	}
	
	/* 扩展模型类资源 */
	protected function ResetModel ($tablename='') {
		if (!empty($tablename) && in_array($tablename, $this->dbs) && isset($this->$tablename)) {
			foreach ($this->dbs as $varname => $table) {
				if ($table == $tablename) {
					$this->$varname = DB::table($tablename);
					break;
				}
			}
		} else {
			$this->current_model = DB::table($this->table_name);
		}
	}
	
	/* 扩展模型类资源 */
	protected function ExtendModel ($dbs) {
		if (!empty($dbs) && is_array($dbs)) {
			foreach ($dbs as $varname => $tablename) {
				$this->$varname = DB::table($tablename);
			}
		}
	}
	
	/* 获取扩展模型资源 */
	protected function GetExtend ($varname) {
		if (isset($this->$varname) && !empty($varname) && is_object($this->$varname)) {
			return $this->$varname;
		}
		return null;
	}
	
	/* 执行模型查询条件 */
	protected function ExecCondition (&$target, $conditions, $result_field='') {
		
		$result = [
			/* 执行成功 */
			'success'=>[],
			/* 参数为空 */
			'null'   =>[],
			/* 参数有误 */
			'fail'   =>[],
			/* 结果返回 */
			'result' => null
		];
		
		if (!empty($target) && is_object($target)) {
			
			foreach ($conditions as $type => $value) {
				if (!empty($type) && is_string($type) && !empty($value)) {
					
					$data = null;
					
					if (isset($value['multi']) && $value['multi'] === true) {
						
						unset($value['multi']);
						
						foreach ($value as $index => $list_val) {
							$data = call_user_func_array([$target, $type], $list_val);
						}
						
					} else {
						if(!is_array($value)) {
							$value = [$value];
						}
						
						$data = call_user_func_array([$target, $type], $value);
					}
					
					if ($result_field == $type) {
						$result['result'] = $data;
					}
					
					$result['success'][] = $type;
					
				} else {
					if (is_string($type) && is_array($value)) {
						
						$result['null'][] = [$type => $value];
						
					} else {
						
						$result['fail'][] = [$type => $value];
						
					}
				}
			}
		}
		
		$this->query_result = $result;
		
	}
	
	/* 获得查询结果集 */
	public function GetResult () {
		return $this->query_result;
	}
	
	/* 开始执行事务 */
	public function StartTransaction () {
		
		if ($this->is_transaction === false) {
			DB::beginTransaction();
			$this->is_transaction = true;
			return true;
		}
		
		return false;
	}
	
	/* 执行事务提交	*/
	public function TransactionCommit () {
		
		if ($this->is_transaction === true) {
			DB::commit();
			$this->is_transaction = false;
			return true;
		}
		
		return false;
	}
	
	/* 执行事务回滚 */
	public function TransactionRollback () {
		
		if ($this->is_transaction === true) {
			DB::rollBack();
			$this->is_transaction = false;
			return true;
		}
		
		return false;
	}
	
	/* 生成原生字段表达式 */
	public function PureFieldsStr ($str) {
		
		if (!empty($str)) {
			return DB::raw($str);
		}
		
		return false;
	}
	
	/* 自定义增删改查 */
	public function PowerfulCURD ($table_alias, $exec, $conditions=[], $datas=[]) {
		
		if (!empty($table_alias) && !empty($exec)) {
			
			$this->ResetModel($table_alias);
			
			if (isset($this->$table_alias)) {
				if (!empty($conditions)) {
					$this->ExecCondition($this->$table_alias, $conditions);
				}
				
				if (!empty($datas)) {
					return $this->$table_alias->$exec($datas);
				} else {
					return $this->$table_alias->$exec();
				}
			}
			
		}
		
	}
}

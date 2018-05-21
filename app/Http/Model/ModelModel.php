<?php

namespace App\Http\Model;

use DB;
use Schema;
use App\Http\Model\Model;
use App\Http\Abstracts\Model\ModelModel as ModelModelAbs;

class ModelModel extends Model {
	
	use ModelModelAbs;
	
	protected $table = 'model';
	
	/* 返回字段类型 */
	public function get_type_list () {
		return $this->field_type_name;
	}
	
	/* 读取模型列表总数 */
	public function acquireListCount ($where=array()) {
		return DB::table('model')->where($where)->count();
	}
	
	/* 读取一个模型信息 */
	public function acquire ($where=array()) {
		return DB::table('model')->where($where)->first();
	}
	
	/* 读取模型列表信息 */
	public function acquireList ($page, $limit=15, $where=array()) {
		$offset = (($page-1)*$limit)?:0;
		return DB::table('model')->where($where)->skip($offset)->take($limit)->get();
	}
	
	/* 添加模型信息 */
	public function appendModel ($data) {
		if (!empty($data) && !empty($data['table'])) {
			
			$count = DB::table('model')->where('table', '=', $data['table'])->count();
			
			if ($count == 0) {
				if (DB::table('model')->insert($data) !== false) {
					return true;
				}
			}
		}
		return false;
	}
	
	/* 修改模型信息 */
	public function modifyModel ($data, $where=array()) {
		if (!empty($data) && !empty($where)) {
			
			$old_data = (array) DB::table('model')->where($where)->first();
			
			$db = DB::table('model')->where($where);
			
			if ($db->update($data) !== false) {
				
				if (Schema::hasTable($old_data['table']) === true) {
					Schema::rename($old_data['table'], $data['table']);
				}
				
				return true;
			}
		}
		return false;
	}
	
	/* 删除模型	*/
	public function deleteModel ($model_id, $isdel=true) {
		if (!empty($model_id)) {
			
			$isdel = $isdel ? 1 : 2;
			
			$data = (array) DB::table('model')->where(['id'=>$model_id, 'isdel'=>$isdel])->first();
			
			if (DB::table('model')->where(['id'=>$model_id, 'isdel'=>$isdel])->delete()) {
				
				DB::table('model_fields')->where(['model_id'=>$model_id])->delete();
				
				DB::table('site_cate_data')->where(['model_id' =>$model_id])->delete();
				
				if (Schema::hasTable($data['table']) === true) {
					Schema::drop($data['table']);
				}
				
				return true;
			}
		}
		return false;
	}
	
	/* 读取模型字段列表总数 */
	public function acquireFieldListCount ($page, $limit=15, $where=array()) {
		return DB::table('model_fields')->where($where)->count();
	}
	
	/* 读取一个模型字段信息 */
	public function acquireField ($where=array()) {
		return DB::table('model_fields')->where($where)->first();
	}
	
	/* 读取模型字段列表信息 */
	public function acquireFieldList ($page, $limit=15, $where=array()) {
		$offset = (($page-1)*$limit)?:0;
		return DB::table('model_fields')->where($where)->skip($offset)->take($limit)->orderBy('id', 'asc')->orderBy('order', 'asc')->get();
	}
	
	/* 添加字段 */
	public function appendField ($data) {
		if (!empty($data) && !empty($data['model_id']) && !empty($data['name'])) {
			
			$count = DB::table('model_fields')->where(['model_id'=>$data['model_id'], 'name'=>$data['name']])->count();
			
			if ($count == 0) {
				if (DB::table('model_fields')->insert($data)) {
					return true;
				}
			}
		}
		return false;
	}
	
	/* 修改字段 */
	public function modifyField ($data, $where=array()) {
		if (!empty($data) && !empty($where)) {
			
			$fdata = (array) DB::table('model_fields')->where($where)->first();
			
			if (DB::table('model_fields')->where($where)->update($data) !== false) {
				
				if (isset($data['name']) && $data['name'] != $fdata['name']) {
					$this->renameField($fdata['model_id'], $fdata['name'], $data['name']);
				}
				
				return true;
			}
		}
		return false;
	}
	
	/* 删除字段 */
	public function deleteField ($field_id, $isdel=true) {
		if (!empty($field_id)) {
			
			$isdel = $isdel ? 1 : 2;
			
			$db   = DB::table('model_fields')->where(['id'=>$field_id, 'isdel'=>$isdel]);
			
			$data = (array) DB::table('model_fields')->join('model', 'model.id', '=', 'model_fields.model_id')->where(['model_fields.id'=>$field_id, 'model_fields.isdel'=>$isdel])->select('model.table', 'model_fields.name as fname')->first();
			
			if (!empty($data)) {
				if ($db->delete()) {
					
					Schema::table($data['table'], function ($table) use($data) {
						if (Schema::hasColumn($data['table'], $data['fname']) === true) {
							$table->dropColumn($data['fname']);
						}
					});
					
					return true;
				}
			}
		}
		return false;
	}
	
	/* 创建数据表 */
	public function createTable ($model_id) {
		
		$flag = false;
		
		if (!empty($model_id)) {
			
			$data   = (array) DB::table('model')->where(['id'=>$model_id, 'isdel'=>1])->first();
			
			if (!empty($data)) {
				$fields = (array) DB::table('model_fields')->where(['model_id'=>$model_id])->get();
				
				//删除表
				Schema::dropIfExists($data['table']);
				
				//创建表
				Schema::create($data['table'], function ($table) use($fields, &$flag) {
					
					//复合索引
					$group_index = [
						'1' => [
							'_name_'  => 'name',
							'_state_' => 'state',
						]
					];
					
					//默认主键
					$table->bigIncrements('id', 1);
					
					//默认名称
					$table->string('name');
					
					//默认内容
					$table->longText('content');
					
					//创建日期
					$table->integer('create_date');
					
					//修改日期
					$table->integer('update_date');
					
					//状态字段
					$table->tinyInteger('state');
					
					//软删除字段
					$table->tinyInteger('isdel');
					
					//创建表字段
					foreach ($fields as $val) {
						
						$val = (array) $val;
						
						$attrs = json_decode($val['attrs'], true);
						
						$param_length = 0;
						//获得参数个数
						foreach ($this->field_type as $len => $search) {
							if (array_search($attrs['type'], $search) !== false) {
								$param_length = $len;
								break;
							}
						}
						
						//预生成字段
						if (!empty($attrs)) {
							
							$field = null;
							
							$create_type = $attrs['type'];
							
							if ($param_length == 1) {
								$field = $table->$create_type($val['name']);
							} else if ($param_length == 2) {
								$field = $table->$create_type($val['name'], $attrs['p2']);
							} else if ($param_length == 3) {
								$field = $table->$create_type($val['name'], $attrs['p2'], $attrs['p3']);
							}
							
							if (isset($attrs['unsigned']) && !empty($attrs['unsigned']) && $attrs['unsigned'] == 1) {
								$field->unsigned();
							}
							
							if (isset($attrs['default']) && !empty($attrs['default'])) {
								$field->default($attrs['default']);
							}
							
							if (isset($attrs['index']) && !empty($attrs['index']) && isset($attrs['indexn']) && !empty($attrs['indexn'])) {
								$group_index[$attrs['index']][$attrs['indexn']][] = $val['name'];
							}
							
						}
					}
					
					//创建索引
					foreach ($group_index as $type => $index_arr) {
						foreach ($index_arr as $indexs) {
							if ($type == '1') {
								$table->index($indexs);
							} else if ($type == '2') {
								$table->unique($indexs);
							}
						}
					}
					
					$flag = true;
					
				});
			}
		}
		return $flag;
	}
	
	/* 重命名字段 */
	private function renameField ($model_id, $oldname, $newname) {
		if (!empty($model_id) && !empty($oldname) && !empty($newname)) {
			
			$data   = (array) DB::table('model')->where(['id'=>$model_id, 'isdel'=>1])->first();
			
			if (!empty($data) && Schema::hasColumn($data['table'], $oldname) === true) {
				
				DB::table('model_fields')->where(['model_id'=>$model_id, 'name'=>$oldname])->update(['name'=>$newname]);
				
				Schema::table($data['table'], function ($table) use ($oldname, $newname) {
					$table->renameColumn($oldname, $newname);
				});
				
				return true;
			}
		}
		return false;
	}
	
	/* 更新数据表 */
	public function updateTable ($model_id) {
		
		$flag = false;
		
		if (!empty($model_id)) {
			
			$data   = (array) DB::table('model')->where(['id'=>$model_id, 'isdel'=>1])->first();
			
			if (!empty($data)) {
				
				$fields = (array) DB::table('model_fields')->where(['model_id'=>$model_id])->get();
				
				//表的索引列表
				$exists_index = $this->getTableIndex($data['table'], config('database.connections.mysql.prefix'));
				
				//修改表
				Schema::table($data['table'], function ($table) use($fields, $data, $exists_index, &$flag) {
					
					//复合索引
					$group_index = [];
					
					// 修改表字段
					foreach ($fields as $val) {
						
						$val = (array) $val;
						
						$attrs = json_decode($val['attrs'], true);
						
						$param_length = 0;
						// 获得参数个数
						foreach ($this->field_type as $len => $search) {
							if (array_search($attrs['type'], $search) !== false) {
								$param_length = $len;
								break;
							}
						}
						
						// 预生成字段
						if (!empty($attrs)) {

							if ($attrs['type'] == 'char') {
								$attrs['type'] = 'string';
							}
							
							$field = null;
							
							$create_type = $attrs['type'];
							
							if ($param_length == 1) {
								$field = $table->$create_type($val['name']);
							} else if ($param_length == 2) {
								$field = $table->$create_type($val['name'], $attrs['p2']);
							} else if ($param_length == 3) {
								$field = $table->$create_type($val['name'], $attrs['p2'], $attrs['p3']);
							}
							
							if (isset($attrs['unsigned']) && !empty($attrs['unsigned']) && $attrs['unsigned'] == 1) {
								$field->unsigned();
							}
							
							if (isset($attrs['default']) && !empty($attrs['default'])) {
								$field->default($attrs['default']);
							}
							
							if (Schema::hasColumn($data['table'], $val['name']) === true) {
								//改变字段信息
								$field->change();
							}
							
							//生成复合索引数组
							$group_index[$attrs['index']][$attrs['indexn']][] = $val['name'];
							
						}
					}
					
					//添加表索引/删除存在的索引
					foreach ($group_index as $type => $index_arr) {
						foreach ($index_arr as $indexs) {
							
							$index_name = $data['table'].'_'.implode('_', $indexs).'_'.($type=='1'?'index':($type=='2'?'unique':'unknown'));
							
							if (array_key_exists($index_name, $exists_index) === true) {
								$table->dropIndex($index_name);
							}
							
							if ($type == '1') {
								$table->index($indexs);
							} else if ($type == '2') {
								$table->unique($indexs);
							}
							
						}
					}
					
					$flag = true;
					
				});
			
			}
		}
		
		return $flag;
	}
	
}
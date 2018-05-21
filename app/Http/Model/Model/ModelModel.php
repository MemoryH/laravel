<?php
namespace App\Http\Model\Model;

use DB;
use Schema;
use App\Http\Model\BaseModel;

class ModelModel extends BaseModel {
	
	protected $model_table_field        = 'module_field_structure';
	protected $model_table_template  = 'module_field_template';
	
	public function __construct () {
		
	}
	
	/*  仅需要实现类方法即可  */
	public function acquire ($where=[], $fields=[]) {}
		
	/*  仅需要实现类方法即可  */
	public function acquireList ($page, $limit=15, $where=[], $fields=[], $order=[], $group=[], $having=[]) {}
	
	/*  仅需要实现类方法即可  */
	public function acquireCount ($where = [], $group = [], $having = []) {}
	
	/*  仅需要实现类方法即可  */
	public function add ($data) {}
	
	/*  仅需要实现类方法即可  */
	public function addMore ($datas) {}
	
	/*  仅需要实现类方法即可  */
	public function modify ($where, $data) {}
	
	/*  仅需要实现类方法即可  */
	public function remove ($where) {}
	
	/* 返回字段类型 */
	public function get_type_list () {
		return $this->field_type_name;
	}
	
	/* 读取模板列表总数 */
	public function acquireTemplateListCount ($where=[]) {
		return DB::table($this->model_table_template)->where($where)->count();
	}
	
	/* 读取一个模板信息 */
	public function acquireTemplate ($where=[], $fields=['*']) {
		return DB::table($this->model_table_template)->where($where)->select($fields)->first();
	}
	
	/* 读取模板列表信息 */
	public function acquireTemplateList ($where=[], $fields=['*']) {

		return DB::table($this->model_table_template)->where($where)->orderBy('id', 'asc')->select($fields)->paginate(15);
	}
	
	/* 添加模板信息 */
	public function appendTemplate ($data) {
		
		if (!empty($data)) {
			if (($id = DB::table($this->model_table_template)->insertGetId($data)) !== false) {
				return $id;
			}
		}
		
		return false;
	}
	
	/* 修改模板信息 */
	public function modifyTemplate ($data, $where=[]) {
		
		if (!empty($data) && !empty($where)) {
			
			$old_data = (array) DB::table($this->model_table_template)->where($where)->first();
			
			$db = DB::table($this->model_table_template)->where($where);
			
			if ($db->update($data) !== false) {
				return $old_data;
			}
		}
		
		return false;
	}
	
	/* 删除模板 */
	public function deleteTemplate ($template_id, $isdel=false) {
		if (!empty($template_id)) {

			$isdel = $isdel ? 1 : 2;

			$data = (array) DB::table($this->model_table_template)->where(['id'=>$template_id, 'template_isdel'=>$isdel])->first();

			if (!empty($data)) {
				if (DB::table($this->model_table_template)->where(['id'=>$template_id, 'template_isdel'=>$isdel])->delete()) {
					return $data;
				}
			}
		}
		return false;
	}
	
	/* 绑定模板字段 */
	public function bindTemplateField ($template_id, $field_ids) {
		
		if (empty($template_id) || empty($field_ids) || !is_array($field_ids)) {
			return false;
		}
		
		$result_data = [];
		
		foreach ($field_ids as $id) {
			$result_data[] = [
				'template_id' => $template_id,
				'field_id'       => $id
			];
		}
		
		return DB::table('module_field_template_relate')->insert($result_data);
	}
	
	/* 读取模板字段列表总数 */
	public function acquireFieldListCount ($where=[]) {
		return DB::table($this->model_table_field)->where($where)->count();
	}
	
	/* 读取一个模板字段信息 */
	public function acquireField ($where=[], $fields=['*']) {
		if (!empty($where)) {
			return DB::table($this->model_table_field)->where($where)->select($fields)->first();
		}
		return false;
	}
	
	/* 读取模板字段列表信息 */
	public function acquireFieldList ($where=[], $fields=['*']) {

		return DB::table($this->model_table_field)->where($where)->orderBy('id', 'asc')->select($fields)->paginate(15);
	}
	
	/* 添加字段 */
	public function appendField ($data) {
		
		if (!empty($data) && !empty($data['module_field'])) {
			
			$md5 = md5(json_encode($data));
			
			$count = DB::table($this->model_table_field)->where(['module_field_md5'=>$md5])->count();
			
			$data['module_field_md5'] = $md5;
			
			if ($count == 0) {
				if (DB::table($this->model_table_field)->insert($data)) {
					return true;
				}
			}
		}
		return false;
	}
	
	/* 修改字段 */
	public function modifyField ($data, $where=[]) {
		
		if (!empty($data) && !empty($where)) {
			
			$result = null;
			
			if (!empty(($result = DB::table($this->model_table_field)->where($where)->update($data)))) {
				return true;
			} else {
				if ($result === 0) {
					return -2;
				}
			}
		}
		return -1;
	}
	
	/* 批量修改字段 */
	public function modifyFields ($data, $condition, $whereIn) {
		
		if (!empty($data) && !empty($condition) && !empty($whereIn)) {
			
			$result = null;
			
			if (!empty(($result = DB::table($this->model_table_field)->whereIn($condition, $whereIn)->update($data)))) {
				return true;
			} else {
				if ($result === 0) {
					return -2;
				}
			}
		}
		return -1;
	}
	
	/* 删除字段 */
	public function deleteField ($field_id, $isdel=false) {
		if (!empty($field_id)) {
			
			$isdel = $isdel ? 1 : 2;
			
			$data = $this->Obj2Arr(DB::table($this->model_table_field)->where(['id'=>$field_id, 'module_isdel'=>$isdel])->select('module_field as fname')->first());
			
			if (!empty($data)) {
				if (DB::table($this->model_table_field)->where(['id'=>$field_id, 'module_isdel'=>$isdel])->delete()) {
					DB::table('module_field_template_relate')->where('field_id', $field_id)->delete();
					return true;
				}
			}
		}
		return false;
	}
	
}
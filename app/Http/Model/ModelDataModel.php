<?php

namespace App\Http\Model;


use Illuminate\Support\Facades\DB;
use Schema;
use App\Http\Model\Model;
use App\Http\Abstracts\Model\ModelDataModel as ModelDataModelAbs;

class ModelDataModel extends BaseModel {
	
	use ModelDataModelAbs;
	
	//模型表名称
	private $model_table = null;
	
	private $model_id    = null;
	
	/* 获取模型的数据表名 */
	public function setModelTable ($model_id) {
		
		$this->model_table = '';
		
		if (!empty($model_id)) {
			$this->model_table = DB::table('model')->where(['id'=>$model_id])->pluck('table');
			$this->model_id    = $model_id;
			return $this->model_table;
		}
	}
	
	/* 获取模型数据总数 */
	public function acquireCateDatasCount ($site_cate_id, $where=array()) {
		if (!empty($site_cate_id) && !empty($this->model_table)) {
			return DB::table('site_cate_data as scd')->where(['site_cate_id'=>$site_cate_id])->join($this->model_table.' as mtable', 'scd.cate_data_id', '=', 'mtable.id')->where($where)->count();
		}
		
		return false;
	}
	
	/* 读取一个模型数据信息 */
	public function acquire ($site_cate_id, $data_id) {
		if (!empty($site_cate_id) && !empty($this->model_table)) {
			return DB::table('site_cate_data as scd')->where(['site_cate_id'=>$site_cate_id])->join($this->model_table.' as mtable', 'scd.cate_data_id', '=', 'mtable.id')->where(['mtable.id'=>$data_id])->first();
		}
		return false;
	}
	
	/* 读取模型数据列表信息 */
	public function acquireList ($site_cate_id, $page, $limit=15, $where=array()) {
		if (!empty($site_cate_id) && !empty($this->model_table)) {
			
			$offset = (($page-1)*$limit)?:0;
			
			return DB::table('site_cate_data as scd')->where(['scd.site_cate_id'=>$site_cate_id])->join($this->model_table.' as mtable', 'scd.cate_data_id', '=', 'mtable.id')->where($where)->skip($offset)->take($limit)->orderBy('mtable.id', 'asc')->orderBy('mtable.create_date', 'asc')->get();
			
		}
		
		return false;
	}
	
	/* 添加模型数据信息 */
	public function appendModelData ($site_cate_id, $data) {
		if (!empty($site_cate_id) && !empty($data) && !empty($this->model_table)) {
			$db = DB::table($this->model_table);
			
			$data['create_date'] = time();
			
			$data['update_date'] = $data['create_date'];
			
			$data['isdel'] = 1;
			
			if (($id = $db->insertGetId($data)) !== false) {
				
				DB::table('site_cate_data')->insert([
					'cate_data_id' => $id,
					'site_cate_id' => $site_cate_id,
					'model_id'     => $this->model_id
				]);
				
				return true;
			}
		}
		return false;
	}
	
	/* 修改模型数据信息 */
	public function modifyModelData ($data, $where=array()) {
		if (!empty($data) && !empty($where) && !empty($this->model_table)) {
			
			$data['update_date'] = time();
			
			$db = DB::table($this->model_table)->where($where);
			
			if ($db->update($data) !== false) {
				return true;
			}
		}
		return false;
	}
	
	/* 删除模型数据信息	*/
	public function deleteModelData ($site_cate_id, $data_id, $soft=true) {
		if (!empty($data_id) && !empty($this->model_table)) {
			
			$data = (array) DB::table($this->model_table)->where(['id'=>$data_id])->first();
			
			$db   = DB::table($this->model_table)->where(['id'=>$data_id]);
			
			if ($soft === true) {
				$db->where(['isdel'=>2]);
			}
			
			if ($db->delete()) {
				
				DB::table('site_cate_data')->where(['model_id'=>$this->model_id, 'cate_data_id'=>$data_id])->delete();
				
				return true;
			}
		}
		return false;
	}

    private function acquireCount($where = [], $group = [], $having = [])
    {
        // TODO: Implement acquireCount() method.
    }

    private function add($data)
    {
        // TODO: Implement add() method.
    }

    private function addMore($datas)
    {
        // TODO: Implement addMore() method.
    }

    private function modify($where, $data)
    {
        // TODO: Implement modify() method.
    }

    private function remove($where)
    {
        // TODO: Implement remove() method.
    }
}
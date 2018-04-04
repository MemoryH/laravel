<?php

namespace App\Http\Model\Category;

use Illuminate\Support\Facades\DB;
use App\Http\Model\BaseModel;
use App\Http\Model\Demo\DemoModelExtend;

//基本模型类（必须实现的方法请查看 'ImitationModel' 文件）
class DemoModel extends BaseModel {
	
	//类的扩展模型方法
	use DemoModelExtend;
	
	//当前表名称（别名始终为:current_model）
	protected $table_name = 'demo';
	
	//扩展表名称
	protected $dbs = [
		//真实表名 > 虚拟表名
		'demo_real' => 'demo_alias'
	];
	
	public function __construct () {
		parent::__construct();
		//生成扩展表资源
		$this->ExtendModel($this->dbs);
	}
	
	public function acquire ($where=[], $fields=['*']) {
		//重置数据资源（必须在执行sql之前调用,否则sql执行不生效）
		$this->ResetModel();
		
		return $this->current_model->where($where)->first($fields);
	}
	
	public function acquireList ($page, $limit, $where=[], $fields=['*'], $order=[], $group=[], $having=[]) {
		
		$this->ResetModel();
		
		$offset = $page<0?0:($page - 1)*$limit;
		
		$conditions = [
			'where' => $where,
			'skip'  => $offset,
			'take'  => $limit,
			'orderBy' => $order,
			'groupBy' => $group,
			'having'  => $having
		];
		
		//配置模型查询条件
		$this->ExecCondition($this->current_model, $conditions);
		
		return $this->current_model->get($fields);
	}
	
	public function acquireCount ($where=[], $group=[], $having=[]) {
		
		$this->ResetModel();
		
		$conditions = [
			'where'   => $where,
			'groupBy' => $group,
			'having'  => $having
		];
		
		$this->ExecCondition($this->current_model, $conditions);
		
		return $this->current_model->count();
		
	}
	
	public function add ($data, $primary_key=null) {
		
		$this->ResetModel();
		
		return $this->current_model->insertGetId($data, $primary_key);
		
	}
	
	public function addMore ($datas) {
		
		$this->ResetModel();
		
		return $this->current_model->insert($datas);
		
	}
	
	public function modify ($where, $data) {
		
		$this->ResetModel();
		
		$conditions = [
			'where' => $where,
		];
		
		$this->ExecCondition($this->current_model, $conditions);
		
		return $this->current_model->update($data);
		
	}
	
	public function remove ($where) {
		
		$this->ResetModel();
		
		$conditions = [
			'where' => $where
		];
		
		$this->ExecCondition($this->current_model, $conditions);
		
		return $this->current_model->delete();
		
	}
	
}

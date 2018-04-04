<?php

namespace App\Http\Model;

//仿造抽象类（始终约定MODEL必须实现的方法）
trait ImitationModel {
	
	//获取单条数据
	abstract protected function acquire ($where=[], $fields=[]);
	
	//获取列表数据
	abstract protected function acquireList ($page, $limit, $where=[], $fields=[], $order=[], $group=[], $having=[]);
	
	//获取列表总数
	abstract protected function acquireCount ($where=[], $group=[], $having=[]);
	
	//添加单条数据
	abstract protected function add ($data);
	
	//添加单条数据
	abstract protected function addMore ($datas);
	
	//修改数据
	abstract protected function modify ($where, $data);
	
	//删除数据
	abstract protected function remove ($where); 
	
	
}

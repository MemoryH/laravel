<?php
namespace App\Http\Model\Server;


use Illuminate\Support\Facades\DB;
use Schema;
use App\Http\Model\BaseModel;

class ServerModel extends BaseModel {
	
	//字段类型参数个数
	private $field_type = [
		'1' => [
			'bigInteger',
			'binary',
			'boolean',
			'date',
			'dateTime',
			'dateTimeTz',
			'float',
			'integer',
			'longText',
			'mediumInteger',
			'mediumText',
			'smallInteger',
			'text',
			'time',
			'timeTz',
			'tinyInteger',
			'timestamp',
			'timestampTz',
			'uuid'
		],
		'2'   => [
			'char',
			'enum',
			'string'
		],
		'3'  => [
			'decimal',
			'double'
		]
	];
	
	//字段类型解释
	private $field_type_name = [
		'bigInteger'     =>['BIGINT', true],
		'binary'         =>['BLOB', true],
		'boolean'        =>['布尔型', true],
		'date'           =>['DATE', true],
		'dateTime'       =>['DATETIME', true],
		'dateTimeTz'     =>['DATETIME(时区)', true],
		'float'          =>['FLOAT', true],
		'integer'        =>['INTEGER', true],
		'longText'       =>['LONGTEXT', true],
		'mediumInteger'  =>['MEDIUMINT', true],
		'mediumText'     =>['MEDIUMTEXT', true],
		'smallInteger'   =>['SMALLINT', true],
		'text'           =>['TEXT', true],
		'time'           =>['TIME', true],
		'timeTz'         =>['TIME(时区)', true],
		'tinyInteger'    =>['TINYINT', true],
		'timestamp'      =>['TIMESTAMP', true],
		'timestampTz'    =>['TIMESTAMP(时区)', true],
		'uuid'           =>['UUID', true],
		'char'           =>['CHAR', true],
		'enum'           =>['ENUM', true],
		'string'         =>['VARCHAR', true],
		'decimal'        =>['DECIMAL', true],
		'double'         =>['DOUBLE', true]
	];
	
	protected $model_table_server_data   = '';
	protected $model_table_server            = 'server';
	protected $model_table_server_relate = 'server_template_relate';
	protected $model_table_server_system = 'server_system';
	
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
	
	/* 读取服务项目列表总数 */
	public function acquireServerListCount ($where=[]) {
		return DB::table($this->model_table_server)->where($where)->count();
	}
	
	/* 读取一个服务项目信息 */
	public function acquireServer ($where=[], $fields=['*']) {
		return DB::table($this->model_table_server)->where($where)->select($fields)->first();
	}
	
	/* 读取服务项目列表信息 */
	public function acquireServerList ( $where=[], $fields=['*']) {
		return DB::table($this->model_table_server)->where($where)->orderBy('id', 'asc')->select($fields)->paginate(15);
	}
	
	/* 添加服务项目信息 */
	public function appendServer ($data, $system_data=[]) {
		
		if (!empty($data)) {
			if (($id = DB::table($this->model_table_server)->insertGetId($data)) !== false) {
				
				$system_data['server_id'] = $id;
				
				if (empty($system_data['table_index']) || !isset($system_data['table_index'])) {
					$system_data['table_index'] = 1;
				}
				
				if (empty($system_data['table_max']) || !isset($system_data['table_max'])) {
					$system_data['table_max']  = 2000000;
				}
				
				DB::table($this->model_table_server_system)->where('server_id', $id)->insert($system_data);
				
				return $id;
			}
		}
		
		return false;
	}
	
	/* 修改服务系统配置 */
	public function modifyServerSystem ($server_id, $data) {
		if (!empty($data)) {
			if (DB::table($this->model_table_server_system)->where('server_id', $server_id)->update($data) !== false) {
				return true;
			}
		}
		return false;
	}
	
	/* 修改服务项目信息 */
	public function modifyServer ($data, $where=[]) {
		
		if (!empty($data) && !empty($where)) {
			
			$old_data = (array) DB::table($this->model_table_server)->where($where)->first();
			
			$db = DB::table($this->model_table_server)->where($where);
			
			if ($db->update($data) !== false) {
				return $old_data;
			}
		}
		
		return false;
	}
	
	/* 删除服务项目 */
	public function deleteServer ($server_id, $isdel=false) {
		if (!empty($server_id)) {
			
			$isdel = $isdel ? 1 : 2;
			
			$data = $this->Obj2Arr(DB::table($this->model_table_server.' as s')->where(['id'=>$server_id, 'server_isdel'=>$isdel])->leftJoin('server_system as ss', 'ss.server_id', '=', 's.id')->select('ss.server_id as id', 'ss.table_name as table_name', 'ss.table_index as table_index')->first());
			
			if (!empty($data)) {
				if (DB::table($this->model_table_server)->where(['id'=>$server_id, 'server_isdel'=>$isdel])->delete()) {
					DB::table($this->model_table_server_relate)->where(['server_id'=>$server_id])->delete();
					DB::table($this->model_table_server_system)->where(['server_id'=>$server_id])->delete();
					$this->dropTable($data['table_name'], $data['table_index']);
					return true;
				}
			}
		}
		
		return false;
	}
	
	/* 绑定模板字段 */
	public function bindServerTemplate ($server_id, $template_id, $template_order, $template_group_data) {
		
		if (empty($server_id) || empty($template_id)) {
			return false;
		}
		
		$server_data = (array) DB::table($this->model_table_server)->where('id', $server_id)->first();
		
		if (!empty($server_data)) {
			
			$template_data = DB::table('module_field_template')->where('id', $template_id)->first();
			
			$result_data = [
				'server_id'      => $server_id,
				'server_name' => $server_data['server_name'],
				'template_id'  => $template_id,
				'template_name'    => $template_data->template_name,
				'template_order'   => $template_order,
				'template_group_name'  => $template_group_data['name'],
				'template_group_order' => $template_group_data['order']
			];
			
			return DB::table($this->model_table_server_relate)->insert($result_data);
		}
		
		return false;
	}
	
	/* 创建服务项目数据表 */
	public function createServerTable ($server_id) {
		
		if (!empty($server_id)) {
			
			$server_sys_data   = DB::table('server_system')->where('server_id', $server_id)->first();
			$server_relate = DB::table($this->model_table_server_relate)->where('server_id', $server_id)->get();

			foreach ($server_relate as $server){
                $servers[]=get_object_vars($server);
            }
            $template_ids  = array_unique(array_column($servers, 'template_id'));
			$field_datas = DB::table('module_field_template_relate as mftr')->whereIn('mftr.template_id', $template_ids)->where('mfs.module_isdel', '<>', 2)->leftJoin('module_field_structure as mfs', 'mfs.id', '=',  'mftr.field_id')->get();
            foreach ($field_datas as $field_data){
                $datas[]=get_object_vars($field_data);
            }
//            var_dump($datas);exit;
			return $this->createTable($server_sys_data->table_name, $datas, $server_sys_data->table_index);
		}
		
		return false;
	}
	
	/* 更新服务项目数据表 */
	public function updateServerTable ($server_id) {
		
		if (!empty($server_id)) {
			
			$server_sys_data   = $this->Obj2Arr(DB::table('server_system')->where('server_id', $server_id)->first());
			$server_relate = $this->Obj2Arr(DB::table($this->model_table_server_relate)->where('server_id', $server_id)->get());
			$template_ids  = array_unique(array_column($server_relate, 'template_id'));
			
			$field_datas = $this->Obj2Arr(DB::table('module_field_template_relate as mftr')->whereIn('mftr.template_id', $template_ids)->where('mfs.module_isdel', '<>', 2)->leftJoin('module_field_structure as mfs', 'mfs.id', '=',  'mftr.field_id')->get());
			
			return $this->updateTable($server_sys_data['table_name'], $field_datas, $server_sys_data['table_index']);
		}
		
		return false;
	}
	
	/* 为条件加上表名 */
	private function ifAddTable ($ifs, $table_name) {
		
		$conditions = [];
		
		foreach ($ifs as $way => $vals) {
				
			if (is_array($vals)) {
				
				if(isset($vals['multi']) && $vals['multi']== true){
					foreach ($vals as $k => $v) {
						if (is_numeric($k)) {
							$vals[$k] = [
								$table_name.'.'.$v[0],
								$v[1]
							];
						}
					}
				} else {
					$vals[0] = $table_name.'.'.$vals[0];
				}
				
				$conditions[$way] = $vals;
			}
		}
		
		return $conditions;
	}
	
	/* 查询服务项目数据信息列表 */
	public function acquireServerDataList ($server_id, $page, $limit=15, $ifs=[], $fields=['*'], $table_index=1) {
		
		if (!empty($server_id)) {
			
			$offset = (($page-1)*$limit)?:0;
			
			$server_sys_data   = $this->Obj2Arr(DB::table('server_system')->where('server_id', $server_id)->select('table_name', 'table_index')->first());
			
			$cur_server_table = $server_sys_data['table_name'].$table_index;
			
			$db_tmp = null;
			
			$db = DB::table($cur_server_table)->select($fields);
			
			for ($i = $table_index; $i <= $server_sys_data['table_index']; $i++) {
				
				$cur_server_table_tmp = $server_sys_data['table_name'].$i;
				
				if ($i > $table_index) {
					
					$db_tmp = DB::table($cur_server_table_tmp)->select($fields);
					
					$this->ExecCondition($db_tmp, $ifs);
					
					$db->union($db_tmp);
					
				} else {
					
					$this->ExecCondition($db, $ifs);
					
				}
				
			}
			
			return $db->skip($offset)->take($limit)->get();
		}
		
		return [];
	}	
	
	/* 读取服务项目数据信息总数 */
	public function acquireServerDataCount ($server_id, $ifs=[], $table_index=1) {
		
		if (!empty($server_id)) {
			
			$server_sys_data   = (array)DB::table('server_system')->where('server_id', $server_id)->select('table_name', 'table_index')->first();
			
			$cur_server_table = $server_sys_data['table_name'].$table_index;
			
			$db_tmp = null;
			
			$db = DB::table($cur_server_table);
			
			for ($i = $table_index; $i <= $server_sys_data['table_index']; $i++) {
				
				$cur_server_table_tmp = $server_sys_data['table_name'].$i;
				
				if ($i > $table_index) {
					
					$db_tmp = DB::table($cur_server_table_tmp);
					
					$this->ExecCondition($db_tmp, $ifs);
					
					$db->union($db_tmp);
					
				} else {
					
					$this->ExecCondition($db, $ifs);
					
				}
				
			}
			
			$sql = $db->toSql();
			foreach ($db->getBindings() as $val) {
				if (!is_numeric($val)) {
					$val = "'".$val."'";
				}
				$sql = str_replace('?', $val, $sql);
			}
			
			$result = DB::select('SELECT count(id) as count FROM ('.$sql.') as tmp');
			return $result[0]->count;
		}
		
		return false;
	}
	
	/* 读取服务项目数据信息 */
	public function acquireServerData ($server_id, $ifs=[], $fields=['*'], $table_index=1) {
		
		if (!empty($server_id)) {
			
//			$offset = (($page-1)*$limit)?:0;
			
			$server_sys_data   = $this->Obj2Arr(DB::table('server_system')->where('server_id', $server_id)->select('table_name', 'table_index')->first());
			
			$cur_server_table = $server_sys_data['table_name'].$table_index;
			
			$db_tmp = null;
			
			$db = DB::table($cur_server_table)->select($fields);
			
			for ($i = $table_index; $i <= $server_sys_data['table_index']; $i++) {
				
				$cur_server_table_tmp = $server_sys_data['table_name'].$i;
				
				if ($i > $table_index) {
					
					$db_tmp = DB::table($cur_server_table_tmp)->select($fields);
					
					$this->ExecCondition($db_tmp, $ifs);
					
					$db->union($db_tmp);
					
				} else {
					
					$this->ExecCondition($db, $ifs);
					
				}
				
			}
			
			return $db->first();
		}
		
		return false;
	}
	
	/* 创建服务项目数据信息 */
	public function appendServerData ($server_id, $data) {
		
		if (!empty($server_id) && !empty($data)) {

            $datas   =(array)DB::table('server_system')->where('server_id', $server_id)->first();

			$data_count = $this->acquireServerDataCount($server_id, [], $datas['table_index']);
			$offset_val  = ceil($datas['table_max'] * 0.05);
			
			// 正式转移数据存储表
			if ($data_count > $datas['table_max']) {
				++$datas['table_index'];
				$this->modifyServerSystem($server_id, [
					'table_index' => $datas['table_index']
				]);
			} else if ($data_count >= ($datas['table_max'] - $offset_val)) {
				// 提前生成数据存储表
				$server_relate =DB::table($this->model_table_server_relate)->where('server_id', $server_id)->get();
                foreach ($server_relate as $field_data){
                    $data_servers[]=get_object_vars($field_data);
                }
				$template_ids  = array_unique(array_column($data_servers, 'template_id'));
				
				$field_datas = DB::table('module_field_template_relate as mftr')->whereIn('mftr.template_id', $template_ids)->where('mfs.module_isdel', '<>', 2)->leftJoin('module_field_structure as mfs', 'mfs.id', '=',  'mftr.field_id')->get();
                foreach ($field_datas as $field_data){
                    $data_field[]=get_object_vars($field_data);
                }
				$this->createTable($datas['table_name'], $data_field, $datas['table_index']+1);
			}
			
			$cur_server_table = $datas['table_name'].$datas['table_index'];
			
			// 基础参数配置
			$data['create_date'] = date('Y-m-d H:i:s');
			$data['update_date'] = date('Y-m-d H:i:s', 0);
			$data['state']    = 1;
			$data['isdel']     = 1;
			$data['tindex']  = $datas['table_index'];
			
			return DB::table($cur_server_table)->insert($data);
			
		}
		
		return false;
	}
	
	/* 修改服务项目数据信息 */
	public function modifyServerData ($server_id, $data, $ifs, $table_index=1) {
		
		if (!empty($server_id)) {
			
			$update_total = 0;
			
			$server_sys_data   = $this->Obj2Arr(DB::table('server_system')->where('server_id', $server_id)->select('table_name', 'table_index')->first());
			
			$cur_server_table = $server_sys_data['table_name'].$table_index;
			
			$db_tmp = null;
			
			for ($i = $table_index; $i <= $server_sys_data['table_index']; $i++) {
				
				$cur_server_table_tmp = $server_sys_data['table_name'].$i;
				
				$db_tmp = DB::table($cur_server_table_tmp);
				
				$this->ExecCondition($db_tmp, $ifs);
				
				$update_total += $db_tmp->update($data)?:0;
				
			}
			
			return $update_total;
		}
		
		return false;
	}
	
	/* 删除服务项目数据信息 */
	public function deleteServerData ($server_id, $ifs=[], $isdel=false, $table_index=1) {
		
		if (!empty($server_id)) {
			
			$isdel = $isdel ? 1 : 2;
			
			$update_total = 0;
			
			$server_sys_data   = $this->Obj2Arr(DB::table('server_system')->where('server_id', $server_id)->select('table_name', 'table_index')->first());
			
			$cur_server_table = $server_sys_data['table_name'].$table_index;
			
			$db_tmp = null;
			
			$this->StartTransaction();
			
			for ($i = $table_index; $i <= $server_sys_data['table_index']; $i++) {
				
				$cur_server_table_tmp = $server_sys_data['table_name'].$i;
				
				$db_tmp = DB::table($cur_server_table_tmp)->where('isdel', $isdel);
				
				$this->ExecCondition($db_tmp, $ifs);
				
				if($db_tmp->delete() === false) {
					$this->TransactionRollback();
					return false;
				}
				
			}
			
			$this->TransactionCommit();
			return true;
		}
		
		return false;
	}
	
	/* 获取表主键字段名 */
	private function getTableIndex ($table, $primary=false) {
	
		if ($primary === true) {
			$index_list = $this->Obj2Arr(DB::select("SHOW index FROM ".$table." WHERE Key_name = 'PRIMARY';"));
			return array_column($index_list, 'Column_name')[0];
		} else {
			$index_list = $this->Obj2Arr(DB::select("SHOW index FROM ".$table." WHERE Key_name <> 'PRIMARY';"));
			return array_unique(array_column($index_list, 'Key_name'));
		}
	}
	
	/* 创建数据表 */
	private function createTable ($table_name, $fields, $tindex) {
		
		$flag = false;
		
		if (is_numeric($tindex)) {
			$table_name .= $tindex;
		}
		
		if (!empty($table_name) && !empty($fields) && is_array($fields)) {
			
			//删除表
            $table_names = 'service_'.$table_name;
			Schema::dropIfExists($table_name);
			
			//创建表
			Schema::create($table_names, function ($table) use($fields, &$flag) {
				
				//复合索引
				$group_index = [
					'1' => [
						'_state_' => 'state'
					]
				];
				
				//默认主键
				$table->bigIncrements('id', 1);
				
				//创建日期
				$table->dateTime('create_date');
				
				//修改日期
				$table->dateTime('update_date');
				
				//状态字段
				$table->tinyInteger('tindex');
				//前台用户id
                $table->integer('user_id');
				
				//状态字段
				$table->tinyInteger('state');
				
				//软删除字段
				$table->tinyInteger('isdel');
				$table->string('title');
				$table->text('introduction');

				//创建表字段
				foreach ($fields as $val) {
					
					$val = (array) $val;
					
					$attrs = json_decode($val['module_field_attrs'], true)?:[];
					
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
							$field = $table->$create_type($val['module_field']);
						} else if ($param_length == 2) {
							$field = $table->$create_type($val['module_field'], $attrs['len']);
						} else if ($param_length == 3) {
							$field = $table->$create_type($val['module_field'], $attrs['len'], $attrs['decimal']);
						}
						
						if (isset($attrs['unsigned']) && !empty($attrs['unsigned']) && $attrs['unsigned'] == 1) {
							$field->unsigned();
						}
						
						if (isset($attrs['default']) && !empty($attrs['default'])) {
							$field->default($attrs['default']);
						}
						
						if (isset($attrs['index']) && !empty($attrs['index']) && isset($attrs['indexn']) && !empty($attrs['indexn'])) {
							$group_index[$attrs['index']][$attrs['indexn']][] = $val['module_field'];
						}
					}
				}
				
				//创建索引
				foreach ($group_index as $type => $index_arr) {
					$index_name = '';
					foreach ($index_arr as $indexs) {
						if (is_array($indexs)) {
							$index_alias = [];
							foreach ($indexs as $inx) {
								$index_alias[] = $inx[0];
							}
							$index_name = implode('_', $index_alias).'_'.($type=='1'?'index':($type=='2'?'unique':'unknown'));
						} else {
							$index_name = $indexs.'_'.($type=='1'?'index':($type=='2'?'unique':'unknown'));
						}
						
						if ($type == '1') {
							$table->index($indexs, $index_name);
						} else if ($type == '2') {
							$table->unique($indexs, $index_name);
						}
					}
				}
				
				$flag = true;
				
			});

            $table_names = 'demand_'.$table_name;
            Schema::dropIfExists($table_name);
            //创建表
            Schema::create($table_names, function ($table) use($fields, &$flag) {

                //复合索引
                $group_index = [
                    '1' => [
                        '_state_' => 'state'
                    ]
                ];

                //默认主键
                $table->bigIncrements('id', 1);

                //创建日期
                $table->integer('create_date');

                //修改日期
                $table->integer('update_date');

                //状态字段
                $table->tinyInteger('tindex');
                //前台用户id
                $table->integer('user_id');

                //状态字段
                $table->tinyInteger('state');

                //软删除字段
                $table->tinyInteger('isdel');
                $table->string('title');
                $table->text('introduction');

                //创建表字段
                foreach ($fields as $val) {

                    $val = (array) $val;

                    $attrs = json_decode($val['module_field_attrs'], true)?:[];

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
                            $field = $table->$create_type($val['module_field']);
                        } else if ($param_length == 2) {
                            $field = $table->$create_type($val['module_field'], $attrs['len']);
                        } else if ($param_length == 3) {
                            $field = $table->$create_type($val['module_field'], $attrs['len'], $attrs['decimal']);
                        }

                        if (isset($attrs['unsigned']) && !empty($attrs['unsigned']) && $attrs['unsigned'] == 1) {
                            $field->unsigned();
                        }

                        if (isset($attrs['default']) && !empty($attrs['default'])) {
                            $field->default($attrs['default']);
                        }

                        if (isset($attrs['index']) && !empty($attrs['index']) && isset($attrs['indexn']) && !empty($attrs['indexn'])) {
                            $group_index[$attrs['index']][$attrs['indexn']][] = $val['module_field'];
                        }
                    }
                }

                //创建索引
                foreach ($group_index as $type => $index_arr) {
                    $index_name = '';
                    foreach ($index_arr as $indexs) {
                        if (is_array($indexs)) {
                            $index_alias = [];
                            foreach ($indexs as $inx) {
                                $index_alias[] = $inx[0];
                            }
                            $index_name = implode('_', $index_alias).'_'.($type=='1'?'index':($type=='2'?'unique':'unknown'));
                        } else {
                            $index_name = $indexs.'_'.($type=='1'?'index':($type=='2'?'unique':'unknown'));
                        }

                        if ($type == '1') {
                            $table->index($indexs, $index_name);
                        } else if ($type == '2') {
                            $table->unique($indexs, $index_name);
                        }
                    }
                }

                $flag = true;

            });

		}
		return $flag;
	}
	
	/* 更新数据表 */
	private function updateTable ($table_name, $fields, $tindex) {
	
		$flag = false;
		
		if (is_numeric($tindex)) {
			$table_name .= $tindex;
		}
		
		if (!empty($table_name) && !empty($fields) && is_array($fields)) {
			
			//表的索引列表
			$exists_index = $this->getTableIndex($table_name);
			
			//修改表
			Schema::table($table_name, function ($table) use($fields, $table_name, $exists_index, &$flag) {
				
				//复合索引
				$group_index = [];
				
				// 修改表字段
				foreach ($fields as $val) {
					
					$val = (array) $val;
					
					$attrs = json_decode($val['module_field_attrs'], true);
					
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
							$field = $table->$create_type($val['module_field']);
						} else if ($param_length == 2) {
							$field = $table->$create_type($val['module_field'], $attrs['len']);
						} else if ($param_length == 3) {
							$field = $table->$create_type($val['module_field'], $attrs['len'], $attrs['decimal']);
						}
						
						if (isset($attrs['unsigned']) && !empty($attrs['unsigned']) && $attrs['unsigned'] == 1) {
							$field->unsigned();
						}
						
						if (isset($attrs['default']) && !empty($attrs['default'])) {
							$field->default($attrs['default']);
						}
						
						if (Schema::hasColumn($table_name, $val['module_field']) === true) {
							//改变字段信息
							$field->change();
						}
						
						//生成复合索引数组
						$group_index[$attrs['index']][$attrs['indexn']][] = $val['module_field'];
						
					}
				}
				
				//添加表索引/删除存在的索引
				foreach ($group_index as $type => $index_arr) {
					$index_name = '';
					foreach ($index_arr as $indexs) {
						if (is_array($indexs)) {
							$index_alias = [];
							foreach ($indexs as $inx) {
								$index_alias[] = $inx[0];
							}
							$index_name = implode('_', $index_alias).'_'.($type=='1'?'index':($type=='2'?'unique':'unknown'));
						} else {
							$index_name = implode('_', $indexs).'_'.($type=='1'?'index':($type=='2'?'unique':'unknown'));
						}
						
						if (($search_inx = array_search($index_name, $exists_index)) !== false) {
							$table->dropIndex($index_name);
							$exists_index[$search_inx] = true;
						} 
						
						if ($type == '1') {
							$table->index($indexs, $index_name);
						} else if ($type == '2') {
							$table->unique($indexs, $index_name);
						}
					}
				}
				
				$flag = true;
				
				$shield_index = ['name_index', 'state_index'];
				
				// 删除多余的索引规则
				foreach ($exists_index as $exists_key) {
					if ($exists_key !== true && !in_array($exists_key, $shield_index)) {
						$table->dropIndex($exists_key);
					}
				}
				
			});
			
		}
		
		return $flag;
	}
	
	/* 修改数据表字段名 */
	public function modifyTableField ($field_id, $newname) {
	
		$result = [];
	
		if (!empty($field_id)) {
			
			$oldname           = $this->Obj2Arr(DB::table('module_field_structure')->where('id', $field_id)->first(['module_field']));
			
			$template_ids   = $this->Obj2Arr(DB::table('module_field_template_relate')->where('field_id', $field_id)->pluck('template_id'));
			
			$server_tables  = $this->Obj2Arr(DB::table($this->model_table_server_relate.' as mtsr')->leftJoin($this->model_table_server_system.' as mtss', 'mtss.server_id', '=', 'mtsr.server_id')->whereIn('mtsr.template_id', $template_ids)->groupBy('mtss.server_id','mtss.table_name')->select( 'mtss.server_id as server_id', 'mtss.table_name as table_name', 'mtss.table_index as table_index')->get());
			
			if ($oldname['module_field'] != $newname) {
				foreach ($server_tables as $server_table) {
					$result_tmp = $this->renameField($server_table['table_name'], $oldname['module_field'], $newname, $server_table['table_index']);
					$result[$server_table['server_id']] = !empty($result_tmp)?$result_tmp:true;
				}
				
				return $result;
			}
			
		}
		
		return $result;
	}
	
	/* 删除数据表字段名 */
	public function deleteTableField ($field_id) {
		
		$result = [];
	
		if (!empty($field_id)) {
			
			$field_data      = $this->Obj2Arr(DB::table('module_field_structure')->where('id', $field_id)->first(['module_field']));
			
			$template_ids  = $this->Obj2Arr(DB::table('module_field_template_relate')->where('field_id', $field_id)->pluck('template_id'));
			
			$server_tables = $this->Obj2Arr(DB::table($this->model_table_server_relate.' as mtsr')->leftJoin($this->model_table_server_system.' as mtss', 'mtss.server_id', '=', 'mtsr.server_id')->whereIn('mtsr.template_id', $template_ids)->groupBy('mtss.server_id','mtss.table_name')->select( 'mtss.server_id as server_id', 'mtss.table_name as table_name', 'mtss.table_index as table_index')->get());
			
			foreach ($server_tables as $server_table) {
				$result[$server_table['server_id']] = [];
				$result[$server_table['server_id']] = array_merge($result[$server_table['server_id']], $this->dropField($server_table['table_name'], $field_data['module_field'], $server_table['table_index']));
			}
			
			return $result;
			
		}
		
		return $result;
	}
	
	/* 重命名字段 */
	private function renameField ($table_name, $oldname, $newname, $tindex) {
		
		if (!empty($table_name) && !empty($oldname) && !empty($newname)) {
			
			$fail_tables = [];
			
			for ($i = 1; $i <= $tindex; $i++) {
				
				$cur_table_name = $table_name.$tindex;
				
				if (Schema::hasColumn($cur_table_name, $oldname) === true) {
					
					Schema::table($cur_table_name, function ($table) use ($oldname, $newname) {
						$table->renameColumn($oldname, $newname);
					});
					
				} else {
					$fail_tables[] = $cur_table_name;
				}
			}
			
			return $fail_tables;
		}
		return false;
	}
	
	/* 修改数据库表名 */
	public function updateTableName ($old_table, $new_table) {
		if (Schema::hasTable($old_table) === true) {
			Schema::rename($old_table, $new_table);
		}
	}
	
	/* 删除数据库表 */
	public function dropTable ($table_name, $table_index) {
		
		$flag = false;
		
		for ($i = 1; $i <= $table_index; $i++) {
			if (Schema::hasTable($table_name.$i) === true) {
				Schema::drop($table_name.$i);
				$flag = true;
			}
		}
		
		return $flag;
	}
	
	/* 移除数据库表字段 */
	private function dropField ($table_name, $field_name, $tindex) {
		
		$fail_tables = [];
		
		if (!empty($table_name) && !empty($field_name)) {
			for ($i = 1; $i <= $tindex; $i++) {
				$flag = false;
				$cur_table_name = $table_name.$i;
				Schema::table($cur_table_name, function ($table) use($cur_table_name, $field_name, &$flag) {
					if (Schema::hasColumn($cur_table_name, $field_name) === true) {
						$table->dropColumn($field_name);
						$flag = true;
					} 
				});
				if ($flag === false) {
					$fail_tables[] = $cur_table_name;
				}
			}
		}
		
		return $fail_tables;
	}
	
}
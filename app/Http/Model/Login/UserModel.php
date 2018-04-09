<?php
namespace App\Http\Model\Login;

use App\Http\Model\BaseModel;

class UserModel extends BaseModel
{

    //当前表名称（别名始终为:current_model）
    protected $table_name = 'user';

    //扩展表名称
    protected $dbs = [
        //真实表名 > 虚拟表名
        'demo_real' => 'demo_alias'
    ];

    public function __construct () {
        parent::__construct();
        //生成扩展表资源

    }

    protected function acquire($where = [], $fields = [])
    {
        // TODO: Implement acquire() method.
    }

    protected function acquireList($page, $limit, $where = [], $fields = [], $order = [], $group = [], $having = [])
    {
        // TODO: Implement acquireList() method.
    }

    protected function acquireCount($where = [], $group = [], $having = [])
    {
        // TODO: Implement acquireCount() method.
    }

    public function add ($data, $primary_key=null) {

        $this->ResetModel();

        return $this->current_model->insertGetId($data, $primary_key);

    }

    protected function addMore($datas)
    {
        // TODO: Implement addMore() method.
    }

    protected function modify($where, $data)
    {
        // TODO: Implement modify() method.
    }

    protected function remove($where)
    {
        // TODO: Implement remove() method.
    }
}
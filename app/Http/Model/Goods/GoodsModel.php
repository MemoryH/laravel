<?php
namespace App\Http\Model\Goods;

use App\Http\Model\BaseModel;

class GoodsModel extends BaseModel
{
    protected $table_name = 'server_product';

    public function __construct()
    {
        parent::__construct();
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
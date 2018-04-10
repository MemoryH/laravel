<?php
namespace App\Http\Model\Merchant;


use App\Http\Model\BaseModel;

class MerchantModel extends BaseModel
{
    //当前表名称（别名始终为:current_model）
    protected $table_name = 'merchant';

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

    protected function add($data)
    {
        // TODO: Implement add() method.
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
<?php
namespace App\Http\Controllers\Order;

use App\Http\Controllers\BaseController;
use App\Http\Model\Order\OrderModel;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = true;

    /* 跳过方法权限验证 */
    protected $skip_func = true;

    protected function index(){
//       $orders =  DB::table('test_order')->get();
//        var_dump(111);exit;
        $res = DB::select('select * from test_order');
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['orders'=>$res]);
    }

    //详情页面
    protected function intro(){
        var_dump($_GET);
    }
}
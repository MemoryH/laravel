<?php
namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\BaseController;
use App\Http\Model\Merchant\MerchantModel;
use Illuminate\Support\Facades\DB;

class MerchantController extends BaseController
{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = true;

    /* 跳过方法权限验证 */
    protected $skip_func = true;

    //展示商户列表页面
    protected function index(){
        $merchant = new MerchantModel();

        $res = DB::select('select * from merchant');
        echo json_encode($res);
    }
    //删除商户
    protected function del($id){
        $res = DB::delete("delete from merchant WHERE `id`=?",[$id]);
    }
    //商户修改
    protected function edit(){
        //判断提交方式
        if (request()->method('POST')){

        }else{

        }
    }

    //新增商户
    protected function add(){
        $merchant = new MerchantModel();

        $date = $this->formatData(['merchant_name','type','regio','contacts','contacts_number'],$this->request->input());
        var_dump($date);
    }

}
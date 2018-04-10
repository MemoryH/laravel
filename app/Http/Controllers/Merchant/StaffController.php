<?php
namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\BaseController;

class StaffController extends BaseController{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = true;

    /* 跳过方法权限验证 */
    protected $skip_func = true;

    //展示商户对应员工
    protected function staff($id){
        $res = DB::select("select * from staff WHERE `merchant_id`='{$id}'");
        echo json_encode($res);
    }

}
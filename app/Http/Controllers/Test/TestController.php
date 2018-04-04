<?php
namespace App\Http\Controllers\Test;

use App\Http\Controllers\BaseController;

class TestController extends BaseController
{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = true;

    /* 跳过方法权限验证 */
    protected $skip_func = true;

    protected function index(){
        echo 11;
    }
}
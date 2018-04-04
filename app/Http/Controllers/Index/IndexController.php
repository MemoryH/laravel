<?php
namespace App\Http\Controllers\Index;


use App\Http\Controllers\BaseController;

class IndexController extends BaseController
{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = true;

    /* 跳过方法权限验证 */
    protected $skip_func = true;

    protected function index(){
        return view($this->domain.'/'.$this->controller.'/'.$this->method);
    }

    protected function menu(){
        return view($this->domain.'/'.$this->controller.'/'.$this->method);
//        echo 111;
    }
}
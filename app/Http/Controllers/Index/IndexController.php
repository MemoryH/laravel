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
//        var_dump($this->is_login);exit;
//        var_dump($_COOKIE);exit;
        if(request()->session()->get('user_info')){
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
        }else{
//            var_dump(request()->session()->get('user_info'));exit;
            return redirect($this->domain.'/login/index');
        }

    }




}
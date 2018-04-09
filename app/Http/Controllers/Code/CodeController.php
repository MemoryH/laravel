<?php
namespace App\Http\Controllers\Code;


use App\Http\Controllers\BaseController;
use Gregwar\Captcha\CaptchaBuilder;
use Symfony\Component\HttpFoundation\Session\Session;


class CodeController extends BaseController
{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = true;

    /* 跳过方法权限验证 */
    protected $skip_func = true;
    protected function code(){
//        echo 111;exit;
        $builder = new CaptchaBuilder();
        ob_start();
        $builder->build(150,32);
       //获取验证码内容
        $phrase = $builder->getPhrase();

       //把内容存入session
request()->session()->put('code',$phrase);


//var_dump($this->session);
//        ob_clean(); //清除缓存
//        echo "<pre>";
//        var_dump($_SESSION);exit;

        return $builder->output();
    }
}
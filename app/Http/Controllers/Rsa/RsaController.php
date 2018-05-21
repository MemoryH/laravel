<?php
namespace App\Http\Controllers\Rsa;


use App\Http\Controllers\BaseController;

class RsaController extends BaseController
{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = true;

    /* 跳过方法权限验证 */
    protected $skip_func = true;

    protected function test(){

//        $res = AuthApiController::rsaEncrypt('123456',public_path().'/rsa/rsa_public_key.pem');
//        var_dump($res);
$res = 'gawf0WZa1F748MDxxPyMvNBFZ+BKVYsVE+X6cl6Nv7N0HeDfrYoer6HAt7s+Pc/ijAbYkpExKg3iuyqYT4EMNMGXS02B3dNvsbNjX0LEQw3297P9gpzf5qLPJv/oV0e5HwUfupEbH5zMeqJC/W649QWMl7L9vfPR5T7S+Zlplwc=';
        $password = AuthApiController::rsaDecrypt($res,public_path().'/rsa/rsa_private_key.pem');
        var_dump($password);
    }



}
<?php
 namespace App\Http\Controllers\Department;

 use App\Http\Controllers\BaseController;

 class DepartmentController extends BaseController
 {
     /* 跳过登录验证 */
     protected $skip_login = true;

     /* 跳过权限验证 */
     protected $skip_auth = true;

     /* 跳过方法权限验证 */
     protected $skip_func = true;


 }

<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

/* 登录验证 */
class LoginRequest extends Request {

	//授权
	public function authorize()
	{
		return true;
	}

	//规则
	public function rules()
	{
		return [];
	}

}
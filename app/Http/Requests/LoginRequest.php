<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

/* ��¼��֤ */
class LoginRequest extends Request {

	//��Ȩ
	public function authorize()
	{
		return true;
	}

	//����
	public function rules()
	{
		return [];
	}

}
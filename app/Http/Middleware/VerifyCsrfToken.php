<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
		'admin/api/document/attach_upload',
		'admin/api/document/dept_list',
		'admin/api/document/template_upload',
		'admin/api/document/stamp_upload',
		'admin/api/document/runcall',
		'admin/api/user/portrait_upload',
		'admin/api/swap/update_img',
        'admin/api/swap/update_attach',
        'admin/api/site/up_home_img',
        'admin/api/site/update_img',
    ];
}

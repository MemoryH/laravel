<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

date_default_timezone_set('Asia/Shanghai');

use Illuminate\Http\Request;

Route::get('/', function (){
    return view('welcome');
});

//Route::get('test','Demo\DemoController@Test');
//Route::get('Test/index','TestController@index');
//Main Controller
Route::prefix(env('DOMAIN_PREFIX'))->group(function () {
	
	//debug写法(/{domain}/debug/{controller}/{method})
    Route::any('/debug/{controller}/{method}', function (Request $request, $controller, $method) {
		
        $namespace_file = '\\App\\Http\\Controllers\\'.ucwords($controller).'\\'.ucwords($controller).'Controller';
		$physics_file   = '\\app\\Http\\Controllers\\'.ucwords($controller).'\\'.ucwords($controller).'Controller';
		
		$real_file_path = dirname($_SERVER['DOCUMENT_ROOT']).str_replace('\\', '/', $physics_file).'.php';
		
		if (is_dir(dirname($real_file_path)) && file_exists($real_file_path)) {
			return (new $namespace_file())->_target_debug($request, env('DOMAIN_PREFIX'), $controller, $method, 'debug');
		} else {
			return view('error/404')->with('controller', $controller);
		}
		
    })->where(['controller'=>'[a-zA-Z0-9\_]+', 'method'=>'[a-zA-Z0-9\_]+']);
	
	//省略写法(/{domain}/{controller})
	Route::any('{controller}', function (Request $request, $controller) {
		
        $namespace_file = '\\App\\Http\\Controllers\\'.ucwords($controller).'\\'.ucwords($controller).'Controller';
        $physics_file   = '\\app\\Http\\Controllers\\'.ucwords($controller).'\\'.ucwords($controller).'Controller';
		
		$real_file_path = dirname($_SERVER['DOCUMENT_ROOT']).str_replace('\\', '/', $physics_file).'.php';
		
		if (is_dir(dirname($real_file_path)) && file_exists($real_file_path)) {
			return (new $namespace_file())->_target($request, env('DOMAIN_PREFIX'), $controller, 'index');
		} else {
			return view('error/404')->with('controller', $controller);
		}
		
    })->where(['controller'=>'[a-zA-Z0-9\_]+']);
//	Route::get('Test/index','TestController@index');
	//标准写法 (/{domain}/{controller}/{method})
    Route::any('{controller}/{method}', function (Request $request, $controller, $method) {
		
        $namespace_file = '\\App\\Http\\Controllers\\'.ucwords($controller).'\\'.ucwords($controller).'Controller';
		$physics_file   = '\\app\\Http\\Controllers\\'.ucwords($controller).'\\'.ucwords($controller).'Controller';
		
		$real_file_path = dirname($_SERVER['DOCUMENT_ROOT']).str_replace('\\', '/', $physics_file).'.php';
		
		if (is_dir(dirname($real_file_path)) && file_exists($real_file_path)) {
			return (new $namespace_file())->_target($request, env('DOMAIN_PREFIX'), $controller, $method);
		} else {
			return view('error/404')->with('controller', $controller);
		}
		
    })->where(['controller'=>'[a-zA-Z0-9\_]+', 'method'=>'[a-zA-Z0-9\_]+']);


	
	//接口写法 (/{domain}/api/{version}/{controller}/{method})
	Route::any('api/{version}/{controller}/{method}', function (Request $request, $version, $controller, $method) {
		
        $namespace_file = '\\App\\Http\\Controllers\\'.ucwords($controller).'\\V'.$version.'\\'.ucwords($controller).'ApiController';
		$physics_file   = '\\app\\Http\\Controllers\\'.ucwords($controller).'\\V'.$version.'\\'.ucwords($controller).'ApiController';
		
		$real_file_path = dirname($_SERVER['DOCUMENT_ROOT']).str_replace('\\', '/', $physics_file).'.php';
		
		if (is_dir(dirname($real_file_path)) && file_exists($real_file_path)) {
			return (new $namespace_file())->_target($request, env('DOMAIN_PREFIX'), $controller, $method, $version, 'api');
		} else {
			return response(json_encode(['code'=>404, 'msg'=>'未找到"'.$controller.'"控制器']), 200, [
				'Content-type: application/json',
			]);
		}
		
    })->where(['controller'=>'[a-zA-Z0-9\_]+', 'method'=>'[a-zA-Z0-9\_]+']);
	
	//接口写法2 (/{domain}/api/{version}/{controller}/{sub_controller}/{method})
	Route::any('api/{version}/{controller}/{sub_controller}/{method}', function (Request $request, $version, $controller, $sub_controller, $method) {
		
        $namespace_file = '\\App\\Http\\Controllers\\'.ucwords($controller).'\\V'.$version.'\\'.ucwords($controller).ucwords($sub_controller).'ApiController';
		$physics_file   = '\\app\\Http\\Controllers\\'.ucwords($controller).'\\V'.$version.'\\'.ucwords($controller).ucwords($sub_controller).'ApiController';
		
		$real_file_path = dirname($_SERVER['DOCUMENT_ROOT']).str_replace('\\', '/', $physics_file).'.php';
		
		if (is_dir(dirname($real_file_path)) && file_exists($real_file_path)) {
			return (new $namespace_file())->_target($request, env('DOMAIN_PREFIX'), $controller, $method, $version, 'api');
		} else {
			return response(json_encode(['code'=>404, 'msg'=>'未找到"'.$controller.'"控制器']), 200, [
				'Content-type: application/json',
			]);
		}
		
    })->where(['controller'=>'[a-zA-Z0-9\_]+', 'sub_controller'=>'[a-zA-Z0-9\_]+', 'method'=>'[a-zA-Z0-9\_]+']);
    Route::any('test/index',['uses'=>'TestController@Test']);
	
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

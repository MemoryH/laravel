<?php
namespace App\Http\Controllers\News;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use JPush\Client as Jpush;

class NewsController extends BaseController{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = true;

    /* 跳过方法权限验证 */
    protected $skip_func = true;

    //展示消息列表
    protected function index(){
        //查询数据
        $news = DB::table('news')->get();
        //加载页面
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['news'=>$news]);
    }

    //创建通知消息
    protected function add(){

        if (request()->isMethod('POST')){

            //接收数据
            $data = $this->formatData(['title','content','type'],$this->request->input());
            $num = DB::table('news')->insert($data);
            if($num){
                return redirect($this->domain.'/'.$this->controller.'/'.'index');
            }

        }else{
//            var_dump(time());exit;
            //加载视图
            return view($this->domain.'/'.$this->controller.'/'.$this->method);
        }
    }

    //删除通知消息
    protected function del(){

        $id = request()->get('id');
        $num =DB::table('news')->where(['id'=>$id])->delete();
        if ($num ){
            echo 'success';
        }else{
            echo 'fail';
        }

    }

    //消息推送
    protected function send(){
        $content = request()->get('content');
        $title = request()->get('title');
        $app_key = '';
        $master_secret = '';
        $client = new Jpush($app_key,$master_secret);
        $push = $client->push();
        //选择推送平台
        $push->setPlatform('winphone');
        $push->setAudience('all');
        $push->addWinPhoneNotification($content,$title);

        $push->send();


    }
}
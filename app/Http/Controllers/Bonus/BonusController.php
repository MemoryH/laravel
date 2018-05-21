<?php
namespace App\Http\Controllers\Bonus;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;

class BonusController extends BaseController
{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = true;

    /* 跳过方法权限验证 */
    protected $skip_func = true;

    //分润机制列表
    protected function index(){
        //加载数据
        $bonuses = DB::table('bonus')->get();
        //加载视图
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['bonuses'=>$bonuses]);
    }

    //添加分润机制
    protected function add(){

        if (request()->isMethod('POST')){
            //接收数据
            $data = $this->formatData(['bonus_name','merchant_id','type','proportion'],$this->request->input());
            $citys = $this->formatData(['provid','cityid','areaid'],$this->request->input());
            //处理地址
            $provid = explode(':',$citys['provid']);
            $data['province_id']=$provid[0];
            $data['province']=$provid[1];
            $city = explode(':',$citys['cityid']);
            $data['city_id']=$city[0];
            $data['city']=$city[1];
            $area = explode(':',$citys['areaid']);
            $data['area_id']=$area[0];
            $data['area']=$area[1];
            $num = DB::table('bonus')->insert($data);
            if ($num){
                return redirect($this->domain.'/'.$this->controller.'/'.'index');
            }else{
                echo '添加失败';
            }
        }else{

            //查询公司信息
            $merchants = DB::table('company_merchant')->where(['source'=>1])->get();
            //加载页面
            return view($this->domain.'/'.$this->controller.'/'.$this->method,['merchants'=>$merchants]);
        }

    }
    //编辑分润机制
    protected function edit(){

        if (request()->isMethod('POST')){
            $id = request()->get('bonus_id');
            //接收数据
            $data = $this->formatData(['bonus_name','merchant_id','type','proportion'],$this->request->input());
            $citys = $this->formatData(['provid','cityid','areaid'],$this->request->input());
            $provid = explode(':',$citys['provid']);
            $data['province_id']=$provid[0];
            $data['province']=$provid[1];
            $city = explode(':',$citys['cityid']);
            $data['city_id']=$city[0];
            $data['city']=$city[1];
            $area = explode(':',$citys['areaid']);
            $data['area_id']=$area[0];
            $data['area']=$area[1];
            //修改数据
            $num = DB::table('bonus')->where(['id'=>$id])->update($data);
            if ($num){
                return redirect($this->domain.'/'.$this->controller.'/'.'index');
            }else{
                echo '编辑失败';
            }
        }else{

            //接收id
            $id = request()->get('bonus_id');
            //查询公司信息
            $merchants = DB::table('company_merchant')->where(['source'=>1])->get();
            //查询数据
            $bonus = DB::table('bonus')->where(['id'=>$id])->first();
            return view($this->domain.'/'.$this->controller.'/'.'add',['bonus'=>$bonus,'merchants'=>$merchants]);
        }


    }
    //删除分润机制
    protected function del(){

        //接收id
        $id = request()->get('bonus_id');
        $res = DB::table('bonus')->where(['id'=>$id])->delete();
        if ($res){
            echo 'success';
        }else{
            echo 'fail';
        }
    }
}
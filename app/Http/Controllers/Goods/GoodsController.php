<?php
namespace App\Http\Controllers\Goods;


use App\Http\Controllers\BaseController;
use App\Http\Model\Goods\GoodsModel;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class GoodsController extends BaseController
{
    /* 跳过登录验证 */
    protected $skip_login = true;

    /* 跳过权限验证 */
    protected $skip_auth = ['admin/login/index','admin/goods/upload'];

    /* 跳过方法权限验证 */
    protected $skip_func = true;
    public function __construct()
    {
        if(request()->session()->get('user_info')){
//            var_dump(request()->session()->get('user_info'));exit;

            $roles =DB::table('company_merchant')->where(['merchant_id'=>request()->session()->get('user_info')->merchant_id])->join('role_user','company_merchant.merchant_id','=','role_user.user_id')->select('role_id')->get();
            foreach ($roles as $role){
                $role_id[] = get_object_vars($role);

            }
            foreach ($role_id as $ro){
                $data[] = $ro['role_id'];
            }
            $permissions = DB::table('permission_role')->whereIn('role_id',$data)->join('permissions','permission_role.permission_id','=','permissions.id')->get();
            foreach ($permissions as $permission){
                $permission_name[] = get_object_vars($permission);

            }
            $permission_name = array_unique(array_column($permission_name,'name'));
            foreach ($permission_name as $name){
                $this->skip_auth[]=$name;
            }
        }else{
//            echo 111;exit;
//            var_dump(request()->session()->get('user_info'));exit;
            echo redirect('admin/login/index');

        }


    }

    //产品展示列表
    protected function index(){

        $data = $this->formatData(['merchant_id','goods_name','goods_sn'],$this->request->input());
//        var_dump($data);
        $where = [];
        $goods_name='';
        $merchant_id ='';
        $goods_sn ='';
        if (!empty($data['goods_name'])){
            $where['goods_name'] = $data['goods_name'];
            $goods_name = $data['goods_name'];
        }elseif (!empty($data['merchant_id'])&&$data['merchant_id']!=0){
            $where['merchant_id'] = $data['merchant_id'];
            $merchant_id = $data['merchant_id'];
        }elseif(!empty($data['goods_sn'])){
            $where['goods_sn'] = $data['goods_sn'];
            $goods_sn = $data['goods_sn'];
        }
        //查询数据
        $res = DB::table('server_product')->where($where)->whereIn('status',[1,2,3])->paginate(15);
        $res->goods_name = $goods_name;
        $res->merchant_id = $merchant_id;
        $res->goods_sn=$goods_sn;
        $merchants = DB::table('company_merchant')->get();
        //加载视图页面
        return view($this->domain.'/'.$this->controller.'/'.$this->method,['goods'=>$res,'merchants'=>$merchants]);


    }

    //修改商品状态
    protected function status(){
        $id = request()->get('id');
        $status = request()->get('status');
        $data['status'] = $status;
        if ($status ==2){
            $data['start_time'] = time();
        }elseif ($status ==3 ){
            $data['end_time'] = time();
        }
//        var_dump($id);
        $num = DB::table('server_product')->where(['id'=>$id])->update($data);
        var_dump($num);
//        echo $num;
    }
    //删除商品
    protected function del(){

        $id= request()->get('id');
        $num = DB::table('server_product')->where(['id'=>$id])->update(['status'=>0]);
        echo $num;

    }

    //修改商品
    protected function edit(){

        if (request()->isMethod('POST')){

            //获取数据
            $data = $this->formatData(['merchant_id','goods_name','goods_category_id','unit','goods_price','goods_intro'],$this->request->input());
            $label = request()->post('label');
            $citys = $this->formatData(['provid','cityid','areaid'],$this->request->input());
//            var_dump($label);exit;
            $category = explode(':',$data['goods_category_id']);
            $data['goods_category_id'] = $category[0];
            $data['goods_category_name']=$category[1];
            $provid = explode(':',$citys['provid']);
            $data['province_id']=$provid[0];
            $data['province']=$provid[1];
            $city = explode(':',$citys['cityid']);
            $data['city_id']=$city[0];
            $data['city']=$city[1];
            $area = explode(':',$citys['areaid']);
            $data['area_id']=$area[0];
            $data['area']=$area[1];
            //转码
            foreach ($label as $lab){
                $label_code[] = base64_encode($lab);
            }

            $product_label['label'] = implode(',',$label_code);
//                        var_dump($product_label);exit;
            $data['update_time']=time();
            $id = request()->post('id');
            $num = DB::table('server_product')->where(['id'=>$id])->update($data);
            $res = DB::table('ft_product_label')->where(['product_id'=>$id])->update($product_label);

              return redirect($this->domain.'/'.$this->controller.'/'.'index');

        }else{
            $id = request()->get('id');
            $goods = DB::table('server_product')->where(['id'=>$id])->first();
            $label = DB::table('ft_product_label')->where(['product_id'=>$goods->id])->first();
            $label_base = [];
//            var_dump($label);exit;
//            var_dump($label);exit;
            if (!empty($label)){
                $label_arr = explode(',',$label->label);
                //转码
                foreach ($label_arr as $arr){
                    $label_base[] = base64_decode($arr);
                }
            }


            $merchants = DB::table('company_merchant')->select(['id','merchant_name'])->get();
            $labels = DB::table('goods_label')->get();
            $servers = DB::table('server')->get();
            //加载视图
            return view($this->domain.'/'.$this->controller.'/'.'add',['servers'=>$servers,'merchants'=>$merchants,'labels'=>$labels,'goods'=>$goods,'label_arr'=>$label_base]);

        }

    }

    //添加商品
    protected function add(){

        if (request()->isMethod('POST')){
            $data = $this->formatData(['merchant_id','status','goods_name','goods_images','goods_category_id','unit','goods_price','goods_intro'],$this->request->input());
            $citys = $this->formatData(['provid','cityid','areaid'],$this->request->input());
            $label = request()->post('label');
            $images=[];
            if (!empty($data['goods_images'])){
                $images[] = $data['goods_images'];
            }
            $data['goods_images'] = json_encode($images);
            $category = explode(':',$data['goods_category_id']);
            $data['goods_category_id'] = $category[0];
            $data['goods_category_name']=$category[1];
            $provid = explode(':',$citys['provid']);
            $data['province_id']=$provid[0];
            $data['province']=$provid[1];
            $city = explode(':',$citys['cityid']);
            $data['city_id']=$city[0];
            $data['city']=$city[1];
            $area = explode(':',$citys['areaid']);
            $data['area_id']=$area[0];
            $data['area']=$area[1];
            $day = strtotime(date('Ymd'));
            $data['create_time'] = $day;
            $identity = (array)DB::table('company_merchant')->where(['id'=>$data['merchant_id']])->select(['identification'])->first();
//            var_dump($identity);exit;
            $num = DB::table('server_product')->where(['create_time'=>$day])->count();
            $num = $num+1;
            $str = str_pad($num, 5, 0, STR_PAD_LEFT);

            $data['goods_sn'] = $identity['identification'].$time.$str;
//            var_dump($data);exit;
            //转码
            foreach ($label as $lab){
                $label_code[] = base64_encode($lab);
            }
//            var_dump($label_code);exit;

            $product_label['label'] = implode(',',$label_code);
//            var_dump($product_label);exit;

            $goods = new GoodsModel();

            $goods->StartTransaction();
            $num = $goods->add($data);
//            var_dump($num);exit;
            $product_label['product_id'] = $num;
            DB::table('ft_product_label')->insert($product_label);

            if ($goods->TransactionCommit()){
                return redirect($this->domain.'/'.$this->controller.'/'.'index');
            }else{
                $goods->TransactionRollback();
                echo '添加失败';
            }



            return redirect($this->domain.'/'.$this->controller.'/'.'index');
        }else{

            $servers = DB::table('server')->get();
            $label = DB::table('goods_label')->get();
            $merchants = DB::table('company_merchant')->select(['id','merchant_name'])->get();
            return view($this->domain.'/'.$this->controller.'/'.$this->method,['merchants'=>$merchants,'labels'=>$label,'servers'=>$servers]);
        }
    }
    //图片处理
    protected function upload(){
//        var_dump($_FILES);exit;
        //实例化上传文件类

        $info = request()->file('file');

        $extension = $info->getClientOriginalExtension();
        $fileName = str_random(10).'.'.$extension;
        $dir = 'goods/'.date('Ymd').'/';
        if (!is_dir($dir)){
            mkdir($dir,777,true);
        }
        $res = $info->move($dir, $fileName);
        $file = public_path().'/'.$dir.$fileName;
//        var_dump($file);exit;
        if ($res){
            //文件保存成功 返回文件路径
            $accessKey ="TxMyeDQ095vC5DtBNrUmE_PqD-Ds6I1mz3i__KJk";
            $secretKey = "M69Cr5LgZCbmJrdmxqRfDl2qvdPYiZB8nZ6P9F7g";

            $bucket = "laravel";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = $file;
            // 上传到七牛后保存的文件名
            $key = $fileName;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if ($err !== null) {
                var_dump($err);
            } else {
                echo "http://p6y8pw5fr.bkt.clouddn.com/{$key}";
            }
//            return json_encode([
//                'url'=>$file
//            ]);
        }
    }
}
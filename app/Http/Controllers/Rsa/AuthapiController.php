<?php
namespace App\Http\Controllers\Rsa;

use App\Http\Controllers\BaseController;

class AuthApiController extends BaseController {
    protected function private_key($type)
    {
        $data = array('privateKey'=> PATH_ROOT.'auth/channel/rsa_private_key.pem','publicKey'=>PATH_ROOT.'auth/channel/dayima.pem');
        return $data[$type];  
    }
	public function grantAuthStr(){
		header("Content-type:text/html;charset=utf-8");
		$openid = array('37908245','37907678','37907861','37907887','37907926','37907930','37907941','37907978'
		);
		$cid = 25;
		foreach($openid as $v){
			//print_r($k.'</br>');
			$url = 'http://agent.event.test.yoloho.com/auth/channel/test?cid='.$cid.'&openid='.$v;
			$row = $this->get($url);
			$userTokenUrl = 'http://agent.event.test.yoloho.com/auth/channel/grantAuthStr';
			$data['userToken'] = $row['userToken'];
			$data['sign'] = $row['sign'];
			$ret = $this->post($userTokenUrl,$data);
			$a = $this->rsaDecrypt($ret['userInfo'],$this->private_key('privateKey'));
            print_r($a);exit;	
		}	
	}

/**
     * RSA加密
     * 
     * @param string $data 待加密数据
     * @param string $publicKey 公钥
     * @return string|false 加密结果
     * @author SC
     */
    public static function rsaEncrypt($data, $publicKey_path)
    {
        $publicKey = file_get_contents($publicKey_path);
        $ciphertext = '';
        $publicKey = openssl_pkey_get_public($publicKey);
        $data = str_split($data, 117); // 加密的数据长度限制为比密钥长度少11位，如128位的密钥最多加密的数据长度为117
        foreach ($data as $d) {
            openssl_public_encrypt($d, $crypted, $publicKey); // OPENSSL_PKCS1_PADDING
            $ciphertext .= $crypted;
        }
        openssl_free_key($publicKey);
        
        return base64_encode($ciphertext);
    }
	/*
     * RSA解密
     * @param $content 需要解密的内容，密文
     * @param $private_key_path 商户私钥文件路径
     * return 解密后内容，明文
    */
    public static function rsaDecrypt($content, $private_key_path) {
        $priKey = file_get_contents($private_key_path);
//        $priKey = $private_key_path;
        $res = openssl_get_privatekey($priKey);
    	//用base64将内容还原成二进制
        $content = base64_decode($content);
    	//把需要解密的内容，按128位拆开解密
        $result  = '';
        for($i = 0; $i < strlen($content)/128; $i++  ) {
            $data = substr($content, $i * 128, 128);
            openssl_private_decrypt($data, $decrypt, $res);
           $result .= $decrypt;
        }
        openssl_free_key($res);
        return $result;
    }

    /**
     * 生成RSA签名
     * 
     * @param string $data 待签名数据
     * @param string $privateKey 私钥
     * @return string 签名
     * @author SC
     */
    public static function rsaSign($data, $privateKey)
    {
        $privateKey = openssl_get_privatekey($privateKey);
        openssl_sign($data, $sign, $privateKey, OPENSSL_ALGO_MD5); // OPENSSL_ALGO_MD5 or OPENSSL_ALGO_SHA1(default)
        openssl_free_key($privateKey);
        
        return base64_encode($sign);
    }
    
    /**
     * 检验RSA签名
     * 
     * @param string $data 待签名数据
     * @param string $sign 待验证签名
     * @param string $publicKey 公钥
     * @return bool 检验结果
     * @author SC
     */
    public static function rsaVerify($data, $sign, $publicKey)
    {
		
		$publicKey=file_get_contents($publicKey);
		
        $publicKey = openssl_get_publickey($publicKey);
        $result = openssl_verify($data, base64_decode($sign), $publicKey, OPENSSL_ALGO_MD5); // OPENSSL_ALGO_MD5 or OPENSSL_ALGO_SHA1(default)
        openssl_free_key($publicKey);
        
        return ($result == 1) ? true : false; // -1:错误；0：签名错误；1：签名正确
    }

    function get($url, $data = array())
    {
        //print_r($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $data);
        $ret = curl_exec($ch);
        //unset($ch);
        if (curl_errno($ch)) echo '<pre><b>错误:</b><br />'.curl_error($ch);
        curl_close($ch);
        return json_decode($ret,true);
    }
    function post($url,$post){
        $opt = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_POSTFIELDS => http_build_query($post),
        );
        $curl = curl_init();
        curl_setopt_array($curl, $opt);
        $info= curl_exec($curl);
        if (curl_errno($curl)) echo '<pre><b>错误:</b><br />'.curl_error($curl);		
        curl_close($curl);
        return json_decode($info,true);
    }
}
<?php

namespace App\Http\Services;

class Curl {
	
	/* CURL资源 */
	private $curl;
	
	/* CURLINFO资源 */
	private $curlInfo = [];
	
	/* Curl 发送/接收 */
	public function CurlSend ($url, $method='GET', $data=[], $headers=[], $timeout=5) {
		
		$this->curl = curl_init(); //初始化CURL句柄 
		
		curl_setopt($this->curl, CURLOPT_HEADER, (empty($headers)?false:true));
		
		$headers[] = "X-HTTP-Method-Override: $method";
		
		curl_setopt($this->curl, CURLOPT_TIMEOUT, $timeout); //设置超时
		curl_setopt($this->curl, CURLOPT_URL, $url); //设置请求的URL
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出 
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
		curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method); //设置请求方式
		
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
		
		curl_setopt($this->curl,CURLOPT_HTTPHEADER,$headers);//设置HTTP头信息
		
		$document = curl_exec($this->curl);//执行预定义的CURL 
		
		$this->curlInfo[] = ['url' => $url, 'result' => curl_getinfo($this->curl)];
		
		curl_close($this->curl);
		 
		return json_decode($document, true)?:$document;
		
	}
	
	/* 获取CURL结果信息 */
	public function GetCurlInfos () {
		return $this->curlInfo;
	}
	
}
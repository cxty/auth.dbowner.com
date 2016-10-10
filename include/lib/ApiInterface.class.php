<?php 
/*
 * 通用接口类
 * 
 * @author wbqing405@sina.com
 */

include_once('Config.class.php'); //引入处理类的编码格式 utf-8

class ApiInterface{
	
	public function __construct(){

	}
	
	public function getApiInfo($nowApi){

		$post = 'https://api.weibo.com/oauth2/access_token';
		$HttpClient = new HttpClient($post);
		
		$url = 'https://localhost';
		
		$arrInfo = $HttpClient->get($url);
		$this->pr($arrInfo);
	}
	
	/*
	 * 打印类
	*/
	public function pr($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
?>
<?php
/**
 * OAuth 1.0 处理类
 * 
 * @author Administrator
 *
 */
class CommonOAuth2{
	
	public $code = 'code'; //默认的验证方式
	
	function __construct($callback=NULL,$ApiInfo=NULL,$fieldArr,$partner=null){
		$this->callback        = strtolower($callback);

		$this->api_key         = $ApiInfo['keys']['api_key'];
		$this->api_sercet      = $ApiInfo['keys']['api_sercet'];
		
		$this->authorizeURL    = $ApiInfo['urls']['authorize_URL'];
		$this->accessTokenURL  = $ApiInfo['urls']['accessToken_URL'];
		$this->hostURL         = $ApiInfo['urls']['hostURL'];
		
		$this->access_token    = $fieldArr['access_token'];
		$this->refresh_token   = $fieldArr['refresh_token'];
		$this->expires_in      = $fieldArr['expires_in'];
		
		$this->partner         = $partner;
	
		$this->code               = $this->code;
		
		$this->getClass('PartnerOAuth2');
		
		$this->partnerOAuth2 = new PartnerOAuth2($this->api_key,$this->api_sercet,$this->access_token,$this->refresh_token);
	}
	
	/**
	 * 第一步请求用户授权临时信息
	 */
	function getAuthorizeOAuth($seArr=false, $response_type='code'){	
		
		$authorize_request_url = $this->partnerOAuth2->getAuthorizeURL($this->authorizeURL,strtolower($this->callback) ,$response_type , $state = NULL, $display = NULL ,$seArr, $this->partner);

		return $authorize_request_url;		
	}
	
	/**
	 * 第二步获取用户授权信息
	 */
	function getAccessOAuth(){
		$key['code']   = $_GET['code'];

		$key['redirect_uri'] = $this->callback;

		return $this->partnerOAuth2->getAccessToken($this->accessTokenURL,$this->code,$key);
	}
	
	/**
	 * 取加密的$_COOKIE值
	 */
	public function getCookies($value=false){
		include_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/include/lib/ComFun.class.php');
		return ComFun::getCookies($value);
	}
	
	/**
	 * OAuth 2.0 处理类地址
	 */
	private function getClass($className){
		switch($className){
			case 'PartnerOAuth2':
				include_once 'PartnerOAuth2.php';
				break;
			case 'setCache':
				include_once 'setCache.php';
				break;
			default:
				break;
		}
	
	}
	
	/**
	 * 打印类
	 * @param unknown_type $arr
	 */
	function pr($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
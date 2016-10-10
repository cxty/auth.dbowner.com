<?php
/**
 * OAuth 1.0 处理类
 * 
 * @author Administrator
 *
 */
class CommonOAuth1{
	
	function __construct($callback=NULL,$ApiInfo=NULL,$fieldArr=NULL,$partner){
		$this->callback        = $callback;

		$this->partner         = $partner;
		
		$this->api_key         = $ApiInfo['keys']['api_key'];
		$this->api_sercet      = $ApiInfo['keys']['api_sercet'];
		
		$this->requestTokenURL = $ApiInfo['urls']['requestTokenURL'];
		$this->authenticateURL = $ApiInfo['urls']['authenticateURL'];
		$this->accessTokenURL  = $ApiInfo['urls']['accessTokenURL'];
		$this->hostURL         = $ApiInfo['urls']['hostURL'];
		$this->tianyaUrl       = $ApiInfo['urls']['callback'];
		
		$this->oauth_token        = $fieldArr['oauth_token'];
		$this->oauth_token_secret = $fieldArr['oauth_token_secret'];
		$this->oauth_verifier     = $fieldArr['oauth_verifier'];
	}
	
	/**
	 * OAuth 1.0 请求临时令牌
	 *
	 * @return string 返回请求地址
	 */
    function getRequestOAuth($seArr=false){
		$this->getClass('PartnerOAuth1');
		$partnerOAuth1 = new PartnerOAuth1($this->api_key,$this->api_sercet);

		if(in_array(strtolower($this->partner), array('qq','twitter','kaixin'))){
			$request_token = $partnerOAuth1->getRequestToken($this->requestTokenURL,$seArr,$this->callback);
		}else{
			$request_token = $partnerOAuth1->getRequestToken($this->requestTokenURL,$seArr);
		}

		$param_str = 'oauth_token=' . $request_token['oauth_token'];

		$this->getClass('SetCache');
		$cookies['oauth_token_secret'] = $request_token['oauth_token_secret'];
		$this->setCookies($cookies);
	
		if(in_array(strtolower($this->partner), array('tianya'))){
			$authorize_request_url = $this->authenticateURL . "?" . $param_str . "&consumer_key=" . $this->api_key . "&oauth_callback=" . urlencode(strtolower($this->callback));
		}elseif(in_array(strtolower($this->partner), array('tumblr'))){
			$authorize_request_url = $this->authenticateURL . "?" . $param_str;
		}else{
			$authorize_request_url = $this->authenticateURL . "?" . $param_str . "&oauth_callback=" . strtolower($this->callback);
		}

		return $authorize_request_url;
	}
	
	/**
	 * OAuth 1.0 请求授权令牌
	 *
	 * @return array 返回授权后的信息
	 */
	function getAccessOAuth(){
		$this->getClass('PartnerOAuth1');
	
		$oauth_token        = $this->oauth_token;
		$oauth_token_secret = $this->oauth_token_secret;
		$oauth_verifier     = $this->oauth_verifier;

		$partnerOAuth1 = new PartnerOAuth1($this->api_key,$this->api_sercet,$oauth_token,$oauth_token_secret);

		$newAuthorizeURI = $partnerOAuth1->getAuthorizeURL($this->accessTokenURL,$oauth_token);
		
		$backArr = $partnerOAuth1->getAccessToken($newAuthorizeURI,$oauth_verifier,$oauth_token);
	
		return $backArr;
	}
	
	/**
	 * OAuth 1.0 处理类地址
	 */
	private function getClass($className){
		switch($className){
			case 'PartnerOAuth1':
				include_once 'PartnerOAuth1.php';
				break;
			case 'SetCache':
				include_once 'SetCache.php';
				break;
			default:
				break;
		}
		
	}
	
	/**
	 * 注册$_COOKIE
	 */
	private function setCookies($cookies){
		include_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/include/lib/ComFun.class.php');
		ComFun::setCookies($cookies);
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
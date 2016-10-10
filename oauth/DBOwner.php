<?php
/**
 * OAuth 1.0 处理类
 * 
 * @author Administrator
 *
 */
include('DBOwnerOAuth.php'); //验证类

class DBOwner{
	var $code = 'code'; //默认的验证方式
	
	function __construct($config,$token=null){
		$this->client_id      = $config['client_id'];
		$this->client_secret  = $config['client_secret'];
		$this->redirect_uri   = $config['redirect_uri'];
		
		$this->authorizeURL   = $config['authorizeURL'];
		$this->accessTokenURL = $config['accessTokenURL'];
		$this->host           = $config['host'];
		
		$this->access_token   = $token['access_token'];
		$this->refresh_token  = $token['refresh_token'];
		$this->user_id        = $token['user_id'];
		
		
		$this->DBOwnerOAuth = new DBOwnerOAuth($this->client_id,$this->client_secret,$this->access_token,$this->refresh_token);
	}
	
	/**
	 * 第一步请求用户授权临时信息
	 */
	function getAuthorizeOAuth($seArr=false){
		$response_type = 'code';
	
		$authorize_request_url = $this->DBOwnerOAuth->getAuthorizeURL($this->authorizeURL,strtolower($this->redirect_uri) ,$response_type , $state = NULL, $display = NULL ,$seArr);
	
		return $authorize_request_url;
	}
	
	/**
	 * 第二步获取用户授权信息
	 */
	function getAccessOAuth(){
		$key['code']   = $_GET['code'];
	
		$key['redirect_uri'] = $this->redirect_uri;
	
		return $this->DBOwnerOAuth->getAccessToken($this->accessTokenURL,$this->code,$key);
	}
	/**
	 * 获取用户信息
	 */
	function getUserInfo(){
		$url = $this->host.'/users/show';
		
		$params['access_token'] = $this->access_token;
		
		$userInfo = $this->DBOwnerOAuth->get($url,$params);

		return $userInfo['data'];
	}
}
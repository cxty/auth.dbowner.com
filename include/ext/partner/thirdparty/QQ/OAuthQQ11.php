<?php
/**
 * 腾讯微博信息处理类
 *
 * @author Administrator
 *
 */
class OAuthQQ11{
	var $version = '1.0';
	
	var $sha1_method = 'HMAC-SHA1';
	
	/**
	 * util function: current nonce
	 */
	private function generate_nonce() {
		$mt = microtime();
		$rand = mt_rand();
	
		return md5($mt . $rand); // md5s look nicer than numbers
	}
	
	/**
	 * util function: current nonce
	 */
	private function generate_timestamp() {
		return time();
	}
	
	
	function __construct($callback=NULL,$ApiInfo=NULL,$fieldArr=NULL){
		$this->callback        = $callback;
		
		$this->api_key         = $ApiInfo['keys']['api_key'];
		$this->api_sercet      = $ApiInfo['keys']['api_sercet'];
		
		$this->requestTokenURL = $ApiInfo['urls']['requestTokenURL'];
		$this->authenticateURL = $ApiInfo['urls']['authenticateURL'];
		$this->accessTokenURL  = $ApiInfo['urls']['accessTokenURL'];
		$this->host            = $ApiInfo['urls']['hostURL'];
		
		$this->oauth_token        = $fieldArr['oauth_token'];
		$this->oauth_token_secret = $fieldArr['oauth_token_secret'];
		$this->oauth_verifier     = $fieldArr['oauth_verifier'];
	}
	
	function getRequestOAuth(){
		echo $this->generate_nonce();
	}
	
	
}
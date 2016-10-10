<?php
/**
 * Tumblr信息处理类
 * 
 * @author wbqing405@sina.com
 *
 */
class Providers_Tumblr{
	var $format = 'json';
	
	function __construct($host,$api_key,$api_sercet,$OAuthArr){
		$this->host                = $host;
		$this->api_key             = $api_key;
		$this->api_sercet          = $api_sercet;

		$this->oauth_token = $OAuthArr['oauth_token'];
		$this->oauth_token_secret = $OAuthArr['oauth_token_secret'];
		
		$root = dirname(dirname(__FILE__));
		
		include($root.'/common/PartnerOAuth1.php');
		$this->oauth = new PartnerOAuth1($this->api_key,$this->api_sercet,$this->oauth_token,$this->oauth_token_secret,$this->host);
	}
	/**
	 * 返回登录者的个人信息
	 * @param 用户ID
	 * @throws Exception 用户ID不存在
	 * @return 用户信息
	 */
	function getUserInfo(){	
		$params['api_key'] = $this->api_key;
		
		return $this->oauth->get($this->host.'v2/user/info', $params);
	}
}
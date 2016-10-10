<?php
/**
 * Linkedin信息处理类
 *
 * @author wbqing405@sina.com
 *
 */
class Providers_Linkedin{
	var $format = 'json';
	
	function __construct($host,$api_key,$api_sercet,$OAuthArr,$partner){
		$this->host                = $host;
		$this->api_key             = $api_key;
		$this->api_sercet          = $api_sercet;
		
		$this->oauth_token = $OAuthArr['oauth_token'];
		$this->oauth_token_secret = $OAuthArr['oauth_token_secret'];

		$this->refresh_token = $OAuthArr['refresh_token'];
		$this->access_token = $OAuthArr['access_token'];
		$this->uid = $OAuthArr['user_id'] ? $OAuthArr['user_id'] : $OAuthArr['openid'];
		$this->partner = $partner;

		$root = dirname(dirname(__FILE__));
		
		include($root.'/common/PartnerOAuth2.php');
		$this->oauth = new PartnerOAuth2($this->api_key,$this->api_sercet,$this->access_token,$this->refresh_token,$this->host, $this->partner);
	}
	
	/**
	 * 当前登录者的信息
	 *
	 * @param unknown_type $uid
	 */
	function getUserInfo(){	
		$params['oauth2_access_token'] = $this->access_token;
		$params['format']              = 'json';
		
		//return $this->host.'v1/people/~';
		return $this->oauth->get( $this->host . 'v1/people/~:(firstName,lastName,location,positions,picture-url)', $params );
	}
	
	/**
	 * 广播一条信息
	 */
	public function addInformation( $fieldArr ){
		$params['oauth2_access_token'] = $this->access_token;
		$params['format']              = 'json';
		$params['content']             = $fieldArr['content'];
		
		return $this->oauth->post( $this->host . 'v1/people/~/shares', $params );
	}
	
	/**
	 * 关注一个用户
	 */
	public function follow( $fieldArr ) {
		return array('state' => false, 'data' => array('msg' => 'The third party had not opened'));
	}
	
	/**
	 * 返回朋友信息列表（用户关注的）
	 */
	public function getFriendList( $fieldArr ){
		$params['access_token']       = $this->access_token;
	
		return $this->oauth->get( $this->host . 'me/contacts', $params );
	}
}
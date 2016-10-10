<?php
/**
 * 百度信息处理类
 *
 * @author wbqing405@sina.com
 *
 */
class Providers_Baidu{
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
	 */
	public function getUserInfo () {	
		$params = array();
		$params['access_token']       = $this->access_token;
	
		return $this->oauth->get( $this->host . 'passport/users/getInfo', $params ? $params : array() );
	}
	
	/**
	 * 广播一条信息
	 */
	public function addInformation ( $fieldArr ) {
		
		return array('dbowner_error' => -1, 'msg' => 'Baidu had not opened the api');
	}
	
	/**
	 * 关注一个用户
	 */
	public function follow( $fieldArr ) {
		return array('dbowner_error' => -1, 'msg' => 'Baidu had not opened the api');
	}
	
	/**
	 * 返回朋友信息列表
	 */
	function getFriendList ( $fieldArr ) {
		$params = array();
		$params['access_token'] = $this->access_token;
		
		if ( $fieldArr['page_size'] ) {
			$params['page_size'] = $fieldArr['page_size'];
		}
	
		if ( $fieldArr['page_no'] ) {
			$params['page_no'] = $fieldArr['page_no'];
		}
		
		if ( $fieldArr['sort_type'] ) {
			$params['sort_type'] = $fieldArr['sort_type'];
		}
		
		return $this->oauth->get( $this->host . 'friends/getFriends', $params);
	}
}
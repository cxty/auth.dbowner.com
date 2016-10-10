<?php
/**
 * 网易信息处理类
 * 
 * @author wbqing405@sina.com
 *
 */
class Providers_Wangyi{
	var $format = 'json';
	
	function __construct($host,$api_key,$api_sercet,$OAuthArr){
		$this->host                = $host;
		$this->api_key             = $api_key;
		$this->api_sercet          = $api_sercet;

// 		$this->oauth_token = $OAuthArr['oauth_token'];
// 		$this->oauth_token_secret = $OAuthArr['oauth_token_secret'];
		
// 		$root = dirname(dirname(__FILE__));
		
// 		include($root.'/common/PartnerOAuth1.php');
// 		$this->oauth = new PartnerOAuth1($this->api_key,$this->api_sercet,$this->oauth_token,$this->oauth_token_secret,$this->host);
		
		$this->refresh_token = $OAuthArr['refresh_token'];
		$this->access_token = $OAuthArr['access_token'];
		$this->uid = $OAuthArr['user_id'];
		
		$root = dirname(dirname(__FILE__));
		
		include($root.'/common/PartnerOAuth2.php');
		$this->oauth = new PartnerOAuth2($this->api_key,$this->api_sercet,$this->access_token,$this->refresh_token,$this->host,$partner);
	}
	
	/**
	 * 返回登录者的个人信息
	 */
	public function getUserInfo ( $fieldArr=array() ) {	
	
		return $this->oauth->get( $this->host . '/users/show' . '.' . $this->format, $params ? $params : array() );
	}
	
	/**
	 * 广播一条信息
	 */
	public function addInformation ( $fieldArr ) {
		if ( !$fieldArr['status'] ) {
			return array('dbowner_error' => -1, 'msg' => 'content is empty');
		}
	
		$params = array();
		$params['status'] = $fieldArr['status'];
	
		return $this->oauth->post($this->host . '/statuses/update' . '.' . $this->format, $params ? $params : array() );
	}
	
	/**
	 * 关注一个用户
	 */
	public function follow( $fieldArr ) {
		$params = array();
	
		if ( $fieldArr['user_id'] ) {
			$params['user_id'] = $fieldArr['user_id'];
			$ident = 'user_id=' . $fieldArr['user_id'];
		} else {
			$params['screen_name'] = urlencode($fieldArr['screen_name']);
			$ident = 'screen_name=' . $fieldArr['screen_name'];
		}
		
		return $this->oauth->post( $this->host . '/friendships/create' . '.' . $this->format . '?' . $ident , $params ? $params : array() );
	}
	
	/**
	 * 返回朋友信息列表
	 */
	function getFriendList ( $fieldArr ) {
		$params = array();
		if ( $fieldArr['user_id'] ) {
			$params['user_id'] = $fieldArr['user_id'];
		} else {
			$params['screen_name'] = $fieldArr['screen_name'];
		}
		if ( $fieldArr['cursor'] ) {
			$params['cursor'] = $fieldArr['cursor'];
		}
	
		return $this->oauth->get( $this->host . '/statuses/friends' . '.' . $this->format, $params);
	}
}
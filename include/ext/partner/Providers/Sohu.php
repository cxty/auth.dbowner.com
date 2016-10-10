<?php
/**
 * 搜狐信息处理类
 * 
 * @author wbqing405@sina.com
 *
 */
class Providers_Sohu{
	var $format = 'json';
	
	function __construct ( $host, $api_key, $api_sercet, $OAuthArr, $parnter ) {
		$this->host                = $host;
		$this->api_key             = $api_key;
		$this->api_sercet          = $api_sercet;

		//$this->oauth_token = $OAuthArr['oauth_token'];
		//$this->oauth_token_secret = $OAuthArr['oauth_token_secret'];
		
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
	 * @param 用户ID
	 */
	public function getUserInfo ( $uid='' ) {
		
		return $this->oauth->get( $this->host . 'users/show' . '.' . $this->format, '');
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
	
		return $this->oauth->post( $this->host . 'statuses/update' . '.' . $this->format, $params ? $params : array() );
	}
	
	/**
	 * 关注一个用户
	 */
	public function follow( $fieldArr ) {
		$params = array();
		
		if ( $fieldArr['id'] ) {
			$params['id'] = $fieldArr['id'];
			$ident = 'id';
		} else {
			$params['nick_name'] = urlencode($fieldArr['nick_name']);
			//$params['nick_name'] = $fieldArr['nick_name'];
			$ident = 'nick_name';
		}
		
		return $this->oauth->post( $this->host . 'friendships/create' . '/' . $ident . '.' . $this->format , $params ? $params : array() );
	}
	
	/**
	 * 返回朋友信息列表
	 */
	function getFriendList($uid='',$start=1,$max=50){	
		$params = array();
		
		$params['page']  = $fieldArr['page'];
		$params['count'] = $fieldArr['count'];
		
		return $this->oauth->get($this->host . 'statuses/friends' . '.' . $this->format, $params ? $params : array() );
		return $this->oauth->get($this->host . 'statuses/followers' . '.' . $this->format, $params ? $params : array() );
	}
}
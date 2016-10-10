<?php
/**
 * 点点信息处理类
 *
 * @author wbqing405@sina.com
 *
 */
class Providers_Diandian{
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
		
		return $this->oauth->get( $this->host.'v1/user/info', $params );
	}
	
	/**
	 * 广播一条信息
	 */
	public function addInformation ( $fieldArr ) {
		if ( !$fieldArr['body'] ) {
			return array('dbowner_error' => -1, 'msg' => 'content is empty');
		}
	
		$_rb = $this->getUserInfo();
		
		if ( intval($_rb['meta']['status']) != 200 ) {
			return $_rb;
		}
	
		$blogIdentity = $_rb['response']['blogs'][0]['blogCName'];
		
		$params = array();
		$params['access_token'] = $this->access_token;
		$params['type']         = 'text';
		$params['state']        = 'published';
		$params['title']        = $fieldArr['body'];
		
		return $this->oauth->post( $this->host.'v1/blog/' . $blogIdentity . '/post', $params );
	}
	
	/**
	 * 关注一个用户
	 */
	public function follow( $fieldArr ) {
		//return array('dbowner_error' => -1, 'msg' => 'Third party port is wrong');
		
		$params = array();
		$params['access_token'] = $this->access_token;
		$params['blogIdentity'] = $fieldArr['blogIdentity'];
		$params['blogCName']    = $fieldArr['blogIdentity'];
		
		return $this->oauth->post( $this->host.'v1/user/follow', $params );
	}
	
	/**
	 * 返回朋友信息列表
	 */
	function getFriendList ( $fieldArr ) {
		return array('dbowner_error' => -1, 'msg' => 'Third party port had not opened');
		
		$params = array();
		$params['access_token'] = $this->access_token;
		if ( $fieldArr['limit'] ) {
			$params['limit'] = $fieldArr['limit'];
		}
		if ( $fieldArr['offset'] ) {
			$params['offset'] = $fieldArr['offset'];
		}
		
		return $this->oauth->post( $this->host.'v1/user/following', $params );
	}
}
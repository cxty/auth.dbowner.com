<?php
/**
 * 人人网信息处理类
 * 
 * @author wbqing405@sina.com
 *
 */
class Providers_Renren{
	
	function __construct($host,$api_key,$api_sercet,$OAuthArr,$partner=''){
		$this->host                = $host;
		$this->api_key             = $api_key;
		$this->api_sercet          = $api_sercet;

		$this->refresh_token = $OAuthArr['refresh_token'];
		$this->access_token = $OAuthArr['access_token'];
		$this->uid = $OAuthArr['user_id'] ? $OAuthArr['user_id'] : $OAuthArr['openid'];
		$this->partner = $partner;

		//include('thirdparty/renren/OAuthRenren.php');
		//$this->oauth = new OAuthRenren($this->api_key,$this->api_sercet,$this->access_token,$this->refresh_token,$this->host);
	
		$root = dirname(dirname(__FILE__));
		
		include($root.'/common/PartnerOAuth2.php');
		$this->oauth = new PartnerOAuth2($this->api_key,$this->api_sercet,$this->access_token,$this->refresh_token,$this->host, $this->partner);
	}
	/**
	 * 返回登录者的个人信息
	 * @param 用户ID
	 * @throws Exception 用户ID不存在
	 * @return 用户信息
	 */
	function getUserInfo_old($uid){		
		$params['uids']         = $uid;
		$params['fields']       = 'uid,name,sex,birthday,mainurl,hometown_location,tinyurl,headurl,mainurl';
		$params['access_token'] = $this->access_token;

		return $this->oauth->_post_curl('users.getInfo', $params);

// 		$params = array('uid' => $uid);
// 		return $this->oauth->_post_curl('users.getProfileInfo', $params);
	}
	
	/**
	 * 返回登录者的个人信息
	 * @param 用户ID
	 * @throws Exception 用户ID不存在
	 * @return 用户信息
	 */
	function getUserInfo ( $fieldArr ) {
		$params = array();
		$params['access_token'] = $this->access_token;
		$params['userId']       = $fieldArr['userId'];
		
		return $this->oauth->get( $this->host . '/v2/user/get', $params ? $params : array() );
	}
	
	/**
	 * 广播一条信息
	 */
	function addInformation( $fieldArr ){
		if ( !$fieldArr['content'] ) {
			return array('dbowner_error' => -1, 'msg' => 'content is empty');
		}
		$params = array();
		$params['access_token'] = $this->access_token;
		$params['content'] = $fieldArr['content'];
		
		return $this->oauth->post( $this->host . '/v2/status/put' , $params ? $params : array() );
	}
	
	/**
	 * 关注一个用户（暂时没有提供此接口）
	 */
	public function follow( $fieldArr ) {
		
	}
	
	/**
	 * 返回朋友信息列表
	 */
	function getFriendList( $fieldArr ) {
		$params = array();
		$params['userId']     = $fieldArr['userId'];
		$params['pageSize']   = $fieldArr['pageSize'];
		$params['pageNumber'] = $fieldArr['pageNumber'];
		
		return $this->oauth->get( $this->host . '/v2/user/friend/list', $params ? $params : array() );
	}
	
	/**
	 * 
	 */
	function pr($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
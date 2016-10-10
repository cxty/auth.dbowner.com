<?php
/**
 * 开心网信息处理类
 *
 * @author wbqing405@sina.com
 *
 */
class Providers_Kaixin{
	
	var $format = 'json';
	
	function __construct($host,$api_key,$api_sercet,$OAuthArr,$partner=''){
		$this->host                = $host;
		$this->api_key             = $api_key;
		$this->api_sercet          = $api_sercet;
		
		$this->refresh_token = $OAuthArr['refresh_token'];
		$this->access_token = $OAuthArr['access_token'];
		$this->uid = $OAuthArr['user_id'] ? $OAuthArr['user_id'] : $OAuthArr['openid'];
		$this->partner = $partner;
		
		$root = dirname(dirname(__FILE__));
		
		include($root.'/common/PartnerOAuth2.php');
		$this->oauth = new PartnerOAuth2($this->api_key,$this->api_sercet,$this->access_token,$this->refresh_token,$this->host, $this->partner);
	}
	
	function get($api,$params = array()){
		$url = $this->host.$api;
		$params['access_token'] = $this->access_token;
		return $this->oauth->get($url, $params);
	}
	function post($api,$params = array(),$multi=false){
		$url = $this->host.$api.".".$this->format;
		$params['access_token'] = $this->access_token;
		return $this->oauth->post($url, $params,$multi);
	}
	/**
	 * 获取用户信息
	 */
	function getUserInfo() {		
		$params = array();
		$params['access_token'] = $this->access_token;
		$params['fields'] = 'uid,name,hometown,city,logo120,motto';
		//$params['fields'] = 'uid, name, gender, logo50,hometown, city';

		return $this->oauth->get( $this->host . 'users/me' . '.' . $this->format, $params ? $params : array() );
	}
	
	/**
	 * 广播一条信息
	 */
	public function addInformation( $fieldArr ){
		if ( !$fieldArr['content'] ) {
			return array('dbowner_error' => -1, 'msg' => 'content is empty');
		}
	
		$params = array();
		$params['access_token'] = $this->access_token;
		$params['content']      = $fieldArr['content'];
	
		return $this->oauth->post( $this->host . 'records/add'  . '.' . $this->format, $params ? $params : array() );
	}
	
	/**
	 * 关注一个用户
	 */
	public function follow( $fieldArr ) {
		if ( !$fieldArr['touid'] ) {
			return array('dbowner_error' => -1, 'msg' => 'uid is empty');
		}
		
		$params = array();
		$params['access_token']   = $this->access_token;
		$params['touid']          = $fieldArr['touid'];
		$params['code']           = $fieldArr['code'] . 'abc123';
		$params['rcode']          = ComFun::getRandom() . '_' . time();
		$params['content']        = $fieldArr['content'] . 'aaaa';
		
		return $this->oauth->post( $this->host . 'friends/add' , $params ? $params : array() );
	}
	
	/**
	 * 返回朋友信息列表
	 */
	function getFriendList($uid,$start=1,$max=50){
		$params = array();
		$params['access_token']   = $this->access_token;
		$params['fields'] = 'uid,name,hometown,city,logo120,motto';
		
		return $this->oauth->get( $this->host . 'friends/me', $params ? $params : array() );
	}
}
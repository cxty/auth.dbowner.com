<?php
/**
 * QQ空间处理类
 * //参考资料 http://wiki.open.qq.com/wiki/website/API%E5%88%97%E8%A1%A8
 * @author wbqing405@sina.com
 *
 */
class Providers_Qq{
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
	public function getUserInfo(){	
		$params['access_token']       = $this->access_token;
		$params['oauth_consumer_key'] = $this->api_key;
		$params['openid']             = $this->uid;
		$params['format']             = 'json';

		//get_info  get_user_info
		return $this->oauth->get( $this->host.'/user/get_user_info', $params ? $params : array() );
	}
	
	/**
	 * 广播一条信息
	 */
	public function addInformation( $fieldArr ){
		if ( !$fieldArr['content'] ) {
			return array('dbowner_error' => -1, 'msg' => 'content is empty');
		}
		
		$params = array();
		$params['access_token']       = $this->access_token;
		$params['oauth_consumer_key'] = $this->api_key;
		$params['openid']             = $this->uid;
		$params['format']             = 'json';
		$params['content']            = $fieldArr['content'];
	
		return $this->oauth->post($this->host . '/t/add_t', $params ? $params : array() );
	}
	
	/**
	 * 关注一个用户
	 */
	public function follow( $fieldArr ) {
		$params = array();
		$params['access_token']       = $this->access_token;
		$params['oauth_consumer_key'] = $this->api_key;
		$params['openid']             = $this->uid;
		$params['format']             = 'json';
		$params['content']            = $fieldArr['content'];
		if ( isset($fieldArr['fopenids']) ) {
			$params['fopenids'] = $fieldArr['fopenids'];
		}
		if ( isset($fieldArr['name']) ) {
			$params['name'] = $fieldArr['name'];
		}
	
		return $this->oauth->post( $this->host . '/relation/add_idol' , $params ? $params : array() );
	}
	
	/**
	 * 返回朋友信息列表（用户关注的）
	 */
	public function getFriendList( $fieldArr ){	
		$params = array();
		$params['access_token']       = $this->access_token;
		$params['oauth_consumer_key'] = $this->api_key;
		$params['openid']             = $this->uid;
		$params['format']             = 'json';
		$params['reqnum']             = $fieldArr['reqnum'];
		$params['startindex']         = $fieldArr['startindex'];
		
		return $this->oauth->get($this->host . '/relation/get_idollist' , $params ? $params : array() );
	}
	

	private function getIP() {
		if( !empty( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ){
			$REMOTE_ADDR = $_SERVER["HTTP_X_FORWARDED_FOR"];
			$tmp_ip = explode( ",", $REMOTE_ADDR );
			$REMOTE_ADDR = $tmp_ip[0];
		}
		return empty( $REMOTE_ADDR ) ? ( $_SERVER["REMOTE_ADDR"] ) : ( $REMOTE_ADDR ) ;
	}
	/**
	 * 打印类
	 * @param unknown_type $arr
	 */
	function pr($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
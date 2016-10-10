<?php
/**
 * Tianyi信息处理类
 *
 * @author wbqing405@sina.com
 *
 */
class Providers_Tianyi{
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
		$params['app_id']           = $this->api_key;
		$params['access_token']     = $this->access_token;
		$params['type']             = 'json';
		
		$uNickname = $this->oauth->get( $this->host.'upc/vitual_identity/user_network_info', $params );

		//http://ip_address:port/resource/vitual_identity/user_avatar?app_id=xxx&access_token=xxx&type=xxx
		
		$uPicture = $this->oauth->get( $this->host.'upc/vitual_identity/user_avatar', $params );
		//return $uPicture;		
		$uLocation = $this->oauth->get( $this->host.'upc/real/cellphone_and_province', $params );
		
// 		$re['nickname'] = $uNickname['user_nickname'];
// 		$re['picture']  = $uPicture;
// 		$re['location'] = $uLocation['province'];

		$re['nickname'] = $uNickname;
		$re['picture']  = $uPicture;
		$re['location'] = $uLocation;
		
		return $re;
	}
	
	/**
	 * 广播一条信息
	 */
	public function addInformation( $fieldArr ){
		return array('state' => false, 'data' => array('msg' => 'The third party had not opened'));
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
		return array('state' => false, 'data' => array('msg' => 'The third party had not opened'));
	}
}
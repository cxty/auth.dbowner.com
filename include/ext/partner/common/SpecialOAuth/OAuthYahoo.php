<?php
/**
 * 参考：http://topic.csdn.net/u/20110915/20/8c49f68d-e7ed-46f7-ae39-55420b220911.html
 * @author Administrator
 *
 */
class OAuthYahoo{
	
	function __construct($callback,$ApiInfo,$fieldArr){
		$this->callback = $callback;
		
		$this->api_key    = $ApiInfo['keys']['api_key'];
		$this->api_sercet = $ApiInfo['keys']['api_sercet'];
		
		$this->authorize_URL    = $ApiInfo['urls']['authorize_URL'];
		$this->accessToken_URL  = $ApiInfo['urls']['accessToken_URL'];
		
// 		$this->api_key = 'dj0yJmk9NWdIS1d3M2FhVURyJmQ9WVdrOVFqZENSV1I0TXpJbWNHbzlNVGczTWpnd05EazJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD1mOQ--';
// 		$this->api_sercet = '8711cf0bc73dbfd141b5618c9966644e11ab7ee0';
// 		$this->callback = 'http://www.onlypo.com/test/yahoo/index.php';
		
		//echo $this->callback;exit;
	}
	function getNonce(){
		return mt_rand();
	}
	function getTimeStamp(){
		return time();
	}
	function getSig_plaintext(){
		return  'plaintext';
	}
	function getsig_HMACSHA1(){
		return 'HMAC-SHA1';
	}
	function getVersion(){
		return '1.0';
	}
	function getXoauthLang(){
		return 'en-us';
	}
	function set_urlencode($str){
		return urlencode($str);
	}
	function set_signature($api_sercet,$auth_secret=''){
		return $api_sercet.'%26'.$auth_secret;
	}
	/**
	 * 数组转get字符串
	 */
	function getHttpStr($params){
		if(is_array($params)){
			foreach($params as $key=>$val){
				$fieldArr[] = $key.'='.$val;
			}
			
			return implode('&', $fieldArr);
		}
	}
	/**
	 * 请求临时令牌
	 */
	function getRequestOAuth(){			
		$params['oauth_nonce']            = $this->getNonce();
		$params['oauth_timestamp']        = $this->getTimeStamp();
		$params['oauth_consumer_key']     = $this->api_key;
		$params['oauth_signature_method'] = $this->getSig_plaintext();
		$params['oauth_signature']        = $this->set_signature($this->api_sercet,'');
		$params['oauth_version']          = $this->getVersion();
		$params['xoauth_lang_pref']       = $this->getXoauthLang();
		$params['oauth_callback']         = $this->set_urlencode($this->callback);
	
		$url = $this->authorize_URL.'?'.$this->getHttpStr($params);
		
		echo $url;
		
		$re = $this->http($url);
		
		$this->pr($re);
		
		exit;
		
		setcookie('auth_secret', $re['oauth_token_secret'], time() + $re['oauth_expires_in'], '/');
		
		header("Location: ".$re['xoauth_request_auth_url']);
		
		
	}
	/**
	 * 请求授权令牌
	 */
	function getAccessOAuth(){
		$verifier 	= $_GET['oauth_verifier'];
		$token 		= $_GET['oauth_token'];
		$auth_secret = $_COOKIE['auth_secret'];
		
		
		$params['oauth_consumer_key']      = $this->api_key;
		$params['oauth_signature_method']  = $this->getSig_plaintext();
		$params['oauth_version']           = $this->getVersion();
		$params['oauth_verifier']          = $verifier;
		$params['oauth_token']             = $token;
		$params['oauth_nonce']             = $this->getNonce();
		$params['oauth_signature']         = $this->set_signature($this->api_sercet,$auth_secret);
		$params['oauth_timestamp']         = $this->getTimeStamp();
		
		$url = $this->authorize_URL.'?'.$this->getHttpStr($params);
		
		echo $url;
		
		$re = $this->http($url);
		
		$this->pr($re);
	}
	/**
	 * 取用户信息
	 */
	function getUserInfo($uid){
		if($uid) {
			//yahoo的用户名
			$url = "http://social.yahooapis.com/v1/user/$oautharr[xoauth_yahoo_guid]/profile?format=json";
			$params['oauth_version'] = '1.0';
			$params['oauth_nonce'] = $nonce;
			$params['oauth_timestamp'] = $timestamp;
			$params['oauth_consumer_key'] = $client_id;
			$params['oauth_token'] = $oautharr['oauth_token'];
		
			//compute hmac-sha1 signature and add it to the params list
			$params['oauth_signature_method'] = 'HMAC-SHA1';
			$params['oauth_signature'] = oauth_compute_hmac_sig('GET', $url, $params, $secret,  $oautharr['oauth_token_secret']);
		
			$header = build_oauth_header($params, "yahooapis.com");
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, TIMEOUT);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$userinfo = curl_exec($ch);
			curl_close($ch);
			$userinfo = json_decode($userinfo);
			$username = $userinfo->profile->nickname;
			echo $username;
			var_dump($userinfo);
		}
	}
	/**
	 * curl请求
	 */
	function http($url,$method='GET'){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$str = curl_exec($ch);
		curl_close($ch);
		
		parse_str($str, $str_arr);
		
		return $str_arr;
	}
	/**
	 * 打印类
	 */	
	function pr($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
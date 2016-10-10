<?php
/**
 * 豆瓣信息处理类
 * 
 * @author wbqing405@sina.com
 *
 */
class Providers_Douban{
	
	var $format = 'json'; //json格式
	
	var $start = 1; //开始条数
	
	var $max = 50; //最大条数
	
	function __construct($host,$api_key,$api_sercet,$OAuthArr,$partner){
		$this->host                = $host;
		$this->api_key             = $api_key;
		$this->api_sercet          = $api_sercet;
	
		//$this->oauth_token = $OAuthArr['oauth_token'];
		//$this->oauth_token_secret = $OAuthArr['oauth_token_secret'];

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
	function getUserInfo () {	
		$params = array();
		$params['access_token'] = $this->access_token;

		return $this->oauth->get( $this->host . 'v2/user/~me', $params);
	}
	
	/**
	 * 广播信息
	 */
	public function addInformation ( $fieldArr ) {
		if ( !$fieldArr['text'] ) {
			return array('dbowner_error' => -1, 'msg' => 'content is empty');
		}
		$params = array();
		$params['text'] = $fieldArr['text'];
	
		return $this->oauth->post( $this->host . 'shuo/v2/statuses/' , $params ? $params : array() );
	}
	
	/**
	 * 关注一个用户
	 */
	public function follow ( $fieldArr ) {
		if ( !$fieldArr['user_id'] ) {
			return array('dbowner_error' => -1, 'msg' => 'uid is empty');
		}
		$params = array();
		$params = $fieldArr;
	
		return $this->oauth->post( $this->host . 'shuo/v2/friendships/create' , $params ? $params : array() );
	}
	
	/**
	 * 返回朋友信息列表（用户关注的）
	 */
	function getFriendList ( $fieldArr ) {
		$params = array();
		
		return $this->oauth->get( $this->host . 'shuo/v2/users/' . $fieldArr['user_id'] . '/following', $params ? $params : array() );
	}

//旧的接口
	/**
	 * 返回朋友信息列表
	 */
	function getFriendList_old($uid,$start=1,$max=50){
		if(!$uid){
			throw new Exception("userid is not exist!");
		}
	
		$url = $this->host.'people/'.$uid.'/friends?format='.$this->format.'&start-index='.$start.'&max-results='.$max;
	
		return $this->oauth->get($url);
	}
	/**
	 * 广播一条信息
	 */
	function addInformation_old($uid=null,$content){	
		if(!$content){
			throw new Exception("content is not exist!");
		}
		
		$url = 'http://api.douban.com/miniblog/saying';
		
		$re =  $this->createAddInfo($url,$content);
		
		if($re == 201){
			return 1;
		}else{
			return -1;
		}	
	}
	
	/**
	 * curl_init方式取豆瓣信息，已json方式返回用户信息
	 */
	function http ($url, $appkey,$postfields, $headermulti) {
		//return array("Authorization:Bearer ".$headermulti);
	
		$headers[] = "Authorization:OAuth2 ".$headermulti;
	
	
		$this->http_info = array();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
	
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
		$response = curl_exec($ch);
		$this->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ch));
	
		return $this->http_info;
	
		curl_close ($ch);
		return $response;
	}
	/**
	 * 获取$consumer
	 */
	function getConsumer(){
		return new OAuthConsumer($this->api_key, $this->api_sercet);
	}
	/**
	 * 获取签名方法
	 * OAuth 验证方法，这里选择 HMAC_SHA1
	 */
	function getSigMethod(){
		return new OAuthSignatureMethod_HMAC_SHA1();
	}
	/**
	 * 构造发表信息类
	 */
	function createAddInfo($url,$content){
		$sig_method = $this->getSigMethod();
		
		$consumer = $this->getConsumer();
	
		$acc_token = new OAuthConsumer($this->oauth_token, $this->oauth_token_secret);
		$acc_req = OAuthRequest::from_consumer_and_token($consumer, $acc_token, "POST", $url);
		$acc_req->sign_request($sig_method, $consumer, $acc_token);
		
		/**
		 * 构造 http header. 因为豆瓣 API 现在不接受在参数中传递 OAuth 信息，详见：
		 *
		 * http://www.douban.com/service/apidoc/auth#%E5%B8%B8%E8%A7%81%E9%97%AE%E9%A2%98
		 *
		 */
		$header = array('Content-Type: application/atom+xml', $acc_req->to_header('http://www.yourappdomain.com'));
		$requestBody = "<?xml version='1.0' encoding='UTF-8'?>
		<entry xmlns:ns0=\"http://www.w3.org/2005/Atom\" xmlns:db=\"http://www.douban.com/xmlns/\">
		<content>".$content."</content>
		</entry>";
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
		curl_setopt($ch,CURLOPT_HEADER,1);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $requestBody);
		$result = curl_exec($ch);
		curl_close($ch);
		
		$re = explode('/n',$result);
		
		$res = explode(' ',$re[0]);
		
		return $res[1];
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
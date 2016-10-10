<?php
/**
 * 腾讯微博处理类
 * 
 * @author Administrator
 *
 */
class OAuthQQ{
	
	function __construct($callback=NULL,$ApiInfo=NULL,$fieldArr=NULL){
		$this->callback        = $callback;
		
		$this->api_key         = $ApiInfo['keys']['api_key'];
		$this->api_sercet      = $ApiInfo['keys']['api_sercet'];
		
		$this->requestTokenURL = $ApiInfo['urls']['requestTokenURL'];
		$this->authenticateURL = $ApiInfo['urls']['authenticateURL'];
		$this->accessTokenURL  = $ApiInfo['urls']['accessTokenURL'];
		$this->host            = $ApiInfo['urls']['hostURL'];
		
		$this->oauth_token        = $fieldArr['oauth_token'];
		$this->oauth_token_secret = $fieldArr['oauth_token_secret'];
		$this->oauth_verifier     = $fieldArr['oauth_verifier'];
		
		$this->getClass('OAuth');
	}
	
	/**
	 * 
	 * @param unknown_type $oauth_token
	 * @param unknown_type $oauth_token_secret
	 */
	function getToken(){
		
		$this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1();	
		$this->consumer = new OAuthConsumer($this->api_key, $this->api_sercet);
		if (!empty($this->oauth_token) && !empty($this->oauth_token_secret)) {
			$this->token = new OAuthConsumer($this->oauth_token, $this->oauth_token_secret);
		} else {
			$this->token = NULL;
		}	
	}
	
	/**
	 * 
	 */
	function getRequestOAuth(){
		$this->getToken();
		
		$request_token = $this->getRequestToken($this->callback);

		$param_str = 'oauth_token=' . $request_token['oauth_token'];
	
		session_start();	
		setcookie($_COOKIE['provider'].'_request_token_secret',$request_token['oauth_token_secret'],time()+24*3600,'/');
	
		$authorize_request_url = $this->authenticateURL . "?" . $param_str ;//. "&oauth_callback=" . $this->callback;

		return $authorize_request_url;
	}
	
	function getRequestToken($oauth_callback = NULL) {
		$parameters = array();
		if (!empty($oauth_callback)) {
			$parameters['oauth_callback'] = $oauth_callback;
		}
	
		$request = $this->oAuthRequest($this->requestTokenURL, 'GET', $parameters);
	
		$token = OAuthUtil::parse_parameters($request);
	
		$this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}
	
	/**
	 *
	 */
	function getAccessOAuth(){
		$this->getToken();
		
		$oauth_token        = $this->oauth_token;
		$oauth_token_secret = $this->oauth_token_secret;
		$oauth_verifier     = $this->oauth_verifier;
		
		$newAuthorizeURI = $this->getAuthorizeURL($oauth_token);
		
		$backArr = $this->getAccessToken($newAuthorizeURI,$oauth_verifier,$oauth_token);
		
		$partner = $_COOKIE['provider'];
		
		foreach($backArr as $key=>$val){
			$cookies[$partner.'_'.$key] = $val;
		}
		
		$this->getClass('SetCache');

		SetCache::setCookies($cookies);
		
		return $backArr;
	}
	
	/**
	 * Get the authorize URL
	 *
	 * @return string
	 */
	function getAuthorizeURL($token) {
		if (is_array($token)) {
			$token = $token['oauth_token'];
		}
	
		return $this->accessTokenURL . "?oauth_token=".$token;
	}
	
	/**
	 * Exchange the request token and secret for an access token and
	 * secret, to sign API calls.
	 *
	 * @return array array("oauth_token" => the access token,
	 *                "oauth_token_secret" => the access secret)
	 */
	function getAccessToken($accessTokenURL,$oauth_verifier = FALSE, $oauth_token = false) {
		$parameters = array();
		if (!empty($oauth_verifier)) {
			$parameters['oauth_verifier'] = $oauth_verifier;
		}
	
		$request = $this->oAuthRequest($accessTokenURL, 'GET', $parameters);
	
		$token = OAuthUtil::parse_parameters($request);
	
		$this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}
	
	/**
	 * Format and sign an OAuth / API request
	 *
	 * @return string
	 */
	function oAuthRequest($url, $method, $parameters , $multi = false ,$callback=NULL) {
		if (strrpos($url, 'http://') !== 0 && strrpos($url, 'http://') !== 0) {
			$url = "{$this->host}{$url}.{$this->format}";
		}
		//echo $url;
	
		$request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters,$qParams);
		$request->sign_request($this->sha1_method, $this->consumer, $this->token);
	
		switch ($method) {
		case 'GET':
		return $this->http($request->to_url(), 'GET');
		default:
		return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata($multi) , $multi );
		}
	}
	
	/**
	 * Make an HTTP request
	 *
	 * @return string API results
	 */
	function http($url, $method, $postfields = NULL , $multi = false) {
		$this->http_info = array();
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
	
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
	
		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
	
		curl_setopt($ci, CURLOPT_HEADER, FALSE);
	
		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
					//echo "=====post data======\r\n";
					//echo $postfields;
				}
				break;
			case 'DELETE':
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if (!empty($postfields)) {
					$url = "{$url}?{$postfields}";
				}
				}
	
				$header_array = array();
	
				//curl_setopt($ci, CURLOPT_URL, SAE_FETCHURL_SERVICE_ADDRESS );
	
				//print_r( $header_array );
				$header_array2=array();
				if( $multi ) {
				$header_array2 = array("Content-Type: multipart/form-data; boundary=" . OAuthUtil::$boundary , "Expect: ");
				}
				foreach($header_array as $k => $v)
				array_push($header_array2,$k.': '.$v);
	
				curl_setopt($ci, CURLOPT_HTTPHEADER, $header_array2 );
				curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
	
				//echo $url."<hr/>";
	
				curl_setopt($ci, CURLOPT_URL, $url);
	
				$response = curl_exec($ci);
				$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
				$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
				$this->url = $url;
	
				//echo '=====info====='."\r\n";
				//print_r( curl_getinfo($ci) );
	
				//echo '=====$response====='."\r\n";
				//print_r( $response );
	
				curl_close ($ci);
				return $response;
	}
	
	/**
	 * Get the header info to store.
	 *
	 * @return int
	 */
	function getHeader($ch, $header) {
		$i = strpos($header, ':');
		if (!empty($i)) {
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			$this->http_header[$key] = $value;
		}
		return strlen($header);
	}
	
	/**
	 * @ignore
	 */
	function request_with_uid( $url , $uid_or_name = null , $page = false , $count = false , $cursor = false , $post = false )
	{
		$param = array();
		if( $page ) $param['page'] = $page;
		if( $count ) $param['count'] = $count;
		if( $cursor )$param['cursor'] =  $cursor;
	
		if( $post ) $method = 'post';
		else $method = 'get';
	
		if( is_numeric( $uid_or_name ) )
		{
			$param['user_id'] = $uid_or_name;
			return $this->$method($url , $param );
	
		}elseif( $uid_or_name !== null )
		{
			$param['screen_name'] = $uid_or_name;
			return $this->$method($url , $param );
		}
		else
		{
			return $this->$method($url , $param );
		}
	
	}
	
	/**
	 * 取得OAuth处理类
	 */
	private function getClass($className){
		$root = dirname(dirname(dirname(__FILE__)));
		
		switch($className){
			case 'OAuth':
				include_once($root.'/common/OAuth.php');	
				break;
			case 'SetCache':
				include_once($root.'/common/SetCache.php');
				break;
			default:
				break;
		}
	}
	
	function pr($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
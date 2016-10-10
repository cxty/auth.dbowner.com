<?php
/**
 * OAuth 2.0 Class
 *
 */

include 'OAuth.php';

class PartnerOAuth2 {
	/**
	 * @ignore
	 */
	public $client_id;
	/**
	 * @ignore
	 */
	public $client_secret;
	/**
	 * @ignore
	 */
	public $access_token;
	/**
	 * @ignore
	 */
	public $refresh_token;
	/**
	 * Contains the last HTTP status code returned.
	 *
	 * @ignore
	 */
	public $http_code;
	/**
	 * Contains the last API call.
	 *
	 * @ignore
	 */
	public $url;
	/**
	 * Set timeout default.
	 *
	 * @ignore
	 */
	public $timeout = 30;
	/**
	 * Set connect timeout.
	 *
	 * @ignore
	 */
	public $connecttimeout = 30;
	/**
	 * Verify SSL Cert.
	 *
	 * @ignore
	 */
	public $ssl_verifypeer = FALSE;
	/**
	 * Respons format.
	 *
	 * @ignore
	 */
	public $format = 'json';
	/**
	 * Decode returned json data.
	 *
	 * @ignore
	 */
	public $decode_json = TRUE;
	/**
	 * Contains the last HTTP headers returned.
	 *
	 * @ignore
	 */
	public $http_info;
	/**
	 * Set the useragnet.
	 *
	 * @ignore
	 */
	//public $useragent = 'Sae T OAuth2 v0.1';

	/**
	 * print the debug info
	 *
	 * @ignore
	 */
	public $debug = FALSE;

	/**
	 * boundary of multipart
	 * @ignore
	 */
	public static $boundary = '';
	
	/**
	 * construct partnerOAuth object
	 */
	function __construct($client_id, $client_secret, $access_token = NULL, $refresh_token = NULL ,$host=null ,$partner = null) {
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->access_token = $access_token;
		$this->refresh_token = $refresh_token;
		$this->host = $host;
		$this->partner = $partner;
	}

	/**
	 * authorize接口
	 *
	 * @param string $accessTokenURL 请求授权地址
	 * @param string $url 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
	 * @param string $response_type 支持的值包括 code 和token 默认值为code
	 * @param string $state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
	 * @param string $display 授权页面类型 可选范围:
	 *  - default		默认授权页面
	 *  - mobile		支持html5的手机
	 *  - popup			弹窗授权页
	 *  - wap1.2		wap1.2页面
	 *  - wap2.0		wap2.0页面
	 *  - js			js-sdk 专用 授权页面是弹窗，返回结果为js-sdk回掉函数
	 *  - apponweibo	站内应用专用,站内应用不传display参数,并且response_type为token时,默认使用改display.授权后不会返回access_token，只是输出js刷新站内应用父框架
	 * @return array
	 */
	function getAuthorizeURL($accessTokenURL, $url, $response_type = 'code', $state = NULL, $display = NULL ,$seArr=false ,$partner) {
		$params = array();

		if(strtolower($partner) == 'tianyi'){
			$params['app_id'] = $this->client_id;
		}else{
			$params['client_id'] = $this->client_id;
		}
		
		$params['redirect_uri'] = $url;
		$params['response_type'] = $response_type;
		$params['state'] = $state;
		$params['display'] = $display;
		
		if($seArr){		
			$params = array_merge($params,$seArr);
		}

		return $accessTokenURL . "?" . http_build_query($params);
	}

	/**
	 * access_token接口
	 *
	 * @param string $type 请求的类型,可以为:code, password, token
	 * @param array $keys 其他参数：
	 *  - 当$type为code时： array('code'=>..., 'redirect_uri'=>...)
	 *  - 当$type为password时： array('username'=>..., 'password'=>...)
	 *  - 当$type为token时： array('refresh_token'=>...)
	 * @return array
	 */
	function getAccessToken($accessTokenURL, $type = 'code', $keys ) {
		$params = array();
		
		switch ( strtolower(ComFun::getCookies('provider')) ) {
			case 'tianyi':
				$params['app_id']     = $this->client_id;
				$params['app_secret'] = $this->client_secret;
				break;
			case 'sohu':
				$params['client_id']     = $this->client_id;
				$params['client_secret'] = base64_encode( $this->client_secret );
				break;
			default:
				$params['client_id']     = $this->client_id;
				$params['client_secret'] = $this->client_secret;
				break;
		}	
			
		if ( $type === 'token' ) {
			$params['grant_type'] = 'refresh_token';
			$params['refresh_token'] = $keys['refresh_token'];
		} elseif ( $type === 'code' ) {
			$params['grant_type'] = 'authorization_code';
			$params['code'] = $keys['code'];
			$params['redirect_uri'] = $keys['redirect_uri'];
		} elseif ( $type === 'password' ) {
			$params['grant_type'] = 'password';
			$params['username'] = $keys['username'];
			$params['password'] = $keys['password'];
		} else {
			throw new OAuthException("wrong auth type");
		}

		$response = $this->oAuthRequest($accessTokenURL, 'POST', $params);

		if($response){
			switch(strtolower(ComFun::getCookies('provider'))){
				case 'facebook':
					$token = $this->decodeToken($response);
				case 'qq':
					$token = $this->decodeToken($response);
					$tArr['access_token'] = $token['access_token'];
					$rb = $this->get('https://graph.qq.com/oauth2.0/me', $tArr, ComFun::getCookies('provider'));
					$token['openid'] = $rb['openid'];
					break;
				case 'github':
					$token = $this->decodeToken($response);
					break;
				default:
					$token = json_decode($response, true);
					break;
			}
		}	

		if ( is_array($token) && !isset($token['error']) ) {
			$this->access_token = $token['access_token'];
			$this->refresh_token = $token['refresh_token'];
		} else {
			throw new OAuthException("get access token failed." . $token['error']);
		}
		
		return $token;
	}
	/**
	 * 解析token值
	 */
	private function decodeToken($params){
		$reArr = explode('&', $params);
			
		if($reArr){
			foreach($reArr as $key=>$val){
				$re2Arr = explode('=', $val);
				$token[$re2Arr[0]] = $re2Arr[1];
			}
		}
		
		return $token;
	}
	/**
	 * 解析 signed_request
	 *
	 * @param string $signed_request 应用框架在加载iframe时会通过向Canvas URL post的参数signed_request
	 *
	 * @return array
	 */
	function parseSignedRequest($signed_request) {
		list($encoded_sig, $payload) = explode('.', $signed_request, 2);
		$sig = self::base64decode($encoded_sig) ;
		$data = json_decode(self::base64decode($payload), true);
		if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') return '-1';
		$expected_sig = hash_hmac('sha256', $payload, $this->client_secret, true);
		return ($sig !== $expected_sig)? '-2':$data;
	}

	/**
	 * @ignore
	 */
	function base64decode($str) {
		return base64_decode(strtr($str.str_repeat('=', (4 - strlen($str) % 4)), '-_', '+/'));
	}

	/**
	 * 读取jssdk授权信息，用于和jssdk的同步登录
	 *
	 * @return array 成功返回array('access_token'=>'value', 'refresh_token'=>'value'); 失败返回false
	 */
	function getTokenFromJSSDK() {
		$key = "weibojs_" . $this->client_id;
		echo $this->getCookies($key);exit;
		//if ( isset($this->getCookies($key)) && $cookie = $this->getCookies($key) ) {
		if ( $this->getCookies($key) ) {
			parse_str($this->getCookies($key), $token);
			if ( isset($token['access_token']) && isset($token['refresh_token']) ) {
				$this->access_token = $token['access_token'];
				$this->refresh_token = $token['refresh_token'];
				return $token;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * 从数组中读取access_token和refresh_token
	 * 常用于从Session或Cookie中读取token，或通过Session/Cookie中是否存有token判断登录状态。
	 *
	 * @param array $arr 存有access_token和secret_token的数组
	 * @return array 成功返回array('access_token'=>'value', 'refresh_token'=>'value'); 失败返回false
	 */
	function getTokenFromArray( $arr ) {
		if (isset($arr['access_token']) && $arr['access_token']) {
			$token = array();
			$this->access_token = $token['access_token'] = $arr['access_token'];
			if (isset($arr['refresh_token']) && $arr['refresh_token']) {
				$this->refresh_token = $token['refresh_token'] = $arr['refresh_token'];
			}

			return $token;
		} else {
			return false;
		}
	}

	/**
	 * GET wrappwer for oAuthRequest.
	 *
	 * @return mixed
	 */
	function get($url, $parameters = array(), $partner=null) {	
		$response = $this->oAuthRequest($url, 'GET', $parameters);
//return $response;
		switch(strtolower($partner)){
			case 'qq':
				//--------检测错误是否发生
				if(strpos($response, "callback") !== false){
					$lpos = strpos($response, "(");
					$rpos = strrpos($response, ")");
					$response = substr($response, $lpos + 1, $rpos - $lpos -1);
				}
				$response = json_decode($response, true);
				break;
			default:
				if ($this->format === 'json' && $this->decode_json) {
					$response = json_decode($response, true);
				}
				break;
		}	
		
		return $response;
	}

	/**
	 * POST wreapper for oAuthRequest.
	 *
	 * @return mixed
	 */
	function post($url, $parameters = array(), $multi = false) {
		$response = $this->oAuthRequest($url, 'POST', $parameters, $multi );
//ComFun::pr($response);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	/**
	 * DELTE wrapper for oAuthReqeust.
	 *
	 * @return mixed
	 */
	function delete($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'DELETE', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	/**
	 * Format and sign an OAuth / API request
	 *
	 * @return string
	 * @ignore
	 */
	function oAuthRequest($url, $method, $parameters, $multi = false) {	
		if(!in_array(strtolower($this->partner), array('tianyi'))){
			if (strrpos($url, 'https://') !== 0 && strrpos($url, 'https://') !== 0) {
				$url = "{$this->host}{$url}.{$this->format}";
			}
		}	
		
		switch ($method) {
			case 'GET':
				$url = $parameters ? ( $url . '?' . http_build_query($parameters) ) : $url;
	
				return $this->http($url, 'GET');
			default:
				$headers = array();
				if (!$multi && (is_array($parameters) || is_object($parameters)) ) {
					$body = http_build_query($parameters);
				} else {
					$body = self::build_http_query_multi($parameters);
					$headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
				}
				//ComFun::pr($body);
				return $this->http($url, $method, $body, $headers);
		}
	}

	/**
	 * Make an HTTP request
	 *
	 * @return string API results
	 * @ignore
	 */
	function http($url, $method, $postfields = NULL, $headers = array()) {		
		//return $url;
		//echo $url;//exit;
		//ComFun::pr($postfields);exit;
		$this->http_info = array();
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		//curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		//curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_ENCODING, "");
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
		curl_setopt($ci, CURLOPT_HEADER, false);
		//curl_setopt($ci, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:19.0) Gecko/20100101 Firefox/19.0');

		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
					$this->postdata = $postfields;
				}
				break;
			case 'DELETE':
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if (!empty($postfields)) {
					$url = "{$url}?{$postfields}";
				}
		}

		if ( isset($this->access_token) && $this->access_token ){
			if(in_array(strtolower($this->partner), array('douban','renren'))){
				$headers[] = "Authorization: Bearer ".$this->access_token;
			}elseif(in_array(strtolower($this->partner), array('google'))){
				$headers[] = "Authorization: Bearer ".$this->access_token;
			}elseif(in_array(strtolower($this->partner), array('linkedin'))){
				
			}else{
				$headers[] = "Authorization: OAuth2 ".$this->access_token;
			}				
		}
		
		if(in_array(strtolower($this->partner), array('google'))){
			$headers[] = "Host: www.googleapis.com";
		}else{
			$headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
		}	
//return $url;
		curl_setopt($ci, CURLOPT_URL, $url );	
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, true);
// 		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, true); //不对认证证书来源的检查
//    		curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, true); 
// 		curl_setopt($ci, CURLOPT_CAINFO, dirname(__FILE__).'/key.pem');
		
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );

		$response = curl_exec($ci);
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;
		
//return $this->http_code;
//return $this->http_info;
		
// 		var_dump($response);
// 		if ($this->debug) {
// 			echo "=====post data======\r\n";
// 			var_dump($postfields);

// 			echo '=====info====='."\r\n";
// 			print_r( curl_getinfo($ci) );

// 			echo '=====$response====='."\r\n";
// 			print_r( $response );
// 		}
		
		curl_close ($ci);
		return $response;
	}

	/**
	 * Get the header info to store.
	 *
	 * @return int
	 * @ignore
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
	public static function build_http_query_multi($params) {
		if (!$params) return '';

		uksort($params, 'strcmp');

		$pairs = array();

		self::$boundary = $boundary = uniqid('------------------');
		$MPboundary = '--'.$boundary;
		$endMPboundary = $MPboundary. '--';
		$multipartbody = '';

		foreach ($params as $parameter => $value) {

			if( in_array($parameter, array('pic', 'image')) && $value{0} == '@' ) {
				$url = ltrim( $value, '@' );
				$content = file_get_contents( $url );
				$array = explode( '?', basename( $url ) );
				$filename = $array[0];

				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"'. "\r\n";
				$multipartbody .= "Content-Type: image/unknown\r\n\r\n";
				$multipartbody .= $content. "\r\n";
			} else {
				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
				$multipartbody .= $value."\r\n";
			}

		}

		$multipartbody .= $endMPboundary;
		return $multipartbody;
	}
	
	/**
	 * 取加密的$_COOKIE值
	 */
	public function getCookies($value=false){
		echo $value;exit;
		echo dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/include/lib/ComFun.class.php';exit;
		include_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/include/lib/ComFun.class.php');
		return ComFun::getCookies($value);
	}
	
	function pr($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
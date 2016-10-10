<?php
class OAuthRenren{
	
	var $APIVersion = '1.0';
	var $decodeFormat = 'json';
	var $_encode         ='utf-8';
	var $_sigKey = 'sig';
	
	private static $paramsOnUrlMethod = array('GET','DELETE');
	private static $userAgent = 'Timescode_RESTClient v0.0.1-alpha';
	
	public $timeout = 10;
	#Set connect timeout.
	public $connecttimeout = 30;
	#Verify SSL Cert.
	public $ssl_verifypeer = false;
	
	function __construct($api_key,$api_sercet,$access_token,$refresh_token,$host){
		$this->api_key = $api_key;
		$this->api_sercet = $api_sercet;
		$this->access_token = $access_token;
		$this->refresh_token = $refresh_token;
		$this->host = $host;
	}
	/**
	 * POST wrapper，基于curl函数，需要支持curl函数才行
	 * @param method String
	 * @param parameters Array
	 * @return mixed
	 */
	public function _post_curl(){
		$args = func_get_args();

		$this->_currentMethod	= trim($args[0]); #Method		
		$this->paramsMerge($args[1])
			 ->getCallId()
			 ->setConfigToMapping()
			 ->generateSignature();
	
		#Invoke
		unset($args);

		return $this->_POST($this->host, $this->_params);	
	}
	/**
	 * Parameters merge
	 * @param $params Array
	 * @modified by Edison tsai on 15:56 2011/01/13 for fix non-object bug
	 * @return RenRenClient
	 */
	private function paramsMerge($params){	
		$this->_params = $params;
		return $this;
	}
	/**
	 * Generate call id
	 * @author Edison tsai
	 * @created 14:48 2011/01/13
	 * @return RenRenClient
	 */
	public function getCallId(){
		$this->_call_id = str_pad(mt_rand(1, 9999999999), 10, 0, STR_PAD_RIGHT);
		return $this;
	}
	/**
	 * Setting mapping value
	 * @modified by Edison tsai on 15:04 2011/01/13 for add call id & session_key
	 * @return RenRenClient
	 */
	private function setConfigToMapping(){
		$this->_keyMapping['api_key']	   = $this->api_key;
		$this->_keyMapping['method']	   = $this->_currentMethod;
		$this->_keyMapping['v']			   = $this->APIVersion;
		$this->_keyMapping['format']	   = $this->decodeFormat;
		$this->_keyMapping['access_token'] = $this->access_token;
		return $this;
	}
	/**
	 * Generate signature for sig parameter
	 * @param method String
	 * @param parameters Array
	 * @return RenRenClient
	 */
	private function generateSignature(){
		$arr = array_merge($this->_params, $this->_keyMapping);

		ksort($arr);
		reset($arr);	

		$str = '';
		foreach($arr AS $k=>$v){
			$v=$this->convertEncoding($v,$this->_encode,"utf-8");
			$arr[$k]=$v;//转码，你懂得
			$str .= $k.'='.$v;
		}

		$this->_params = $arr;
		$str = md5($str.$this->api_sercet);
		$this->_params[$this->_sigKey] = $str;
		$this->_sig = $str;

		unset($str, $arr);
	
		return $this;
	}
	public function setEncode($encode){
		!empty($encode) and $this->_encode = $encode;
		return $this;
	}
	public static function convertEncoding($source, $in, $out){
		$in	= strtoupper($in);
		$out = strtoupper($out);
		if ($in == "UTF8"){
			$in = "UTF-8";
		}
		if ($out == "UTF8"){
			$out = "UTF-8";
		}
		if( $in==$out ){
			return $source;
		}
	
		if(function_exists('mb_convert_encoding')) {
			return mb_convert_encoding($source, $out, $in );
		}elseif (function_exists('iconv'))  {
			return iconv($in,$out."//IGNORE", $source);
		}
		return $source;
	}
	/**
	 * POST wrapper for insert data
	 * @param $url String
	 * @param $params mixed
	 * @param $username String
	 * @param $password String
	 * @param $contentType String
	 * @return RESTClient
	 */
	public function _POST($url,$params=null,$username=null,$password=null,$contentType=null) {
		return json_decode($this->call($url,'post',$params),true);
	}
	/**
	 * curl构造
	 */
	public function call($url,$method,$params=null){
		$ch = curl_init();
		//curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
		//curl_setopt($ch,CURLOPT_HEADER,1);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_POST,1);
		
		switch($method){
			case 'post':
			curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($params));
			break;
		}
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		return $result;
	}
}
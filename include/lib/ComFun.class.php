<?php
/**
 * 处理用户信息类
 * 
 * @author wbqing405@sina.com
 */
class ComFun{
	static $key = '741123';
	static $iv = 'QWE123';
	
	/**
	 * 配置文件
	 */
	private static function config () {
		global $config;
		return $config;
	}
	
	/**
	 * 声明方法
	 */
	public static function _des($key, $iv){
		include_once('DES.class.php');
		return new DES($key, $iv);
	}
	/**
	 * 加密
	 */
	public static function _encrypt($value=false){
		if($value){
			$des = self::_des(self::$key,self::$iv);
			return $des->encrypt($value);
		}
	}
	/**
	 * 解密
	 */
	public static function _decrypt($value=false){
		if($value){
			$des = self::_des(self::$key,self::$iv);
			return $des->decrypt($value);
		}
	}	
// 	/**
// 	 * 加密
// 	 */
// 	public static function _encrypt($value=false){
// 		if($value){
// 			include_once('DES.class.php');
// 			$des = new DES(self::$key,self::$iv);
				
// 			return $des->encrypt($value);
// 		}
// 	}
// 	/**
// 	 * 解密
// 	 */
// 	public static function _decrypt($value=false){
// 		if($value){
// 			include_once('DES.class.php');
// 			$des = new DES(self::$key,self::$iv);
	
// 			return $des->decrypt($value);
// 		}
// 	}
	/**
	 * 加密值
	 */
	public static function __encrypt($value){
		if($value){
			$des = self::_des(self::$key,self::$iv);
			return self::_urlencode_rfc3986(base64_encode($des->encrypt($value)));
		}
	}
	/**
	 * urlencode处理
	 */
	public static function _urlencode_rfc3986($input) {
		if (is_scalar($input)) {
			return str_replace(
					'+',
					' ',
					str_replace('%7E', '~', rawurlencode($input))
			);
		} else {
			return '';
		}
	}
	/**
	 * 解密值
	 */
	public static function __decrypt($value){
		if($value){
			$des = self::_des(self::$key,self::$iv);
			return $des->decrypt(base64_decode($value));
		}
	}
	/**
	 * url转化
	 */
	public function url_replace($string){
		return self::_decrypt(str_replace('%20', '+', $string));
	}
	/**
	 * urlencode处理
	 */
	public function urlencode_rfc3986($input) {
		if (is_scalar($input)) {
			return str_replace(
					'+',
					' ',
					str_replace('%7E', '~', rawurlencode(self::_encrypt($input)))
			);
		} else {
			return '';
		}
	}
	
	/**
	 * urldecode处理
	 */
	public static function urldecode_rfc3986($string) {
		return urldecode(self::_decrypt($string));
	}
	/**
	 * 对邮件数组进行加密处理
	 */
	public static function _encodeArr($arr){
		if(is_array($arr)){
			return self::urlencode_rfc3986(json_encode($arr));
		}
	}
	/**
	 * 对邮箱数组进行解密处理
	 */
	public static function _decodeArr($str){
		if($str){
			return json_decode(self::urldecode_rfc3986($str),true);
		}else{
			return $str;
		}
	}
	/**
	 * 注册$_COOKIES
	 */
	public static function SetCookies($params,$lifeTime=false){
		if(is_array($params)){
			session_start();
			//设定$_COOKIES保存时间;
			if(!$lifeTime){
				$lifeTime = 24 * 3600;
			}
			//self::pr($params);exit;	
			foreach($params as $key=>$val){
				setcookie($key,self::_encrypt($val),time()+$lifeTime,"/");
			}
		}
	}
	/**
	 * 取$_COOKIE值 decrypt
	 */
	public static function getCookies($pStr=false){
		if(is_array($pStr)){
			return self::getCookiesArr($pStr);
		}elseif($pStr){
			return self::_decrypt($_COOKIE[$pStr]);
		}else{
			return self::getCookiesArr($_COOKIE);
		}
	}
	/**
	 * 对$_COOKIE数组的处理，是getCookies函数的后续
	 */
	public static function getCookiesArr($fieldArr){
		if(is_array($fieldArr)){
			foreach($fieldArr as $key=>$val){
				if($key == 'cp_language' || $key == 'PHPSESSID'){
					$cookies[$key] = $val;
				}else{
					$cookies[$key] = self::_decrypt($val);
				}
			}
		}
	
		return $cookies;
	}
	/**
	 * 注销$_COOKIES
	 */
	public static function destoryCookies($fieldArr=null){
		if(is_array($fieldArr)){
			$cookies = $fieldArr;
		}else{
			$cookies = $_COOKIE;
		}
		
		session_start();
		foreach($cookies as $key=>$val){
			setcookie($key,'',time()-3600,'/');
		}
	}
	
	/**
	 * 当前URL
	 */
	public static function GetThisURL() {
		$protocol = strpos ( strtolower ( $_SERVER ['SERVER_PROTOCOL'] ), 'https' ) === false ? 'http' : 'https';
		$host = $_SERVER ['HTTP_HOST'];
		$script = $_SERVER ['SCRIPT_NAME'];
		$params = $_SERVER ['REQUEST_URI'];
		$page = $_SERVER ['PHP_SELF'];
		$script = ($script == '/index.php'?'':$script);
		return $protocol . '://' . $host . $script . $params;
	}
	
	/**
	 * 取第三方配置库
	 */
	public static function getAPIConfig(){
		$url = dirname(dirname(dirname(__FILE__))).'/conf/configProviders.php';
	
		return include($url);
	}
	/**
	 * 取指定第三方的配置信息
	 */
	public static function getNowApi($partner=false){
		$apiArr = self::getAPIConfig();
	
		$re['callback']  = $apiArr['callback'];
		$re['providers'] = $apiArr['providers'];

		if($partner){
			foreach((array)$apiArr['providers'] as $key=>$val){
				if(strtolower(trim($key)) == strtolower(trim($partner))){
					$re['provider'] = $val;
					break;
				}
			}
		}
		
		$re['partner'] = $partner;

		return $re;
	}
	/**
	 * 取指定第三方配置信息
	 */
	public static function getApiByParter($partner){
		if($partner){
			$apiArr = self::getAPIConfig();
			$re = null;
			foreach((array)$apiArr['providers'] as $key=>$val){
				if(strtolower(trim($key)) == strtolower(trim($partner))){
					$re = $val;
					break;
				}
			}
			
			return $re;
		}else{
			return null;
		}
	}
	/**
	 * 构造通过curl的条件
	 */
	public static function getTConditionByCurl($provider){
		$apiArr = self::getNowApi($provider);
		
		$OAuthArr['partner']  = $provider;
		$OAuthArr['provider'] = $apiArr['provider'];
		
		switch($apiArr['provider']['authway']){
			case 'auth1':
				$OAuthArr['OAuthArr']['oauth_token']        = self::getCookies($provider.'_oauth_token');
	 			$OAuthArr['OAuthArr']['oauth_token_secret'] = self::getCookies($provider.'_oauth_token_secret');
				break;
			case 'auth2':
				$OAuthArr['OAuthArr']['refresh_token']      = self::getCookies($provider.'_refresh_token');
				$OAuthArr['OAuthArr']['access_token']       = self::getCookies($provider.'_access_token');				
				break;
			case 'openid':
				break;
		}
		$OAuthArr['OAuthArr']['user_id']            = self::getCookies($provider.'_user_id');

		return $OAuthArr;
	}
	/**
	 * 取第三方用户信息
	 */
	public static function getTUserInfo($fieldArr){
		include_once(dirname(dirname(dirname(__FILE__))).'/include/ext/partner/common/GetUserInfo.php');
		$getUserInfo = new GetUserInfo($fieldArr['partner'],$fieldArr['provider'],$fieldArr['OAuthArr']);
		
		return $getUserInfo->getUserInfo();
	}
	/**
	 * 取第三方用户信息
	 */
	public static function getThirdInfoByGet($url,$fieldArr){
		if(isset($_COOKIE['googleLogin']) && strtolower(ComFun::getCookies('provider')) == 'google'){
			return ComFun::getUserInfoCookies();
		}else{
			$config = self::config();
			
			$url = $config['db_oauth']['host'] . $url ;//. '?' . http_build_query($fieldArr);
	
			$result = DBCurl::dbGet($url,'get',$fieldArr);
			
			return $result;
			/*
			$ci = curl_init();
			curl_setopt($ci,CURLOPT_URL, $url);
			curl_setopt($ci,CURLOPT_RETURNTRANSFER, TRUE);
			$result = curl_exec($ci);
			curl_close($ci);
		
			return json_decode($result,true);
			*/
		}	
	}
	/**
	 * 去Cookies中的用户信息
	 */
	public static function getUserInfoCookies(){
		$partner = 'Google';
	
		if(isset($_COOKIE[$partner.'_uProvider_uid'])){
			$backInfo['uDisplay_name']    = ComFun::getCookies($partner.'_uDisplay_name');
			$backInfo['location']         = ComFun::getCookies($partner.'_location');
			$backInfo['uImages']          = ComFun::getCookies($partner.'_uImages');
			$backInfo['uProvider_uid']    = ComFun::getCookies($partner.'_uProvider_uid');
		}else{
			$backInfo['error'] = -1;
		}
	
		return $backInfo;
	}
	/**
	 * 发送邮件
	 */
	public static function toSendMail($emailArr){
		include_once('Email.class.php'); //邮件发送类
		$email = new Email();
		$backStr = $email->sendMail($emailArr);
	}
	/**
	 * 随机明文 md5 16位
	 */
	public static function getRandom($len=10,$start=2,$end=16){
		$srcstr="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	
		mt_srand();
		$strs="";
	
		for($i=0;$i<$len;$i++){
			$strs.=$srcstr[mt_rand(0,35)];
		}
	
		$strs .= time();
	
		return substr(md5($strs),$start,$end);
	}
	/**
	 * 随机明文 md5 16位
	 */
	public static function getRandomCode($len=10){
		$srcstr="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	
		mt_srand();
		$strs="";

		for($i=0;$i<$len;$i++){
			$strs.=$srcstr[mt_rand(0,61)];
		}
	
		return $strs;
	}
	/**
	 * 获取IP
	 */
	public static function getIP(){
		if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]){
			$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
		}elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]){
			$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
		}elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]){
			$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
		}elseif (getenv("HTTP_X_FORWARDED_FOR")){
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}elseif (getenv("HTTP_CLIENT_IP")){
			$ip = getenv("HTTP_CLIENT_IP");
		}elseif (getenv("REMOTE_ADDR")){
			$ip = getenv("REMOTE_ADDR");
		}else{
			$ip = "Unknown";
		}
	
		return $ip;
	}
	/**
	 * 判断用户是通过PC端还是手机端访问网站
	 */
	public static function checkBrowse(){
		$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
		
		$uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";
		
		if(($ua == '' || preg_match($uachar, $ua))&& !strpos(strtolower($_SERVER['REQUEST_URI']),'wap')){
			return 1;
		}else{
			return -1;
		}
	}
	/**
	 * 判断用户是通过PC端还是手机端访问网站
	 */
	public static function checkBrowseBool(){
		$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	
		$uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";
	
		if(($ua == '' || preg_match($uachar, $ua))&& !strpos(strtolower($_SERVER['REQUEST_URI']),'wap')){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 判断手机类型
	 */
	public static function getPhoneType(){
		$ua = self::getUA();

		if($ua != false){
			if(strstr(strtolower($ua), 'iphone')){
				return 'iphone';
			}else{
				return '';
			}
		}else{
			return '';
		}
	}
	/**
	 * 函数名称: getUA
	 * 函数功能: 取UA
	 * 输入参数: none
	 * 函数返回值: 成功返回号码，失败返回false
	 * 其它说明: 说明
	 */
	function getUA(){
		if (isset($_SERVER['HTTP_USER_AGENT'])){
			return $_SERVER['HTTP_USER_AGENT'];
		}else{
			return false;
		}
	}
	/**
	 * 另一种判断通过PC亦或手机
	 */
	public static function check_wap() {
		return stristr($_SERVER['HTTP_VIA'],"wap") ? true : false;
	}
	/**
	 * url回调参数构造(index)
	 */
	public static function makeCallBack($fieldArr){
		if($fieldArr){
			foreach($fieldArr as $key=>$val){
				if($key != '_module' && $key != '_action'){
					$urlArr[$key] =  $val;
				}
			}
			if($urlArr){
				return http_build_query($urlArr);
			}
		}	
	}
	/**
	 * 回调参数保存为$_COOKIES(index)
	 */
	public static function SaveCallBack($fieldArr){
		self::SetCookies(self::pickCallBack($fieldArr));
	}
	/**
	 * 回调参数保存为$_COOKIES(index)
	 */
	public static function pickCallBack($fieldArr){
		$cookies = array();
		if($fieldArr['ident']){
			$cookies['ident'] = $fieldArr['ident'];
			foreach($fieldArr as $key=>$val){
				if($key != '_module' && $key != '_action' && $key != 'ident'){
					$cookies[$fieldArr['ident'].'_'.$key] =  $val;
				}
			}
		}
		
		return $cookies;
	}
	
	/**
	 * 回调参数保存为$_COOKIES(index)
	 */
	public static function pickParams($fieldArr){
		$urlArr = '';
		if($fieldArr){
			foreach($fieldArr as $key=>$val){
				if($key != '_module' && $key != '_action'){
					$urlArr[$key] =  $val;
				}
			}
		}
		
		return $urlArr;
	}
	
	/**
	 * 不需要登录，若存在ident系列Cookies值，不给予删除此系列值
	 */
	public static function delNoLoginCookies() {
		if ( self::getCookies('ident') ) {
			$nck = array();
			
			foreach ( self::getCookies() as $k => $v ) {
				if ( $k != 'ident' ) {
					if ( strpos($k, self::getCookies('ident') . '_') === false ) {
						$nck[$k] = $v;
					}
				}
			}
			
			self::destoryCookies($nck);
		} else {
			self::destoryCookies();
		}
	}
	
	/**
	 * 获取不需要登录的ident系列Cookies值
	 */
	public static function getNoLoginCookies() {
		if ( self::getCookies('ident') ) {
			$nck['ident'] = self::getCookies('ident');
				
			foreach ( self::getCookies() as $k => $v ) {
				if ( strpos($k, self::getCookies('ident') . '_') !== false ) {
					$nck[$k] = $v;
				}
			}
				
			return $nck;
		}
	}
	
	/**
	 * 返回授权3个值
	 */
	public function getAuthValue($model,$config,$fieldArr,$classFunction=null){
		$UserID       = $fieldArr['UserID'];
		$client_id    = $fieldArr['client_id'];
		$redirect_uri = $fieldArr['redirect_uri'];
			
		if(empty($UserID)){
			ThrowMessage::ThrowMsg(Lang::get('Ex_LostParam101'));
		}
		if(empty($client_id)){
			ThrowMessage::ThrowMsg(Lang::get('Ex_LostParam102'));
		}
		if(empty($redirect_uri)){
			ThrowMessage::ThrowMsg(Lang::get('Ex_LostParam103'));
		}
			
		//处理登录，成功后返回3个授权值
		$tArr['UserID']    = $UserID;
		$tArr['client_id'] = $client_id;
		
		if($classFunction){
			$MandOAuth = $classFunction;
		}else{
			include('MandOAuth.class.php');
			$MandOAuth = new MandOAuth($model,$config);
		}
		$token = $MandOAuth->getAuthToken($tArr);
		
		$url = $redirect_uri.'?'.http_build_query($token);
		
		return $url;
	}
	
	/**
	 * 取回调地址
	 */
	public static function getThirdCallBack ( $appInfo, $redirect_uri ) {
		if ( $appInfo['data'] ) {
			if ( $appInfo['data']['appset'] ) {
				if ( $appInfo['data']['appset']['asReCall'] ) {
					$redirect_uri = $appInfo['data']['appset']['asReCall'];
				}
			}
		}
		
		return $redirect_uri;
	}
	
	/**
	 * 取回调地址(index)
	 */
	public static function getCallBack($model,$config,$classFunction=null){
		$ident = ComFun::getCookies('ident');
		
		if($classFunction){
			$MandOAuth = $classFunction;
		}else{
			include('MandOAuth.class.php');
			$MandOAuth = new MandOAuth($model,$config);
		}
		
		if ( strtolower($ident) == 'oauthlogin' ) {
			$UserID       = ComFun::getCookies('UserID');
			$client_id    = ComFun::getCookies($ident.'_client_id');
			$redirect_uri = ComFun::getCookies($ident.'_redirect_uri');
			
			if(empty($UserID)){
				ThrowMessage::ThrowMsg(Lang::get('Ex_LostParam101'));
			}			
			if(empty($client_id)){
				ThrowMessage::ThrowMsg(Lang::get('Ex_LostParam102'));
			}			
			if(empty($redirect_uri)){
				ThrowMessage::ThrowMsg(Lang::get('Ex_LostParam103'));
			}
			
			$appInfo = $MandOAuth->getAuthAppInfo($client_id);
			$redirect_uri = self::getThirdCallBack($appInfo, $redirect_uri);
			
			//处理登录，成功后返回3个授权值
			$tArr['UserID']    = ComFun::getCookies('UserID');
			$tArr['client_id'] = ComFun::getCookies($ident.'_client_id');
		
			$token = $MandOAuth->getAuthToken($tArr);
			
			//如果access_token为1，则重新登录。此为第三方登录退出后，在线ID删除的原因
			if ( $token['access_token'] == -1 ) {
				/*
				$rgArr['UserID']    = self::getCookies('UserID');
				$rgArr['uName']     = self::getCookies('uName');
				$rgArr['client_id'] = $client_id;
				
				$token['access_token'] = $MandOAuth->reGenerateAccessToken($rgArr);
				*/
			} else {
				
			}
			
			$url = $redirect_uri.'?'.http_build_query($token);
			
			//成功操作后，清除$_COOKIES
			$cookies['ident'] = $ident;
			foreach(ComFun::getCookies() as $key=>$val){
				if( strpos($key, $ident) !== false ){
					$cookies[$key] = $val;
				}
			}
			self::destoryCookies($cookies);
		} elseif ( strtolower($ident) == 'mobileLogin' ) {
			$redirect      = ComFun::getCookies($ident.'_redirect');
			$client_id     = ComFun::getCookies($ident.'_client_id');
			$redirect_uri  = ComFun::getCookies($ident.'_redirect_uri');
			$response_type = ComFun::getCookies($ident.'_response_type');
			
			if(empty($client_id)){
				ThrowMessage::ThrowMsg(Lang::get('Ex_LostParam201'));
			}
			if(empty($redirect_uri)){
				ThrowMessage::ThrowMsg(Lang::get('Ex_LostParam202'));
			}
			if(empty($redirect_uri)){
				ThrowMessage::ThrowMsg(Lang::get('Ex_LostParam202'));
			}
			if(empty($redirect)){
				ThrowMessage::ThrowMsg(Lang::get('Ex_LostParam202'));
			}
			
			$appInfo = $MandOAuth->getAuthAppInfo($client_id);
			$redirect = self::getThirdCallBack($appInfo, $redirect_uri);
			
			//成功操作后，清除$_COOKIES
			foreach(ComFun::getCookies() as $key=>$val){
				if( strpos($key, $ident) !== false && $key != $ident.'_display'){
					$cookies[substr($key,strlen($ident)+1)] = $val;
				}
			}
			
			$url = $redirect.'?display='.self::getCookies($ident.'_display').'&'.http_build_query($cookies);

			$cookies['ident'] = $ident;
			self::destoryCookies($cookies);
		} elseif (strtolower($ident) == 'iframe') {
			$UserID       = ComFun::getCookies('UserID');
			$client_id    = ComFun::getCookies($ident.'_client_id');
				
			if(empty($UserID)){
				ThrowMessage::ThrowMsg(Lang::get('Ex_LostParam101'));
			}
			if(empty($client_id)){
				ThrowMessage::ThrowMsg(Lang::get('Ex_LostParam102'));
			}
				
			$appInfo = $MandOAuth->getAuthAppInfo($client_id);
			$redirect_uri = self::getThirdCallBack($appInfo, '');
				
			//处理登录，成功后返回3个授权值
			$tArr['UserID']    = ComFun::getCookies('UserID');
			$tArr['client_id'] = ComFun::getCookies($ident.'_client_id');
			
			$token = $MandOAuth->getAuthToken($tArr);
				
			$url = $redirect_uri.'?'.http_build_query($token);
				
			//成功操作后，清除$_COOKIES
			$cookies['ident'] = $ident;
			foreach(ComFun::getCookies() as $key=>$val){
				if( strpos($key, $ident) !== false ){
					$cookies[$key] = $val;
				}
			}
			self::destoryCookies($cookies);
		} elseif (strtolower($ident) == 'inviteCode') {
			//ComFun::pr(ComFun::getCookies());
			
			$url = '/index/inviteCode?';
			$url .= 'ic_clientid='.ComFun::getCookies('inviteCode_ic_clientid');
			$url .= '&ic_InviteCode='.ComFun::getCookies('inviteCode_ic_InviteCode');
		
			//成功操作后，清除$_COOKIES
			$cookies['ident'] = $ident;			
			foreach(ComFun::getCookies() as $key=>$val){
				if( strpos($key, $ident) !== false ){
					$cookies[$key] = $val;
				}
			}			
			self::destoryCookies($cookies);						
		} elseif ($ident != '') {
			$url = self::getCookies($ident.'_redirect');
			
			//成功操作后，清除$_COOKIES
			$cookies['ident'] = $ident;			
			foreach(ComFun::getCookies() as $key=>$val){
				if( strpos($key, $ident) !== false ){
					$cookies[$key] = $val;
				}
			}				
			self::destoryCookies($cookies);
		} else {
			$url = '/main/index';
		}
		
		return $url;
	}
	/**
	 * 获取$_GET或$_POST参数
	 */
	public static function GetString($key, $len = 0, $def = null) {
		$_val = $_GET [$key] ? $_GET [$key] : $_POST [$key];
		self::pr($_val);exit;
		if ($_val) {
			$_val = $this->_addslashes ( $_val );
			if ($len > 0) {
				return substr ( $_val, 0, $len );
			} else {
				return $_val;
			}
		} else if ($def) {
			return $def;
		} else {
			return null;
		}
	}
	/**
	 * 过滤html标签
	 */
	public static function preg_html($str){
		$str = htmlspecialchars_decode($str);
		$str = preg_replace( "@<script(.*?)</script>@is", "", $str );
		$str = preg_replace( "@<iframe(.*?)</iframe>@is", "", $str );
		$str = preg_replace( "@<style(.*?)</style>@is", "", $str );
		//$str = preg_replace( "@<(.*?)>@is", "", $str );
	
		return $str;
	}
	/**
	 * 过滤数组
	 */
	public static function uhtml($value)
	{
		if(is_array($value)){
			foreach($value as $key=>$val){
				$value[$key] = self::_uhtml($val);
			}
		}else{
			$value = self::_uhtml($value);
		}

		return $value;
	}
	/**
	 * 过滤
	 */
	private static function _uhtml($str){
		$farr = array(
				"/\s+/", //过滤多余空白
				//过滤 <script>等可能引入恶意内容或恶意改变显示布局的代码,如果不需要插入flash等,还可以加入<object>的过滤
				"/<(\/?)(script|i?frame|style|html|body|title|link|meta|\?|\%)([^>]*?)>/isU",
				"/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",//过滤javascript的on事件
		);
		$tarr = array(
				" ",
				"＜\1\2\3＞",//如果要直接清除不安全的标签，这里可以留空
				"\1\2",
		);
		$str = preg_replace( $farr,$tarr,$str);
		return $str;
	}
	/**
	 * 剥去 HTML、XML 以及 PHP 的标签
	 */
	public static function preg_strip_tags($str){
		return strip_tags($str);
	}
	/**
	 * curl传递过滤标签处理
	 */
	public static function preg_curl_strip($str){
		$str = self::preg_html($str);
		$str = self::preg_strip_tags($str);
		
		return $str;
	}
	/**
	 * 剥去所有html标签代码
	 */
	public static function delhtml($str){   //清除HTML标签
		$st=-1; //开始
		$et=-1; //结束
		$stmp=array();
		$stmp[]="&nbsp;";
		$len=strlen($str);
		for($i=0;$i<$len;$i++){
		   $ss=substr($str,$i,1);
		   if(ord($ss)==60){ //ord("<")==60
		    $st=$i;
		   }
		   if(ord($ss)==62){ //ord(">")==62
		    $et=$i;
		    if($st!=-1){
		     $stmp[]=substr($str,$st,$et-$st+1);
		    }
		   }
		}
		$str=str_replace($stmp,"",$str);
		return $str;
	}
	/**
	 * 取授权信息
	 */
	public static function getPermissionInfo(){
		return include(dirname(dirname(dirname(__FILE__))).'/conf/config_oauthinfo.php');
	}
	/**
	 * 获取oauth错误信息的中文说明
	 */
	public static function getErrorValue($fKey,$key){
		$errArr = include(dirname(dirname(dirname(__FILE__))).'/conf/error.php');
		if($errArr){
			return $errArr[$fKey][$key];
		}else{
			return null;
		}
	}
	//直接跳转
	public static function throwMsg($msgkey='Ex_UnknowError',$fieldArr=null) {
		if($fieldArr){
			$param = '&'.http_build_query($fieldArr);
		}else{
			$param = '';
		}
		$url = __ROOT__.'/throwMessage/throwMsg?msgkey='.$msgkey.$param;
		header ( 'location:' . $url, false, 301 );
		exit ();
	}
	//直接跳转
	public static function throwErrorMsg($value) {	
		if($value){
			$msg = self::__encrypt($value);
		}else{
			$msg = '';
		}

		$url = __ROOT__.'/throwMessage/throwMsg?msg='.$msg;
		header ( 'location:' . $url, false, 301 );
		exit ();
	}
	/**
	 * 验证是否是邮箱
	 */
	public static function checkEmail($emial){
		try{
			if($emial && !is_array($emial)){
				if(preg_match("/^[0-9a-zA-Z]+(?:[\_\-][a-z0-9\-]+)*@[a-zA-Z0-9]+(?:[-.][a-zA-Z0-9]+)*\.[a-zA-Z]+$/i",$emial)){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}	
	}
	/**
	 * 引入安全认证类型配置文件
	 */
	public function cdAuthType(){
		return include_once(dirname(dirname(dirname(__FILE__))).'/conf/Dictionary/AuthType.php');
	}
	/**
	 * 安全认证选择框
	 */
	public function stAuthType($stName="AuthType", $value=0){
		$_re = self::cdAuthType();

		$html = '<select name="'.$stName.'" id="'.$stName.'">';
		if($_re){
			foreach($_re as $key=>$val){
				if(intval($value) == $key){
					$html .= '<option value="'.$key.'" selected>'.$val.'</option>';
				}else{
					$html .= '<option value="'.$key.'">'.$val.'</option>';
				}
			}
		}
		$html .= '</select>';

		return $html;
	}
	/**
	 *程  序：判断是否是通过手机访问
	 *版  本：Ver 1.0 beta
	 *修  改：奇迹方舟(imiku.com)
	 *最后更新：2010.11.4 22:56
	 *程序返回：@return bool 是否是移动设备
	 *该程序可以任意传播和修改，但是请保留以上版权信息!
	 */
	public static function isMobileClient() { 
		// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
		if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
			return true;
		}
		//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
		if (isset ($_SERVER['HTTP_VIA'])) {  //找不到为flase,否则为true
			return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
		}
		//脑残法，判断手机发送的客户端标志,兼容性有待提高
		if (isset ($_SERVER['HTTP_USER_AGENT'])) {
			$clientkeywords = array (   'nokia',   'sony',   'ericsson',   'mot',   'samsung',   'htc',   'sgh',   'lg',   'sharp',   'sie-',   'philips',   'panasonic',   'alcatel',   'lenovo',   'iphone',   'ipod',   'blackberry',   'meizu',   'android',   'netfront',   'symbian',   'ucweb',   'windowsce',   'palm',   'operamini',   'operamobi',   'openwave',   'nexusone',   'cldc',   'midp',   'wap',   'mobile'  );
			// 从HTTP_USER_AGENT中查找手机浏览器的关键字
			if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
				return true;
			}
		}
		//协议法，因为有可能不准确，放到最后判断
		if (isset ($_SERVER['HTTP_ACCEPT'])) {
			// 如果只支持wml并且不支持html那一定是移动设备
			// 如果支持wml和html但是wml在html之前则是移动设备
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
				return true;
			}
		}
		return false;
	}
	/**
	 * 数组某一键名排序
	 */
	public static function array_sort($arr,$keys,$type='asc',$rekey=false,$bNum=-1){
		if( $arr ){
			$keysvalue = $new_array = array();
			foreach ($arr as $k=>$v){
				$keysvalue[$k] = $v[$keys];
			}
			if($type == 'asc'){
				asort($keysvalue);
			}else{
				arsort($keysvalue);
			}
			reset($keysvalue);
			if( $rekey ){
				$i = 0;
				foreach ($keysvalue as $k=>$v){
					$new_array[$i] = $arr[$k];
					if( ++$i == $bNum ){
						break;
					}
				}
			}else{
				$i = 0;
				foreach ($keysvalue as $k=>$v){
					$new_array[$k] = $arr[$k];
					if( ++$i == $bNum ){
						break;
					}
				}
			}
			return $new_array;
		}else{
			return '';
		}
	}

	/**
	 * 回调地址重组
	 */
	public static function recombinationUrl ( $redirect_uri, $params=array() ) {
		$redirectArr = parse_url($redirect_uri);
	
		$_query = '';
		if ( $redirectArr['query'] ) {
			$_query = '&';
		} else {
			$_query = '?';
		}
	
		return $redirect_uri . ( $params ? $_query . http_build_query($params) : '');
	}
	
	
	/**
	 * 加载数据处理类,并返回类对象
	 *
	 * @param string $ClassName
	 * @param
	 *        	$model
	 * @param
	 *        	$config
	 * @return Object new ClassName
	 */
	static public function RequireClass($ClassName, $model, $config, $model_mongo = null) {
		if (is_file ( dirname(dirname(dirname(__FILE__))) . '/include/lib/' . $ClassName . '.class.php' ))
			require_once (dirname(dirname(dirname(__FILE__))) . '/include/lib/' . $ClassName . '.class.php');
		if ($model_mongo) {
			return new $ClassName ( $model,$model_mongo, $config );
		} else {
			return new $ClassName ( $model, $config );
		}
	}
	
	/**
	 * 判断是否是邮箱，并返回邮箱@前面的字符
	 * @param unknown $email
	 * @return multitype:boolean string
	 */
	static public function getEmail ( $email ) {
		$pattern = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
		//$pattern = '/^[_.0-9a-z-a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,4}$/';
		preg_match_all($pattern, $email, $emailArr);
	
		return array(
				'state' => $emailArr[0] ? true : false,
				'str' => $emailArr[1] ? $emailArr[1][0] : ''
		);
	}
	
	/**
	 * addslashes() 别名函数,加强对数组类型(array)的数据处理
	 * 该函数并添加了对MSSQL 的转义字符异常的支持,但前提是SQL 的分界符为’ 即单引号
	 *
	 * @param
	 *        	string | array $string
	 * @param boolean $force
	 *        	是否强制转换转义字符
	 * @return string | array
	 */
	static public function _addslashes($string, $force = 0) {
		global $db_type;
		if (! get_magic_quotes_gpc () || $force) {
			if (is_array ( $string )) {
				foreach ( $string as $key => $val ) {
					$string [$key] = self::_addslashes ( $val, $force );
				}
			} else {
				$string = addslashes ( $string );
			}
		}
		return $string;
	}
	
	/**
	 * 打印类
	 */
	public static function pr($arr=null){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
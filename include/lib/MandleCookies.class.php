<?php
/**
 * 设置$_COOKIES
 * 
 * @author wbqing405@sina.com
 *
 */

class MandleCookies{
	
	static $key = '741123';
	static $iv = 'QWE123';
	
	/**
	 * 加密
	 */
	public static function _encrypt($value=false){
		if($value){
			include_once('DES.class.php');			
			$des = new DES(self::$key,self::$iv);
			
			return $des->encrypt($value);
		}	
	}	
	/**
	 * 解密
	 */
	public static function _decrypt($value=false){
		if($value){
			include_once('DES.class.php');		
			$des = new DES(self::$key,self::$iv);
		
			return $des->decrypt($value);
		}	
	}	
	/**
	 * 注册$_COOKIES  
	 */
	public static function SetCookies($params,$lifeTime=false){	
		if(is_array($params)){
			session_start();		
			//设定$_COOKIES保存时间;
			if($lifeTime){
				$lifeTime = $lifeTime;
			}else{
				$lifeTime = 24 * 3600;
			}
			
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
	public static function destoryCookies($params){	
		if(is_array($params)){
			session_start();
			foreach($params as $key=>$val){
				setcookie($key,'',time()-3600,'/');
			}
			
		}
	}
	/**
	 * 取第三方user_id值
	 */
	public static function getTUserID($fieldArr=null){
		$provider = $this->getCookies('provider');
		
		if(is_array($fieldArr)){
			$uid = $this->getFUserID($provider,$fieldArr);
		}else{
			$uid = $this->getCUserID($provider);
		}
		
		return $uid;
	}
	/**
	 * 取数组中的TUserID
	 */
	public static function getFUserID($provider,$fieldArr){
		if(is_array($fieldArr)){
			if($provider == 'Douban'){
				$user_id = $fieldArr['douban_user_id'];
			}elseif($provider == 'QQ'){
				$user_id = $fieldArr['name'];
			}else{
				$user_id = $fieldArr['user_id'];
			}
			
			return $user_id;
		}	
	}
	/**
	 * 取$_COOKIE中的TUserID
	 */
	public static function getCUserID($provider){
		if($provider == 'Douban'){
			$user_id = $this->getCookies($provider.'_douban_user_id');
		}elseif($provider == 'QQ'){
			$user_id = $this->getCookies($provider.'_name');
		}else{
			$user_id = $this->getCookies($provider.'_user_id');
		}
		
		return $user_id;
	}
	/**
	 * 取第三方配置库
	 */
	public static function getAPIConfig(){
		$url = dirname(dirname(dirname(__FILE__))).'/conf/configProviders.php';
		
		return include_once($url);
	}
	/**
	 * 取指定第三方的配置信息
	 */
	public static function getPartnerInfo($partner){
		$apiArr = self::getAPIConfig();
		
		$re['callback'] = $apiArr['callback'];
		
		foreach($apiArr['providers'] as $key=>$val){
			if(trim($key) == trim($partner)){
				$re['provider'] = $val;
			}
		}
		
		$re['partner'] = $partner;
		
		return $re;
	}
}
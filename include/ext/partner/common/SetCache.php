<?php
/**
 * 设置$_COOKIES
 * 
 * @author wbqing405@sina.com
 *
 */
class SetCache{
	function __construct(){
		
	}
	
	/**
	 * 注册$_COOKIES
	 * @param  $params
	 */
	public static function setCookies($params){
		if(is_array($params)){
			session_start();
			$lifeTime = 24 * 3600;//设定$_COOKIES保存时间;
			foreach($params as $key=>$val){
				setcookie($key,$val,time()+$lifeTime,"/");
			}
		}
	}
	
	/**
	 * 注销$_COOKIES
	 * @param  $params
	 */
	public static function destoryCookies($params){
		if(is_array($params)){
			session_start();
			foreach($params as $key=>$val){
				setcookie($key,'',time()-3600,'/');
			}
		}
	}
}
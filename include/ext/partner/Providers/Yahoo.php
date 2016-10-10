<?php
/**
 * Yahoo信息处理类
 *
 * @author wbqing405@sina.com
 *
 */
class Providers_Yahoo{
	
	function __construct(){
		
	}
	
	/**
	 * 当前登录者的信息
	 * 
	 * @param unknown_type $uid
	 */
	function getUserInfo($uid){
		return $this->oauth->get('http://api.t.sina.com.cn/account/verify_credentials.json');
	}
}
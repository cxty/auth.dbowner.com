<?php
/**
 * DB内部curl传递类
 *
 * @author wbqing405@sina.com
 */
class DBInner{
	/**
	 * 获取用户D币总额度
	 */
	public static function getUserDBCount(){
		$access_token = ComFun::getCookies('access_token');
		
		if(!$access_token){
			return 0;
		}
		
		$tArr['access_token'] = $access_token;
		
		$_rb = DBCurl::dbGet($GLOBALS['config']['PLATFORM']['Pay'].'/coin/total', 'get', $tArr);

		if(!$_rb['state']){
			return 0;
		}
		
		return $_rb['total'];
	}
}
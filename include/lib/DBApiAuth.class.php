<?php
/**
 * 邀请码处理类
 *
 * @author wbqing405@sina.com
 */

include_once('Addslashes.class.php'); //数据过滤类

class DBApiAuth{
	public function __construct($config){
		$this->config = $config;
		
		$this->init();
	}
	/**
	 * 初始化
	 */
	private function init(){
		$this->Addslashes = new Addslashes();
	}
	/**
	 * 邀请码应用用户调用鉴权
	 */
	public function checkValid($fieldArr){
		return DBCurl::dbGet($this->config['PLATFORM']['Expand'].'/apiAuth/check', 'GET', $fieldArr);
	}
	/**
	 * 检验用户是否已经输入过邀请码
	 */
	public function checkUserHadDone($fieldArr){
		return DBCurl::dbGet($this->config['PLATFORM']['Plus'].'/inviteCode/used', 'GET', $fieldArr);
	}
	/**
	 * 通过url进行邀请码验证
	 */
	public function useInviteCode($fieldArr){
		return DBCurl::dbGet($this->config['PLATFORM']['Plus'].'/inviteCode/useInviteCode', 'GET', $fieldArr);
	}
	/**
	 * 通过邀请码获取应用ID
	 */
	public function getClientIDByInviteCode($fieldArr){
		return DBCurl::dbGet($this->config['PLATFORM']['Plus'].'/inviteCode/getClientIDByInviteCode', 'GET', $fieldArr);
	}
}
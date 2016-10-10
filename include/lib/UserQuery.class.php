<?php 
/**
 * 处理用户信息类
 * 
 * @author wbqing405@sina.com
 */

include_once('Config.class.php'); //引入处理类的编码格式 utf-8
include_once('Addslashes.class.php'); //数据过滤类
include_once('ComFun.class.php'); //公共方法

class UserQuery{
	
	var $tbUserInfo = 'tbUserInfo'; //用户基础信息表
	
	var $tbUserLoginInfo = 'tbUserLoginInfo'; //用户登录累计信息
	
	var $tbUserOnLineLogInfo = 'tbUserOnLineLogInfo';  //用户在线记录信息 
	
	var $tbUserFailedLoginLogInfo = 'tbUserFailedLoginLogInfo'; //用户登录失败记录信息 
	
	var $tbUserAuthenticationsInfo = 'tbUserAuthenticationsInfo'; //第三方平台登录信息
	
	var $tbUserOnLineTime = 'tbUserOnLineTime'; //用户在线时间记录信息(每月一条)
	
	public function __construct($base){
		$this->model  = $base;		

		$this->init();
	}
	
	/**
	 * 初始化
	 */
	private function init(){
		$this->Addslashes = new Addslashes();
	}
	/**
	 * 查询用户基础信息
	 */
	public function seUserInfo($condition,$field=''){
		return $this->selectTable($this->tbUserInfo,$condition,$field);
	}
	/**
	 * 增加用户基础信息
	 */
	public function inUserInfo($fieldArr){
		$data['UserGroupsID']  = $fieldArr['UserGroupsID'];
		$data['uEmail']        = $fieldArr['uEmail'];
		$data['uName']         = $fieldArr['uName'];
		$data['uPWD']          = $fieldArr['uPWD'];
		$data['uCode']         = $fieldArr['uCode'];
		$data['uAppendTime']   = time();
		$data['uEstate']       = -1;

		return $this->insertTable($this->tbUserInfo,$data);
	}
	/**
	 * 更新用户基础表
	 */
	public function upUserInfo($condition,$data){
		return $this->updateTable($this->tbUserInfo,$condition,$data);
	}
	/**
	 * 执行用户信息sql
	 */
	public function doUserInfo($where){
		$sql = 'select * from '.$this->tbUserInfo.' where '.$where;
		
		return $this->doTable($sql);
	}
	/**
	 * 查询用户累计信息
	 */
	public function seUserLoginInfo($condition,$field=''){
		return $this->selectTable($this->tbUserLoginInfo,$condition,$field);
	}
	/**
	 * 更新用户累计信息
	 */
	public function upUserLoginInfo($condition,$data){
		return $this->updateTable($this->tbUserLoginInfo,$condition,$data);
	}
	/**
	 * 增加用户累计信息
	 */
	public function inUserLoginInfo($fieldArr){
		$time = time();
		$IP   = ComFun::getIP();
		
		$data['UserID']        = $fieldArr['UserID'];
		$data['uUpAppendTime'] = $time;
		$data['uLastActivity'] = $time;
		$data['uLastIP']       = $IP;
		$data['uRegIP']        = $IP;
		$data['olTime']        = 0;
		
		return $this->insertTable($this->tbUserLoginInfo,$data);
	}
	/**
	 * 查询用户在线时间记录信息(每月一条) 
	 */
	public function seUserOnLineTime($condition,$field=''){
		return $this->selectTable($this->tbUserOnLineTime,$condition,$field);
	}
	/**
	 * 更新用户在线时间记录信息(每月一条)
	 */
	public function upUserOnLineTime($condition,$data){
		return $this->updateTable($this->tbUserOnLineTime,$condition,$data);
	}
	/**
	 * 增加用户在线时间记录信息(每月一条)
	 */
	public function inUserOnLineTime($fieldArr){
		$data['UserID']       = $fieldArr['UserID'];
		$data['thisyear']     = date('Y');
		$data['thismonth']    = date('m');
		$data['total']        = 0;
		$data['lastupdate']   = time();
		
		return $this->insertTable($this->tbUserOnLineTime,$data);
	}
	/**
	 * 查询用户在线记录信息
	 */
	public function seUserOnLineLogInfo($condition,$field=''){
		return $this->selectTable($this->tbUserOnLineLogInfo,$condition,$field);
	}
	/**
	 * 更新用户在线记录信息
	 */
	public function upUserOnLineLogInfo($condition,$data){
		return $this->updateTable($this->tbUserOnLineLogInfo,$condition,$data);
	}
	/**
	 * 删除用户在线信息
	 */
	public function deUserOnLineLogInfo($condition){
		return $this->deleteTable($this->tbUserOnLineLogInfo, $condition);
	}
	/**
	 * 增加用户在线记录信息
	 */
	public function inUserOnLineLogInfo($fieldArr){
		$time = time();
		$IP   = ComFun::getIP();
		
		$data['UserID']        = $fieldArr['UserID'];
		$data['oIP']           = $IP;
		$data['oUserName']     = $fieldArr['uName'];
		$data['UserGroupsID']  = $fieldArr['UserGroupsID'];
		$data['oAppendTime']   = $time;
		$data['oLastTime']     = $time;
		$data['oCode']         = $_COOKIE['PHPSESSID'];
		
		return $this->insertTable($this->tbUserOnLineLogInfo,$data);
	}
	/**
	 * 查询用户登录失败记录信息
	 */
	public function seUserFailedLoginLogInfo($condition,$field=''){
		return $this->selectTable($this->tbUserFailedLoginLogInfo,$condition,$field);
	}
	/**
	 * 更新用户登录失败记录信息
	 */
	public function upUserFailedLoginLogInfo($condition,$data){
		$data['lastupdate'] = time();
		
		return $this->updateTable($this->tbUserFailedLoginLogInfo,$condition,$data);
	}
	/**
	 * 插入用户登录失败记录信息
	 */
	public function inUserFailedLoginLogInfo(){
		$data['ip']         = ComFun::getIP();
		$data['errcount']   = 1;
		$data['lastupdate'] = time();
		
		return $this->insertTable($this->tbUserFailedLoginLogInfo,$data);
	}
	/**
	 * 删除用户登录失败记录信息
	 */
	public function deUserFailedLoginLogInfo($condition){
		return $this->deleteTable($this->tbUserFailedLoginLogInfo, $condition); 
	}
	/**
	 *  查询第三方平台登录信息
	 */
	public function seUserAuthenticationsInfo($condition,$field){
		return $this->selectTable($this->tbUserAuthenticationsInfo,$condition,$field);
	}
	/**
	 * 更新第三方平台登录信息
	 */
	public function upUserAuthenticationsInfo($condition,$data){
		$data['lastupdate'] = time();
	
		//return $this->updateTable($this->tbUserAuthenticationsInfo,$condition,$data);
	}
	/**
	 * 插入第三方平台登录信息
	 */
	public function inUserAuthenticationsInfo($data){
		return $this->insertTable($this->tbUserAuthenticationsInfo,$data);
	}
	/**
	 * 查询方法
	 */
	private function selectTable($table,$condition,$field){
		if($field){
			return $this->model->table($table)->field($field)->where($condition)->select();
		}else{
			return $this->model->table($table)->where($condition)->select();
		}
		
	}
	/**
	 * 更新方法
	 */
	private function updateTable($table,$condition,$data){
		return $this->model->table($table)->data($data)->where($condition)->update();
	}
	/**
	 * 插入方法
	 */
	private function insertTable($table,$data){
		return $this->model->table($table)->data($data)->insert();
	}
	/**
	 * 删除方法
	 */
	private function deleteTable($table,$condition){
		return $this->model->table($table)->where($condition)->delete();
	}
	/**
	 * 执行原型SQL
	 */
	private function doTable($sql){
		return $this->model->query($sql);
	}
}
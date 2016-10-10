<?php
/**
 * 用户处理类
 *
 * @author wbqing405@sina.com
 */
header('Content-type:text/html;charset=utf-8');
class User{
	
	var $tbUserGroupsInfo = 'tbUserGroupsInfo'; //用户组表
	var $tbUserInfo = 'tbUserInfo'; //用户信息表
	var $tbUserDetInfo = 'tbUserDetInfo'; //用户详细信息
	var $tbUserHeadInfo = 'tbUserHeadInfo'; //用户头像信息
	var $tbUserLoginInfo = 'tbUserLoginInfo'; //用户在线登录累计信息
	var $tbUserAccountSafeInfo = 'tbUserAccountSafeInfo'; //用户账户安全信息
	var $tbUserAuthInfo = 'tbUserAuthInfo'; //户应用授权信息
	var $tbUserCharacterInfo = 'tbUserCharacterInfo'; //户个性设置信息
	var $tbUserPointInfo = 'tbUserPointInfo'; //户积分记录信息
	var $tbUserAuthenticationsInfo = 'tbUserAuthenticationsInfo'; //第三方应用信息
	var $tbUserOnLineLogInfo = 'tbUserOnLineLogInfo'; //用户在线记录信息
	var $tbUserOnLineTime = 'tbUserOnLineTime'; //用户在线记录信息（每月一条）
	var $tbUserInviteFriendInfo = 'tbUserInviteFriendInfo'; //用户邀请表
	var $tbSysErrorLogInfo = 'tbSysErrorLogInfo'; //系统错误信息表
	var $tbUserFailedLoginLogInfo = 'tbUserFailedLoginLogInfo'; //用户登录失败记录信息 
	var $tbAppAuthNumLogInfo = 'tbAppAuthNumLogInfo'; //应用连接请求次数 
	var $tbUserAuthNumLogInfo = 'tbUserAuthNumLogInfo'; //用户连接请求次数 
	var $tbUserAuthFailedLoginLogInfo = 'tbUserAuthFailedLoginLogInfo'; //用户登录失败记录信息 
	
	
	function __construct($model){
		$this->model = $model;
	}
	/**
	 * 选表的信息
	 */
	function seTableData($tableName,$condition,$order=''){
		$userInfo = $this->model->table($tableName)->where($condition)->order($order)->select();
		
		if($userInfo){
			return $userInfo;
		}else{
			return -1;
		}
	}
	/**
	 * 更新表的信息
	 */
	function upTableData($tableName,$condition,$udata){	
		$ndata = $this->getUpdate($udata);

		$re = $this->model->table($tableName)->data($ndata)->where($condition)->update();
		
		return 1;
		
		if($re){
			return $re;
		}else{
			return -1;
		}
	}
	/**
	 * 更新数据判断，为空则略过数据
	 */
	function getUpdate($udata){
		if(is_array($udata)){
			foreach($udata as $key=>$val){
				if($val == 0 || $val){
					$ndata[$key] = $val;
				}
			}
			
			return $ndata;
		}	
	}
	/**
	 * 增加表的信息
	 */
	function inTableData($tableName,$idata){
		$re = $this->model->table($tableName)->data($idata)->insert();
		if($re){
			return $re;
		}else{
			return -1;
		}
	}
	/**
	 * 删除表的信息
	 */
	function deTableData($tableName,$condition){	
		$re = $this->model->table($tableName)->where($condition)->delete();
		
		return 1;
		
		if($re){
			return 1;
		}else{
			return -1;
		}
	}
	/**
	 * 选出表的列表信息
	 */
	function geTableData($tableName,$page=1,$pagesize=10,$condition,$order){
		$page = $this->getPage($page);
		
		if($condition){
			$condition = ' where '.$condition;
		}else{
			$condition = '';
		}
	
		if($order){
			$order = ' order by '.$order;
		}else{
			$order = '';
		}
		
		$sql_count = 'select COUNT(1) as count from '.$tableName.$condition;
		
		$_re = $this->model->query($sql_count);
		if($_re){
			$count = $_re[0]['count'];
		}else{
			$count = 0;
		}
		
		$sql = 'select * from '.$tableName.$condition.$order.' limit '.(($page-1)*$pagesize).','.$pagesize;
	
		$userList = $this->model->query($sql);
		
		if($userList){
			$re['count'] = intval($count);
			$re['list']  = $userList;
		}else{
			$re['count'] = 0;
			$re['list']  = null;
		}
		
		return $re;
	}
	/**
	 * 列表页码
	 */
	public function getPage($page){
		if($page <= 1){
			return 1;
		}else{
			return $page;
		}
	}
	/**
	 * 取UserID用户信息
	 */
	public function getUserInfo($UserID){		
		$condition['UserID'] = intval($UserID);

		$UserInfo = $this->model->table($this->tbUserInfo)->where($condition)->select();

		if($UserInfo){			
			$rb['UserID'] = $UserInfo[0]['UserID'];
			$rb['uName']  = $UserInfo[0]['uName'];
			$rb['uEmail'] = $UserInfo[0]['uEmail'];
			
			return $rb;
		}else{
			return -1;
		}
	}
}
?>
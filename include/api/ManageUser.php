<?php
/**
 * SOAP服务器处理类
 *
 * @author wbqing405@sina.com
 */
include('Server.class.php');

class ManageUser extends Server{
	
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
	var $tbUserInviteCode = 'tbUserInviteCode'; //邀请码
	var $tbAppScoreInfo = 'tbAppScoreInfo'; //应用评论
	
	public $authorized = false;
	
    function __construct($model=null){
    	$this->model = $model;
    	include(dirname(dirname(dirname(__FILE__))).'/conf/config.php');
    	$this->config      = $config;
    	$this->SOAP_USER   = $this->config['DES']['SOAP_USER'];
		$this->DES_PWD     = $this->config['DES']['SOAP_PWD'];
		$this->DES_IV      = $this->config['DES']['SOAP_IV'];
		$this->user        = $this->config['DES']['SOAP_USER'];
		
		$this->ClientIP = parent::fun()->GetIP ();
		
		if (! in_array ( $this->ClientIP, $this->config ['DES']['SOAP_SERVER_CLIENTIP'] )) {
			$this->authorized = false;
			return parent::Unauthorized_IP();
		}
	}
	
	/**
	 * 接口鉴权
	 *
	 * @param array $a
	 * @throws SoapFault
	 */
	public function Auth($a) {
		if ($a->user === $this->user) {
			$this->authorized = true;
			return $this->_return ( true, 'OK', null );
		} else {
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 负责data加密
	 *
	 * @see Service::_return()
	 */
	public function _return($state, $msg, $data) {
// 		if($data){
// 			return parent::_return ( $state, $msg, $this->_encrypt ( json_encode ( array (
// 					'data' => $data
// 			) ), $this->DES_PWD, $this->DES_IV ) );
// 		}else{
// 			return parent::_return ( $state, $msg, $data );
// 		}
		return parent::_return ( $state, $msg,
				$this->_encrypt ( json_encode(array('data'=>$data)),
						$this->DES_PWD, $this->DES_IV ) );
	}		
	/**
	 * 负责解密data,还原客户端传来的参数
	 */
	public function _value($data) {
		if (isset ( $data )) {
			return json_decode ( trim ( $this->_decrypt ( $data, $this->DES_PWD , $this->DES_IV ) ) );
		} else {
			return $data;
		}
	}
	/**
	 * 数组转化
	 */
	public function arrAddslashes($data){
		foreach($data as $key=>$val){
			$rb[$key] = parent::_addslashes($val);
		}
		return $rb;
	}
	/**
	 * 字符串转化
	 */
	public function strAddslashes($str){
		return parent::_addslashes($str);
	}
	/**
	 * 取得用户信息
	 * @param unknown_type $pa
	 */
	public  function GetUserInfo($pa){
		if($this->authorized){			
			$rb = null;			
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
			
				$user = parent::RequireClass($this->model,'User');
				
				$rb = $user->getUserInfo($this->strAddslashes($data->UserID));
				
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}			
		}else{
			return parent::Unauthorized_User();
		}	
	}
	
	/**
	 * 选出用户信息
	 */
	public function SelectUserInfo($pa){
		//return $this->_return ( true, 'OK', parent::_getRandom() );		
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');

				$rb = $user->seTableData($this->tbUserInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 更新用户信息
	 */
	public function UpdateUserInfo($pa){	
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );

				$user = parent::RequireClass($this->model,'User');

				$condition['UserID'] = $this->strAddslashes($data->UserID);
				
				//限制更新的字段
				$udata['UserGroupsID'] = $this->strAddslashes($data->UserGroupsID);
				$udata['uName']        = $this->strAddslashes($data->uName);
				$udata['uPWD']         = md5($this->strAddslashes($data->uPWD));
				$udata['uEstate']      = $this->strAddslashes($data->uEstate);
				
				$rb = $user->upTableData($this->tbUserInfo,$condition,$udata);
		
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 删除用户信息
	 */
	public function DeleteUserInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
		
				$user = parent::RequireClass($this->model,'User');
		
				$rb = $user->deTableData($this->tbUserInfo,$this->strAddslashes($data->condition));
		
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 取用户列表
	 */
	function GetUserInfoList($pa){	
	   if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
		
				$user = parent::RequireClass($this->model,'User');
				$rb = $user->geTableData($this->tbUserInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 增加用户基础信息
	 */
	function InsertUserInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
		
				$user = parent::RequireClass($this->model,'User');
				
				$idata['uEmail']        = $this->strAddslashes($data->uEmail);
				
				if($user->seTableData($this->tbUserInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['UserGroupsID']  = $this->strAddslashes($data->UserGroupsID);
				$idata['uName']         = $this->strAddslashes($data->uName);
				$idata['uPWD']          = md5($this->strAddslashes($data->uPWD));
				$idata['uCode']         = parent::_getRandom();
				$idata['uAppendTime']   = time();
				$idata['uEstate']       = $this->strAddslashes($data->uEstate);
				
				$rb = $user->inTableData($this->tbUserInfo,$idata);
		
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	/**
	 * 取得用户详细信息
	 */
	function SelectUserDetInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbUserDetInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
				
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新用户详细信息
	 */
	function UpdateUserDetInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$condition['UserID'] = $this->strAddslashes($data->UserID);
				
				$udata['uNickName']      = $this->strAddslashes($data->uNickName);
				$udata['uFirstName']     = $this->strAddslashes($data->uFirstName);
				$udata['uLastName']      = $this->strAddslashes($data->uLastName);
				$udata['uSex']           = $this->strAddslashes($data->uSex);
				$udata['uMarriage']      = $this->strAddslashes($data->uMarriage);
				$udata['uBirthday']      = $this->strAddslashes($data->uBirthday);
				$udata['uCulture']       = $this->strAddslashes($data->uCulture);
				$udata['uComeFrom']      = $this->strAddslashes($data->uComeFrom);
				$udata['uIsSystemMsg']   = $this->strAddslashes($data->uIsSystemMsg);
				
				$rb = $user->upTableData($this->tbUserDetInfo,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户详细信息列表
	 */
	function GetUserDetInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserDetInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));				
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户详细信息
	 */
	function InsertUserDetInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$idata['UserID']         = $this->strAddslashes($data->UserID);

				if($user->seTableData($this->tbUserDetInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['uNickName']      = $this->strAddslashes($data->uNickName);
				$idata['uFirstName']     = $this->strAddslashes($data->uFirstName);
				$idata['uLastName']      = $this->strAddslashes($data->uLastName);
				$idata['uSex']           = $this->strAddslashes($data->uSex);
				$idata['uMarriage']      = $this->strAddslashes($data->uMarriage);
				$idata['uBirthday']      = $this->strAddslashes($data->uBirthday);
				$idata['uCulture']       = $this->strAddslashes($data->uCulture);
				$idata['uComeFrom']      = $this->strAddslashes($data->uComeFrom);
				$idata['uIsSystemMsg']   = $this->strAddslashes($data->uIsSystemMsg);
				
				$rb = $user->inTableData($this->tbUserDetInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户详细信息
	 */
	public function DeleteUserDetInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserDetInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取得用户头像信息
	 */
	function SelectUserHeadInfo($pa){	
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				$user = parent::RequireClass($this->model,'User');
				
				return $this->_return ( true, 'OK', 'aaaa' );
				
				$rb = $user->geTableData($this->tbUserDetInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
								
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新用户头像信息
	 */
	function UpdateUserHeadInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );

				$user = parent::RequireClass($this->model,'User');
				
				$condition['UserID'] = $this->strAddslashes($data->UserID);
								
				if($user->seTableData($this->tbUserHeadInfo,$condition,'') == -1){											
					$idata['UserID']        = $this->strAddslashes($data->UserID);
					$idata['uhURL']         = $this->strAddslashes($data->uhURL);
					$idata['uhState']       = $this->strAddslashes($data->uhState);
					$idata['uhAppendTime']  = time();
					
					$rb = $user->inTableData($this->tbUserHeadInfo,$idata);
				}else{
					$udata['uhURL']         = $this->strAddslashes($data->uhURL);
					$udata['uhState']       = $this->strAddslashes($data->uhState);
					$udata['uhAppendTime']  = time();
					
					$rb = $user->upTableData($this->tbUserHeadInfo,$condition,$udata);
				}
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户头像信息列表
	 */
	function GetUserHeadInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');

				$rb = $user->geTableData($this->tbUserHeadInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户头像信息
	 */
	function InsertUserHeadInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$idata['UserID']        = $this->strAddslashes($data->UserID);
				
				if($user->seTableData($this->tbUserHeadInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['uhURL']         = $this->strAddslashes($data->uhURL);
				$idata['uhState']       = $this->strAddslashes($data->uhState);
				$idata['uhAppendTime']  = time();
				
				$rb = $user->inTableData($this->tbUserHeadInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户头像信息
	 */
	public function DeleteUserHeadInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserHeadInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	/**
	 * 取用户在线登录累计信息
	 */
	function SelectUserLoginInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbUserLoginInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新用户在线登录累计信息
	 */
	function UpdateUserLoginInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$condition['UserID'] = $this->strAddslashes($data->UserID);
	
				$udata['uUpAppendTime']  = $this->strAddslashes($data->uUpAppendTime);
				$udata['uLastActivity']  = time();
				$udata['uLastIP']        = $this->strAddslashes($data->uLastIP);
				
				$rb = $user->upTableData($this->tbUserLoginInfo,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户在线登录累计信息列表
	 */
	function GetUserLoginInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserLoginInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户在线登录累计信息
	 */
	function InsertUserLoginInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$idata['UserID']         = $this->strAddslashes($data->UserID);

				if($user->seTableData($this->tbUserLoginInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['uUpAppendTime']  = $this->strAddslashes($data->uUpAppendTime);
				$idata['uLastActivity']  = time();
				$idata['uLastIP']        = $this->strAddslashes($data->uLastIP);
				$idata['uRegIP']         = $this->strAddslashes($data->uRegIP);
				$idata['olTime']         = 0;
	
				$rb = $user->inTableData($this->tbUserLoginInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户在线登录累计信息
	 */
	public function DeleteUserLoginInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserLoginInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	/**
	 * 取用户账户安全信息
	 */
	function SelectUserAccountSafeInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbUserAccountSafeInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新用户账户安全信息
	 */
	function UpdateUserAccountSafeInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$condition['UserID'] = $this->strAddslashes($data->UserID);
				
				$udata['uSafeEmail']   = $this->strAddslashes($data->uSafeEmail);
				$udata['uSafePhone']   = $this->strAddslashes($data->uSafePhone);
	
				$rb = $user->upTableData($this->tbUserAccountSafeInfo,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户账户安全信息列表
	 */
	function GetUserAccountSafeInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserAccountSafeInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户账户安全信息
	 */
	function InsertUserAccountSafeInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$idata['UserID']       = $this->strAddslashes($data->UserID);
				
				if($user->seTableData($this->tbUserAccountSafeInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['uSafeEmail']   = $this->strAddslashes($data->uSafeEmail);
				$idata['uSafePhone']   = $this->strAddslashes($data->uSafePhone);
				
				$rb = $user->inTableData($this->tbUserAccountSafeInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户账户安全信息
	 */
	public function DeleteUserAccountSafeInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserAccountSafeInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	/**
	 * 取用户应用授权信息
	 */
	function SelectUserAuthInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbUserAuthInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新用户应用授权信息
	 */
	function UpdateUserAuthInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$condition['UserID'] = $this->strAddslashes($data->UserID);
				
				$udata['uAuthDefault']   = $this->strAddslashes($data->uAuthDefault);
				$udata['uAuthApp']       = $this->strAddslashes($data->uAuthApp);
				$udata['uAuthWay']       = $this->strAddslashes($data->uAuthWay);
	
				$rb = $user->upTableData($this->tbUserAuthInfo,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户应用授权信息列表
	 */
	function GetUserAuthInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserAuthInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户应用授权信息
	 */
	function InsertUserAuthInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$idata['UserID']         = $this->strAddslashes($data->UserID);
				
				if($user->seTableData($this->tbUserAuthInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['uAuthDefault']   = $this->strAddslashes($data->uAuthDefault);
				$idata['uAuthApp']       = $this->strAddslashes($data->uAuthApp);
				$idata['uAuthWay']       = $this->strAddslashes($data->uAuthWay);
	
				$rb = $user->inTableData($this->tbUserAuthInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户应用授权信息
	 */
	public function DeleteUserAuthInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserAuthInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	
	/**
	 * 取用户个性设置信息
	 */
	function SelectUserCharacterInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbUserCharacterInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新用户个性设置信息
	 */
	function UpdateUserCharacterInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$condition['UserID'] = $this->strAddslashes($data->UserID);
				
				$udata['uLanguage']        = $this->strAddslashes($data->uLanguage);
				$udata['uCountrySpace']   = $this->strAddslashes($data->uCountrySpace);
				$udata['uNowTimeZong']    = $this->strAddslashes($data->uNowTimeZong);
				$udata['uAuthRecordGeo']  = $this->strAddslashes($data->uAuthRecordGeo);
	
				$rb = $user->upTableData($this->tbUserCharacterInfo,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户个性设置信息列表
	 */
	function GetUserCharacterInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserCharacterInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户个性设置信息
	 */
	function InsertUserCharacterInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$idata['UserID']          = $this->strAddslashes($data->UserID);
				
				if($user->seTableData($this->tbUserCharacterInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['uLanguage']        = $this->strAddslashes($data->uLanguage);
				$idata['uCountrySpace']   = $this->strAddslashes($data->uCountrySpace);
				$idata['uNowTimeZong']    = $this->strAddslashes($data->uNowTimeZong);
				$idata['uAuthRecordGeo']  = $this->strAddslashes($data->uAuthRecordGeo);
				
				$rb = $user->inTableData($this->tbUserCharacterInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户个性设置信息
	 */
	public function DeleteUserCharacterInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserCharacterInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	
	/**
	 * 取用户积分记录信息
	 */
	function SelectUserPointInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbUserPointInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新用户积分记录信息
	 */
	function UpdateUserPointInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$condition['UserID'] = $this->strAddslashes($data->UserID);
				
				$udata['uPointType']  = $this->strAddslashes($data->uPointType);
				$udata['uPoints']     = $this->strAddslashes($data->uPoints);
				$udata['uPointCome']  = $this->strAddslashes($data->uPointCome);
	
				$rb = $user->upTableData($this->tbUserPointInfo,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户积分记录信息列表
	 */
	function GetUserPointInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserPointInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户积分记录信息
	 */
	function InsertUserPointInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$idata['UserID']      = $this->strAddslashes($data->UserID);
				
				if($user->seTableData($this->tbUserPointInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['uPointType']  = $this->strAddslashes($data->uPointType);
				$idata['uPoints']     = $this->strAddslashes($data->uPoints);
				$idata['uPointCome']  = $this->strAddslashes($data->uPointCome);				
				$idata['uAppendTime'] = time();
				
				$rb = $user->inTableData($this->tbUserPointInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户积分记录信息
	 */
	public function DeleteUserPointInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserPointInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	/**
	 * 取用户组信息 
	 */
	function SelectUserGroupsInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbUserGroupsInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更用户组信息
	 */
	function UpdateUserGroupsInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$condition['UserGroupsID'] = $this->strAddslashes($data->UserGroupsID);
				
				$udata['ugName']   = $this->strAddslashes($data->ugName);
				$udata['ugType']   = $this->strAddslashes($data->ugType);
				$udata['ugState']  = $this->strAddslashes($data->ugState);
				
				$rb = $user->upTableData($this->tbUserGroupsInfo,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户组信息列表
	 */
	function GetUserGroupsInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserGroupsInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户组信息
	 */
	function InsertUserGroupsInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$idata['ugName']         = $this->strAddslashes($data->ugName);
				$idata['ugType']         = $this->strAddslashes($data->ugType);
				
				if($user->seTableData($this->tbUserGroupsInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['ugState']        = $this->strAddslashes($data->ugState);
				$idata['ugAppendTime']   = time();
	
				$rb = $user->inTableData($this->tbUserGroupsInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户组信息
	 */
	public function DeleteUserGroupsInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserGroupsInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	/**
	 * 取用户邀请信息（用户好友）
	 */
	function SelectUserInviteFriendInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$rb = $user->seTableData($this->tbUserInviteFriendInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新用户邀请信息（用户好友）
	 */
	function UpdateUserInviteFriendInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$condition['UserID'] = $this->strAddslashes($data->UserID);
				
				$udata['uifByUserID']      = $this->strAddslashes($data->uifByUserID);
				$udata['uifCode']          = $this->strAddslashes($data->uifCode);
				$udata['uifState']         = $this->strAddslashes($data->uifState);
				$udata['uifExpDateTime']   = $this->strAddslashes($data->uifExpDateTime);
	
				$rb = $user->upTableData($this->tbUserInviteFriendInfo,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户邀请信息（用户好友）列表
	 */
	function GetUserInviteFriendInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserInviteFriendInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户邀请信息（用户好友）
	 */
	function InsertUserInviteFriendInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$idata['UserID']          = $this->strAddslashes($data->UserID);
				$idata['uifCode']         = $this->strAddslashes($data->uifCode);			
				
				if($user->seTableData($this->tbUserInviteFriendInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['uifByUserID']     = $this->strAddslashes($data->uifByUserID);
				$idata['uifState']        = $this->strAddslashes($data->uifState);			
				$idata['uifAppendTime']   = time();
				$idata['uifExpDateTime']  = time();
				
	
				$rb = $user->inTableData($this->tbUserInviteFriendInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户邀请信息（用户好友）
	 */
	public function DeleteUserInviteFriendInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserInviteFriendInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	/**
	 * 取第三方平台登录信息
	 */
	function SelectUserAuthenticationsInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbUserAuthenticationsInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新第三方平台登录信息
	 */
	function UpdateUserAuthenticationsInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$condition['UserID'] = $this->strAddslashes($data->UserID);
				
				$udata['uEmail']          = $this->strAddslashes($data->uEmail);
				$udata['uDisplay_name']   = $this->strAddslashes($data->uDisplay_name);
				$udata['uFirst_name']     = $this->strAddslashes($data->uFirst_name);
				$udata['uLast_name']      = $this->strAddslashes($data->uLast_name);
				$udata['uProfile_url']    = $this->strAddslashes($data->uProfile_url);
				$udata['uWebsite_url']    = $this->strAddslashes($data->uWebsite_url);
				$udata['uImages']         = $this->strAddslashes($data->uImages);
				$idata['uEstate']         = $this->strAddslashes($data->uEstate);
	
				$rb = $user->upTableData($this->tbUserAuthenticationsInfo,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取第三方平台登录信息列表
	 */
	function GetUserAuthenticationsInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserAuthenticationsInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加第三方平台登录信息
	 */
	function InsertUserAuthenticationsInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$idata['UserID']              = $this->strAddslashes($data->UserID);
				$idata['uProvider']           = $this->strAddslashes($data->uProvider);
				$idata['uProvider_uid']       = $this->strAddslashes($data->uProvider_uid);
				
				if($user->seTableData($this->tbUserAuthenticationsInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['uEmail']              = $this->strAddslashes($data->uEmail);
				$idata['uDisplay_name']       = $this->strAddslashes($data->uDisplay_name);
				$idata['uFirst_name']         = $this->strAddslashes($data->uFirst_name);
				$idata['uLast_name']          = $this->strAddslashes($data->uLast_name);
				$idata['uProfile_url']        = $this->strAddslashes($data->uProfile_url);
				$idata['uWebsite_url']        = $this->strAddslashes($data->uWebsite_url);
				$idata['uPermissions']        = $this->strAddslashes($data->uPermissions);
				$idata['uImages']             = $this->strAddslashes($data->uImages);
				$idata['uCreatedDateTime']    = time();
				$idata['uCode']               = parent::_getRandom();
				$idata['uEstate']             = $this->strAddslashes($data->uEstate);
	
				$rb = $user->inTableData($this->tbUserAuthenticationsInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除第三方平台登录信息
	 */
	public function DeleteUserAuthenticationsInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserAuthenticationsInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	/**
	 * 取系统运行错误记录信息
	 */
	function SelectSysErrorLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbSysErrorLogInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新系统运行错误记录信息
	 */
	function UpdateSysErrorLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$udata = '';
	
				$rb = $user->upTableData($this->tbSysErrorLogInfo,$this->strAddslashes($data->condition),$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取系统运行错误记录信息列表
	 */
	function GetSysErrorLogInfoList($pa){	
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbSysErrorLogInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加系统运行错误记录信息
	 */
	function InsertSysErrorLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$idata['eType']          = $this->strAddslashes($data->eType);
				$idata['eNode']          = $this->strAddslashes($data->eNode);
				$idata['eCodeData']      = $this->strAddslashes($data->eCodeData);
				$idata['eAppendTime']    = time();
	
				$rb = $user->inTableData($this->tbSysErrorLogInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除系统运行错误记录信息
	 */
	public function DeleteSysErrorLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbSysErrorLogInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	/**
	 * 取用户在线记录信息
	 */
	function SelectUserOnLineLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbUserOnLineLogInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新用户在线记录信息
	 */
	function UpdateUserOnLineLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$condition['UserID'] = $this->strAddslashes($data->UserID);
				
				$udata['oIP']           = $this->strAddslashes($data->oIP);
				$udata['oUserName']     = $this->strAddslashes($data->oUserName);
				$udata['UserGroupsID']  = $this->strAddslashes($data->UserGroupsID);
				$udata['oLastTime']     = time();
	
				$rb = $user->upTableData($this->tbUserOnLineLogInfo,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户在线记录信息列表
	 */
	function GetUserOnLineLogInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserOnLineLogInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户在线记录信息
	 */
	function InsertUserOnLineLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$idata['UserID']        = $this->strAddslashes($data->UserID);
				$idata['oIP']           = $this->strAddslashes($data->oIP);
				
				if($user->seTableData($this->tbUserOnLineLogInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['oUserName']     = $this->strAddslashes($data->oUserName);
				$idata['UserGroupsID']  = $this->strAddslashes($data->UserGroupsID);
				$idata['oCode']         = $this->strAddslashes($data->oCode);
				$idata['oAppendTime']   = time();
				$idata['oLastTime']     = time();
	
				$rb = $user->inTableData($this->tbUserOnLineLogInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户在线记录信息
	 */
	public function DeleteUserOnLineLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserOnLineLogInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	/**
	 * 取用户在线时间记录信息(每月一条) 
	 */
	function SelectUserOnLineTime($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbUserOnLineTime,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新用户在线时间记录信息(每月一条)
	 */
	function UpdateUserOnLineTime($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$condition['UserID']    = $this->strAddslashes($data->UserID);
				$condition['thisyear']  = $this->strAddslashes($data->thisyear);
				$condition['thismonth'] = $this->strAddslashes($data->thismonth);

				
				$udata['total']        = $this->strAddslashes($data->total);
				$udata['lastupdate']   = time();
	
				$rb = $user->upTableData($this->tbUserOnLineTime,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户在线时间记录信息(每月一条)列表
	 */
	function GetUserOnLineTimeList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserOnLineTime,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户在线时间记录信息(每月一条)
	 */
	function InsertUserOnLineTime($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$idata['UserID']       = $this->strAddslashes($data->UserID);
				$idata['thisyear']     = date('Y');
				$idata['thismonth']    = date('m');
				
				if($user->seTableData($this->tbUserOnLineTime,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['total']        = 0;
				$idata['lastupdate']   = time();
	
				$rb = $user->inTableData($this->tbUserOnLineTime,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户在线时间记录信息(每月一条)
	 */
	public function DeleteUserOnLineTime($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserOnLineTime,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	/**
	 * 取用户登录失败记录信息
	 */
	function SelectUserFailedLoginLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbUserFailedLoginLogInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新用户登录失败记录信息
	 */
	function UpdateUserFailedLoginLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$udata = '';
	
				$rb = $user->upTableData($this->tbUserFailedLoginLogInfo,$this->strAddslashes($data->condition),$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户登录失败记录信息列表
	 */
	function GetUserFailedLoginLogInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserFailedLoginLogInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户登录失败记录信息
	 */
	function InsertUserFailedLoginLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
			
				$idata['ip']           = $this->strAddslashes($data->ip);				
				
				if($user->seTableData($this->tbUserFailedLoginLogInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['errcount']     = 1;
				$idata['lastupdate']   = time();
	
				$rb = $user->inTableData($this->tbUserFailedLoginLogInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户登录失败记录信息
	 */
	public function DeleteUserFailedLoginLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserFailedLoginLogInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	/**
	 * 取应用连接请求次数
	 */
	function SelectAppAuthNumLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbAppAuthNumLogInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新应用连接请求次数
	 */
	function UpdateAppAuthNumLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$condition['AppID'] = $this->strAddslashes($data->AppID);
				
				$udata['aTotal']        = $this->strAddslashes($data->aTotal);
				$udata['aLastupdate']   = time();
	
				$rb = $user->upTableData($this->tbAppAuthNumLogInfo,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取应用连接请求次数列表
	 */
	function GetAppAuthNumLogInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbAppAuthNumLogInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加应用连接请求次数
	 */
	function InsertAppAuthNumLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$idata['AppID']        = $this->strAddslashes($data->AppID);
				
				if($user->seTableData($this->tbUserFailedLoginLogInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['aTotal']       = 1;
				$idata['aStartdate']   = time();
				$idata['aLastupdate']  = time();
	
				$rb = $user->inTableData($this->tbUserFailedLoginLogInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除应用连接请求次数
	 */
	public function DeleteAppAuthNumLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbAppAuthNumLogInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	
	/**
	 * 取用户连接请求次数
	 */
	function SelectUserAuthNumLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$rb = $user->seTableData($this->tbUserAuthNumLogInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新用户连接请求次数
	 */
	function UpdateUserAuthNumLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$condition['uAuthNumLogID'] = $this->strAddslashes($data->uAuthNumLogID);
				
				$udata['uTotal']        = $this->strAddslashes($data->uTotal);
				$udata['uLastupdate']   = time();
				$udata['uLimit']        = $this->strAddslashes($data->uLimit);
	
				$rb = $user->upTableData($this->tbUserAuthNumLogInfo,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户连接请求次数列表
	 */
	function GetUserAuthNumLogInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserAuthNumLogInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户连接请求次数
	 */
	function InsertUserAuthNumLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');

				$idata['UserID']        = $this->strAddslashes($data->UserID);
				$idata['AppID']         = $this->strAddslashes($data->AppID);
				
				if($user->seTableData($this->tbUserAuthNumLogInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['uTotal']        = 1;
				$idata['uLimit']        = $this->strAddslashes($data->uLimit);
				$idata['uStartdate']    = tiem();
				$idata['uLastupdate']   = time();
				
				
				$rb = $user->inTableData($this->tbUserAuthNumLogInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户连接请求次数
	 */
	public function DeleteUserAuthNumLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserAuthNumLogInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	
	/**
	 * 取用户登录失败记录信息 
	 */
	function SelectUserAuthFailedLoginLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbUserAuthFailedLoginLogInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));

				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新用户登录失败记录信息 
	 */
	function UpdateUserAuthFailedLoginLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$condition['UserID'] = $this->strAddslashes($data->UserID);
				
				$udata['errcount']     = $this->strAddslashes($data->errcount);
				$udata['lastupdate']   = time();
					
				$rb = $user->upTableData($this->tbUserAuthFailedLoginLogInfo,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户登录失败记录信息 列表
	 */
	function GetUserAuthFailedLoginLogInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserAuthFailedLoginLogInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加用户登录失败记录信息 
	 */
	function InsertUserAuthFailedLoginLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$idata['ip']           = $this->strAddslashes($data->ip);
				$idata['UserID']       = $this->strAddslashes($data->UserID);
				
				if($user->seTableData($this->tbUserAuthFailedLoginLogInfo,$idata,'') != -1){
					return $this->_return ( true, 'OK', false );
				}
				
				$idata['errcount']     = 1;
				$idata['lastupdate']   = time();
	
				$rb = $user->inTableData($this->tbUserAuthFailedLoginLogInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除用户登录失败记录信息 
	 */
	public function DeleteUserAuthFailedLoginLogInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbUserAuthFailedLoginLogInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 取邀请码信息
	 */
	function SelectUserInviteCode($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbUserInviteCode,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新邀请码记录信息
	 */
	function UpdateUserInviteCode($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$condition['autoid'] = $this->strAddslashes($data->autoid);
				
				$udata['status']   = 1;
				$udata['toUserID'] = $this->strAddslashes($data->toUserID);
				$udata['useTime']  = time();

				$rb = $user->upTableData($this->tbUserInviteCode,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取邀请码记录信息 列表
	 */
	function GetUserInviteCodeList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbUserInviteCode,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加邀请码记录信息
	 */
	function InsertUserInviteCode($pa){		
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				$user = parent::RequireClass($this->model,'User');
				
				$inviteCode = ComFun::getRandom(20,3,25); //激活码
				
				$idata['client_id']  = $this->strAddslashes($data->client_id);
				$idata['inviteCode'] = $inviteCode;
				$idata['fromUserID'] = $this->strAddslashes($data->fromUserID);
				$idata['appendTime'] = time();

				$rb = $user->inTableData($this->tbUserInviteCode,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除邀请码记录信息
	 */
	public function DeleteUserInviteCode($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$rb = $user->deTableData($this->tbUserInviteCode,$this->strAddslashes($data->condition));
				
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 取邀请码信息
	 */
	function SelectAppScoreInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->seTableData($this->tbAppScoreInfo,$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 更新邀请码记录信息
	 */
	function UpdateAppScoreInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$condition['AppScoreID'] = $this->strAddslashes($data->AppScoreID);
	
				$udata['aState']   = $this->strAddslashes($data->aState);
	
				$rb = $user->upTableData($this->tbAppScoreInfo,$condition,$udata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取邀请码记录信息 列表
	 */
	function GetAppScoreInfoList($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->geTableData($this->tbAppScoreInfo,parent::getListPage($data->page),parent::getListPageSize($data->pagesize),$this->strAddslashes($data->condition),$this->strAddslashes($data->order));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 增加邀请码记录信息
	 */
	function InsertAppScoreInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
				
				$idata['AppInfoID']   = $this->strAddslashes($data->AppInfoID);
				$idata['UserKeyID']   = $this->strAddslashes($data->UserKeyID);
				$idata['aStar']       = $data->aStar == 0 ? 1 : $this->strAddslashes($data->aStar);
				$idata['aComment']    = $this->strAddslashes($data->aComment);
				$idata['aAppendTime'] = time();
				$idata['aState']      = 2;
	
				$rb = $user->inTableData($this->tbAppScoreInfo,$idata);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 删除邀请码记录信息
	 */
	public function DeleteAppScoreInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'User');
	
				$rb = $user->deTableData($this->tbAppScoreInfo,$this->strAddslashes($data->condition));
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	/**
	 * 通过加密的user_id选出相应的用户名
	 */
	public function SelectUserNameByUserID($pa){	
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				$user = parent::RequireClass($this->model,'ReleaseCode');

				$rb = $user->getUserNameByListUserID($data->ListID, $data->order);
				
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 通过加密的user_id选出相应的用户名
	 */
	public function GetUserNameListByUserID($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				$user = parent::RequireClass($this->model,'ReleaseCode');
				
				$rb = $user->getUserNameByListUserID($data->ListID, $data->order);
	
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 通过加密的user_id选出用户名，去掉重复的
	 */
	public function GetUserNameArrByUserID($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				if(is_array($data->ListID)){
					$this->_connect(); //连接数据库
					
					$mandOAuth = $this->_getClass('MandOAuth');
					
					foreach($data->ListID as $key=>$val){
						$uid = explode('|',$mandOAuth->dodecrypt($val));
						$uArr[] = $uid[0];
					}
					
					$user = $this->_getClass('User');
					$uNameList = $user->getUserNameListByID(implode(',', $uArr));
					
					if($uNameList){
						foreach($uNameList as $key=>$val){
							$rb[] = $val['uName'];
						}
					}
				}
		
				return $this->_return ( true, 'OK', $rb );
				
				$user = parent::RequireClass($this->model,'ReleaseCode');
		
				$rb = $user->getUserNameByListUserID($data->ListID, $data->order);
		
				return $this->_return ( true, 'OK', $rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 加密值
	 */
	public function SelectEncryptValue($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				//$data->value = '3|app16';	
				if($data->value){
					$mandOAuth = $this->_getClass('MandOAuth');
					
					return $this->_return ( true, 'OK', $mandOAuth->doencrypt(trim($data->value)));
				}else{
					return $this->_return ( false, 'Data Empty', null);
				}	
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 加密值
	 */
	public function SelectDecryptValue($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				//$data->value = '3|app16';
				if($data->value){
					$mandOAuth = $this->_getClass('MandOAuth');
						
					return $this->_return ( true, 'OK', $mandOAuth->dodecrypt(trim($data->value)));
				}else{
					return $this->_return ( false, 'Data Empty', null);
				}
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取应用相应的UserID加密值
	 */
	public function SelectUserIDWithAppID($pa){	
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if(!isset($data->UserID)){
					$this->_return ( false, 'Value Missing', $rb );
				}
				if(!isset($data->client_id)){
					$this->_return ( false, 'Value Missing', $rb );
				}		
				
				$mandOAuth = $this->_getClass('MandOAuth');
					
				$IDStr = $mandOAuth->dodecrypt(trim($data->UserID));
					
				$IDArr = explode('|', $IDStr);
					
				if(is_array($IDArr)){
					return $this->_return ( true, 'OK', $mandOAuth->doencrypt($IDArr[0].'|'.$data->client_id));
				}else{
					return $this->_return ( false, 'Data Error', null);
				}
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 通过用户名和client_id获取user_id
	 */
	public function GetUserIDByUserNameAndClientID($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if(!isset($data->uName)){
					return $this->_return ( false, 'uName is Missing', null );
				}
				if(!isset($data->client_id)){
					return $this->_return ( false, 'client_id is Missing', null );
				}
				
				$this->_connect(); //连接数据库
				
				$user = $this->_getClass('User');
				$UserID = $user->getUserIDByUserName($data->uName);
			
				if($UserID == -1){
					return $this->_return ( false, $data->uName.' is not exist', null);
				}
				
				$mandOAuth = $this->_getClass('MandOAuth');
				$_re = $mandOAuth->doencrypt($UserID.'|'.$data->client_id);

				return $this->_return ( true, 'OK', $_re );
			}else{
				return $this->_return ( false, 'Data Error', null );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 选出user_id对应的应用信息
	 */
	public function GetAppListByUserID($pa){	
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if(!isset($data->user_id)){
					return $this->_return ( false, 'user_id is Missing', $rb );
				}

				$this->_connect(); //连接数据库
				
				$mandOAuth = $this->_getClass('MandOAuth');
				$_re = $mandOAuth->dodecrypt($data->user_id);

				if($_re){
					$_arr = explode('|', $_re);
					
					if(is_array($_arr)){
						$userOAuth = $this->_getClass('UserOAuth');

						$_rb = $userOAuth->dogetAppList($_arr[0]);
						return $this->_return ( true, 'OK', $_rb );
					}else{
						return $this->_return ( false, 'user_id is error', $rb );
					}		
				}else{
					return $this->_return ( false, 'user_id is error', $rb );
				}			
				
				return $this->_return ( true, 'OK', $_rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取应用client_id对应的用户列表
	 * 对应db:getUserListByClientID
	 */
	public function GetUserListByClientID($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if(!isset($data->client_id)){
					return $this->_return ( false, 'client_id is Missing', $rb );
				}
	
				$this->_connect(); //连接数据库
				
				$tArr['client_id'] = $data->client_id;
				if($data->uName){
					$tArr['uName']     = $data->uName;
				}
				
				$mandOAuthLog = parent::getClass($this->_getConnect, 'MandOAuthLog');
				$_rb = $mandOAuthLog->getUserListByClientID($tArr);
	
				return $this->_return ( true, 'OK', $_rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 取具体用户信息
	 */
	public function GetAppUserInfo ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if(!isset($data->client_id)){
					return $this->_return ( false, 'client_id is Missing', $rb );
				}
				if(!isset($data->user_id)){
					return $this->_return ( false, 'user_id is Missing', $rb );
				}
		
				$this->_connect(); //连接数据库
				$mandOAuth = parent::getClass($this->_getConnect, 'MandOAuth');
				$mandOAuthLog = parent::getClass($this->_getConnect, 'MandOAuthLog');
				
				$crypt = $mandOAuth->_getUserIDArr( $data->user_id );
				
				$tArr['AppID']  = $data->client_id;
				$tArr['UserID'] = $crypt[0];
				
				$_rb = $mandOAuthLog->GetUserInfoByAppIDAndUserID($tArr);
		
				return $this->_return ( true, 'OK', $_rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 取应用client_id对应的用户列表
	 * 对应db:getUserListByClientID
	 */
	public function GetUserPageListByClientID($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				if(!isset($data->client_id)){
					return $this->_return ( false, 'client_id is Missing', $rb );
				}
				
				$this->_connect(); //连接数据库
	
				$tArr['client_id'] = $data->client_id;
				if($data->uName){
					$tArr['uName']     = $data->uName;
				}
	
				$mandOAuthLog = parent::getClass($this->_getConnect, 'MandOAuthLog');
				$_rb = $mandOAuthLog->getUserPageListByClientID($tArr, $data->order, parent::getListPage($data->page),parent::getListPageSize($data->pagesize));
	
				return $this->_return ( true, 'OK', $_rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 增加应用用户
	 */
	public function GetAddAppUser($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if(!isset($data->client_id)){
					return $this->_return ( false, 'client_id is Missing', $rb );
				}
				if(!isset($data->user_id)){
					return $this->_return ( false, 'user_id is Missing', $rb );
				}
		
				$this->_connect(); //连接数据库
				$mandOAuth = parent::getClass($this->_getConnect, 'MandOAuth');
				$mandOAuthLog = parent::getClass($this->_getConnect, 'MandOAuthLog');
				
				$crypt = $mandOAuth->_getUserIDArr( $data->user_id );
				
				$tArr['AppID']  = $data->client_id;
				$tArr['UserID'] = $crypt[0];
				
				$_rb = $mandOAuthLog->addAppUser($tArr);
		
				return $this->_return ( true, 'OK', $_rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 删除应用用户
	 */
	public function GetDeleteAppUser($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if(!isset($data->client_id)){
					return $this->_return ( false, 'client_id is Missing', $rb );
				}
				if(!isset($data->user_id)){
					return $this->_return ( false, 'user_id is Missing', $rb );
				}
		
				$this->_connect(); //连接数据库
				$mandOAuth = parent::getClass($this->_getConnect, 'MandOAuth');
				$mandOAuthLog = parent::getClass($this->_getConnect, 'MandOAuthLog');
				
				$crypt = $mandOAuth->_getUserIDArr( $data->user_id );
				
				$tArr['AppID']  = $data->client_id;
				$tArr['UserID'] = $crypt[0];
				
				$_rb = $mandOAuthLog->deleteAppUser($tArr);
		
				return $this->_return ( true, 'OK', $_rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 应用用户是否已经存在
	 */
	public function GetIsExistAppUser ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
		
				if(!isset($data->client_id)){
					return $this->_return ( false, 'client_id is Missing', $rb );
				}
				if(!isset($data->user_id)){
					return $this->_return ( false, 'user_id is Missing', $rb );
				}
				
				$this->_connect(); //连接数据库
				$mandOAuth = parent::getClass($this->_getConnect, 'MandOAuth');
				$mandOAuthLog = parent::getClass($this->_getConnect, 'MandOAuthLog');
		
				$crypt = $mandOAuth->_getUserIDArr( $data->user_id );
		
				$tArr['AppID']  = $data->client_id;
				$tArr['UserID'] = $crypt[0];
		
				$_rb = $mandOAuthLog->isExsitAppUser($tArr);
		
				return $this->_return ( true, 'OK', $_rb );
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 屏蔽某用户使用应用
	 */
	public function GetForbidUserUseApp ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
		
				if(!isset($data->user_id)){
					return $this->_return ( false, 'user_id is Missing', $rb );
				}
				if(!isset($data->uState)){
					return $this->_return ( false, 'uState is Missing', $rb );
				}
		
				$this->_connect(); //连接数据库
				$mandOAuth = parent::getClass($this->_getConnect, 'MandOAuth');
				$mandOAuthLog = parent::getClass($this->_getConnect, 'MandOAuthLog');
				
				$crypt = $mandOAuth->_getUserIDArr( $data->user_id );
				
				if ( $crypt ) {
					$tArr['UserID']  = $crypt[0];
					$tArr['AppID']   = $crypt[1];
					$tArr['uState']  = $data->uState;
					
					$_rb = $mandOAuthLog->updateUserAuthNumLogState($tArr);
					
					return $this->_return ( true, 'OK', $_rb );
				} else {
					return $this->_return ( false, 'The user_id is not exist', $rb );
				}
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * ad广告平台生成加密验证码
	 * db:getAdsIdentID
	 */
	public function GetIdentCode($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if(!isset($data->user_id)){
					return $this->_return ( false, 'user_id is Missing', $rb );
				}
				if(!isset($data->ads_type)){
					return $this->_return ( false, 'ads_type is Missing', $rb );
				}
				
				$this->_connect(); //连接数据库
				
				$mandOAuth = $this->_getClass('MandOAuth');
				$_re = $mandOAuth->dodecrypt($data->user_id);
				
				$IdentCode = $mandOAuth->doencrypt($_re[0].'|'.$data->ads_type.'|'.time());
				
				return $this->_return ( true, 'OK', array(
							'status' => true,
							'IdentCode' => $IdentCode) 
						);
				
				echo $this->_return(array('state' => true, 'IdentCode' => $IdentCode));exit;
			}else{
				return $this->_return ( false, 'Data Error', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取用户认证信息
	 * db:payAuthInfo
	 */
	public function GetAuthInfo($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if(!isset($data->user_id)){
					return $this->_return ( false, 'user_id is Missing', $rb );
				}
				
				$this->_connect(); //连接数据库
				
				$mandOAuth = $this->_getClass('MandOAuth');
				$_re = $mandOAuth->dodecrypt($data->user_id);
				
				$modifyProfile = $this->_getClass('ModifyProfile');
				
				$_rb = $modifyProfile->getSafeInfoByUserID($_re[0]);
				
				$rb['uRealName']  = $_rb['uRealName'];
				$rb['uAuthName']  = $_rb['uAuthName'];
				$rb['uSafeEmail'] = $_rb['uSafeEmail'];
				$rb['uAuthEmail'] = $_rb['uAuthEmail'];
				$rb['uSafePhone'] = $_rb['uSafePhone'];
				
				return $this->_return ( true, 'OK', $rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取pay平台消费双方user_id
	 * db:getCoinTokenID
	 */
	public function GetCoinTokenID($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
		
				if(!isset($data->user_id)){
					return $this->_return ( false, 'user_id is Missing', $rb );
				}
		
				return $this->_return ( true, 'OK', $data );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取pay平台消费双方user_id
	 * db:getCoinUserID
	 */
	public function GetCoinUserID($pa){
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
		
				if(!isset($data->user_id)){
					return $this->_return ( false, 'user_id is Missing', $rb );
				}
		
				return $this->_return ( true, 'OK', $data );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	/**
	 * 取指定用户指定client_id的授权user_id
	 * db:checkAccountValid
	 */
	public function GetAccountValid($pa){
		return $this->_return ( true, 'OK', 'bbb' );
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
		
				if(!isset($data->user_id)){
					return $this->_return ( false, 'user_id is Missing', $rb );
				}
		
				return $this->_return ( true, 'OK', $data );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 获取授权列表
	 */
	public function GetAuthList ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				$dbSoapInterface = parent::getlib('DB_SoapInterface');
				$_rb = $dbSoapInterface->getAuthListInfo ();
	
				return $this->_return ( true, 'OK', $_rb );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 登录者access_token是否有对appid应用数据使用权，有返回当前登录者的用户信息
	 */
	public function GetAuthAppID ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if ( !isset($data->access_token) ) {
					return $this->_return ( false, 'access_token is missing', null );
				}
				if ( !isset($data->AppID) ) {
					return $this->_return ( false, 'AppID is missing', null );
				}
				
				$this->_connect(); //加载数据库
				
				$mandOAuth = parent::getClass($this->_getConnect, 'MandOAuth');
				$_rb = $mandOAuth->dodecrypt( $data->access_token );
				if ( $_rb ) {
					$_auArr = explode('|', $_rb);
					$tokenID = $_auArr ? $_auArr[1] : 0;
					
					$mandOAuthLog = parent::getClass($this->_getConnect, 'MandOAuthLog');
					$_toArr = $mandOAuthLog->getAppIDByTokenID( $tokenID );
					
					if ( $_toArr ) {
						$tArr['UserID']     = $_toArr['UserID'];
						$tArr['client_id']  = $data->AppID;
						
						if ( !in_array($data->AppID, $this->config['oauth']['login']) ) {
							if ($mandOAuthLog->getTokenIDByUserIDAndAppID( $tArr ) === 0 ) {
								return $this->_return ( false, 'Permission denied', null );
							}
						}
						
						include(dirname(dirname(dirname(__FILE__))).'/conf/config.php');
						$userOAuth = parent::getClass($this->_getConnect, 'UserOAuth', $config);
						$_rb = $userOAuth->doGetUserInfo($_toArr['UserID']);
						
						$_rb['account']  = $config['PLATFORM']['Auth'] . '/main/index';
						$_rb['signout']  = $config['PLATFORM']['Auth'] . '/index/loginOut';
						$_rb['id'] = $mandOAuth->getUserOAuthID($_toArr['UserID'], $data->AppID);
						
						return $this->_return ( true, 'OK', $_rb  );
					} else {
						return $this->_return ( false, ' access_token is error', null );
					}
				} else {
					return $this->_return ( false, 'access_token is invalid, please login again', null );
				}
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	
	/**
	 * 保存短信息（以数据的形式保存，跟队列对接）
	 */
	public function GetSendShortMsg ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if ( $data->data ) {
					$this->_connect(); //加载数据库
					$userMessage = parent::getClass($this->_getConnect, 'UserMessage');
					
					if ( !isset($data->data->mSender) ) {
						return $this->_return ( false, 'mSender is missing', null );
					}
					if ( !isset($data->data->mAccepter) ) {
						return $this->_return ( false, 'mAccepter is missing', null );
					}
					if ( !isset($data->data->mTitle) ) {
						return $this->_return ( false, 'mTitle is missing', null );
					}
					if ( !isset($data->data->uContent) ) {
						return $this->_return ( false, 'uContent is missing', null );
					}
					
					//$_v->mType：1为姓名，2为UserID
					$UserID = $data->data->mType == 2 ? $data->data->mSender : $userMessage->getUserMsg ( $data->data->mSender );
					if ( $UserID === 0 ) {
						return $this->_return ( false, 'mSender is not exist', null );
					}
					
					foreach ( $data->data->mAccepter as $_v ) {
						$tArr['uToID']      = $UserID;
						$tArr['uName']      = $data->data->mSender;
						$tArr['accepter']   = $_v;
						$tArr['uTitle']     = $data->data->mTitle;
						$tArr['uContent']   = ComFun::_decrypt($data->data->uContent);
						
						if ( $data->data->mType == 2 ) {
							$_id = $userMessage->doSaveShortMsgWithID ( $tArr );
						} else {
							$_id = $userMessage->doSaveShortMsgWithName ( $tArr );
						}
						
						$_rb[] = $_id;
					}
					
					return $this->_return ( true, 'OK', $_rb );
				} else {
					return $this->_return ( false, 'data is empty', null );
				}
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 发送第三方平台信息
	 */
	public function GetSendThirdPartyMsg ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if ( $data->data ) {
					$this->_connect(); //加载数据库
					$mandOAuth = parent::getClass($this->_getConnect, 'MandOAuth');
					$userOAuth = parent::getClass($this->_getConnect, 'UserOAuth');
					
					$thirdparty = false; //是否绑定所有第三方
					
					if ( !isset($data->data->user_id) ) {
						return $this->_return ( false, 'user_id is missing', null );
					}
					
					if ( !isset($data->data->content) ) {
						return $this->_return ( false, 'content is missing', null );
					}
					
					if ( isset($data->data->thirdparty) ) {
						if ( $data->data->thirdparty === true ) {
							$thirdparty = true;
						}
					}
					
					if ( $thirdparty === false ) {
						if ( !isset($data->data->providers) ) {
							return $this->_return ( false, 'providers is missing', null );
						}
						
						
						foreach ( $data->data->providers as $_v2 ) {
							$_tmp[] = '\'' . $_v2 . '\'';
						}
						$_uProvider = implode(',', $_tmp);
						$tArr['uProvider'] = $_uProvider;
					}
					
					foreach ( $data->data->user_id as $k => $_v ) {
						$uRb = $mandOAuth->dodecrypt( $_v );
						
						if ( $uRb === false ) {
							$_rb[$k] = array('state' => false, 'msg' => 'user is not exist');
						} else {
							$tArr['UserID']    = $uRb[0];
							
							$banding = $userOAuth->getBindingThirdPartyInfo( $tArr );
							
							if ( !$banding ) {
								$_rb[$k] = array('state' => false, 'msg' => 'There is no designated third party');
							} else {
								foreach ( $banding as $k2 => $v2 ) {
									if ( $v2['uProvider'] ) {
										$provider = $v2['uProvider'];
										
										$apiArr = ComFun::getNowApi($provider);
										
										$tArr2['partner']  = $provider;
										$tArr2['provider'] = $apiArr['provider'];
										if ( $v2['uPermissions'] ) {
											$tArr2['OAuthArr'] = json_decode($v2['uPermissions'], true);
										} else {
											$tArr2['OAuthArr'] = '';
										}
										$tArr2['content'] = ComFun::_decrypt($data->data->content);
							
										$_sRb = DBCurl::dbGet( $this->config['PLATFORM']['Auth_https'] . '/db/sendMsg', 'POST', $tArr2);
							
										$rm[$k2]['provider'] = $v2['uProvider'];
										if ( !$_sRb['state'] ) {
											$rm[$k2]['state'] = false;
											$rm[$k2]['info']  = $_sRb['data'];
										} else {
											$rm[$k2]['state'] = true;
											$rm[$k2]['info']  = '';
										}
									}
								}
								
								$_rb[$k] = array('state' => true, 'msg' => 'OK', 'result' => $rm);
							}
						}
					}
						
					return $this->_return ( true, 'OK', $_rb );
				} else {
					return $this->_return ( false, 'data is empty', null );
				}
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 通过access_token验证用户是否有使用AppID权限，如有则返回client_id对应的用户UserID
	 */
	public function GetUserIDByAccessTokenAndAppID ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if ( !isset($data->access_token) ) {
					return $this->_return ( false, 'access_token is missing', null );
				}
				if ( !isset($data->AppID) ) {
					return $this->_return ( false, 'AppID is missing', null );
				}
				if ( !isset($data->client_id) ) {
					return $this->_return ( false, 'client_id is missing', null );
				}
				
				$this->_connect(); //加载数据库
				
				$mandOAuth = parent::getClass($this->_getConnect, 'MandOAuth');
				$_rb = $mandOAuth->dodecrypt( $data->access_token );
				
				if ( $_rb ) {
					$_auArr = explode('|', $_rb);
					$tokenID = $_auArr ? $_auArr[1] : 0;
					
					$mandOAuthLog = parent::getClass($this->_getConnect, 'MandOAuthLog');
					$_toArr = $mandOAuthLog->getAppIDByTokenID( $tokenID );
					
					if ( $_toArr ) {
						$tArr['UserID']     = $_toArr['UserID'];
						$tArr['client_id']  = $data->AppID;
					
						if ($mandOAuthLog->getTokenIDByUserIDAndAppID( $tArr ) === 0 ) {
							return $this->_return ( true, 'Permission denied', null );
						} else {
							$rb = array('UserID' => $mandOAuth->doencrypt( $_toArr['UserID'] . '|' . $data->client_id ) );
							return $this->_return ( true, 'OK',  $rb );
						}
					} else {
						return $this->_return ( false, ' access_token is error', null );
					}
				} else {
					return $this->_return ( false, 'access_token is invalid, please login again', null );
				}
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 通过用户名换取指定应用的UserID
	 */
	/*
	public function GetUserIDByUserName ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
		
				if ( $data->data ) {
					$this->_connect(); //加载数据库
					$userMessage = parent::getClass($this->_getConnect, 'UserMessage');
						
					
						
					return $this->_return ( true, 'OK', array( 'msgID' => $_rb ) );
				} else {
					return $this->_return ( false, 'data is empty', null );
				}
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	*/
	/**
	 * 通过UserID换取指定应用的UserID
	 */
	/*
	public function GetUserIDByUserID ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				if ( $data->data ) {
					$this->_connect(); //加载数据库
					$userMessage = parent::getClass($this->_getConnect, 'UserMessage');
	
						
	
					return $this->_return ( true, 'OK', array( 'msgID' => $_rb ) );
				} else {
					return $this->_return ( false, 'data is empty', null );
				}
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	*/
	
	/**
	 * 通过user_id查找用户信息
	 */
	public function GetUserInfoByUserID ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if ( !isset($data->user_id) ) {
					return $this->_return ( false, 'user_id is missing', null );
				}
				if ( !isset($data->client_id) ) {
					return $this->_return ( false, 'client_id is missing', null );
				}
				
				$this->_connect(); //加载数据库
				
				$mandOAuth = parent::getClass($this->_getConnect, 'MandOAuth');
				$crypt = $mandOAuth->_getUserIDArr( $data->user_id );
				
				if ( $crypt ) {
					include(dirname(dirname(dirname(__FILE__))).'/conf/config.php');
					$userOAuth = parent::getClass($this->_getConnect, 'UserOAuth', $config);
					$re = $userOAuth->doGetUserInfo($crypt[0]);
				
					if ( $re ) {
						$mandOAuth = $this->_getClass('MandOAuth');
						$re['id'] = $mandOAuth->getUserOAuthID($re['UserID'], $data->client_id);
						unset($re['UserID']);
							
						return $this->_return ( true, 'OK', $re );
					} else {
						return $this->_return ( false, 'users is not exist', null );
					}
				} else {
					return $this->_return ( false, 'user_id is error', null );
				}
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 通过用户名查找用户信息
	 */
	public function GetUserInfoByUserName ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				if ( !isset($data->uName) ) {
					return $this->_return ( false, 'uName is missing', null );
				}
				if ( !isset($data->client_id) ) {
					return $this->_return ( false, 'client_id is missing', null );
				}
	
				$this->_connect(); //加载数据库
				include(dirname(dirname(dirname(__FILE__))).'/conf/config.php');
				$userOAuth = parent::getClass($this->_getConnect, 'UserOAuth', $config);
				$re = $userOAuth->doGetUserInfoByUserName($data->uName);
	
				if ( $re ) {
					$mandOAuth = $this->_getClass('MandOAuth');
					$re['id'] = $mandOAuth->getUserOAuthID($re['UserID'], $data->client_id);
					unset($re['UserID']);
						
					return $this->_return ( true, 'OK', $re );
				} else {
					return $this->_return ( false, 'users is not exist', null );
				}
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 通过邮箱查找用户信息
	 */
	public function GetUserInfoByEmail ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if ( !isset($data->uEmail) ) {
					return $this->_return ( false, 'uEmail is missing', null );
				}
				if ( !isset($data->client_id) ) {
					return $this->_return ( false, 'client_id is missing', null );
				}
				
				$this->_connect(); //加载数据库
				include(dirname(dirname(dirname(__FILE__))).'/conf/config.php');
				$userOAuth = parent::getClass($this->_getConnect, 'UserOAuth', $config);
				$re = $userOAuth->doGetUserInfoByEmail($data->uEmail);
				
				if ( $re ) {
					$mandOAuth = $this->_getClass('MandOAuth');
					$re['id'] = $mandOAuth->getUserOAuthID($re['UserID'], $data->client_id);
					unset($re['UserID']);
	
					return $this->_return ( true, 'OK', $re );
				} else {
					return $this->_return ( false, 'users is not exist', null );
				}
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 通过用户的用户名判断用户是否属于应用
	 */
	public function GetUserBelongAppByUserName ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				if ( !isset($data->AppID) ) {
					return $this->_return ( false, 'AppID is missing', null );
				}
				if ( !isset($data->uName) ) {
					return $this->_return ( false, 'uName is missing', null );
				}
				
				$this->_connect(); //加载数据库
				include(dirname(dirname(dirname(__FILE__))).'/conf/config.php');
				$userOAuth = parent::getClass($this->_getConnect, 'UserOAuth', $config);
				$re = $userOAuth->isUserBelongAppByUserName(array(
						'AppID' => $data->AppID,
						'uName' => $data->uName
				));
	
				return $this->_return ( true, 'OK', $re );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 通过用户的邮件地址判断用户是否属于应用
	 */
	public function GetUserBelongAppByEmail ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
				
				if ( !isset($data->AppID) ) {
					return $this->_return ( false, 'AppID is missing', null );
				}
				if ( !isset($data->uEmail) ) {
					return $this->_return ( false, 'uEmail is missing', null );
				}
	
				$this->_connect(); //加载数据库
				include(dirname(dirname(dirname(__FILE__))).'/conf/config.php');
				$userOAuth = parent::getClass($this->_getConnect, 'UserOAuth', $config);
				
				$re = $userOAuth->isUserBelongAppByEmail(array(
						'AppID' => $data->AppID,
						'uEmail' => $data->uEmail
				));
	
				return $this->_return ( true, 'OK', $re );
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 通过用户名判断用户是否是应用的开发者
	 */
	public function GetIsUserOwnAppByUserName ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				if ( !isset($data->AppID) ) {
					return $this->_return ( false, 'AppID is missing', null );
				}
				if ( !isset($data->uName) ) {
					return $this->_return ( false, 'uName is missing', null );
				}
				
				$this->_connect(); //加载数据库
				include(dirname(dirname(dirname(__FILE__))).'/conf/config.php');
				$user = parent::getClass($this->_getConnect, 'User', $config);
				$UserID = $user->getUserIDByUserName($data->uName);
				
				if ( $UserID == -1 ) {
					return $this->_return ( false, 'The uName of the User is not exist', null );
				}
				
				$mandOAuth = parent::getClass($this->_getConnect, 'MandOAuth');
				$user_id = $mandOAuth->doencrypt( $UserID . '|' . $config['oauth']['platform_clientid']['dev'] );
				
				$DBOwnerSoapClient_Dev = parent::getSoap('DBOwnerSoapClient_Dev', $config);
				$re = $DBOwnerSoapClient_Dev->GetIsUserOwnApp($user_id, $data->AppID);

				if ( $re['state'] ) {
					return $this->_return ( true, 'OK', ($re['data'] > 0 ? true : false) );
				} else {
					return $this->_return ( false, $re['msg'], null );
				}
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 通过用户名判断用户是否是应用的开发者
	 */
	public function GetIsUserOwnAppByEmail ( $pa ) {
		if($this->authorized){
			$rb = null;
			if (isset($pa)) {
				$data = $this->_value ( json_decode ( $pa->data )->data );
	
				if ( !isset($data->AppID) ) {
					return $this->_return ( false, 'AppID is missing', null );
				}
				if ( !isset($data->uEmail) ) {
					return $this->_return ( false, 'uEmail is missing', null );
				}
	
				$this->_connect(); //加载数据库
				include(dirname(dirname(dirname(__FILE__))).'/conf/config.php');
				$user = parent::getClass($this->_getConnect, 'User', $config);
				$UserID = $user->doCheckEmail($data->uEmail);
	
				if ( $UserID == -1 ) {
					return $this->_return ( false, 'The uEmail of the User is not exist', null );
				}
	
				$mandOAuth = parent::getClass($this->_getConnect, 'MandOAuth');
				$user_id = $mandOAuth->doencrypt( $UserID . '|' . $config['oauth']['platform_clientid']['dev'] );
	
				$DBOwnerSoapClient_Dev = parent::getSoap('DBOwnerSoapClient_Dev', $config);
				$re = $DBOwnerSoapClient_Dev->GetIsUserOwnApp($user_id, $data->AppID);

				if ( $re['state'] ) {
					return $this->_return ( true, 'OK', ($re['data'] > 0 ? true : false) );
				} else {
					return $this->_return ( false, $re['msg'], null );
				}
			}
		}else{
			return parent::Unauthorized_User();
		}
	}
	
	/**
	 * 链接数据库
	 */
	private function _connect(){
		$this->_getConnect = parent::getConnect();
	}
	/**
	 * 实例化类
	 */
	private function _getClass($className,$fieldArr=''){
		switch($className){
			case 'MandOAuth':
				include_once(dirname(dirname(__FILE__)).'/lib/MandOAuth.class.php');
				return new MandOAuth($this->_getConnect, $this->config);
				break;
			case 'MandOAuthLog':
				include_once(dirname(dirname(__FILE__)).'/lib/MandOAuthLog.class.php');
				return new MandOAuthLog($this->_getConnect);
				break;
			case 'User':
				include(dirname(dirname(__FILE__)).'/lib/User.class.php');
				return new User($this->_getConnect);
				break;
			case 'UserOAuth':
				include(dirname(dirname(__FILE__)).'/lib/UserOAuth.class.php');
				return new UserOAuth($this->_getConnect, $this->config);
				break;	
			case 'ModifyProfile':
				include(dirname(dirname(__FILE__)).'/lib/ModifyProfile.class.php');
				return new ModifyProfile($this->_getConnect);
				break;	
		}
	
	}
}
?>
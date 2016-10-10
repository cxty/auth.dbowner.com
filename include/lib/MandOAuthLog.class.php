<?php
/**
 * 第三方接口类 OAuth 2.0 验证方式
 *
 * @author wbqing405@sina.com
 */

include_once('Addslashes.class.php'); //数据过滤类
include_once('ComFun.class.php'); //公共方法

class MandOAuthLog{
	
	var $tbUserInfo = 'tbUserInfo'; // 用户信息表
	var $tbUserHeadInfo = 'tbUserHeadInfo'; //用户头像信息
	var $tbAppAuthNumLogInfo = 'tbAppAuthNumLogInfo'; //应用连接请求次数 	
	var $tbUserAuthNumLogInfo = 'tbUserAuthNumLogInfo'; //用户连接请求次数	
	var $tbUserAuthFailedLoginLogInfo = 'tbUserAuthFailedLoginLogInfo';  //用户错误登录时信息记录	
	var $tbOauthLoginLog = 'tbOauthLoginLog'; //授权信息请求记录
	var $tbOauthSuccessLog = 'tbOauthSuccessLog'; //授权成功后授权值记录
	
	private function getNowTime(){ return time();} //时间
	
	public function __construct($model){
		$this->model   = $model;
		$this->config  = $GLOBALS['config'];
		
		$this->init();
	}
	
	/**
	 * 初始化对象
	 */
	private function init(){
		$this->Addslashes = new Addslashes();
	}
	
	/**
	 * Authosize请求时，App的登录记录表 
	 */
	public function setAuthosizeAppLog($AppID){
		$this->AppID = $this->Addslashes->get_addslashes($AppID);

		$condition['AppID'] = $this->AppID;
	
		$this->backArr = $this->model->table($this->tbAppAuthNumLogInfo)->where($condition)->select();

		$this->nowTime = $this->getNowTime();

		if(!$this->backArr){
			$insertArr['AppID']       = $this->AppID;
			$insertArr['aTotal']      = 1;
			$insertArr['aStartdate']  = $this->nowTime;
			$insertArr['aLastupdate'] = $this->nowTime;
			
			$this->model->table($this->tbAppAuthNumLogInfo)->data($insertArr)->insert();
		}else{
			$updateArr['aTotal']      = $this->backArr[0]['aTotal']+1;
			$updateArr['aLastupdate'] = $this->nowTime;
			
			$this->model->table($this->tbAppAuthNumLogInfo)->data($updateArr)->where($condition)->update();
		}
	}
	
	/**
	 * 用户连接应用信息记录
	 */
	public function setAuthosizeUserLog($fieldArr){
		$AppID  = $fieldArr['client_id'];
		$UserID = $fieldArr['UserID'];
		$uLimit = $fieldArr['uLimit'];
		
		$condition['AppID']  = $AppID;
		$condition['UserID'] = $UserID;
		
		$backArr = $this->model->table($this->tbUserAuthNumLogInfo)->where($condition)->select();
		
		$nowTime = $this->getNowTime();
	
		if ( !$backArr ) {
			$idata['AppID']           = $AppID;
			$idata['UserID']          = $UserID;
			$idata['uTotal']          = 1;
			$idata['uStartdate']      = $nowTime;
			$idata['uLastupdate']     = $nowTime;
			$idata['uLimit']          = $uLimit;
			$idata['uanScope']        = $fieldArr['uanScope'];
			$idata['uanPermissions']  = $fieldArr['uanPermissions'];

			$this->model->table($this->tbUserAuthNumLogInfo)->data($idata)->insert();
		}else{
			$udata['uTotal']          = $backArr[0]['uTotal']+1;
			$udata['uLastupdate']     = $nowTime;
			if($uLimit){
				$udata['uLimit']      = $uLimit;
			}
			$udata['uanScope']        = $fieldArr['uanScope'];
			$udata['uanPermissions']  = $fieldArr['uanPermissions'];
		
			$this->model->table($this->tbUserAuthNumLogInfo)->data($udata)->where($condition)->update();
		}
	}
	
	/**
	 * 增加应用用户
	 */
	public function addAppUser ( $params ) {
		$da = 0;
		try {
			$cond['AppID']           = $params['AppID'];
			$cond['UserID']          = $params['UserID'];
			if ( $this->model->table($this->tbUserAuthNumLogInfo)->field('TokenID')->where($cond)->select() ) {
				return $da;
			} else {
				$idata['AppID']           = $params['AppID'];
				$idata['UserID']          = $params['UserID'];
				$idata['uTotal']          = 1;
				$idata['uStartdate']      = time();
				$idata['uLastupdate']     = time();
				$idata['uLimit']          = '';
				$idata['uanScope']        = '';
				$idata['uanPermissions']  = '';
					
				$re = $this->model->table($this->tbUserAuthNumLogInfo)->data($idata)->insert();
				if ( $re ) {
					return $re[0]['TokenID'];
				} else {
					return $da;
				}
			}
			
		} catch ( Exception $e ) {
			return $da;
		}
	}
	
	/**
	 * 删除应用用户
	 */
	public function deleteAppUser ( $params ) {
		$da = false;
		try {
			$cond['AppID']           = $params['AppID'];
			$cond['UserID']          = $params['UserID'];
			
			$this->model->table($this->tbUserAuthNumLogInfo)->where($cond)->delete();
			
			return true;
		} catch ( Exception $e ) {
			return $da;
		}
	}
	
	/**
	 * 应用用户是否已经存在
	 */
	public function isExsitAppUser ( $params ) {
		$da = false;
		try {
			$cond['AppID']           = $params['AppID'];
			$cond['UserID']          = $params['UserID'];
				
			$re = $this->model->table($this->tbUserAuthNumLogInfo)->where($cond)->select();
				
			if ( $re ) {
				return true;
			} else {
				return $da;
			}
		} catch ( Exception $e ) {
			return $da;
		}
	}
	
	/**
	 * 判断用户是否已经使用过应用
	 */
	public function IsExistAppInfo($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$condition['UserID'] = $fieldArr['UserID'];
		$condition['AppID']  = $fieldArr['client_id'];
		
		try{
			$re = $this->model->table($this->tbUserAuthNumLogInfo)->where($condition)->select();
			
			if($re){
				return $re;
			}else{
				return -1;
			}
		}catch(Exception $e){
			return -2;
		}	
	}
	
	/**
	 * 判断用户是否授权过应用（旧方法）
	 */
	public function isAuthPermLog($fieldArr){	
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
			
			$condition['UserID'] = $fieldArr['UserID'];
			$condition['AppID']  = $fieldArr['client_id'];
			
			$re = $this->model->table($this->tbUserAuthNumLogInfo)->field('TokenID,uLimit')->where($condition)->select();
	
			if($re){
				if($re[0]['uLimit']){
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
	 * 判断用户是否授权过应用（新方法）
	 */
	public function isNeedAuthPermLog ( $fieldArr ) {
		try{
			$_cond['UserID']          = $fieldArr['UserID'];
			$_cond['AppID']           = $fieldArr['client_id'];
			$_cond['uanPermissions']  = $fieldArr['uanPermissions'];
				
			$re = $this->model->table($this->tbUserAuthNumLogInfo)->field('TokenID,uLimit')->where($_cond)->select();
	
			if($re){
				if ( $re[0]['uLimit']  ){
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
	 * 检查用户是否被禁止
	 */
	public function isValidAppForUser ( $params ) {
		$da = false;
		try {
			$cond['UserID'] = $params['UserID'];
			$cond['AppID']  = $params['AppID'];
			
			$re = $this->model->table($this->tbUserAuthNumLogInfo)->field('uState')->where($cond)->select();
		
			if ( $re ) {
				if ( $re[0]['uState'] == 1 ) {
					return true;	
				} else {
					return $da;
				}
			} else {
				return $da;
			}
		} catch ( Exception $e ) {
			return $da;
		}
	}
	
	/**
	 * 判断用户是否已经使用过应用，且用户权限不为空
	 */
	public function checkUseApp($fieldArr){	
		$re = $this->IsExistAppInfo($fieldArr);

		if($re == -1){
			return -1;
		}elseif($re == -2){
			return -1;
		}else{
			if($re[0]['uLimit']){
				return 1;
			}else{
				return -1;
			}
		}
	}
	/**
	 * 获取指定用户的权限代码
	 */
	public function getUserPermission($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$condition['UserID'] = $fieldArr['UserID'];
		$condition['AppID']  = $fieldArr['client_id'];
		
		$re = $this->model->table($this->tbUserAuthNumLogInfo)->where($condition)->select();
		
		if($re){
			return $re[0]['uLimit'];
		}else{
			return '';
		}
	}
	/**
	 * 登录失败后，记录本机IP的登录次数
	 */
	public function setUserFailLogin($uEmail){
		$this->uEmail = $uEmail;
		$this->ip      = ComFun::getIP();
		
		$condition['uEmail']  = $this->uEmail;
		$condition['ip']      = $this->ip;
		
		$this->time = $this->getNowTime();
	
		$failArr = $this->model->table($this->tbUserAuthFailedLoginLogInfo)->where($condition)->select();
		
		if($failArr){
			$updateArr['errcount']    = $failArr[0]['errcount']+1;
			$updateArr['lastupdate']  = $this->time;
		
			$this->model->table($this->tbUserAuthFailedLoginLogInfo)->data($updateArr)->where($condition)->update();
		}else{
			$insertArr['ip']         = $this->ip;
			$insertArr['uEmail']     = $this->uEmail;
			$insertArr['errcount']   = 1;
			$insertArr['lastupdate'] = $this->time;
				
			$this->model->table($this->tbUserAuthFailedLoginLogInfo)->data($insertArr)->insert();
		}
	}
	
	/**
	 * 成功登录后，删除本IP、本邮箱的错误记录
	 */
	public function setUserSuccessLogin($uEmail){
		$condition['uEmail'] = $uEmail;
		$condition['ip']     = ComFun::getIP();
		
		$this->model->table($this->tbUserAuthFailedLoginLogInfo)->where($condition)->delete();
	}
	/**
	 * 删除过期登录失败的信息记录
	 */
	public function delUserFailLoginin($datedTime){
		$condition = 'lastupdate < \''.(time() - $datedTime).'\'';
		$this->model->table($this->tbUserAuthFailedLoginLogInfo)->where($condition)->delete();
	}
	/**
	 * 删除过期记录数据：包括作为第三方应用连接、用户连接和用户登录失败次数的更新
	 */
	public function delSysDate($expire_time){
		$this->userFailLogin = $expire_time['USERFAILLOGIN'];

		$this->delFailIP();
	}
	
	/**
	 * 删除登录错误的过期的记录
	 */
	public function delFailIP(){
		$this->nowTime = $this->getNowTime();

		$expireTime = $this->nowTime - $this->userFailLogin;
		$sql = 'delete from '.$this->tbUserAuthFailedLoginLogInfo.' where lastUpdate < \''.$expireTime.'\'';
		
		$this->model->query($sql);
	}
	/**
	 * 使用应用的用户
	 */
	public function CountAppUser($appid){
		$where = ' where AppID = \''.$appid.'\'';		
		$sql = 'select count(UserID) as count from '.$this->tbUserAuthNumLogInfo.$where;
		
		$re = $this->model->query($sql);
		
		return $re[0]['count'];
	}
	/**
	 * 增加连接授权信息
	 */
	public function addAuthNumLogInfo($fieldArr){
		$data['UserID']        = $fieldArr['UserID'];
		$data['AppID']         = $fieldArr['client_id'];
		$data['uTotal']        = 1;
		$data['uStartdate']    = time();
		$data['uLastupdate']   = time();
		$data['access_token']  = $fieldArr['access_token'];		
		$data['refresh_token'] = $fieldArr['refresh_token'];
		$data['user_id']       = $fieldArr['user_id'];
		$data['token_time']    = time();

		return $this->model->table($this->tbUserAuthNumLogInfo)->data($data)->insert();
	}
	/**
	 * 返回用户连接授权信息
	 */
	public function getAuthNumLogInfo($fieldArr){
		$condition['UserID'] = $fieldArr['UserID'];
		$condition['AppID']  = $fieldArr['client_id'];	

		return $this->model->table($this->tbUserAuthNumLogInfo)->where($condition)->select();
	}
	/**
	 * 通过授权应用信息取用户授权信息
	 */
	public function getAuthNumLogByID($tokenID){
		$condition['TokenID'] = $tokenID;
		
		return $this->model->table($this->tbUserAuthNumLogInfo)->where($condition)->select();
	}
	/**
	 * 更新授权信息
	 */
	public function updateAuthNumLogInfo($fieldArr){
		$condition['UserID'] = $fieldArr['UserID'];
		$condition['AppID']  = $fieldArr['client_id'];
		
		$data['refresh_token'] = $fieldArr['refresh_token'];
		$data['user_id']       = $fieldArr['user_id'];
		$data['token_time']    = time();

		$this->model->table($this->tbUserAuthNumLogInfo)->data($data)->where($condition)->update();
	}
	/**
	 * 授权信息请求记录
	 */
	public function setOauthLoginLog($fieldArr){
		$data['Request_Type']   = $fieldArr['Request_Type'];
		$data['Request_Client'] = $fieldArr['Request_Client'];
		$data['Request_String'] = $fieldArr['Request_String'];
		$data['Request_Time']   = time();
		
		return $this->model->table($this->tbOauthLoginLog)->data($data)->insert();
	}
	/**
	 * 授权返回信息记录
	 */
	public function setOauthBackLoginLog($fieldArr){
		$data['Back_State']  = $fieldArr['Back_State'];
		$data['Back_String'] = $fieldArr['Back_String'];
		$data['Back_Time']   = time();
		
		$condition['Autoid'] = $fieldArr['Autoid'];

		return $this->model->table($this->tbOauthLoginLog)->data($data)->where($condition)->update();
	}
	/**
	 * 授权成功后授权值记录
	 */
	public function setOauthSuccessLog($fieldArr){
		$data['TokenID']       = $fieldArr['TokenID'];
		$data['client_id']     = $fieldArr['client_id'];
		$data['access_token']  = $fieldArr['access_token'];
		$data['refresh_token'] = $fieldArr['refresh_token'];
		$data['user_id']       = $fieldArr['user_id'];
		$data['appendTime']    = time();
		
		return $this->model->table($this->tbOauthSuccessLog)->data($data)->insert();
	}
	/**
	 * 删除用户应用信息
	 */
	public function delOauthInfoLog($fieldArr){
		$condition['UserID'] = $fieldArr['UserID'];
		$condition['AppID']  = $fieldArr['AppID'];

		return $this->model->table($this->tbUserAuthNumLogInfo)->where($condition)->delete();
	}
	/**
	 * 更新用户授权信息
	 */
	public function updatePermValue($fieldArr){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
				
			$condition['UserID'] = $fieldArr['UserID'];
			$condition['AppID']  = $fieldArr['client_id'];
				
			$data['ulimit'] = $fieldArr['ulimit'];
				
			return $this->model->table($this->tbUserAuthNumLogInfo)->data($data)->where($condition)->update();
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 取用户权限信息
	 */
	public function getPermValue($fieldArr){
		$_da = '';
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
			
			$condition['UserID'] = $fieldArr['UserID'];
			$condition['AppID']  = $fieldArr['client_id'];
			
			$re = $this->model->table($this->tbUserAuthNumLogInfo)->field('uLimit')->where($condition)->select();
			
			if($re){
				return $re[0]['uLimit'];
			}else{
				return $_da;
			}
		}catch(Exception $e){
			return $_da;
		}
	}
	/**
	 * 取应用对应的用户名和加密的用户user_id
	 */
	public function getUserListByClientID($fieldArr){
		try{
			$where = 'where a.AppID = \''.$fieldArr['client_id'].'\'';
			if($fieldArr['uName']){
				$where .= ' and b.uName like \'%'.$fieldArr['uName'].'%\'';
			}
			
			$sql = 'select a.AppID,a.UserID,b.uName,c.uhURL from '.$this->tbUserAuthNumLogInfo.' as a left join '.$this->tbUserInfo.' as b on a.UserID = b.UserID ' 
					. ' left join ' . $this->tbUserHeadInfo . ' as c on b.UserID = c.UserID '
					. $where;

			$_re = $this->model->query($sql);
			
			if($_re){
				$rb = array();
				$mandOAuth = $this->getClass('MandOAuth');
				
				foreach($_re as $key=>$val){
					$rb[$key]['user_id'] = $mandOAuth->getUserOAuthID($val['UserID'], $fieldArr['client_id']);
					$rb[$key]['uName']   = $val['uName'];
					$rb[$key]['uhURL']   = $this->config['FILE_SERVER_GET'] . '&filecode=' . ( $val['uhURL'] ?  $val['uhURL'] : $this->config['IMAGES']['FILECODES'] );
				}
				return $rb;
			}else{
				return '';
			}
		}catch(Exception $e){
			return '';
		}
	}
	
	/**
	 * 取用户信息
	 */
	public function GetUserInfoByAppIDAndUserID ( $params ) {
		$da = array();
		try {
			$where = ' where a.AppID = \'' . $params['AppID'] . '\' and a.UserID = ' . $params['UserID'];
			
			$sql = 'select a.AppID,a.UserID,a.uState,b.uName,b.uEmail,c.uhURL from '.$this->tbUserAuthNumLogInfo.' as a left join '.$this->tbUserInfo.' as b on a.UserID = b.UserID '
					. ' left join ' . $this->tbUserHeadInfo . ' as c on b.UserID = c.UserID '
					. $where;
			
			$_re = $this->model->query($sql);
			
			if ( $_re ) {
				$mandOAuth = $this->getClass('MandOAuth');
				
				$rb = array();
				
				$rb['user_id'] = $mandOAuth->getUserOAuthID($_re[0]['UserID'], $params['AppID']);
				$rb['uName']   = $_re[0]['uName'];
				$rb['uEmail']  = $_re[0]['uEmail'];
				$rb['uhURL']   = $this->config['FILE_SERVER_GET'] . '&filecode=' . ( $_re[0]['uhURL'] ?  $_re[0]['uhURL'] : $this->config['IMAGES']['FILECODES'] );
				$rb['uState']  = $_re[0]['uState'];
				
				return $rb;
			} else {
				return $da;
			}
		} catch ( Exception $e ) {
			return $da;
		}
	}
	
	/**
	 * 取应用对应的用户名和加密的用户user_id
	 */
	public function getUserPageListByClientID($fieldArr, $order='', $page=1, $pagesize=10){
		$da = array(
	    		'count' => 0,
				'list' => null
		);
		try{
			$limit = ' limit ' . ($page-1)*$pagesize . ',' . $pagesize;
			$order = $order ? ' order by ' . $order : '';
			$where = ' where a.AppID = \''.$fieldArr['client_id'].'\'';
			if($fieldArr['uName']){
				$where .= ' and b.uName like \'%'.$fieldArr['uName'].'%\'';
			}
				
			$sql = 'select count(1) as count from '.$this->tbUserAuthNumLogInfo.' as a left join '.$this->tbUserInfo.' as b on a.UserID = b.UserID '
					. $where;
			$count = $this->model->query($sql);
			
			$sql = 'select a.AppID,a.UserID,a.uState,b.uName,b.uEmail,c.uhURL from '.$this->tbUserAuthNumLogInfo.' as a left join '.$this->tbUserInfo.' as b on a.UserID = b.UserID '
					. ' left join ' . $this->tbUserHeadInfo . ' as c on b.UserID = c.UserID '
					. $where . $order . $limit;
	
			$_re = $this->model->query($sql);
			
			if($_re){
				$rb = array();
				$mandOAuth = $this->getClass('MandOAuth');
	
				foreach($_re as $key=>$val){
					$rb[$key]['user_id'] = $mandOAuth->getUserOAuthID($val['UserID'], $fieldArr['client_id']);
					$rb[$key]['uName']   = $val['uName'];
					$rb[$key]['uEmail']  = $val['uEmail'];
					$rb[$key]['uhURL']   = $this->config['FILE_SERVER_GET'] . '&filecode=' . ( $val['uhURL'] ?  $val['uhURL'] : $this->config['IMAGES']['FILECODES'] );
					$rb[$key]['uState']  = $val['uState'];
				}
				return array(
					    'count' => $count[0]['count'],
						'list' => $rb
				);
			}else{
				return $da;
			}
		}catch(Exception $e){
			return $da;
		}
	}
	
	/**
	 * 获取指定用户的权限代码
	 */
	public function getTokenIDByUserIDAndAppID ($fieldArr) {
		$_da = 0;
		try {
			$_cond['UserID'] = $fieldArr['UserID'];
			$_cond['AppID']  = $fieldArr['client_id'];
			
			$_re = $this->model->table($this->tbUserAuthNumLogInfo)->where($_cond)->select();
			
			if ( $_re ) {
				return $_re[0]['TokenID'];
			} else {
				return $_da;
			}
		} catch ( Exception $e ) {
			return $_da;
		}
	}
	
	/**
	 * 根据TokenID获取应用AppID
	 */
	public function getAppIDByTokenID ( $TokenID ) {
		$_da = array();
		try {
			$_cond['TokenID'] = $TokenID;
			
			$_re = $this->model->table($this->tbUserAuthNumLogInfo)->field('UserID,AppID')->where($_cond)->select();
			
			if ( $_re ) {
				return $_re[0];
			} else {
				return $_da;
			}
		} catch ( Exception $e ) {
			return $_da;
		}
	}
	
	/**
	 * 屏蔽用户进入应用状态
	 */
	public function updateUserAuthNumLogState ( $params ) {
		$da = false;
		try {
			$cond['UserID'] = $params['UserID'];
			$cond['AppID']  = $params['AppID'];
			
			$data['uState'] = $params['uState'];
			
			$this->model->table($this->tbUserAuthNumLogInfo)->data($data)->where($cond)->update();
			
			return true;
		} catch ( Exception $e ) {
			return $da;
		}
	}
	
	
	/**
	 * 取得类
	 */
	private function getClass($className,$fieldArr=null){
		switch($className){
			case 'MandOAuth':
				include_once('MandOAuth.class.php');
				return new MandOAuth($this->model, $GLOBALS['config']);
				break;
			default:
				break;
		}
	}
}
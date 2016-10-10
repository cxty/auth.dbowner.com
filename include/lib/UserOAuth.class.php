<?php 
/**
 * 处理用户信息类
 * 
 * @author wbqing405@sina.com
 */

include_once('Config.class.php'); //引入处理类的编码格式 utf-8
include_once('Addslashes.class.php'); //数据过滤类
include_once('ComFun.class.php'); //公共方法

class UserOAuth{
	
	var $tbUserInfo = 'tbUserInfo'; //用户基础信息表
	
	var $tbUserDetInfo = 'tbUserDetInfo'; //用户详细信息
	
	var $tbUserLoginInfo = 'tbUserLoginInfo'; //用户登录累计信息
	
	var $tbUserOnLineLogInfo = 'tbUserOnLineLogInfo';  //用户在线记录信息 
	
	var $tbUserAuthenticationsInfo = 'tbUserAuthenticationsInfo'; //第三方平台登录信息 
	
	var $tbUserHeadInfo = 'tbUserHeadInfo'; //用户头像信息 
	
	var $tbUserAuthNumLogInfo = 'tbUserAuthNumLogInfo'; //用户连接请求次数
	
	public function __construct ($base, $config='') {
		$this->model = $base;	

		$this->config = $config;
		
		$this->COFGIGDES = $this->config['DES'];
		 
		$this->init();
	}
	/**
	 * 初始化
	 */
	private function init(){
		$this->Addslashes = new Addslashes();
		
		//DBSoap类
		if ( !isset($GLOBALS['DBInnerSoap']) ) {
			include(dirname(__FILE__).'/DBInnerSoap.class.php');
			$this->dbInnerSoap = new DBInnerSoap();
				
			$GLOBALS['DBInnerSoap'] = $this->dbInnerSoap;
		} else {
			$this->dbInnerSoap = $GLOBALS['DBInnerSoap'];
		}
	}
	/**
	 * 获取用户信息
	 */
	public function getOAuthUserInfo($UserID,$modifyProfile){
		$UserID = $this->Addslashes->get_addslashes($UserID);

		$sql = 'select * from '.$this->tbUserInfo.' where uEstate != 2 and UserID = '.$UserID;
		$re = $this->model->query($sql);
		if(!$re){
			return '';
		}
		
		return $this->doGetUserInfo($UserID,$modifyProfile);
	}
	/**
	 * 获取用户信息
	 */
	public function doGetUserInfo($UserID,$modifyProfile=array()){
		$sql = 'select a.UserID,a.uName,a.uEmail,a.UserGroupsID,b.uSex,b.uComeFrom,c.uhURL from '.$this->tbUserInfo.' as a left join '.$this->tbUserDetInfo.' as b on a.UserID = b.UserID left join '.$this->tbUserHeadInfo.' as c on a.UserID = c.UserID where a.uEstate != 2 and a.UserID = '.$UserID;
	
		$userInfo = $this->model->query($sql);
	
		if($userInfo){
			$re['UserID']    = $userInfo[0]['UserID'];
			$re['name']      = $userInfo[0]['uName'];
			$re['email']     = $userInfo[0]['uEmail'];
			$re['sex']       = $userInfo[0]['uSex'] ? $userInfo[0]['uSex'] : 0;
			$re['location']  = $userInfo[0]['uComeFrom'];
			$re['group']     = $userInfo[0]['UserGroupsID'];
				
			if ( empty ( $modifyProfile )) {
				include('ModifyProfile.class.php');
					
				$modifyProfile = new ModifyProfile($this->model,$this->config);
			}
			
			$imagesUrl = $modifyProfile->getPortrait($UserID);
		
			$re['ico']['b']      = $imagesUrl['imagesUrl_1'];
			$re['ico']['m']      = $imagesUrl['imagesUrl_2'];
			$re['ico']['s']      = $imagesUrl['imagesUrl_3'];
				
		}else{
			$re = '';
		}
			
		return $re;
	}
	
	/**
	 * 获取用户信息
	 */
	public function doGetUserInfoByUserName($uName,$modifyProfile=array()){
		$da = array();
		
		try {
			$sql = 'select a.UserID,a.uName,a.uEmail,a.UserGroupsID,b.uSex,b.uComeFrom,c.uhURL from '.$this->tbUserInfo.' as a left join '.$this->tbUserDetInfo.' as b on a.UserID = b.UserID left join '.$this->tbUserHeadInfo.' as c on a.UserID = c.UserID where a.uEstate != 2 and a.uName = \'' . $uName . '\'';
			
			$userInfo = $this->model->query($sql);
			
			if($userInfo){
				$re['UserID']    = $userInfo[0]['UserID'];
				$re['name']      = $userInfo[0]['uName'];
				$re['email']     = $userInfo[0]['uEmail'];
				$re['sex']       = $userInfo[0]['uSex'] ? $userInfo[0]['uSex'] : 0;
				$re['location']  = $userInfo[0]['uComeFrom'];
				$re['group']     = $userInfo[0]['UserGroupsID'];
				
				if ( empty ( $modifyProfile )) {
					//include('ModifyProfile.class.php');
					
					$modifyProfile = new ModifyProfile($this->model,$this->config);
				}
			
				$imagesUrl = $modifyProfile->getPortrait($UserID);
			
				$re['ico']['b']      = $imagesUrl['imagesUrl_1'];
				$re['ico']['m']      = $imagesUrl['imagesUrl_2'];
				$re['ico']['s']      = $imagesUrl['imagesUrl_3'];
				
				return $re;
			}else{
				return $da;
			}
		} catch ( Exception $e ) {
			return $da;
		}
	}
	
	/**
	 * 获取用户信息
	 */
	public function doGetUserInfoByEmail($uEmail,$modifyProfile=array()){
		$da = array();
	
		try {
			$sql = 'select a.UserID,a.uName,a.uEmail,a.UserGroupsID,b.uSex,b.uComeFrom,c.uhURL from '.$this->tbUserInfo.' as a left join '.$this->tbUserDetInfo.' as b on a.UserID = b.UserID left join '.$this->tbUserHeadInfo.' as c on a.UserID = c.UserID where a.uEstate != 2 and a.uEmail = \'' . $uEmail . '\'';
			
			$userInfo = $this->model->query($sql);
				
			if($userInfo){
				$re['UserID']    = $userInfo[0]['UserID'];
				$re['name']      = $userInfo[0]['uName'];
				$re['email']     = $userInfo[0]['uEmail'];
				$re['sex']       = $userInfo[0]['uSex'] ? $userInfo[0]['uSex'] : 0;
				$re['location']  = $userInfo[0]['uComeFrom'];
				$re['group']     = $userInfo[0]['UserGroupsID'];
	
				if ( empty ( $modifyProfile )) {
					//include('ModifyProfile.class.php');
						
					$modifyProfile = new ModifyProfile($this->model,$this->config);
				}
					
				$imagesUrl = $modifyProfile->getPortrait($UserID);
					
				$re['ico']['b']      = $imagesUrl['imagesUrl_1'];
				$re['ico']['m']      = $imagesUrl['imagesUrl_2'];
				$re['ico']['s']      = $imagesUrl['imagesUrl_3'];
	
				return $re;
			}else{
				return $da;
			}
		} catch ( Exception $e ) {
			return $da;
		}
	}
	
	/**
	 * 获取第三方验证信息
	 */
	public function getOAuthInfo($UserID,$partner){
		$UserID    = $this->Addslashes->get_addslashes($UserID);
		$uProvider = ucfirst($this->Addslashes->get_addslashes($partner));
		
		$condition = 'uEstate != 2 and UserID = \''.$UserID.'\' and uProvider = \''.$uProvider.'\'';
			
		$re = $this->model->table($this->tbUserAuthenticationsInfo)->where($condition)->select();

		if($re){
			return json_decode($re[0]['uPermissions'],true);
		}else{
			return -1;
		}
		
	}
	/**
	 * 取指定用户的App和授权列表
	 */
	public function dogetAppList($UserID){	
		$_da = array();
		
		$condition['UserID'] = $UserID;
		
		$re = $this->model->table($this->tbUserAuthNumLogInfo)->field('AppID,uLimit')->where($condition)->select();	

		if($re){
			$AppIDList = '';
			foreach($re as $key=>$val){
				$AppIDList .= ',' . $val['AppID'];
				$rb[$key]['AppID']          = $val['AppID'];
				$rb[$key]['apppermissions'] = $val['uLimit'];
			}
			
			if ( $AppIDList ) {
				$AppList = $this->dbInnerSoap->devGetAppByIDList( substr($AppIDList,1) );
			
				if ( $AppList['data'] ) {
					if ( count($AppList['data']) > 0 ) {
						for ($i=0;$i<count($AppList['data']);$i++) {
							$rb[$i]['aName']          = $AppList['data'][$i]['appinfo']['aName'];
							$rb[$i]['aIcoCode']       = $AppList['data'][$i]['appinfo']['aIcoCode'];
							$rb[$i]['aInfo']          = $AppList['data'][$i]['appinfo']['aInfo'];
							$rb[$i]['appplus']        = $AppList['data'][$i]['appplus'];
						}
					}
					
					return $rb;
				} else {
					return $_da;
				}
			} else {
				return $_da;
			}
		}else{
			return $_da;
		}
		
	}
	/**
	 * 取指定用户的App和授权分页列表
	 */
	public function getAppListPage($UserID,$page=1,$pagesize=10){
		$_da = array(
					'count' => $count,
					'list' => $list
					);
		try{
			$limit = ((($page ? $page : 1) - 1) * $pagesize) . ',' . $pagesize;
			
			$condition['UserID'] = $UserID;
			
			$count = $this->model->table($this->tbUserAuthNumLogInfo)->field('AutoID')->where($condition)->count();
			$list = $this->model->table($this->tbUserAuthNumLogInfo)->field('AppID,uLimit')->where($condition)->limit($limit)->select();
		
			if($list){
				$AppIDList = '';
				foreach($list as $key=>$val){
					$AppIDList .= ',' . $val['AppID'];
					$list[$key]['AppID']          = $val['AppID'];
					$list[$key]['apppermissions'] = $val['uLimit'];
					
				}
				
				if ( $AppIDList ) {
					$AppList = $this->dbInnerSoap->devGetAppByIDList( substr($AppIDList,1) );
				
					if ( $AppList['data'] ) {
						if ( count($AppList['data']) > 0 ) {
							for ($i=0;$i<count($AppList['data']);$i++) {
								$list[$i]['aName']          = $AppList['data'][$i]['appinfo']['aName'];
								$list[$i]['aIcoCode']       = $AppList['data'][$i]['appinfo']['aIcoCode'];
								$list[$i]['aInfo']          = $AppList['data'][$i]['appinfo']['aInfo'];
								$list[$i]['appplus']        = $AppList['data'][$i]['appplus'];
							}
						}
							
						return array(
								'count' => $count,
								'list' => $list
								);
					} else {
						return $_da;
					}
				} else {
					return $_da;
				}
				
			}
			
			return $_da;
		}catch(Exception $e){
			return $_da;
		}
	}
	/**
	 * 获取应用信息
	 */
	public function getAuthAppInfo($client_id){
		$tArr['client_id'] = $client_id;
		$soapc = $this->getClass('soapc',$tArr);
		$AppInfo = $soapc->run();
	
// 		$tArr['AppID'] = $AppID;
// 		$dbSoap = $this->getClass('DBSoap');
// 		$AppInfo = $dbSoap->GetTableInfo('Dev', 'GetAppByID', $where);
		
		return $AppInfo;
	}
	/**
	 * 获取应用信息
	 */
	public function getDevAppInfo($AppID){
//		$tArr['AppID'] = $AppID;
		//$where = 'AppID = \''.AppID.'\'';
// 		echo $where;
// 		$soapc = $this->getClass('soapc',$tArr);
// 		$AppInfo = $soapc->run();
		$where = ' AppID = \''.$AppID.'\' ';
		$tArr['AppID'] = $AppID;
		$dbSoap = $this->getClass('DBSoap');
		$AppInfo = $dbSoap->GetTableInfo('Dev', 'GetAppByID', $where);
// 		$AppID= 1;
// 		$where = ' AppID = \''.$AppID.'\' ';
// 		$dbSoap = $this->getClass('DBSoap',$tArr);
// 		$AppInfo = $dbSoap->GetTableList('Dev', 'GetAppList', 1, 100, '');
	
		ComFun::pr($AppInfo);
		
		return $AppInfo;
	}
	/**
	 * 查询指定用户名的用户信息
	 */
	public function doshow_by_name($fieldArr,$modifyProfile){
		
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$apiInfo = $this->getAuthAppInfo($fieldArr['client_id']);
		
		$AppInfoID = $apiInfo['data']['appset']['AppInfoID'];
		
		$user = $this->getClass('User');
		$userInfo = $user->getUserInfoByName($fieldArr['uName']);

		if($userInfo){
			return $this->doGetUserInfo($userInfo[0]['UserID'],$modifyProfile);
		}else{
			return '';
		}	
	}
	/**
	 * 查询指定用户user_id的用户信息
	 */
	public function doshow_by_userid($fieldArr,$modifyProfile=''){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$apiInfo = $this->getAuthAppInfo($fieldArr['client_id']);
		
		$AppInfoID = $apiInfo['data']['appset']['AppInfoID'];
		
		$MandOAuth = $this->getClass('MandOAuth');
		$UserID = $MandOAuth->_getUserID($fieldArr['user_id']);
		
		$user = $this->getClass('User');
		$userInfo = $user->getUserInfo($UserID);
		
		if($userInfo){
			return $this->doGetUserInfo($userInfo[0]['UserID'],$modifyProfile);
		}else{
			return '';
		}
	}
	
	/**
	 * 获取绑定指定用户的第三方平台
	 */
	public function getBindingThirdPartyInfo ( $fieldArr ) {
		$_da = '';
		try {
			$_where = 'uEstate != 2 and UserID = ' . $fieldArr['UserID'] ;
			if ( $fieldArr['uProvider'] ) {
				$_where .= ' and uProvider in (' . $fieldArr['uProvider'] . ')';
			}
			return $this->model->query('select uProvider,uPermissions from ' . $this->tbUserAuthenticationsInfo . ' where ' . $_where);
		} catch ( Exception $e ) {
			return $_da;
		}
	}
	
	/**
	 * 获取绑定指定用户的第三方平台详细信息
	 */
	public function getBindingThirdPartyInfoByUserID ( $UserID ) {
		$_da = '';
		try {
			$_where = 'uEstate != 2 and UserID = ' . $UserID ;
			
			return $this->model->query('select uProvider,
												uProvider_uid,
												uProfile_url,
												uImages,
												uLocalion
												from ' . $this->tbUserAuthenticationsInfo . ' where ' . $_where);
		} catch ( Exception $e ) {
			return $_da;
		}
	}
	
	/**
	 * 通过用户名判断用户是否属于应用
	 */
	public function isUserBelongAppByUserName ( $params ) {
		$da = false;
		try {
			$re = $this->model->query('select b.TokenID from tbUserInfo as a left join tbUserAuthNumLogInfo as b on a.UserID = b.UserID where a.uName = \'' . $params['uName'] . '\' and b.AppID = \'' . $params['AppID'] . '\'');
			
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
	 * 通过用户名判断用户是否属于应用
	 */
	public function isUserBelongAppByEmail ( $params ) {
		$da = false;
		try {
			$re = $this->model->query('select b.TokenID from tbUserInfo as a left join tbUserAuthNumLogInfo as b on a.UserID = b.UserID where a.uEmail = \'' . $params['uEmail'] . '\' and b.AppID = \'' . $params['AppID'] . '\'');
			
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
	 * 通过用户名判断用户是否属于应用
	 */
	public function isUserBelongAppByUserID ( $params ) {
		$da = false;
		try {
			$re = $this->model->query('select TokenID from tbUserAuthNumLogInfo where UserID = \'' . $params['UserID'] . '\' and AppID = \'' . $params['AppID'] . '\'');
				
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
	 * 取得类
	 */
	private function getClass($className,$fieldArr=null){
		switch($className){
			case 'User':
				include_once('User.class.php');
				return new User($this->model);
				break;
			case 'MandOAuth':
				include_once('MandOAuth.class.php');
				return new MandOAuth($this->model);
				break;
			case 'Login':
				include_once('Login.class.php');
				return new Login($this->model);
				break;
			case 'soapc':
				include_once('soapc.class.php');
				return new soapc($this->COFGIGDES,$fieldArr['client_id']);
				break;
			case 'DBSoap':
				include_once('DBSoap.class.php');
				return new DBSoap();
				break;	
			default:
				break;
		}
	}
	
}
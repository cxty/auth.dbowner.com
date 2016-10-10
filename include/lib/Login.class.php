<?php 
/**
 * 处理用户信息类
 * 
 * @author wbqing405@sina.com
 */

include_once('Config.class.php'); //引入处理类的编码格式 utf-8
include_once('Addslashes.class.php'); //数据过滤类
include_once('ComFun.class.php'); //公共方法

class Login{
	
	var $tbUserInfo = 'tbUserInfo'; //用户基础信息表
	
	var $tbUserLoginInfo = 'tbUserLoginInfo'; //用户登录累计信息
	
	var $tbUserOnLineLogInfo = 'tbUserOnLineLogInfo';  //用户在线记录信息 
	
	var $tbUserFailedLoginLogInfo = 'tbUserFailedLoginLogInfo'; //用户登录失败记录信息 
	
	var $tbUserAuthenticationsInfo = 'tbUserAuthenticationsInfo'; //第三方平台登录信息
	
	var $tbUserOnLineTime = 'tbUserOnLineTime'; //用户在线时间记录信息(每月一条)
	
	var $default_username = 'user'; //默认用户名
	
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
	//==============以下方法是写用户表和第三方用户信息表==================
	/**
	 * 账户注册数据处理
	 */
	public function doRegister($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$uName  = trim($fieldArr['uName']);
		$uEmail = trim($fieldArr['uEmail']);
		$uPWD   = trim($fieldArr['uPWD']);
		
		if(empty($uName)){
			ComFun::throwMsg('Ex_NotNull301');
		}
		if(empty($uEmail)){
			ComFun::throwMsg('Ex_NotNull302');
		}
		if(empty($uPWD)){
			ComFun::throwMsg('Ex_NotNull303');
		}
				
		$this->isUsedEmail($uEmail); //检验邮箱是否已经使用过

		$uCode = ComFun::getRandom();
		
		$tArr['uName']   = $uName;
		$tArr['uEmail']  = $uEmail;
		$tArr['uPWD']    = md5($uPWD);
		$tArr['uCode']   = $uCode;
		
		$UserID = $this->addNewUser($tArr);
		
		if(empty($UserID)){
			ComFun::throwMsg('Ex_RegistrError');
		}
	
		//登录成功后，更新日志表和写$_COOKIES
		$ckArr['UserID'] = $UserID;
		$ckArr['uName']  = $uName;
		$this->setLoginSuccess($ckArr);
		
		//发邮件
		if($uEmail){
			$emArr['uName']   = $uName;
			$emArr['uEmail']  = $uEmail;
			$emArr['uCode']   = $uCode;
			$emArr['type']    = 'register';
	
			ComFun::toSendMail($emArr);
		}
	}	
	/**
	 * 检验邮箱是否已经使用过
	 */
	private function isUsedEmail($uEmail){
		$condition = 'uEstate != 2 and uEmail = \''.$this->Addslashes->get_addslashes($uEmail).'\'';
		
		$re = $this->model->table($this->tbUserInfo)->field('UserID,uEmail')->where($condition)->select();
		
		if($re){
			ComFun::throwMsg('Ex_ErrorValueUsed501');
		}
	}
	/**
	 * 增加用户表新记录
	 */
	private function addNewUser($fieldArr){	
		$iData['UserGroupsID'] = isset($fieldArr['UserGroupsID']) ? $fieldArr['UserGroupsID'] : 0;
		$iData['uEmail']       = $fieldArr['uEmail'];
		$iData['uName']        = $fieldArr['uName'];
		$iData['uPWD']         = $fieldArr['uPWD'];
		$iData['uCode']        = $fieldArr['uCode'];
		$iData['uAppendTime']  = time();
		$iData['uEstate']      = $fieldArr['uEstate'] ? $fieldArr['uEstate'] : -1;
			
		return $this->model->table($this->tbUserInfo)->data($iData)->insert();
	}
	
	/**
	 * 直接增加用户信息
	 */
	public function addNewUserDir ( $params ) {
		$uCode = ComFun::getRandom();
		
		//增加用户信息
		$tArr['uEmail']  = $params['uEmail'];
		$tArr['uName']   = $params['uName'];
		$tArr['uPWD']    = md5($params['uPWD']);
		$tArr['uCode']   = $uCode;
		$tArr['uEstate'] = -2;
		
		//发邮件
		if ( $params['uEmail'] && $params['isEmail'] === true ) {
			$emArr['uName']   = $params['uName'];
			$emArr['uEmail']  = $params['uEmail'];
			$emArr['uCode']   = $uCode;
			$emArr['type']    = 'bandAccount';
		
			ComFun::toSendMail($emArr);
		}
		
		return $this->addNewUser($tArr);
	}
	
	/**
	 * 登录成功操作
	 */
	public function doCheckLogin($fieldArr){		
		$user = $this->getClass('User');
		$rbArr = $user->checkBeforeLogin($fieldArr);
		
		if($rbArr != -1){
			$fieldArr['UserID'] = $rbArr['UserID'];
			$fieldArr['uName']  = $rbArr['uName'];
			$this->doSucessLogin($fieldArr);
				
			return $rbArr['UserID'];
		}else{
			$mandOAuthlog = $this->getClass('MandOAuthLog');
			$mandOAuthlog->setUserFailLogin($fieldArr['uEmail']);
			
			return -1;
		}
	}
	/**
	 * 登录成功后操作
	 */
	public function doSucessLogin($fieldArr){	
		$mandOAuthlog = $this->getClass('MandOAuthLog');
		$mandOAuthlog->setUserSuccessLogin($fieldArr['uEmail']);
		
		//登录成功后，更新日志表和写$_COOKIES
		$ckArr['UserID'] = $fieldArr['UserID'];
		$ckArr['uName']  = $fieldArr['uName'];
		if($fieldArr['remusrname'] == 'checked'){
			$ckArr['remusrname']  = true;
		}
		
		$this->setLoginSuccess($ckArr);
	}
	/**
	 * 登出处理
	 */
	public function doLoginOut(){
		$UserID = $UserID ? $UserID : ComFun::getCookies('UserID');

		if($UserID){
			//操作log表
			$this->doLoginOutLog($UserID);			
		}
			
		//注销$_COOKIE
		ComFun::destoryCookies();
	}
	/**
	 * 自动操作检查记录信息和下次关联操作
	 */
	public function doCheckOauthInfo(){
		$re = $this->checkOAuthInfo();

		if($re == -1){
			$this->addDireAccountInfo(); //第一次使用，增加用户信息和第三方信息
		}else{
			//写日志和写$_COOKIES
			$rbArr = $this->IsExistUserInfo($re['UserID']);
			
			//当用户名不存在，则添加
			if ( !$rbArr['uName'] ) {
				$this->addUserName( $re['uDisplay_name'], $rbArr['UserID'] );
			}
			
			if($rbArr == -1){  //用户信息不存在，则写用户信息并更新第三方信息表
				$this->addNewUserInfoByThird($re);
				//$this->setSuccessLog($rbArr);			
			}else{
				$this->updateOauthThirdInfo(ComFun::getCookies('provider')); //用户信息存在，则只更新第三方信息表
				$this->setCookiesByUserID($re['UserID']);
			}
		}
	}
	
	/**
	 * 当用户名不存在的情况下，添加用户名
	 */
	private function addUserName ( $uName, $UserID ) {
		try {
			$cond['UserID'] = $UserID;
			
			$data['uName'] = $uName;
			
			$this->model->table($this->tbUserInfo)->data($data)->where($cond)->update();
			
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	
	/**
	 * 检查第三方是否已经绑定，存在则返回用户ID跟邮箱
	 */
	public function checkOAuthInfo($provider=false){
		//检验第三方唯一性的条件
		$uProvider     = $provider ? $provider : ComFun::getCookies('provider');
		$uProvider_uid = ComFun::getCookies($uProvider.'_user_id');
	
		if(empty($uProvider)){
			ComFun::throwMsg('Ex_NotNull304');
		}
		if(empty($uProvider_uid)){
			ComFun::throwMsg('Ex_NotNull306');
		}
		
		$condition = 'uEstate != 2 and uProvider = \''.$uProvider.'\' and uProvider_uid = \''.$uProvider_uid.'\'';
		
		$re = $this->model->table($this->tbUserAuthenticationsInfo)->field('UserAuthenticationsID,UserID,uProvider,uEmail,uDisplay_name,OUserID')->where($condition)->select();

		if($re){
			return $re[0];
		}else{
			return -1;
		}
	}
	
	/**
	 * 检测第三方是否已经绑定过账号，若同时绑定两个账号到同一账号，则废除除最新那个之前的
	 */
	public function updateOldThirdPartyAccount ( $fieldArr ) {
		$_da = false;
		try {
			$_where = 'uEstate != 2';
			$_where .= ' and uProvider = \'' . $fieldArr['uProvider'] . '\' ';
			$_where .= ' and UserID = \'' . $fieldArr['UserID'] . '\'';
			$_where .= ' and uProvider_uid != \'' . $fieldArr['uProvider_uid'] . '\'';
			
			$_data['uEstate'] = 2;
			$_data['OUserID'] = ',' . $fieldArr['UserID'];
			
			$this->model->table($this->tbUserAuthenticationsInfo)->data($_data)->where($_where)->update();
			
			return true;
		} catch ( Exception $e ) {
			return $_da;
		}
	}
	
	/**
	 * 做为第三方登录（情况1）：第三方信息表中没有记录，直接增加用户信息和第三方信息（做为第三方接口登录）
	 * 第一种情况：增加用户信息、第三方用户信息
	 */
	private function addDireAccountInfo($fieldArr=null){
		$fieldArr      = $this->Addslashes->get_addslashes($fieldArr);	
		$uEmail        = $fieldArr['uEmail'] ? $fieldArr['uEmail'] : '';
		$provider      = ComFun::getCookies('provider');
		$uProvider_uid = ComFun::getCookies($provider.'_user_id');
		
		if(empty($provider)){
			ComFun::throwMsg('Ex_NotNull304');
		}
		if(empty($uProvider_uid)){
			ComFun::throwMsg('Ex_NotNull306');
		}

		$thirdInfo = ComFun::getThirdInfoByGet('/db/getUserInfo',ComFun::getTConditionByCurl($provider));
		
		if(isset($thirdInfo['error'])){
			ComFun::throwMsg('Ex_ErrorSystem401');
		}

		$uName = $this->checkRepeatName($thirdInfo['uDisplay_name']);
		
		$uCode = ComFun::getRandom();
		
		//增加用户信息
		$tArr['uEmail']  = $uEmail;
		$tArr['uName']   = $uName;	
		$tArr['uCode']   = $uCode;
		$tArr['uEstate'] = -2;
		$UserID = $this->addNewUser($tArr);
		
		if(empty($UserID)){
			ComFun::throwMsg('Ex_RegistrError');
		}
		
		//增加第三方信息记录
		$itArr['UserID']            = $UserID;
		$itArr['uProvider']         = $provider;
		$itArr['uProvider_uid']     = $uProvider_uid;
		$itArr['uEmail']            = $uEmail;
		$itArr['uDisplay_name']     = $thirdInfo['uDisplay_name'];
		$itArr['uImages']           = $thirdInfo['uImages'];
		$itArr['uProfile_url']      = $thirdInfo['uProfile_url'];
		$itArr['uLocalion']      	= $thirdInfo['uLocalion'];
		$itArr['uCode']             = $uCode;
		$this->addUserAuthenticationsInfo($itArr);
			
		//登录成功后，更新日志表和写$_COOKIES
		$ckArr['UserID'] = $UserID;
		$ckArr['uName']  = $uName;
		$this->setLoginSuccess($ckArr);
		
		//返回数据
		$rbArr['UserID'] = $UserID;
		$rbArr['uName']  = $uName;
		$rbArr['uEmail'] = $uEmail;
		$rbArr['uCode']  = $uCode;

		return $rbArr;
	}
	/**
	 * 增加第三方授权库信息
	 */
	private function addUserAuthenticationsInfo($fieldArr){
		$idata['UserID']            = $fieldArr['UserID'];
		$idata['uProvider']         = $fieldArr['uProvider'];
		$idata['uProvider_uid']     = $fieldArr['uProvider_uid'];
		$idata['uEmail']            = $fieldArr['uEmail'];
		$idata['uDisplay_name']     = $fieldArr['uDisplay_name'];
		$idata['uPermissions']      = $this->getPermission($fieldArr['uProvider']);
		$idata['uImages']           = $fieldArr['uImages'];
		$idata['uProfile_url']      = $fieldArr['uProfile_url'];
		$idata['uLocalion']    	    = $fieldArr['uLocalion'];
		$idata['uCreatedDateTime']  = time();
		$idata['uCode']             = $fieldArr['uCode'];
		$idata['uEstate']           = $fieldArr['uEstate'] ? $fieldArr['uEstate'] : -1;

		return $this->model->table($this->tbUserAuthenticationsInfo)->data($idata)->insert();
	}
	/**
	 * 构成授权表字段uPermissions为json格式
	 */
	private function getPermission($provider){
		if(empty($provider)){
			ComFun::throwMsg('Ex_NotNull304');
		}
		
		$apiArr = ComFun::getNowApi($provider);

		switch($apiArr['provider']['authway']){
			case 'auth1':
				$uPermissions = '"oauth_token":"'.ComFun::getCookies($provider.'_oauth_token').'"';
				$uPermissions .= ',"oauth_token_secret":"'.ComFun::getCookies($provider.'_oauth_token_secret').'"';
				break;
			case 'auth2':
				$uPermissions = '"refresh_token":"'.ComFun::getCookies($provider.'_refresh_token').'"';
				$uPermissions .= ',"access_token":"'.ComFun::getCookies($provider.'_access_token').'"';
				break;
			case 'openid':
				break;
		}

		$uPermissions .= ',"user_id":"'.ComFun::getCookies($provider.'_user_id').'"';
	
		return $this->Addslashes->get_addslashes('{'.$uPermissions.'}');
	}
	/**
	 * 做为第三方登录（情况2）：账户已经存在，则操作日志表并写$_COOKIES；不存在则返回-1
	 */
	public function IsExistUserInfo($UserID){
		$UserID = $this->Addslashes->get_addslashes($UserID);
		if(empty($UserID)){
			ComFun::throwMsg('Ex_RegistrError');
		}
		
		$condition = 'uEstate != 2 and UserID = \''.$UserID.'\'';
		
		$re = $this->model->table($this->tbUserInfo)->field('UserID,uName,uEmail')->where($condition)->select();
		
		if($re){			
			return $re[0];
		}else{
			return -1;
		}
	}
	/**
	 * 做为第三方登录（情况3）：若用户信息已删除，则默认增加用户信息
	 * 第二种情况：增加用户信息、更新第三方用户信息
	 */
	public function addNewUserInfoByThird($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		$uEmail = $fieldArr['uEmail'] ? $fieldArr['uEmail'] : '';
		$provider = ComFun::getCookies('provider');
		$uProvider_uid = ComFun::getCookies($provider.'_user_id');
		$OUserID = $fieldArr['UserID'];
		
		if(empty($OUserID)){
			ComFun::throwMsg('Ex_LostParam204');
		}
		if(empty($provider)){
			ComFun::throwMsg('Ex_NotNull304');
		}
		if(empty($uProvider_uid)){
			ComFun::throwMsg('Ex_NotNull306');
		}
		$thirdInfo = ComFun::getThirdInfoByGet('/db/getUserInfo',ComFun::getTConditionByCurl($provider));
		
		if(isset($thirdInfo['error'])){
			ComFun::throwMsg('Ex_ErrorSystem401');
		}
		
		$uName = $this->checkRepeatName($thirdInfo['uDisplay_name']);
		
		$uCode = ComFun::getRandom();

		//增加用户信息
		$tArr['uEmail']  = $uEmail;
		$tArr['uName']   = $uName;
		$tArr['uCode']   = $uCode;
		$tArr['uEstate'] = -2;
		
		$UserID = $this->addNewUser($tArr);
		
		if(empty($UserID)){
			ComFun::throwMsg('Ex_RegistrError');
		}
				
		//更新第三方信息
		$condition = 'uEstate != 2 and uProvider = \''.$provider.'\' and uProvider_uid = \''.$uProvider_uid.'\'';
		
		$udata['UserID']          = $UserID;
		$udata['uDisplay_name']   = $thirdInfo['uDisplay_name'];
		$udata['uImages']         = $thirdInfo['uImages'];
		$udata['uProfile_url']    = $thirdInfo['uProfile_url'];
		$udata['uLocalion']    	  = $thirdInfo['uLocalion'];
		$udata['uPermissions']    = $this->getPermission($provider);
		$udata['uCode']           = $uCode;
		$udata['uEstate']         = -1;
		$udata['OUserID']         = $fieldArr['OUserID'].','.$OUserID;

		$re = $this->model->table($this->tbUserAuthenticationsInfo)->data($udata)->where($condition)->update();
		
		//登录成功后，更新日志表和写$_COOKIES
		$ckArr['UserID'] = $UserID;
		$ckArr['uName']  = $uName;
		$this->setLoginSuccess($ckArr);
	}
	/**
	 * 关联账户（情况1：通过第三方登录）：若第三方信息不存在则增加第三方信息，若已存在则更新
	 */
	public function doUserInfoByThirdParty($fieldArr){
		$proNum = ComFun::getCookies('proNum');
		if(empty($proNum)){ return; }

		for($i=1;$i<=$proNum;$i++){
			$provider = ComFun::getCookies('provider_'.$i);
			$fieldArr['provider'] = $provider;
			
			$rbArr = $this->checkOAuthInfo($provider);
			
			if($rbArr != -1){
				$fieldArr['NUserID']  = $rbArr['UserID'];
				$fieldArr['OUserID']  = $rbArr['OUserID'];
				$this->reBandThirdInfo($fieldArr); 
			}else{
				$this->addThirdOauthInfo($fieldArr);
			}
		}	
	}
	/**
	 * 只增加第三方信息
	 * 绑定到指定帐号
	 */
	private function addThirdOauthInfo($fieldArr){
		$provider = $fieldArr['provider'];
		$uEmail   = $fieldArr['uEmail'];
		$uCode    = ComFun::getRandom();

		$thirdInfo = ComFun::getThirdInfoByGet('/db/getUserInfo',ComFun::getTConditionByCurl($provider));

		//增加第三方信息记录
		$itArr['UserID']        = $fieldArr['UserID'];
		$itArr['uProvider']     = $provider;
		$itArr['uProvider_uid'] = ComFun::getCookies($provider.'_user_id');
		$itArr['uEmail']        = $uEmail;
		$itArr['uDisplay_name'] = $thirdInfo['uDisplay_name'];
		$itArr['uImages']       = $thirdInfo['uImages'];
		$itArr['uProfile_url']  = $thirdInfo['uProfile_url'];
		$itArr['uLocalion']     = $thirdInfo['uLocalion'];
		$itArr['uCode']         = $uCode;
		$itArr['uEstate']       = -1;

		$this->addUserAuthenticationsInfo($itArr);
		
		//发邮件 
		if($uEmail){
			$emArr['uName']   = $fieldArr['uName'];
			$emArr['uEmail']  = $uEmail;
			$emArr['uCode']   = $uCode;
			$emArr['type']    = 'bandAccount';
			
			ComFun::toSendMail($emArr);
		}
	}
	/**
	 * 第三方信息已经存在，重新绑定第三方并更新原用户信息
	 * 重新绑定到指定帐号
	 */
	private function reBandThirdInfo($fieldArr){
		$provider      = $fieldArr['provider'];
		$uProvider_uid = ComFun::getCookies($provider.'_user_id');
		$uEmail        = $fieldArr['uEmail'];
		$UserID        = $fieldArr['UserID'];
		$NUserID       = $fieldArr['NUserID'];
		
		if(empty($uProvider_uid)){
			ComFun::throwMsg('Ex_NotNull306');
		}
		
		$thirdInfo = ComFun::getThirdInfoByGet('/db/getUserInfo',ComFun::getTConditionByCurl($provider));
		
		//更新用户信息
		$where = 'UserID = \''.$NUserID.'\'';
		
		$usdata['uEstate'] = 2;
		$usdata['OUserID'] = $UserID;
		
		$this->model->table($this->tbUserInfo)->data($usdata)->where($where)->update();
		
		//更新第三方信息
		$condition = 'uEstate != 2 and uProvider = \''.$provider.'\' and uProvider_uid = \''.$uProvider_uid.'\'';
		
		$uCode = ComFun::getRandom();
	
		$udata['UserID']          = $UserID;
		$udata['uEmail']          = $uEmail;
		$udata['uDisplay_name']   = $thirdInfo['uDisplay_name'];
		$udata['uImages']         = $thirdInfo['uImages'];
		$udata['uProfile_url']    = $thirdInfo['uProfile_url'];
		$udata['uLocalion']       = $thirdInfo['uLocalion'];
		$udata['uPermissions']    = $this->getPermission($provider);
		$udata['uCode']           = $uCode;
		$udata['uEstate']         = -2;
		$udata['OUserID']         = $fieldArr['OUserID'].','.$NUserID;

		$this->model->table($this->tbUserAuthenticationsInfo)->data($udata)->where($condition)->update();
		
		//发邮件
		if($uEmail){		
			$emArr['uName']   = $fieldArr['uName'];
			$emArr['uEmail']  = $uEmail;
			$emArr['uCode']   = $uCode;
			$emArr['type']    = 'reBandAccount';

			ComFun::toSendMail($emArr);
		}
	}
	/**
	 * 第三方成功登录后，更新第三方授权信息：只更新第三方信息表
	 */
	public function updateOauthThirdInfo($provider){
		$uProvider_uid = ComFun::getCookies($provider.'_user_id');
		
		if(empty($provider)){
			ComFun::throwMsg('Ex_NotNull304');
		}
		if(empty($uProvider_uid)){
			ComFun::throwMsg('Ex_NotNull306');
		}
		$thirdInfo = ComFun::getThirdInfoByGet('/db/getUserInfo',ComFun::getTConditionByCurl($provider));
		
		if(isset($thirdInfo['error'])){
			ComFun::throwMsg('Ex_ErrorSystem401');
		}
		
		//更新第三方信息
		$condition = 'uEstate != 2 and uProvider = \''.$provider.'\' and uProvider_uid = \''.$uProvider_uid.'\'';
		
		$udata['uDisplay_name']   = $thirdInfo['uDisplay_name'];
		$udata['uImages']         = $thirdInfo['uImages'];
		$udata['uProfile_url']    = $thirdInfo['uProfile_url'];
		$udata['uLocalion']       = $thirdInfo['uLocalion'];
		$udata['uPermissions']    = $this->getPermission($provider);
		
		$re = $this->model->table($this->tbUserAuthenticationsInfo)->data($udata)->where($condition)->update();	
	}
	
	/**
	 * 更新第三方授权值
	 */
	public function updateOauthThirdAccessInfo ( $provider ) {
		$uProvider_uid = ComFun::getCookies($provider.'_user_id');
		
		if(empty($provider)){
			return false;
		}
		if(empty($uProvider_uid)){
			return false;
		}
		
		//更新第三方信息
		$condition = 'uEstate != 2 and uProvider = \''.$provider.'\' and uProvider_uid = \''.$uProvider_uid.'\'';
	
		$udata['uPermissions']    = $this->getPermission($provider);
		
		$re = $this->model->table($this->tbUserAuthenticationsInfo)->data($udata)->where($condition)->update();
	}
	
	/**
	 * 检查用户名是否重复，若是则在用户名后加3个随机数
	 */
	public function checkRepeatName($uName){
		$condition['uName'] = $uName ? $uName : $this->default_username;
	
		if($this->model->table($this->tbUserInfo)->where($condition)->select()){
			return self::checkRepeatName($uName.mt_rand(100,999));
		}else{
			return $uName;
		}
	}
	/**
	 * 检测邮箱是否已绑定过第三方，若已绑定，则选出所有已绑定的第三方
	 */
	public function thirdBandedInfo($uEmail){	
		$condition = 'uEstate != 2 and uEmail = \''.$this->Addslashes->get_addslashes($uEmail).'\'';
	
		return $this->model->table($this->tbUserAuthenticationsInfo)->field('uProvider')->where($condition)->select();
	}
	/**
	 * 判断是否存在用户在线ID
	 */
	public function checkOnLineID(){
		$condition['UserID'] = ComFun::getCookies('UserID');
	
		$rbArr = $this->model->table($this->tbUserOnLineLogInfo)->field('UserOnLineLogID')->where($condition)->select();
		
		if($rbArr){
			return $rbArr[0]['UserOnLineLogID'];
		}else{
			return -1;
		}
	}
	/**
	 * 关联账户（情况2）通过邮箱登录：若第三方信息不存在则增加第三方信息，若已存在则更新
	 * 前提用户已经存在
	 */
	public function doUserInfoByEmail($userInfo,$authInfo){
		$provider = ComFun::getCookies('provider');
		$UserID = $userInfo['UserID'];
		$uName  = $userInfo['uName'];
		
		if(empty($provider)){
			ComFun::throwMsg('Ex_NotNull304');
		}

		$tArr['provider'] = $provider;
		$tArr['UserID']   = $UserID;
		$tArr['uName']    = $uName;
		$tArr['uEmail']   = $userInfo['uEmail'];
		
		if($authInfo != -1){ //第三方信息存在
			$tArr['NUserID']  = $authInfo['UserID'];
			$tArr['OUserID']  = $authInfo['OUserID'];

			$this->reBandThirdInfo($tArr);
		}else{ //第三方信息不存在					
			$this->addThirdOauthInfo($tArr);
		}
		
		//登录成功后，更新日志表和写$_COOKIES
		$ckArr['UserID'] = $UserID;
		$ckArr['uName']  = $uName;
		$this->setLoginSuccess($ckArr);
	}
	/**
	 * 关联账户（情况3）：邮箱未注册，且第三方信息不存在；则直接增加用户信息和第三方帐号信息
	 */
	public function addJoinAccountInfo($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);

		$rbArr = $this->addDireAccountInfo($fieldArr);

		//发邮件
		if($rbArr['uEmail']){
			$emArr['uName']   = $rbArr['uName'];
			$emArr['uEmail']  = $rbArr['uEmail'];
			$emArr['uCode']   = $rbArr['uCode'];
			$emArr['type']    = 'bandAccount';
				
			ComFun::toSendMail($emArr);
		}
	}
	/**
	 * 关联账户（情况4）：邮箱未注册，且第三方信息存在；则更新用户信息和第三方信息，并发邮件
	 */
	public function addAccountEmail($fieldArr){
		$uCode = ComFun::getRandom();
		
		//更新用户信息表
		$condition['UserID'] = $fieldArr['UserID'];
		
		$data['uEmail']   = $fieldArr['uEmail'];
		$data['uCode']    = $uCode;
		$data['uEstate']  = -1;
		
		$this->model->table($this->tbUserInfo)->data($data)->where($condition)->update();

		//更新第三方信息表
		$condition['uProvider']     = $fieldArr['provider'];
		$condition['uProvider_uid'] = ComFun::getCookies($fieldArr['provider'].'_user_id');
		
		$this->model->table($this->tbUserAuthenticationsInfo)->data($data)->where($condition)->update();

		//登录成功后，更新日志表和写$_COOKIES
		$ckArr['UserID'] = $fieldArr['UserID'];
		$ckArr['uName']  = $fieldArr['uName'];
		$this->setLoginSuccess($ckArr);
		
		//发邮件
		if($fieldArr['uEmail']){
			$emArr['uName']   = $fieldArr['uName'];
			$emArr['uEmail']  = $fieldArr['uEmail'];
			$emArr['uCode']   = $uCode;
			$emArr['type']    = 'bandAccount';
				
			ComFun::toSendMail($emArr);
		}
	}
	/**
	 * 直接增加第三方信息（个人中心绑定）
	 */
	public function dirjoinThird(){
		$provider = ComFun::getCookies('provider');
		$UserID = ComFun::getCookies('UserID');

		if(empty($provider)){
			ComFun::throwMsg('Ex_NotNull304');
		}
		if(empty($UserID)){
			ComFun::throwMsg('Ex_RegistrError');
		}

		$user = $this->getClass('User');
		$userInfo = $user->getUserInfo($UserID);

		if($userInfo == -1){
			ComFun::throwMsg('Ex_ErrorSystem402');
		}
		
		$uEmail  = $userInfo[0]['uEmail'];
		$uName   = $userInfo[0]['uName'];
		$uCode   = ComFun::getRandom();
		
		$thirdInfo = ComFun::getThirdInfoByGet('/db/getUserInfo',ComFun::getTConditionByCurl($provider));
		
		//增加第三方信息记录
		$itArr['UserID']        = $UserID;
		$itArr['uProvider']     = $provider;
		$itArr['uProvider_uid'] = ComFun::getCookies($provider.'_user_id');
		$itArr['uEmail']        = $uEmail;
		$itArr['uDisplay_name'] = $thirdInfo['uDisplay_name'];
		$itArr['uImages']       = $thirdInfo['uImages'];
		$itArr['uProfile_url']  = $thirdInfo['uProfile_url'];
		$itArr['uLocalion']    	= $thirdInfo['uLocalion'];
		$itArr['uCode']         = $uCode;
		$itArr['uEstate']       = -1;

		$this->addUserAuthenticationsInfo($itArr);
		
		//发邮件
		if($uEmail){
			$emArr['uName']   = $fieldArr['uName'];
			$emArr['uEmail']  = $uEmail;
			$emArr['uCode']   = $uCode;
			ComFun::toSendMail($emArr,'banding');
		}
	}
	/**
	 * 根据UserID登录验证通过后，进行日志记录和$_COOKIES操作
	 */
	public function setCookiesByUserID($UserID){
		$user = $this->getClass('User');
		$rbArr = $user->getUserInfo($UserID);
		
		if($rbArr != -1){
			//登录成功后，更新日志表和写$_COOKIES
			$ckArr['UserID'] = $rbArr[0]['UserID'];
			$ckArr['uName']  = $rbArr[0]['uName'];
			$this->setLoginSuccess($ckArr);
		}
	}
	/**
	 * 操作日志表和$_COOKIES
	 */
	public function setSuccessLog($fieldArr){
		//登录成功后，更新日志表和写$_COOKIES
		$ckArr['UserID'] = $fieldArr['UserID'];
		$ckArr['uName']  = $fieldArr['uName'];
	
		$this->setLoginSuccess($ckArr);
	}
	/**
	 * 登录成功后，更新日志表和写$_COOKIES
	 */
	private function setLoginSuccess($fieldArr){
		if(empty($fieldArr['UserID'])){
			ComFun::throwMsg('Ex_LostParam204');
		}
		if(empty($fieldArr['uName'])){
			ComFun::throwMsg('Ex_LostParam205');
		}
		
		//操作log表
		$this->updateUserInfoLog($fieldArr);
		$this->updateUserMonthLog($fieldArr);
		$this->updateUserOnlineLog($fieldArr);
		
		//记录$_COOKIE
		ComFun::setCookies($fieldArr);
	}
	//======================以下方法是操作Log表==================
	/**
	 * 更新系统动态数据：包括用户在线信息、用户登录失败次数
	 */
	public function updateSysDate($fieldArr){
		$eTimeOnLineLog = time() - $fieldArr['ONLINE'];
	
		$condition = 'oLastTime < \''.$eTimeOnLineLog.'\'';
	
		$rbArr = $this->model->table($this->tbUserOnLineLogInfo)->where($condition)->select();
	
		if($rbArr){
			foreach($rbArr as $ke=>$va){
				if($va['UserID']){
					$tArr['UserID']        = $va['UserID'];
					$tArr['uName']         = $va['oUserName'];
					$tArr['oAppendTime']   = $va['oAppendTime'];
					$tArr['oIP']           = $va['oIP'];
						
					$this->updateLoginOutLog($tArr);
				}
			}
				
			$this->model->table($this->tbUserOnLineLogInfo)->where($condition)->delete();
		}
	
		//删除登录失败的IP记录
		$user = $this->getClass('User');
		$mandOAuthlog = $this->getClass('MandOAuthLog');
		$mandOAuthlog->delUserFailLoginin($fieldArr['FAILlOGIN']);
	}
	/**
	 * 登出或过期日志表操作
	 */
	private function doLoginOutLog($UserID){
		$condition['UserID'] = $UserID;
			
		$reArr = $this->model->table($this->tbUserOnLineLogInfo)->where($condition)->select();

		if($reArr){
			$tArr['UserID']        = $reArr[0]['UserID'];
			$tArr['uName']         = $reArr[0]['oUserName'];
			$tArr['oAppendTime']   = $reArr[0]['oAppendTime'];	
			$tArr['oIP']           = $reArr[0]['oIP'];

			$this->updateLoginOutLog($tArr);
			
			$this->model->table($this->tbUserOnLineLogInfo)->where($condition)->delete();
		}
	}
	/**
	 * 登出或过期表的具体操作
	 */
	private function updateLoginOutLog($fieldArr){
		$tArr['ident']         = 'loginOut';
		$tArr['UserID']        = $fieldArr['UserID'];
		$tArr['uName']         = $fieldArr['oUserName'];
		$tArr['totaltime']     = (time()-$fieldArr['oAppendTime'])/24/3600;
	
		$this->updateUserMonthLog($tArr);
		
		$tArr['uLastIP']       = $fieldArr['oIP'];
		$tArr['uUpAppendTime'] = $fieldArr['oAppendTime'];
		
		$this->updateUserInfoLog($tArr);
	} 
	/**
	 * 用户每次动作，更新此次的时间
	 */
	public function updateLastTime(){
		$tArr['UserID'] = ComFun::getCookies('UserID');
		$tArr['uName']  = ComFun::getCookies('uName');
	
		$this->updateUserInfoLog($tArr);
		$this->updateUserMonthLog($tArr);
		$this->updateUserOnlineLog($tArr);
	}
	/**
	 * 更新用户登录累计信息
	 */
	private function updateUserInfoLog($fieldArr){
		$UserID     = $fieldArr['UserID'];
		$totaltime  = $fieldArr['totaltime'] ? $fieldArr['totaltime'] : 0 ;
		
		$condition['UserID'] = $UserID;
		
		$rbArr = $this->model->table($this->tbUserLoginInfo)->where($condition)->select();
		
		if($rbArr){
			$udata['uLastActivity'] = time();
			
			//登出或过期需更新的数据
			if($fieldArr['ident'] == 'loginOut'){ 
				$udata['olTime']        = $rbArr[0]['olTime'] + $totaltime;
				$udata['uUpAppendTime'] = $fieldArr['uUpAppendTime'];
				$udata['uLastIP']       = $fieldArr['uLastIP'];
			}
			
			$this->model->table($this->tbUserLoginInfo)->data($udata)->where($condition)->update();
		}else{
			$idata['UserID']        = $UserID;
			$idata['uUpAppendTime'] = time();
			$idata['uLastActivity'] = time();
			$idata['uLastIP']       = ComFun::getIP();
			$idata['uRegIP']        = ComFun::getIP();
			$idata['olTime']        = $totaltime;
				
			return $this->model->table($this->tbUserLoginInfo)->data($idata)->insert();
		}
	}
	/**
	 * 更新每月用户累计信息
	 */
	private function updateUserMonthLog($fieldArr){
		$UserID     = $fieldArr['UserID'];
		$totaltime  = $fieldArr['totaltime'] ? $fieldArr['totaltime'] : 0 ;
		$year       = date('Y');
		$month      = date('m');		
		
		$condition['UserID']    = $UserID;
		$condition['thisyear']  = $year;
		$condition['thismonth'] = $month;
		
		$rbArr = $this->model->table($this->tbUserOnLineTime)->where($condition)->select();
		
		if($rbArr){
			$udata['lastupdate'] = time();
			
			//登出或过期需更新的数据
			if($fieldArr['ident'] == 'loginOut'){
				$udata['total'] = $rbArr[0]['total'] + $totaltime;
			}
			
			$this->model->table($this->tbUserOnLineTime)->data($udata)->where($condition)->update();
		}else{		
			$idata['UserID']      = $UserID;
			$idata['thisyear']    = $year;
			$idata['thismonth']   = $month;
			$idata['total']       = $totaltime;
			$idata['lastupdate']  = time();
			
			return $this->model->table($this->tbUserOnLineTime)->data($idata)->insert();
		}
	}
	/**
	 * 更新在线信息表
	 */
	private function updateUserOnlineLog($fieldArr){
		$UserID = $fieldArr['UserID'];
		
		$condition['UserID'] = $UserID;
		
		$rbArr = $this->model->table($this->tbUserOnLineLogInfo)->where($condition)->select();
		
		if($rbArr){
			$udata['oLastTime'] = time();
			$udata['oCode']     = $_COOKIE['PHPSESSID'];
		
			$this->model->table($this->tbUserOnLineLogInfo)->data($udata)->where($condition)->update();			
		}else{
			$idata['UserID']       = $UserID;
			$idata['oIP']          = ComFun::getIP();
			$idata['oUserName']    = $fieldArr['uName'] ? $fieldArr['uName'] : '游客';
			$idata['UserGroupsID'] = $fieldArr['UserGroupsID'] ? $fieldArr['UserGroupsID'] : 0;
			$idata['oAppendTime']  = time();
			$idata['oLastTime']    = time();
			$idata['oCode']        = $_COOKIE['PHPSESSID'];
		
			$UserOnLineLogID = $this->model->table($this->tbUserOnLineLogInfo)->data($idata)->insert();
		}
	}
	
	/**
	 * 取用户指定第三方相关信息
	 */
	public function getThirdInfoByUserIDAndProvider ( $UserID, $uProvider ) {
		$da = array();
		try {
			$condition['UserID']    = $UserID;
			$condition['uProvider'] = $uProvider;
				
			return $this->model->table($this->tbUserAuthenticationsInfo)->field('OUserID')->where($condition)->select();
		} catch ( Exception $e ) {
			return $da;
		}
	}
	
	/**
	 * 解除绑定（个人中心）
	 */
	public function doUnbinding($fieldArr){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);

			$condition['UserID']    = ComFun::getCookies('UserID');
			$condition['uProvider'] = $fieldArr['partner'];
			
			$re = $this->getThirdInfoByUserIDAndProvider($condition['UserID'], $condition['uProvider']);
			
			if ( $re ) {
				foreach ( $re as $k => $v ) {
					if ( $v['OUserID'] ) {
						$OUserID = explode(',', substr($v['OUserID'], 1));
						if ( $OUserID ) {
							foreach ( $OUserID as $v2 ) {
								$this->model->table($this->tbUserInfo)->where('UserID = ' . $v2)->delete();
							}
						}
					}
				}
			}
			
			$this->model->table($this->tbUserAuthenticationsInfo)->where($condition)->delete();
			
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 解除绑定（邮件激活）
	 */
	public function doUnbindingViaMail($fieldArr){
		try{
			$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
	
			$condition['uEmail']  = $fieldArr['uEmail'];
			$condition['uCode']   = $fieldArr['uCode'];
			
			$tArr['urlTurn'] = '/main/index';
			
			if($this->model->table($this->tbUserAuthenticationsInfo)->field('UserAuthenticationsID')->where($condition)->select()){
				$condition2['uEstate'] = 0;
				$condition2['uEmail']  = $fieldArr['uEmail'];
				$condition2['uCode']   = $fieldArr['uCode'];
				if($this->model->table($this->tbUserAuthenticationsInfo)->field('UserAuthenticationsID')->where($condition2)->select()){
					ComFun::throwMsg('Ex_ErrorSystem406',$tArr);
				}else{
					$this->model->table($this->tbUserAuthenticationsInfo)->where($condition)->delete();
					ComFun::throwMsg('Ex_Succuss101',$tArr);
				}		
			}else{			
				ComFun::throwMsg('Ex_ErrorSystem411', $tArr);
			}				
		}catch(Exception $e){
			ComFun::throwMsg('Ex_ErrorSystem411', $tArr);
		}
	}
	//=================邮箱激活处理==========
	/**
	 * 邮箱激活
	 * return 1为激活成功，2为解绑成功，3为找回密码
	 */
	public function doActivate($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$deArr = ComFun::_decodeArr($fieldArr['data']);

		$uEmail = $deArr['uEmail'];
		$uCode  = $deArr['uCode'];
		$type   = strtolower($deArr['type']);

		$condition['uEmail']   = $uEmail;
		$condition['uCode']    = $uCode;

		switch($type){
			case 'register':  //注册激活
				$ckArr['uCode'] = $uCode;
				$rbArr = $this->IsUserActiveCodeByUserInfo($ckArr);
				$udata['uEstate']  = 0;
				$reUpdate = $this->model->table($this->tbUserInfo)->data($udata)->where($condition)->update();
				
				$record['type'] = 1;
				break;
			case 'bandaccounts': //成功绑定
				$ckArr['uCode'] = $uCode;
				
				$rbArr = $this->IsUserActiveCodeByUserOauthInfo($ckArr);
				$udata['uEstate'] = 0;
				$this->model->table($this->tbUserInfo)->data($udata)->where($condition)->update();
				$this->model->table($this->tbUserAuthenticationsInfo)->data($udata)->where($condition)->update();
				
				$record['type'] = 'turnToMain';
				$record['type'] = 1;
				break;
			case 'bandaccountf': //取消绑定			
				$this->doUnbindingViaMail($condition);
				exit;
				
				/*=====旧的方法
				$ckArr['uCode'] = $uCode;
				$rbArr = $this->IsUserActiveCodeByUserInfo($ckArr);
				$udata['uEstate'] = 2;
				$this->model->table($this->tbUserInfo)->data($udata)->where($condition)->update();
				$this->model->table($this->tbUserAuthenticationsInfo)->data($udata)->where($condition)->update();
				
				$record['type'] = 2;
				*/
				break;
			case 'rebandaccounts': //成功重新解绑
				$ckArr['uCode'] = $uCode;
				$rbArr = $this->IsUserActiveCodeByUserOauthInfo($ckArr);
				$udata['uEstate']  = 0;
				$this->model->table($this->tbUserAuthenticationsInfo)->data($udata)->where($condition)->update();
				
				$record['type'] = 1;
				break;
			case 'rebandaccountf': //取消重新解绑
				$rbArr = $this->model->table($this->tbUserAuthenticationsInfo)->where($condition)->select();

				if($rbArr){
					$OUserID = $rbArr[0]['OUserID'];

					if($OUserID){
						$idArr = array_reverse(explode(',', $OUserID));
						
						$udata['uEmail']  = '';
						$udata['UserID']  = $idArr[0];
						$udata['uEstate'] = -1;
						$udata['OUserID'] = '';
						
						$this->model->table($this->tbUserAuthenticationsInfo)->data($udata)->where($condition)->update();
					
						$condition2['UserID'] = $idArr[0];
						$udata2['uEstate'] = -2;
						$udata2['OUserID'] = 0;
						
						$this->model->table($this->tbUserInfo)->data($udata2)->where($condition2)->update();
					}else{
						ComFun::throwMsg('Ex_ErrorSystem404');
					}
				}else{
					ComFun::throwMsg('Ex_ErrorSystem405');
				}
		
				$record['type'] = 2;
				break;
			case 'retakepwd': //取回密码
				$record['uEmail'] = $deArr['uEmail'];
				$record['type']   = $type;
				$record['uCode']  = $uCode;
				break;
			case 'authaccount':
				$tArr['uSafeEmail']   = $uEmail;
				$tArr['uEmailCode']   = $uCode;
				
				$modifyProfile = $this->getClass('ModifyProfile');
				//ComFun::pr($tArr);exit;
				$record['type']   = $type;
				$record['status']  = $modifyProfile->updateSafeEmail($tArr);
				break;
			default: //默认方式
				$record['type']         = $type;
				$record['inviteCode']   = $uCode;	
				break;
		}
		
		return $record;
	}
	/**
	 * 激活码是否已经使用过(用户信息表的激活码)
	 */
	private function IsUserActiveCodeByUserInfo($fieldArr){
		$rbArr = $this->model->table($this->tbUserInfo)->field('uCode,uEstate')->where($fieldArr)->select();
		if($rbArr){
			switch($rbArr[0]['uEstate']){
				case 0:
					ComFun::throwMsg('Ex_ErrorSystem406');
					break;
				case 2:
					ComFun::throwMsg('Ex_ErrorSystem407');
					break;
			}
		}else{
			ComFun::throwMsg('Ex_ErrorSystem405');
		}
	}
	/**
	 * 激活码是否已经使用过(第三方帐号信息的激活码)
	 */
	private function IsUserActiveCodeByUserOauthInfo($fieldArr){
		$rbArr = $this->model->table($this->tbUserAuthenticationsInfo)->field('uCode,uEstate')->where($fieldArr)->select();
		if($rbArr){
			switch($rbArr[0]['uEstate']){
				case 0:
					ComFun::throwMsg('Ex_ErrorSystem406');
					break;
				case 2:
					ComFun::throwMsg('Ex_ErrorSystem408');
					break;
			}
		}else{
			ComFun::throwMsg('Ex_ErrorSystem405');
		}
	}
	/**
	 * 重置密码，并写$_COOKIES
	 * 通过邮件重置密码
	 */
	public function doResetPwdByEmail($fieldArr){
		$uEmail = $fieldArr['uEmail'];
		$user = $this->getClass('User');
		$rbArr = $user->getEmailUserID($uEmail);
		
		if($rbArr != -1){
			$condition['uEmail'] = $uEmail;		
			$udata['uPWD'] = md5(trim($fieldArr['pwd']));
		
			$this->model->table($this->tbUserInfo)->data($udata)->where($condition)->update();
			
			$ckArr['UserID'] = $rbArr['UserID'];
			$ckArr['uName']  = $rbArr['uName'];
			$this->setLoginSuccess($ckArr);
			
			return 1;
		}else{
			return -1;
		}
	}
	/**
	 * 重置密码
	 * 在个人中心页面重置密码
	 */
	public function doResetPwdByCenter($fieldArr){
		$condition['UserID'] = $fieldArr['UserID'];

		$udata['uPWD'] = md5(trim($fieldArr['uPWD']));
		
		$this->model->table($this->tbUserInfo)->data($udata)->where($condition)->update();
		
		echo 1;
	}
	/**
	 * 检验原始密码是否输入有误
	 */
	public function checkOrigPwd($fieldArr){
		try{
			$condition['UserID'] = $fieldArr['UserID'];
			$condition['uPWD'] = md5(trim($fieldArr['uPWD']));
			
			if($this->model->table($this->tbUserInfo)->field('UserID')->where($condition)->select()){
				return 1;
			}else{
				return -1;
			}
		}catch(Exception $e){
			return -1;
		}
	}
	/**
	 * 删除用户在线信息
	 */
	public function delOnLineID($UserID){
		if($UserID){
			$condition['UserID'] = $UserID;
			
			$this->model->table($this->tbUserOnLineLogInfo)->where($condition)->delete();
 		}	
	}
	
	
	//======================以下方法是根据需求后加==================
	/**
	 * 取用户第三方授权信息
	 */
	public function getPartnerAuthInfoByID ( $fieldArr ) {
		$_da = '';
		try {
			$_where = 'uEstate != 2';
			$_where .= ' and UserID = ' . $fieldArr['UserID'];
			$_where .= ' and uProvider = \'' . $fieldArr['uProvider'] . '\'';
			
			$_re = $this->model->table($this->tbUserAuthenticationsInfo)->field('uPermissions')->where($_where)->select();
			
			if ( $_re ) {
				if ( $_re[0]['uPermissions'] ) {
					return json_decode($_re[0]['uPermissions'], true);
				} else {
					return $_da;
				}
			} else {
				return $_da;
			}
		} catch ( Exception $e ) {
			return $_da;
		}
	}
	
	
	/**
	 * 取得类
	 */
	private function getClass($className,$fieldArr=Null){
		switch($className){
			case 'User':
				include_once('User.class.php');
				return new User($this->model);
				break;
			case 'MandOAuthLog':
				include_once('MandOAuthLog.class.php');
				return new MandOAuthLog($this->model);
				break;
			case 'GetUserInfo':
				include_once(dirname(dirname(dirname(__FILE__))).'/include/ext/partner/common/GetUserInfo.php');
				return new GetUserInfo($fieldArr['partner'],$fieldArr['provider'],$fieldArr['OAuthArr']);
				break;
			case 'ModifyProfile':
				include_once('ModifyProfile.class.php');
				return new ModifyProfile($this->model);
				break;
			default:
				break;
		}
	}
}
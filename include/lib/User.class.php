<?php 
/**
 * 处理用户信息类
 * 
 * @author wbqing405@sina.com
 */

include_once('Config.class.php'); //引入处理类的编码格式 utf-8
include_once('Addslashes.class.php'); //数据过滤类
include_once('ComFun.class.php'); //公共方法

class User{
	
	var $tbUserInfo = 'tbUserInfo'; //用户基础信息表
	
	var $tbUserDetInfo = 'tbUserDetInfo'; //用户详细信息
	
	var $tbUserLoginInfo = 'tbUserLoginInfo'; //用户登录累计信息
	
	var $tbUserOnLineLogInfo = 'tbUserOnLineLogInfo';  //用户在线记录信息 
	
	var $tbUserAuthenticationsInfo = 'tbUserAuthenticationsInfo'; //第三方平台登录信息 
	
	var $tbUserHeadInfo = 'tbUserHeadInfo'; //用户头像信息 
	
	var $tbUserNickNameLogInfo = 'tbUserNickNameLogInfo'; //用户昵称休息记录表
	
	var $tbUserPassWordLogInfo = 'tbUserPassWordLogInfo'; //用户密码休息记录表
	
	var $tbUserEmailLogInfo = 'tbUserEmailLogInfo'; //用户邮箱休息记录表
	
	public function __construct($base, $config=array()){
		$this->model = $base;	

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
	 * 获取当前用户UserID的信息
	 */
	public function getUserInfo($UserID){
		$this->UserID = $this->Addslashes->get_addslashes($UserID);
		
		$condition = 'uEstate != 2 and UserID = \''.$this->UserID.'\'';
		
		$re = $this->model->table($this->tbUserInfo)->where($condition)->select();

		if($re){
			return $re;		
		}else{
			return -1;
		}	
	}
	
	/**
	 * 获取当前用户UserID的信息
	 */
	public function getUserInfoNew ($UserID) {
		$result = array();
		try {
			$condition = 'uEstate != 2 and UserID = \'' . $UserID . '\'';
			
			$re = $this->model->table($this->tbUserInfo)->where($condition)->select();
			
			if ( $re ) {
				$result = $re[0];
			}
		} catch (Exception $e) {
			
		}
		
		return $result;
	}
	
	/**
	 * 通过用户ID获取用户名
	 */
	public function getUserNameByID($UserID){
		$this->UserID = $this->Addslashes->get_addslashes($UserID);
		
		$condition = 'uEstate != 2 and UserID = \''.$this->UserID.'\'';
		
		$re = $this->model->table($this->tbUserInfo)->field('uName')->where($condition)->select();
		
		if($re){
			return $re[0]['uName'];
		}else{
			return false;
		}
	}
	/**
	 * 通过用户ID获取用户名
	 */
	public function getUserNameListByID($ListID){
		try{
			$where = 'uEstate != 2 and UserID in ('.$ListID.')';
			
			$re = $this->model->table($this->tbUserInfo)->field('uName')->where($where)->select();
			
			return $re;
		}catch(Exception $e){
			return '';
		}	
	}
	/**
	 * 返回邮箱所属用户的UserID
	 */
	public function getEmailUserID($uEmail){
		$this->uEmail = $this->Addslashes->get_addslashes($uEmail);
		
		//$condition['uEmail'] = $this->uEmail;		
		$condition = 'uEstate != 2 and uEmail = \''.$this->uEmail.'\'';
		
		$rbArr = $this->model->table($this->tbUserInfo)->field('UserID,uEmail,uName,uPWD')->where($condition)->select();
		
		if($rbArr){
			return $rbArr[0];
		}else{
			return -1;
		}
	}
	
	/**
	 * 返回邮箱所属用户的UserID
	 */
	public function getEmailUserIDNew($uEmail){
		$this->uEmail = $this->Addslashes->get_addslashes($uEmail);
	
		//$condition['uEmail'] = $this->uEmail;
		$condition = 'uEstate != 2 and uEmail = \''.$this->uEmail.'\'';
	
		$rbArr = $this->model->table($this->tbUserInfo)->field('UserID,uEmail,uName,uPWD')->where($condition)->select();
	
		if($rbArr){
			return $rbArr[0];
		}else{
			return array();
		}
	}
	
	/**
	 * 返回邮箱所属用户的UserID
	 */
	public function getUserInfoByUserID($UserID){
		$this->UserID = $this->Addslashes->get_addslashes($UserID);
	
		$condition = 'uEstate != 2 and UserID = \''.$this->UserID.'\'';
	
		$rbArr = $this->model->table($this->tbUserInfo)->field('UserID,uEmail,uName,uPWD')->where($condition)->select();
	
		if($rbArr){
			return $rbArr[0];
		}else{
			return -1;
		}
	}
	/**
	 * 通过用户名获取用户ID
	 */
	public function getUserIDByUserName($uName){
		$where = 'uEstate != 2 and uName = \''.$uName.'\'';

		$re = $this->model->table($this->tbUserInfo)->field('UserID')->where($where)->select();
	
		if($re){
			return $re[0]['UserID'];
		}else{
			return -1;
		}
	}
	/**
	 * 检查邮箱的唯一性
	 */
	public function doCheckEmail($uEmail){
		$uEmail = $this->Addslashes->get_addslashes($uEmail);
	
		$condition = 'uEstate != 2 and uEmail = \''.$uEmail.'\'';
	
		$rbArr = $this->model->table($this->tbUserInfo)->field('UserID,uEmail')->where($condition)->select();
	
		if($rbArr){
			return $rbArr[0]['UserID'];
		}else{
			return -1;
		}
	}
	/**
	 * 根据所传条件检验用户记录是否存在
	 */
	public function checkUserInfo($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$condition = 'uEstate != 2 and UserID = \''.$fieldArr['UserID'].'\'';
		
		$re = $this->model->table($this->tbUserInfo)->where($condition)->select();
		if($re){
			return $re[0]['UserID'];
		}else{
			return -1;
		}
	}
	/**
	 * 检查登录
	 */
	public function checkLogin($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$condition = 'uEstate != 2 and uEmail = \''.$fieldArr['uEmail'].'\' and uPWD = \''.md5(trim($fieldArr['uPWD'])).'\'';

		$userInfo = $this->model->table($this->tbUserInfo)->field('UserID')->where($condition)->select();

		if($userInfo){
			return $userInfo[0]['UserID'];
		}else{
			return -1;
		}
	}
	/**
	 * 验证登录
	 */
	public function checkBeforeLogin($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$condition = 'uEstate != 2 and uEmail = \''.$fieldArr['uEmail'].'\' and uPWD = \''.md5(trim($fieldArr['uPWD'])).'\'';

		$userInfo = $this->model->table($this->tbUserInfo)->field('UserID,uName')->where($condition)->select();
		
		if($userInfo){
			return $userInfo[0];
		}else{
			return -1;
		}
	}
	/**
	 * 返回用户的个人简介信息
	 */
	public function getProfile($UserID=false){	
		if($UserID){
			$this->UserID = $UserID;
		}else{
			$this->UserID = ComFun::getCookies('UserID');
		}
		
		$sql = 'select a.uName,a.uEmail,a.uEstate,b.uComeFrom,b.uSex,b.uBirthday from '.$this->tbUserInfo.' a left join '.$this->tbUserDetInfo.' b on a.UserID = b.UserID where a.uEstate != 2 and a.UserID = '.$this->UserID;

		$userInfo = $this->model->query($sql);
		
		return $userInfo[0];
	}
	
	/**
	 * 保存账号邮箱
	 */
	public function modifyContactEmail($params){
		$this->params = $this->Addslashes->get_addslashes($params);
		
		$this->UserID = ComFun::getCookies('UserID');
		
		if(!$this->UserID){
			return -1;
		}
		
		$condition['UserID'] = $this->UserID;
		
		$this->uEmail      = $this->params['uEmail'];
		$this->code        = ComFun::getRandom();
		
		$data['uEmail']  = $this->uEmail;
		$data['uCode']   = $this->code;
		
		$lRe = $this->model->table($this->tbUserInfo)->field('uEmail')->where($condition)->select();
		if($lRe){
			$ldata['UserID']      = $this->UserID;
			$ldata['uEmail']      = $lRe[0]['uEmail'];
			$ldata['uAppendTime'] = time();
			
			$this->model->table($this->tbUserEmailLogInfo)->data($ldata)->insert();	
		}
		
		$this->model->table($this->tbUserInfo)->data($data)->where($condition)->update();
		
		$this->model->table($this->tbUserAuthenticationsInfo)->data($data)->where($condition)->update();
		
		$cookies['UserID'] = $this->UserID;
		ComFun::setCookies($cookies);
		
		if($this->uEmail){
			$emArr['uName']   = $this->uName;
			$emArr['uEmail']  = $this->uEmail;
			$emArr['uCode']   = $this->code;
			$emArr['type']    = 'register';
			
			ComFun::toSendMail($emArr);
		}	
	}
	
	/**
	 * 保存账号邮箱，账号邮箱已存在，更新新绑定，并更新新账号的状态，并用旧账号
	 */
	public function updateContactEmail($params){
		$this->params = $this->Addslashes->get_addslashes($params);
		
		$this->NUserID = ComFun::getCookies('UserID');
		
		if(!$this->NUserID){
			return -1;
		}
		
		$this->code        = ComFun::getRandom();
		
		$this->UserID  = $this->params['UserID'];
		$this->uEmail  = $this->params['uEmail'];	
		
		$udate['uEstate'] = 2;
		$udate['OUserID'] = $this->UserID;
		$udate['uCode']   = $this->code;
		
		$uAdate['uEmail'] = $this->uEmail;
		$uAdate['UserID'] = $this->UserID;
		$uAdate['uCode']  = $this->code;
		
		$condition['UserID'] = $this->NUserID;
		
		$this->model->table($this->tbUserInfo)->data($udate)->where($condition)->update();
		$this->model->table($this->tbUserAuthenticationsInfo)->data($uAdate)->where($condition)->update();
		
		if($this->uEmail){	
			$emArr['uName']   = $this->uName;
			$emArr['uEmail']  = $this->uEmail;
			$emArr['uCode']   = $this->code;
			$emArr['type']    = 'register';
			
			ComFun::toSendMail($emArr);
		}
		
	}
	
	/**
	 * 取得用户社交信息
	 */
	public function getSocial($UserID=false){
		if($UserID){
			$this->UserID = $UserID;
		}else{
			$this->UserID = ComFun::getCookies('UserID');
		}

		$sql = 'select UserAuthenticationsID,uProvider,uEmail,uDisplay_name from '.$this->tbUserAuthenticationsInfo.' where uEstate != 2 and UserID = '.$this->UserID;
		
		return $this->model->query($sql);
	}
	/**
	 * 保存个人基础信息
	 */
	public function modifyBaseInfo($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$UserID = ComFun::getCookies('UserID');
		$condition['UserID'] = $UserID;
		
		$type = $fieldArr['type'];
		
		if($type == 'uName' &&  $fieldArr['tValue']){					
			if($this->docheckUserName($fieldArr['tValue']) == 1){
				return -1;
			}
			
			$this->modifyNickName($UserID);
			
			$data['uName']      = $fieldArr['tValue'];		
		}

		$this->model->table($this->tbUserInfo)->data($data)->where($condition)->update();
	}
	/**
	 * 检查用户名是否重复
	 */
	public function docheckUserName($uName){
		$uName = $this->Addslashes->get_addslashes($uName);
		
		$condition = 'uEstate != 2 and uName = \''.$uName.'\'';
		
		$re = $this->model->table($this->tbUserInfo)->field('UserID,uName')->where($condition)->select();
		
		if($re){
			return $re[0]['UserID'];
		}else{
			return -1;
		}
	}
	/**
	 * 通过用户名返回指定用户信息
	 */
	public function getUserInfoByName($uName){
		$uName = $this->Addslashes->get_addslashes($uName);
		
		$condition = 'uEstate != 2 and uName = \''.$uName.'\'';
		
		return $this->model->table($this->tbUserInfo)->where($condition)->select();
	}
	/**
	 * 昵称修改记录
	 */
	public function modifyNickName($UserID){
		$condition['UserID'] = $UserID;
		
		$re = $this->model->table($this->tbUserInfo)->field('UserID,uName')->where($condition)->select();
		
		if($re){
			$ldata['UserID']      = $UserID;
			$ldata['uNickName']   = $re[0]['uName'];
			$ldata['uAppendTime'] = time();
				
			$this->model->table($this->tbUserNickNameLogInfo)->data($ldata)->insert();
		}
	}
	/**
	 * 保存昵称
	 */
	public function saveNickName($fieldArr){
		try{
			$where = 'UserID != \''.$fieldArr['UserID'].'\' and uName = \''.$fieldArr['uName'].'\'';
			
			if( !$this->model->table($this->tbUserInfo)->field('UserID')->where($where)->select() ){
				$condition['UserID'] = $fieldArr['UserID'];
				$data['uName'] = $fieldArr['uName'];	
				
				$this->model->table($this->tbUserInfo)->data($data)->where($condition)->update();
				
				return 1;
			}else{
				return -2;
			}
		}catch(Exception $e){
			return -1;
		}
	}
	/**
	 * 保存个人常规信息
	 */
	public function modifyCommon($fieldArr){
		try{
			$condition['UserID'] = $fieldArr['UserID'];
			
			$data['uSex']      = $fieldArr['uSex'];
			$data['uBirthday'] = $fieldArr['uBirthday'];
			
			if( $this->model->table($this->tbUserDetInfo)->field('UserID')->where($condition)->select() ){
				$this->model->table($this->tbUserDetInfo)->data($data)->where($condition)->update();
			}else{
				$data['UserID'] = $fieldArr['UserID'];
				
				$this->model->table($this->tbUserDetInfo)->data($data)->insert();
			}
			
			return 1;
		}catch(Exception $e){
			return -1;
		}
	}
	/**
	 * 修改时间
	 */
	public function doChangeTime($fieldArr=''){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);

		$TimeConfig = $this->getClass('TimeConfig');
		
		switch($fieldArr['type']){
			case 'year':
				return $TimeConfig->getYearConfig($fieldArr['name'],$fieldArr);
				break;
			case 'month':
				return $TimeConfig->getMonthConfig($fieldArr['name'],$fieldArr);
				break;				
			case 'day':
				return $TimeConfig->getDayConfig($fieldArr['name'],$fieldArr);
				break;
			default:
				return $TimeConfig->getDefaultConfig($fieldArr['name']);
				break;
		}
	}
	/**
	 * 保存修改的出生时间
	 */
	/*
	public function saveChangeTime($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);

		if($fieldArr['year']){
			$year = $fieldArr['year'];
		}else{
			$year = date('Y');
		}
		
		if($fieldArr['month'] != 0){
			$month = $fieldArr['month'];
		}else{
			$month = 1;
		}
		
		if($fieldArr['day'] != 0){
			$day = $fieldArr['day'];
		}else{
			$day = 1;
		}
		
		$date = $year.'-'.$month.'-'.$day;
		
		$date = strtotime($date);
		
		$UserID = ComFun::getCookies('UserID');
		$condition['UserID'] = $UserID;
		$re = $this->model->table($this->tbUserDetInfo)->where($condition)->select();

		if($re){
			$udata['uBirthday'] = $date;
			
			$this->model->table($this->tbUserDetInfo)->data($udata)->where($condition)->update();
		}else{
			$idata['UserID']    = $UserID;
			$idata['uBirthday'] = $date;
			
			$this->model->table($this->tbUserDetInfo)->data($idata)->insert();
		}
	}
	*/
	/**
	 * 保存邮箱
	 */
	public function saveEmail($fieldArr){
		try{
			$where = 'UserID != \''.$fieldArr['UserID'].'\' and uEmail = \''.$fieldArr['uEmail'].'\'';
				
			if( !$this->model->table($this->tbUserInfo)->field('UserID')->where($where)->select() ){
				$condition['UserID'] = $fieldArr['UserID'];
				$data['uEmail'] = $fieldArr['uEmail'];
	
				$this->model->table($this->tbUserInfo)->data($data)->where($condition)->update();
	
				return 1;
			}else{
				return -2;
			}
		}catch(Exception $e){
			return -1;
		}
	}
	/**
	 * 保存个人联系信息
	 */
	public function modifyContact($fieldArr){
		try{
			$condition['UserID'] = $fieldArr['UserID'];

			$data['uComeFrom'] = $fieldArr['uComeFrom'];
			
			if( $this->model->table($this->tbUserDetInfo)->field('UserID')->where($condition)->select() ){
				$this->model->table($this->tbUserDetInfo)->data($data)->where($condition)->update();
			}else{
				$data['UserID'] = $fieldArr['UserID'];
				
				$this->model->table($this->tbUserDetInfo)->data($data)->insert();
			}
			
			return 1;
		}catch(Exception $e){
			return -1;
		}
		exit;
		$this->params = $this->Addslashes->get_addslashes($params);
	
		$this->UserID = ComFun::getCookies('UserID');
		$condition['UserID'] = $this->UserID;
	
		$this->type  = strtolower($this->params['type']);
		if($this->type == 'ucomefrom'){
			$data['uComeFrom'] = $this->params['uComeFrom'];
		}
			
		if($this->model->table($this->tbUserDetInfo)->where($condition)->select()){
			$this->model->table($this->tbUserDetInfo)->data($data)->where($condition)->update();
		}else{
			$data['UserID'] = $this->UserID;
			$this->model->table($this->tbUserDetInfo)->data($data)->insert($condition);
		}
	}
	/**
	 * 保存个人社交信息
	 */
	public function modifySocail($post){
		$this->post = $this->Addslashes->get_addslashes($post);

		foreach($this->post['UserAuthenticationsID'] as $key=>$val){
			$condition['UserAuthenticationsID'] = $val;
			$data['uEmail']  =  $this->post['uEmail'][$key];
			$this->model->table($this->tbUserAuthenticationsInfo)->data($data)->where($condition)->update();
		}
	}
	
	/**
	 * 密码修改 旧密码检查
	 */
	public function checkPassWord($get){
		$this->get = $this->Addslashes->get_addslashes($get);
		
		if(ComFun::getCookies('UserID')){
			$UserID = ComFun::getCookies('UserID');		
		}else{
			$UserID = 0;
		}
		
		//$condition['uPWD'] = md5($this->get['oldPsw']);
		$condition = 'uEstate != 2 and UserID = \''.$UserID.'\' and uPWD = \''.md5(trim($this->get['oldPsw'])).'\'';
		
		if($this->model->table($this->tbUserInfo)->where($condition)->select()){
			return 1;
		}else{
			return -1;
		}
	}
	/**
	 * 密码修改 保存新密码
	 */
	public function saveNPassWord($get){
		$this->get = $this->Addslashes->get_addslashes($get);
		
		$UserID = ComFun::getCookies('UserID');
		if($UserID){
			$condition['UserID'] = $UserID;
		}else{
			$condition['UserID'] = 0;
		}
		
		$data['uPWD'] = md5(trim($this->get['newPsw']));
		
		$lRe = $this->model->table($this->tbUserInfo)->field('uPWD')->where($condition)->select();
		if($lRe){
			$ldata['UserID']      = $UserID;
			$ldata['uPWD']        = $lRe[0]['uPWD'];
			$ldata['uAppendTime'] = time();
				
			$this->model->table($this->tbUserPassWordLogInfo)->data($ldata)->insert();
		}
		
		if($this->model->table($this->tbUserInfo)->data($data)->where($condition)->update()){
			return 1;
		}else{
			return -1;
		}
	}
	/**
	 * 修改密码
	 */
	public function modifyPassword($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$condition['UserID'] = $fieldArr['UserID'];
		
		$udata['uPWD'] = md5(trim($fieldArr['newPWD']));

		$this->model->table($this->tbUserInfo)->data($udata)->where($condition)->update();
	}
	
	/**
	 * 修改密码
	 */
	public function modifyPasswordByEmail($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
	
		$condition['uEmail'] = $fieldArr['uEmail'];
	
		$udata['uPWD'] = md5(trim($fieldArr['uPWD']));
	
		$this->model->table($this->tbUserInfo)->data($udata)->where($condition)->update();
	}
	
	/**
	 * 用户OnLineLogId存在则返回ID，若不存在在插入信息
	 */
	public function getUserOnLineID($fieldArr){
	 	$condition['UserID'] = $fieldArr['UserID'];
	 	
	 	$re = $this->model->table($this->tbUserOnLineLogInfo)->where($condition)->select();
	 	
	 	if($re){ 			
	 		$UserOnLineLogID = $re[0]['UserOnLineLogID'];	 			
	 	}else{
	 		$nowTime = time();
	 		$IP      = ComFun::getIP();
	 	
	 		$iData['UserID']        = $fieldArr['UserID'];
	 		$iData['oIP']           = $IP;
	 		$iData['oUserName']     = $fieldArr['uName'];
	 		$iData['oAppendTime']   = $nowTime;
	 		$iData['oLastTime']     = $nowTime;
	 		$iData['oCode']         = $_COOKIE['PHPSESSID'];
	 	
	 		$UserOnLineLogID = $this->model->table($this->tbUserOnLineLogInfo)->data($iData)->insert();
	 	}
	 	
	 	return $UserOnLineLogID;
	 }
	/**
	 * 取得用户最后一次的OnLineLogId
	 */
	public function getUserOnlineLogID($UserID){	
		$this->UserID = $this->Addslashes->get_addslashes($UserID);

		$sql = 'select UserOnLineLogID  from '.$this->tbUserOnLineLogInfo.' where UserID = '.$this->UserID.' order by UserOnLineLogID DESC LIMIT 0 , 1';

		$re = $this->model->query($sql);

		if($re){
			return $re[0]['UserOnLineLogID'];
		}else{
			return -1;
// 			$this->nowTime             = $this->time();
			
// 			$insertArr['UserID']       = $this->UserID;
// 			$insertArr['oIP']          = ComFun::getIP();
// 			$insertArr['oUserName']    = $this->uName;
// 			$insertArr['oAppendTime']  = $this->nowTime;
// 			$insertArr['oLastTime']    = $this->nowTime;
			
// 			return  $this->model->table($this->tbUserOnLineLogInfo)->data($insertArr)->insert();exit;
		}		
	}
	/**
	 * 新
	 * 再次激活邮箱
	 */
	public function activateAgain($fieldArr){
		try{
			$condition['uEmail'] = $fieldArr['uEmail'];
			
			$code   = ComFun::getRandom();
			
			$data['uCode']   = $code;
			$data['uEstate'] = -1;
	
			$this->model->table($this->tbUserInfo)->data($data)->where($condition)->update();
			
			if($fieldArr['uEmail']){
				//发邮件
				$emArr['uName']   = $fieldArr['uName'];
				$emArr['uEmail']  = $fieldArr['uEmail'];
				$emArr['uCode']   = $code;
				$emArr['type']    = 'register';

				ComFun::toSendMail($emArr);
			}
			
			return 1;
		}catch(Exception $e){
			return -1;
		}
	}
	/**
	 * 旧
	 * 再次激活邮箱
	 */
	public function doActivateAgain($fieldArr){
		try{
			$condition = 'uEstate != 2 and uEmail = \''.$fieldArr['uEmail'].'\'';
			
			$re = $this->model->table($this->tbUserInfo)->where($condition)->select();
			
			if($re){
				//发邮件
				$emArr['uName']   = $re[0]['uName'];
				$emArr['uEmail']  = $re[0]['uEmail'];
				$emArr['uCode']   = $re[0]['uCode'];
				$emArr['type']    = 'register';
			
				ComFun::toSendMail($emArr);
			}
			
			return 1;
		}catch(Exception $e){
			return -1;
		}	
	}
	/**
	 * 取用户详细信息
	 */
	public function getUserDetInfo($UserID){
		$sql = 'select a.uName,a.uEmail,b.uComeFrom,c.uhURL from '.$this->tbUserInfo.' as a left join '.$this->tbUserDetInfo.' as b on a.UserID = b.UserID left join '.$this->tbUserHeadInfo.' as c on a.UserID = c.UserID where a.uEstate != 2 and a.UserID = '.$UserID;
		
		return $this->model->query($sql);
	}
	/**
	 * 保存第三方邮箱信息
	 */
	public function doSaveSocailEmail($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		//ComFun::pr($fieldArr);
		
		$codition['UserAuthenticationsID'] = $fieldArr['id'];
		
		$re = $this->model->table($this->tbUserAuthenticationsInfo)->where($codition)->select();
		
		if($re){
			$uEmail = $fieldArr['tValue'];
			$code   = ComFun::getRandom();
			
			$udate['uEmail'] = $uEmail;
			$udate['uCode']  = $code;
			
			$this->model->table($this->tbUserAuthenticationsInfo)->data($udate)->where($codition)->update();
			
			if($uEmail){
				$emArr['uName']   = $re[0]['uDisplay_name'];
				$emArr['uEmail']  = $uEmail;
				$emArr['uCode']   = $code;
				$emArr['type']    = 'jointhird';
					
				ComFun::toSendMail($emArr);
			}	
		}		
	}
	/**
	 * 取用户随机码
	 */
	public function getUserCodeByUserID($UserID){
		try{
			$condition['UserID'] = $UserID;
			
			$re = $this->model->table($this->tbUserInfo)->field('uCode')->where($condition)->select();
			
			if($re){
				if($re[0]['uCode']){
					return $re[0]['uCode'];
				}else{
					return '';
				}
			}else{
				return '';
			}			
		}catch(Exception $e){
			return '';
		}
	}
	/**
	 * 检验用户是否在线
	 */
	public function checkUserOnlineByOnLineID($UserOnLineLogID){
		try{
			$condition['UserOnLineLogID'] = $UserOnLineLogID;
			
			$_re = $this->model->table($this->tbUserOnLineLogInfo)->field('UserOnLineLogID')->where($condition)->select();
			
			if($_re){
				return true;
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}
// 	/**
// 	 * 写个人有关信息到$_COOKIE值
// 	 */
// 	function setUserInfoCookies($fieldArr){
// 		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
// 		$where = 'UserID = \''.$fieldArr['UserID'].'\'';
		
// 		$re = $this->model->table($this->tbUserInfo)->where($where)->select();
// 		if($re){
// 			$cookies['uName'] = $re[0]['uName'];
// 			ComFun::setCookies($cookies);
// 		}
// 	}
	/**
	 * 取得类
	 */
	private function getClass($className,$fieldArr=null){
		switch($className){
			case 'TimeConfig':
				include_once('TimeConfig.php');
				return new TimeConfig();
				break;
			case 'Login':
				include_once('Login.class.php');
				return new Login($this->model);
				break;
		}
	}
}
?>
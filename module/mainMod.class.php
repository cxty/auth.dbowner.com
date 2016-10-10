<?php
/**
 *
 * @author wbqing405@sina.com
 *
 * 个人中心
 *
 */
//!DBOwner && header('location:'.__ROOT__);

class mainMod extends commonMod {
	
	public function __construct() {
		parent::__construct ();
		
		//对登录之后，不能跳转到登录界面进行再次验证，验证完之后清空回调地址
		if ( $_COOKIE['_callbackurl'] ) {
			//$_callbackurl = ComFun::getCookies('_callbackurl');
			//ComFun::SetCookies(array('_callbackurl' => ''));
			//$this->redirect($_callbackurl);
		}
	}

	public function index() {
		//ComFun::pr(ComFun::getCookies());
		$this->assign ( 'title', Lang::get('PersonInfo_title') );
		if ( !isset($_COOKIE['UserID']) ) {
			ComFun::destoryCookies();
			$this->redirect( $this->config['db_oauth']['host'] . '/index/index');
			//ComFun::pr(ComFun::getCookies());
		}
		
		$_GET[0] = isset($_GET[0])?$_GET[0]:'u_profile';	

		$this->doDefault($_GET[0]);

		$this->display (); //输出模板
	}

	/**
	 * 首页处理
	 */
	private function doDefault($type){
		$user = $this->getClass('User');
		$modifyProfile = $this->getClass('ModifyProfile');
		$getDictionary = $this->getClass('GetDictionary');
	
		switch(strtolower($type)){
			case 'u_profile':		
				//用户基础信息		
			    $userInfo = $user->getProfile();    
			    if( $userInfo ){
			    	$userInfo['uBirthday'] = date('Y-m-d',$userInfo['uBirthday']);
			    }
				$this->assign('userInfo',$userInfo);
				
				//用户工作信息
				$tArr['UserID'] = ComFun::getCookies('UserID');
				$dbUserWork = $this->_getClass('DBUserWork');
				$workInfo = $dbUserWork->selectUserWorkInfo($tArr, 'wStartYear asc');
				$this->assign('workInfo', $workInfo);
				break;
			case 'u_safe':			
                $userInfo = $modifyProfile->getPwd();
				if($userInfo['uPWD']){
					$pwd = 1;
				}else{
					$pwd = -1;
				}
				$this->assign('pwd',$pwd);
				
				$safeInfo = $modifyProfile->getSafeInfo();
				$this->assign('safeInfo',$safeInfo);
				
				$appArr = $modifyProfile->getAuthApp();

				$AuthDefault = $getDictionary->getDtSelect('AuthDefault',isset($appArr['uAuthDefault'])?$appArr['uAuthDefault']:1);		
				$AuthApp     = $getDictionary->getDtSelect('AuthApp',isset($appArr['uAuthApp'])?$appArr['uAuthApp']:1);
				$AuthWay     = $getDictionary->getDtSelect('AuthWay',isset($appArr['uAuthWay'])?$appArr['uAuthWay']:1);			
				
				$this->assign('AuthDefaule',$AuthDefault);
				$this->assign('AuthApp',$AuthApp);
				$this->assign('AuthWay',$AuthWay);
				break;
			case 'u_private':	
				//个性设置			
// 				$apriArr = $modifyProfile->getCharacterInfo();
// 				$Language        = $getDictionary->getDtSelect('Language',isset($apriArr['uLanguage'])?$apriArr['uLanguage']:1);
// 				$CountrySpace    = $getDictionary->getDtSelect('CountrySpace',isset($apriArr['uCountrySpace'])?$apriArr['uCountrySpace']:1);
// 				$NowTimeZong     = $getDictionary->getDtSelect('NowTimeZong',isset($apriArr['uNowTimeZong'])?$apriArr['uNowTimeZong']:1);
// 				$AuthRecordGeo   = $getDictionary->getDtSelect('AuthRecordGeo',isset($apriArr['uAuthRecordGeo'])?$apriArr['uAuthRecordGeo']:1);
// 				$this->assign('Language',$Language);
// 				$this->assign('CountrySpace',$CountrySpace);
// 				$this->assign('NowTimeZong',$NowTimeZong);
// 				$this->assign('AuthRecordGeo',$AuthRecordGeo);
				
				//个人二维码信息
				$UserID = ComFun::getCookies('UserID');
				$uArr['UserID'] = $UserID;
				$uArr['client_id'] = $this->config['Auth']['client_id'];
				$mandOAuth = $this->_getClass('MandOAuth');
				$qArr['user_id'] = $mandOAuth->getUserID($uArr);
				$dbTokenCode = $this->_getClass('DBTokenCode');
				$qArr['uCode'] = $dbTokenCode->subUserCode($user->getUserCodeByUserID($UserID));
				$this->assign('clientSDK', $this->config['DB']['QRCode']['clientSDK']);
				$this->assign('qrUrl', $this->config['PLATFORM']['Plus'].'/qrCode/getQRCode?'.http_build_query($qArr));
				
				//社交信息
				$socialInfo = $user->getSocial();
				$apiArr = ComFun::getNowApi();
				if($socialInfo){
					foreach($socialInfo as $sk=>$sv){
						foreach($apiArr['providers'] as $ke=>$va){
							if(strtolower($sv['uProvider']) ==  strtolower($ke)){
								$provArr[]                  = $va['txt'];
								$socialInfo[$sk]['txt']     = $va['txt'];
								$socialInfo[$sk]['icon']    = $va['icon'];
								continue;
							}
						}
					}
				}else{
					$provArr[] = '';
				}
				$i = 0;
				foreach($apiArr['providers'] as $ke=>$va){
					if(!in_array($va['txt'], $provArr) && $va['enabled']){
						$bindArr[$i]['txt']      = $va['txt'];
						$bindArr[$i]['provider'] = $ke;
						$bindArr[$i]['icon']     = $va['icon'];
						$i++;
					}
				}
			
				$this->assign('bindArr',$bindArr);
				$this->assign('socialInfo',$socialInfo);
				break;
			case 'u_points':
				$SurplusPoint = $modifyProfile->getSurplurPoint();
				$this->assign('SurplusPoint',$SurplusPoint);

				//积分
				$tArr['UserID'] = ComFun::getCookies('UserID');
				$tArr['client_id'] = $this->config['PLATFORM']['Pay_client_id'];
				$mandOAuth = $this->getClass('MandOAuth');
				$inter = $modifyProfile->getUserInter($mandOAuth->getUserID($tArr));
				$this->assign('inter', $inter);
				
				$PointContent = $getDictionary->getDt('PointContent');
				$this->assign('PointContent',$PointContent);
				$this->assign('platform_pay', $this->config['PLATFORM']['Pay']);
				break;
			case 'u_active':
				$tArr['UserID'] = ComFun::getCookies('UserID');

				$inviteCode = $this->getClass('InviteCode');
				$isActive = $inviteCode->IsUserActive($tArr);
				
		
				$pagesize = 5;
				$page = $_GET['page'] ? $_GET['page'] : 1;

				//读取dev应用信息缓存
				$memKey = '|main|doDefault|getAppListPage-1|' . $tArr['UserID'] . '-' . $page . '-' . $pagesize;
				$menVal = $this->_Cache->get( $memKey );
				if ( $menVal ) {
					$appList = $menVal;
				} else {
					$userOAuth = new UserOAuth($this->model);
					$appList = $userOAuth->getAppListPage($tArr['UserID'], $page, $pagesize);
					$this->_Cache->set( $memKey, $appList, $this->config['MEM_EXPIRE'] );
				}

				if($appList['list']){
					$mandOAuth = $this->getClass('MandOAuth');
					$lArr['UserID']    = ComFun::getCookies('UserID');
					//是否需要生成验证码
					foreach($appList['list'] as $key=>$val){
						if($val['aIcoCode']){
							$picArr = explode(',', $val['aIcoCode']);
							$picArr2 = explode('|', $picArr[0]);
							$appList['list'][$key]['img'] = $this->config['FILE_SERVER_GET'].'&filecode='.$picArr2[0].'&w=120';
						}
						
						//权限字符串，判断应用是否有生成激活码功能
						$plusArr = $val['appplus'];
						if(is_array($plusArr)){
							foreach($plusArr as $ke=>$va){
								$codeArr = json_decode($va['PlusCode'],true);
								if(in_array($this->config['Expand']['AppPlugIn']['InviteCode_En'], $codeArr)){
									$appList['list'][$key]['inviteCode'] = $codeArr['MaxValue'];
									break;
								}else{
									$appList['list'][$key]['inviteCode'] = 0;
								}
								
							}
						}else{
							$appList['list'][$key]['inviteCode'] = false;
						}
						
						$lArr['client_id'] = $val['AppID'];
						$appList['list'][$key]['user_id'] = $mandOAuth->getUserID($lArr);
					}
				}	

				$this->assign('furl', $this->config['PLATFORM']['Plus'].'/inviteCode/set');
				$this->assign('appList',$appList['list']);
				$this->assign('showpage', $this->showpage('/main/index-u_active', $appList['count'], $pagesize));
				$this->assign('isActive',$isActive);
				break;
			default:
				break;	
		}
	}
	/**
	 * 修改图片
	 */
	public function uploadPortrait(){
		$this->display ('main/index.html'); //输出模板
	}	
	/**
	 * 上传原图
	 */
	public function upload(){
		$uploadPortrait = $this->getClass('UploadPortrait');
		echo $this->uploaddir.'/'.$uploadPortrait->uploadOrigin();
	}
	/**
	 * 剪切头像
	 */
	public function cutImage(){
		$targArr['xlen'] = $_GET['xlen'];
		$targArr['ylen'] = $_GET['ylen'];
		$targArr['wlen'] = $_GET['wlen'];
		$targArr['hlen'] = $_GET['hlen'];
		
		$uploadPortrait = $this->getClass('UploadPortrait');
		$uploadPortrait->cutFixedPic($_GET['bigImage'],$targArr);
		
		$modifyProfile = $this->getClass('ModifyProfile');
		$modifyProfile->savePortrait($_GET['bigImage']);
		
		$this->redirect('index');
	}	
	/**
	 * 修改用户信息
	 */
	public function profileCommon(){
		$user = $this->getClass('User');
		$userInfo = $user->getProfile();	
		if($userInfo['uBirthday']){
			$date = date('Y-m-d',$userInfo['uBirthday']);
			$date = explode('-',$date);
		
			$deYear  = $date[0];
			$deMonth = $date[1];
			$deDay   = $date[2];
		
			$tArr['type']    = 'year';
			$tArr['name']    = 'year';
			$tArr['default'] = $deYear;
			$year = $user->doChangeTime($tArr);
			$tArr['type']    = 'month';
			$tArr['name']    = 'month';
			$tArr['default'] = $deMonth;
			$month = $user->doChangeTime($tArr);
			$tArr['type']    = 'day';
			$tArr['name']    = 'day';
			$tArr['year']    = $deYear;
			$tArr['month']   = $deMonth;
			$tArr['default'] = $deDay;
			$day = $user->doChangeTime($tArr);
		}else{
			$tArr['type']    = 'year';
			$tArr['name']    = 'year';
			$year = $user->doChangeTime($tArr);
			$tArr['type']    = 'other';
			$tArr['name']    = 'month';
			$month = $user->doChangeTime($tArr);
			$tArr['name']    = 'day';
			$day = $user->doChangeTime($tArr);
		}
			
		$this->assign('year',$year);
		$this->assign('month',$month);
		$this->assign('day',$day);

		$this->assign('userInfo', $userInfo);
		$this->display('main/profileCommon.html');
	}
	/**
	 * 保存个人常规信息
	 */
	public function saveCommon(){
		if(!$_GET['nickName']){
			echo -1;exit;
		}
		if(!$_GET['year']){
			echo -2;exit;
		}
		if(!$_GET['month']){
			echo -3;exit;
		}

		$UserID = ComFun::getCookies('UserID');
		if( empty($UserID) ){
			echo -5;exit;
		}

		$year  = $_GET['year'] ? $_GET['year'] : date('Y');
		$month = $_GET['month'] ? $_GET['month'] : 1;
		$day   = $_GET['day'] ? $_GET['day'] : 1;
		
		$tArr['UserID']    = $UserID;
		$tArr['uName']     = $_GET['nickName'];
		
		$user = $this->getClass('User');

		if( $user->saveNickName($tArr) != 1 ){
			echo -4;exit;
		}
		
		$tArr['uSex']      = $_GET['sex'] ? $_GET['sex'] : 0;
		$tArr['uBirthday'] = strtotime($year.'-'.$month.'-'.$day);
		
		$userInfo = $user->modifyCommon($tArr);

		echo 1;
	}
	/**
	 * 修改生日信息
	 */
	public function changeTime(){
		
		$user = $this->getClass('User');
		switch(strtolower($_GET['type'])){
			case 'year':
				if($_GET['year'] == 0){
					echo -1;exit;
				}
				$tArr['type']    = 'month';
				$tArr['name']    = 'month';
				$tArr['head']    = true;
				$re['month'] = $user->doChangeTime($tArr);
				
				$tArr2['head'] = true;
				$TimeConfig = $this->getClass('TimeConfig');
				$re['day'] = $TimeConfig->getDayConfig('day',$tArr2);
				break;
			case 'month':
				$tArr['type']    = 'day';
				$tArr['name']    = 'day';
				$tArr['year']    = $_GET['year'];
				$tArr['month']   = $_GET['month'];
				$tArr['head']    = true;
				$re['day'] = $user->doChangeTime($tArr);
				break;
			case 'day':
				break;
		}
		
		echo json_encode(array('data' => $re));
	}
	/**
	 * 修改联系信息
	 */
	public function profileContact(){
		$user = $this->getClass('User');
		$userInfo = $user->getProfile();
		
		$this->assign('userInfo', $userInfo);
		$this->display('main/profileContact.html');
	}
	/**
	 * 保存个人联系信息
	 */
	public function saveContact(){
		if( !$_GET['uComeFrom'] ){
			echo -1;exit;
		}
		if( !$_GET['uEmail'] ){
			echo -2;exit;
		}
		
		$UserID = ComFun::getCookies('UserID');
		if( empty($UserID) ){
			echo -3;exit;
		}

		$tArr['UserID']    = $UserID;
		$tArr['uEmail']    = trim($_GET['uEmail']);
		
		$user = $this->getClass('User');
		
		if( !is_email($tArr['uEmail']) ){
			echo -4;exit;
		}
		if( $user->saveEmail($tArr) != 1 ){
			echo -5;exit;
		}	
		
		
		$tArr['uComeFrom'] = $_GET['uComeFrom'];
		$user->modifyContact($tArr);
		
		$userInfo = $user->getUserInfoNew($UserID);
		
		if ( $userInfo['uEmail'] != $tArr['uEmail'] ) {
			$tArr['uName'] = ComFun::getCookies('uName');
			$user = $this->getClass('User');
			$user->activateAgain($tArr);
		}
		
		echo 1;
	}
	/**
	 * 修改工作信息
	 */
	public function profileWork(){
		if( $_GET['id'] ){
			$dbUserWork = $this->_getClass('DBUserWork');
			$listInfo = $dbUserWork->getUserWorkByID( $_GET['id'] );
		}
		$user = $this->getClass('User');
		$tArr['type']    = 'year';
		$tArr['name']    = 'wStartYear';
		$tArr['default'] = $listInfo['wStartYear'] ? $listInfo['wStartYear'] : 0;
		$this->assign('startYear', $user->doChangeTime($tArr));
		$tArr['name']    = 'wEndYear';
		$tArr['default'] = $listInfo['wEndYear'] ? $listInfo['wEndYear'] : 0;
		$this->assign('endYear', $user->doChangeTime($tArr));
		
		$this->assign('state', DBState::getState('wState', $listInfo['wState']));
		$this->assign('provice', DBState::getProvice('wProvice', $listInfo['wProvice']));
		$this->assign('city', DBState::getCity('wCity', $listInfo['wProvice'], $listInfo['wCity']));
		$this->assign('cJson', DBState::getCityJson());
		$this->assign('listInfo', $listInfo);
		$this->display('main/profileWork.html');
	}
	/**
	 * 保存工作信息
	 */
	public function saveWorkInfo(){
		if( !$_COOKIE['UserID'] ){
			echo -1;exit;
		}
		if( !$_POST['wCompanyName'] ){
			echo -2;exit;
		}
		
		$tArr['AutoID']       = $_POST['AutoID'];
		$tArr['UserID']       = ComFun::getCookies('UserID');
		$tArr['wCompanyName'] = $_POST['wCompanyName'];
		$tArr['wDepartment']  = $_POST['wDepartment'];
		$tArr['wStartYear']   = $_POST['wStartYear'];
		$tArr['wEndYear']     = $_POST['wEndYear'];
		$tArr['wState']       = $_POST['wState'];
		$tArr['wProvice']     = $_POST['wProvice'];
		$tArr['wCity']        = $_POST['wCity'];

		$dbUserWork = $this->_getClass('DBUserWork');
		if( $dbUserWork->addUserWorkInfo($tArr) ){
			echo 1;
		}else{
			echo -3;exit;
		}
	}
	/**
	 * 删除工作信息
	 */
	public function delProfileWork(){
		$tArr['AutoID'] = $_GET['AutoID'];
		$dbUserWork = $this->_getClass('DBUserWork');
		$dbUserWork->deleteUserWorkInfo($tArr);
	}
	/**
	 * 修改教育信息
	 */
	public function profileEducation(){
		
		
		$this->display('main/profileEducation.html');
	}
	/**
	 * 修改个人联系信息
	 */
	public function profile_contact(){
		$user = $this->getClass('User');
		$userInfo = $user->getProfile();		
		$this->assign('userInfo',$userInfo);		
		$this->display ('main/index.html'); //输出模板
	}		
	/**
	 * 检查邮箱是否已经注册
	 */
	public function checkEmail(){
		$user = $this->getClass('User');
		$userInfo = $user->getEmailUserID($_GET['uEmail']);
		if($userInfo != -1){
			echo $userInfo['UserID'];
		}else{
			echo -1;
		}
	}	
	/**
	 * 保存邮箱
	 */
	public function saveContactEmail(){
		$user = $this->getClass('User');

		if($_GET['UserID'] == -1){
			$user->modifyContactEmail($_GET);
		}else{
			$user->updateContactEmail($_GET);
		}	
	}
	/**
	 * 重新激活邮箱
	 */
	public function ReActivate(){
		
	}
	/**
	 * 激活邮件
	 */
	public function doactive(){
		$user = ComFun::RequireClass('User', $this->model, $this->config);
		$userInfo = $user->getUserInfoNew(ComFun::getCookies('UserID'));
		
		$emArr['uName']   = $userInfo['uName'];
		$emArr['uEmail']  = $userInfo['uEmail'];
		$emArr['uCode']   = $userInfo['uCode'];
		$emArr['type']    = 'register';
		
		ComFun::toSendMail($emArr);
		
		exit;
		/*
		$login = $this->getClass('Login');	
		$email = $this->getClass('Email');
		$this->uEmail = $_GET['uEmail'];
		$this->uCode   = ComFun::getRandom();
		
		$fieldArr['uEmail']  = $this->uEmail;
		$fieldArr['uCode']   = $this->uCode;
	
		$login->seUpdateAccount($fieldArr);
		$email->sendMail($fieldArr,'joinReg');
		*/
	}	
	/**
	 * 修改个人常规信息
	 */
	public function profile_common(){
		$user = $this->getClass('User');
		$userInfo = $user->getProfile();	
		$this->assign('userInfo',$userInfo);		
		$this->display ('main/index.html'); //输出模板
	}
	/**
	 * 保存个人基础信息
	 */
	public function saveBaseInfo(){
		$user = $this->getClass('User');
		echo json_encode($user->modifyBaseInfo($_POST));
	}	
	/**
	 * 修改个人社交信息
	 */
	public function profile_social(){
		$user = $this->getClass('User');
		$socialInfo = $user->getSocial();
		$this->assign('socialInfo',$socialInfo);	
		$this->display ('main/index.html'); //输出模板
	}
	/**
	 * 保存个人社交信息
	 */
	public function saveSocial(){
		$user = $this->getClass('User');
		$userInfo = $user->modifySocail($_POST);		
		$this->redirect('index');
	}
	/**
	 * 密码修改
	 */
	public function resetpwd(){
		$backArr['view']   = 'byCenter';
		$this->assign('backArr',$backArr);
		$this->display ('index/resetpwd.html'); //输出模板
	}
	
	/**
	 * 重置密码：进行密码修改
	 */
	public function resetPwdSave(){
		$login = $this->getClass('Login');
	
		$params = array(
				'type'     => $_POST['type'],
				'uEmail'   => $_POST['uEmail'],
				'opwd'     => $_POST['opwd'],
				'uPWD'     => $_POST['pwd'],
		);
	
		switch ( strtolower($params['type']) ) {
			case 'byemail':
				$params['pwd'] = $params['uPWD'];
				echo $login->doResetPwdByEmail($params);
				break;
			case 'bycenter':
				$UserID = ComFun::getCookies('UserID');
				if(!$UserID){
					echo -2;exit;
				}
	
				$tArr['UserID'] = $UserID;
				$tArr['uPWD']   = $params['opwd'];
					
				if($login->checkOrigPwd($tArr) == -1){
					echo -3;exit;
				}
					
				$tArr['uPWD']   = $params['uPWD'];
					
				echo $login->doResetPwdByCenter($tArr);
				break;
			default:
				echo -1;
				break;
		}
	}
	
	/**
	 * 密码修改 就密码检查
	 */
	public function checkPassWord(){
		$user = $this->getClass('User');
		echo $user->checkPassWord($_GET);
	}
	/**
	 *  密码修改 保存新秘密
	 */
	public function saveNpsw(){
		$user = $this->getClass('User');
		echo $user->saveNPassWord($_GET);
	}	
	/**
	 * 保存安全邮箱
	 */
	public function checkSafeEmail(){
		$modifyProfile = $this->getClass('ModifyProfile');
		$modifyProfile->checkSafeEmail($_GET);
	}	
	/**
	 * 保存安全手机号
	 */
	public function checkSafePhone(){
		$modifyProfile = $this->getClass('ModifyProfile');
		$modifyProfile->checkSafePhone($_GET);
	}	
	/**
	 * 授权信息更新
	 */
	public function updateAuthApp(){
		$modifyProfile = $this->getClass('ModifyProfile');
		$modifyProfile->updateAuthApp($_GET);
	}	
	/**
	 * 个性设置更新
	 */
	public function updatePrivate(){
		$modifyProfile = $this->getClass('ModifyProfile');
		$modifyProfile->updatePrivate($_GET);
	}
	/**
	 * 保存第三方邮箱信息
	 */
	public function saveSocailEmail(){
		$user = $this->getClass('User');
		echo $user->doSaveSocailEmail($_GET);
	}
//=====短信息=====
	/**
	 * 个人信息中心
	 */
	public function message(){
		$this->assign ( 'title', Lang::get('Msg_title') );
		
		$type = $_GET['type'] ? $_GET['type'] : 'unreadMsg' ;
		$UserMessage = $this->getClass('UserMessage');
		
		$tArr['UserID'] = ComFun::getCookies('UserID');

		$UserMessage = new UserMessage($this->model);
		
		$tArr['type'] = 'readMsg';
		$msgNum['readMsg'] = $UserMessage->getUnreadNum($tArr);
		
		$tArr['type'] = 'sendMsg';
		$msgNum['sendMsg'] = $UserMessage->getUnreadNum($tArr);
		
		//$tArr['type'] = 'delMsg';
		//$msgNum['delMsg'] = $UserMessage->getUnreadNum($tArr);
	
		$tArr['type'] = $type;
		if($_GET['view'] == 'detail'){
			$tArr['uMsgID'] = $_GET['uMsgID'];
			$tArr['ident']  = $_GET['ident'];
			$detailMsg = $UserMessage->getDetailMsg($tArr);
			
			if ( $detailMsg ) {
				if($_GET['ident'] == 'send'){
					if($detailMsg['list']){
						foreach($detailMsg['list'] as $key=>$val){
							$pStr[$key]              = $val['uName'];
							$detailMsg['list'][$key]['portrait'] = $this->modifyProfile->getPortrait($val['UserID']);
						}
					}
				}else{
					$detailMsg['portrait'] = $this->modifyProfile->getPortrait($detailMsg['UserID']);
				}	
			}
			
			
//ComFun::pr($detailMsg);
			$this->assign('ident',$_GET['ident']);
			$this->assign('view',$_GET['view']);
			$this->assign('detailMsg',$detailMsg);
		}else{	
			$page = $_GET['page'] ? $_GET['page'] : 1;
			$pagesize = 10;
			
			$msgArr = $UserMessage->getMsgRecord($tArr,$pagesize,$page);
			//$modifyProfile = $this->_getClass('ModifyProfile');
			$user = $this->_getClass('User');

			if($msgArr['record']){
				foreach($msgArr['record'] as $key=>$val){
					$msgArr['record'][$key]['uContent'] = strip_tags($val['uContent']);			
					if($type == 'sendMsg'){
						$idArr = explode(',', $val['idList']);
						$nameArr = explode(',', $val['userList']);
						if($idArr){
							foreach($idArr as $ke=>$va){
								$msgArr['record'][$key]['portrait'][$ke] = $this->modifyProfile->getPortrait($va);
								$msgArr['record'][$key]['nameList'][$ke] = $nameArr[$ke];
							}
						}
					}else{
						$msgArr['record'][$key]['portrait'] = $this->modifyProfile->getPortrait($val['UserID']);
						$msgArr['record'][$key]['uName']    = $user->getUserNameByID($val['UserID']);
					}	
				}
			}
//			ComFun::pr($msgArr);
// 			$fieldArr['page_size']   = $pagesize;
// 			$fieldArr['all_size']    = $msgArr['count'];
// 			$fieldArr['sub_pages']   = 5;
// 			$fieldArr['pageCurrent'] = $page;
// 			$fieldArr['page_url'] = '/main/message?type='.$type;
			
// 			$subPages = $this->getClass('SubPages',$fieldArr);
			
			//$this->assign('subPage',$subPages->subPageCss1());
			$this->assign('showpage', $this->showpage('/main/message?type='.$type, $msgArr['count'], $pagesize));
			$this->assign('msgArr',$msgArr['record']);
			
		}
		
		$this->assign('nowtime',time());
		$this->assign('yestime',strtotime('yesterday'));
		$this->assign('type',$type);
		$this->assign('msgNum',$msgNum);		
		$this->display ('main/message.html'); //输出模板
	}
	/**
	 * 保存短信息
	 */
	public function saveMsg(){		
		$this->assign('type','writeMsg');
		
		if($_POST['accepter'] == ''){		
			$this->assign('msg',Lang::get('NotNullAccepter'));
			$this->display ('main/message.html'); //输出模板
			exit;
		}elseif($_POST['uContent'] == ''){
			$this->assign('msg',Lang::get('NotNullContent'));
			$this->display ('main/message.html'); //输出模板
			exit;
		}

		$_POST['UserID'] = ComFun::getCookies('UserID');
		$_POST['uName']  = ComFun::getCookies('uName');
		
		$UserMessage = $this->getClass('UserMessage');
		$UserMessage->doSaveMsg($_POST);
		
		$this->redirect('/main/message?type=sendMsg');
	}
	/**
	 * 回复信息
	 */
	public function answerMsg(){	
		$UserMessage = $this->getClass('UserMessage');
		$UserMessage->doAnswerMsg($_POST);
		
		$this->redirect('/main/message?type='.$_POST['type']);
	}
	/**
	 * 每隔一段时间查询短信息条目
	 */
	public function checkMsgNum(){		
		$UserMessage = new UserMessage($this->model);
		
		$tArr['UserID'] = ComFun::getCookies('UserID');
		$tArr['type'] = 'unreadMsg';
		$msgNum['unreadMsg'] = $UserMessage->getUnreadNum($tArr);
		
		$tArr['type'] = 'readMsg';
		$msgNum['readMsg'] = $UserMessage->getUnreadNum($tArr);
		
		$tArr['type'] = 'sendMsg';
		$msgNum['sendMsg'] = $UserMessage->getUnreadNum($tArr);
		
		$tArr['type'] = 'delMsg';
		$msgNum['delMsg'] = $UserMessage->getUnreadNum($tArr);
		
		echo json_encode($msgNum);
	}
	/**
	 * 删除短信息
	 */
	public function delMsg(){
		$UserMessage = $this->getClass('UserMessage');
		$UserMessage->doDelMsg($_GET);
		
	}
//=====短信息=====
/**/
	/**
	 * 生成激活码
	 */
	public function createInviteCode(){
		$UserID    = ComFun::getCookies('UserID');
		$client_id = $_GET['client_id'];
		//用户必须先登录系统
		if(empty($UserID)){
			ComFun::throwMsg('Ex_LostParam102');
		}
		if(empty($_GET['client_id'])){
			ComFun::throwMsg('Ex_LostParam102');
		}
		
		$tArr['UserID']    = $UserID;
		$tArr['client_id'] = $client_id;
		
		//旧的方法
// 		$inviteCode = $this->getClass('InviteCode');
// 		$inviteArr = $inviteCode->getAllInviteCode($tArr);
		
		$dbSoapExpandInviteCode = $this->getClass('DBSoapExpandInviteCode');
		$inviteCodeList = $dbSoapExpandInviteCode->getUserInviteCodeList($tArr);

		$i = 0;
		$code = array();
		if(is_array($inviteCodeList)){
			foreach($inviteCodeList as $val){
				if(empty($val['TUID'])){				
					$code[] = $val['InviteCode'];
				}else{
					$i++;
				}
			}
		}
	
		$inviteInfo['total']     = $_GET['count'] ? $_GET['count'] : 5;
		$inviteInfo['product']     = $inviteCodeList ? count($inviteCodeList) : 0;
		$inviteInfo['used']      = $i;
		$inviteInfo['code']      = json_encode($code);
		$inviteInfo['client_id'] = $client_id;

		$this->assign('inviteInfo',$inviteInfo);
		$this->display ('main/createInviteCode.html');
	}
	/**
	 * 取激活码
	 */
	public function getActiveCode(){
		$UserID = ComFun::getCookies('UserID');
		$client_id = trim($_GET['client_id']);
		//用户必须先登录系统
		if(empty($UserID)){
			echo -1;exit;
		}
		if(empty($client_id)){
			echo -2;exit;
		}
/*		
		//能生成激活码的个数
		$UserOAuth = $this->getClass('UserOAuth');
		$appList = $UserOAuth->dogetAppList($UserID);	
			
		if(is_array($appList)){
			foreach($appList as $val){
				if($val['AppID'] == $client_id){
					$plusArr = $val['appplus'];
					if(is_array($plusArr)){
						foreach($plusArr as $ke=>$va){
							$codeArr = json_decode($va['PlusCode'],true);
							if(in_array('inviteCode', $codeArr)){
								$maxValue = $codeArr['MaxValue'];
								break;
							}
						}
					}
				}
			}
		}
		
		//已经生成激活码的个数
		$tArr['UserID']    = $UserID;
		$tArr['client_id'] = $client_id;
		
		$inviteCode = $this->getClass('InviteCode');
		$inviteArr = $inviteCode->getAllInviteCode($tArr);
		
		$proNum = $inviteArr ? count($inviteArr) : 0;
*/		
		if(intval($_GET['proNum']) >= intval($_GET['maxValue'])){
			echo -3;exit;
		}else{
			$tArr['UserID']    = $UserID;
			$tArr['client_id'] = $client_id;
			
			$dbSoapExpandInviteCode = $this->getClass('DBSoapExpandInviteCode');
			$rb = $dbSoapExpandInviteCode->addInviteCode($tArr);

			if($rb){
				echo $rb;
			}else{
				echo -4;
			}
			exit;
			//echo $inviteCode->getInviteCode($tArr);exit; //旧的方法
		}	
	}
/**/
	/**
	 * 删除应用信息
	 */
	public function delOauthInfoLog(){
		$UserID = ComFun::getCookies('UserID');
		$client_id = trim($_GET['AppID']);
		//用户必须先登录系统
		if(empty($UserID)){
			echo -1;exit;
		}
		if(empty($client_id)){
			echo -2;exit;
		}
		
		$tArr['UserID'] = $UserID;
		$tArr['AppID']  = $client_id;
	
		$MandOAuthLog = $this->getClass('MandOAuthLog');
		$MandOAuthLog->delOauthInfoLog($tArr);
	}
	/**
	 * 邮件发送激活码
	 */
	public function sendInviteCode(){
		$uName = ComFun::getCookies('uName');
		$Email = $_GET['Email'];
		$inviteCode = $_GET['inviteCode'];
		$client_id = $_GET['client_id'];
		
		if(empty($uName)){
			ComFun::throwMsg('Ex_ErrorSystem409');
		}
		if(empty($Email)){
			ComFun::throwMsg('Ex_NotNull307');
		}
		if(empty($inviteCode)){
			ComFun::throwMsg('Ex_NotNull308');
		}
		if(empty($client_id)){
			ComFun::throwMsg('Ex_LostParam102');
		}
		
		$UserOAuth = $this->getClass('UserOAuth');
		$AppInfo = $UserOAuth->getAuthAppInfo($client_id);
		
		if($AppInfo){
			$emArr['aName'] = $AppInfo['data']['appinfo']['aName'];
		}
	
		$emArr['uName']   = $uName;
		$emArr['uEmail']  = $Email;
		$emArr['uCode']   = $inviteCode;
		$emArr['type']    = 'inviteCode';

		ComFun::toSendMail($emArr);
	}
	/**
	 * 真实姓名认证
	 */
	public function safeRealName(){
		
		$this->assign('authType', ComFun::stAuthType('saveAuthType'));
		$this->display('main/safeRealName.html');
	}
	/**
	 * 保存真实姓名认证信息
	 */
	public function doSaveRealName(){
		$tArr['UserID'] = ComFun::getCookies('UserID');
		
		switch(intval($_GET['saveAuthType'])){
			case 1;
				$tArr['uRealName'] = $_GET['safeRealName'];
				$tArr['uAuthType'] = $_GET['saveAuthType'];
				$tArr['uAuthNum']  = $_GET['safeAuthNum'];
				
				if(!DBCheckIDCard::validation_filter_id_card($_GET['safeAuthNum'])){
					echo -1;exit;
				}
				
				$modifyProfile = $this->getClass('ModifyProfile');
				$modifyProfile->addRealName($tArr);
				
				ComFun::pr($_GET);
				break;
		}	
	}
	/**
	 * 安全邮箱认证
	 */
	public function safeEmail(){
		$modifyProfile = $this->getClass('ModifyProfile');
		$listInfo = $modifyProfile->getAuthEmailByUserID(ComFun::getCookies('UserID'));
		
		if($listInfo){
			$cookies['uSafeEmail'] = $listInfo['uSafeEmail'];
			ComFun::SetCookies($cookies);
		}

		$this->assign('listInfo', $listInfo);
		$this->display('main/safeEmail.html');
	}
	/**
	 * 保存安全邮箱地址
	 */
	public function doSafeEmail(){
		$uEmail = $_GET['safeEmail'];
		if(!is_email($uEmail)){
			echo -1;exit;
		}

		$cArr['uSafeEmail'] = $uEmail;
		$cArr['UserID']     = ComFun::getCookies('UserID');
		$modifyProfile = $this->getClass('ModifyProfile');
		if($modifyProfile->checkExistEmail($cArr)){
			echo -2;exit;
		}
		
		$uCode = ComFun::getRandom();	
		$tArr['UserID']      = ComFun::getCookies('UserID');
		$tArr['uSafeEmail']  = $_COOKIE['uSafeEmail'] ? ComFun::getCookies('uSafeEmail') : $uEmail;
		$tArr['uOSafeEmail'] = $_COOKIE['uSafeEmail'] ? $uEmail : '';
		$tArr['uEmailCode']  = $uCode;
		$modifyProfile->addSafeEmail($tArr);

		//发邮件
		if($uEmail){
			$emArr['uName']   = ComFun::getCookies('uName');
			$emArr['uEmail']  = $uEmail;
			$emArr['uCode']   = $uCode;
			$emArr['type']    = 'authaccount';

			ComFun::toSendMail($emArr);
		}
		
		//Cookies注销
		$cookies['uSafeEmail'] = '';
		ComFun::destoryCookies($cookies);
		
		echo 1;exit;
	}
	/**
	 * 安全手机认证
	 */
	public function safePhone(){
		$modifyProfile = $this->getClass('ModifyProfile');
		$listInfo = $modifyProfile->getAuthPhoneByUserID(ComFun::getCookies('UserID'));
		
		if($listInfo){
			$cookies['uSafePhone'] = $listInfo['uSafePhone'];
			ComFun::SetCookies($cookies);
		}
		
		$this->assign('listInfo', $listInfo);
		$this->display('main/safePhone.html');
	}
	/**
	 * 保存安全手机号
	 */
	public function doSafePhone(){
		$safePhone = $_GET['safePhone'];
		if(!is_mobile($safePhone)){
			echo -1;exit;
		}
		
		$cArr['uSafePhone'] = $safePhone;
		$cArr['UserID']     = ComFun::getCookies('UserID');
		$modifyProfile = $this->getClass('ModifyProfile');
		if($modifyProfile->checkExistPhone($cArr)){
			echo -2;exit;
		}
		
		$code = $_GET['uAuthPhone'];
		if( !( $code && (trim(strtolower($code)) == strtolower(ComFun::getCookies('uPhoneCode'))) ) ){
			echo -3;exit;
		}
		
		$tArr['UserID']      = ComFun::getCookies('UserID');
		$tArr['uSafePhone']  = $safePhone;
		$modifyProfile->addSafePhone($tArr);
		
		echo 1;exit;
	}
	/**
	 * 获取验证码
	 */
	public function gerateAuthCode(){
		//ComFun::pr($_GET);
		$code = ComFun::getRandomCode(6);
		$cookies['uPhoneCode'] = $code;
		ComFun::SetCookies($cookies);
		echo $code;
	}
	
	/**
	 * 解除绑定
	 */
	public function unBinding(){
		$login = $this->getClass('Login');
		$login->doUnbinding($_GET);
	}
	
	/**
	 * 
	 */
	public function dotest(){
		
		$html_body ="<a href='#'>www.111cn.net</a>";
		
		echo $html_body;
		
		echo '<br>';
		
		//preg_replace("/(</?)(w+)([^>]*>)/e", "'\1'.strtoupper('\2').'\3'", $html_body);
		
		echo msubstr(strip_tags($html_body), 0, 4);
		
		echo '<br>';
		
		echo strip_tags($html_body);exit;
		
		//ComFun::pr($_POST);
		$this->display ('main/dotest.html'); //输出模板
	}	
	/**
	 * 取得类
	 */
	private function getClass($className,$fieldArr=null){
		$root = dirname(dirname(__FILE__));
		switch($className){
			case 'UploadPortrait':
				$this->uploaddir = 'cache/images';
				$this->filename = 'userfile';
				include_once($root.'/include/lib/UploadPortrait.class.php');
				return new UploadPortrait($this->uploaddir,$this->filename);
				break;
			case 'ModifyProfile':
				include_once($root.'/include/lib/ModifyProfile.class.php');
				return new ModifyProfile($this->model);
				break;
			case 'User':
				include_once($root.'/include/lib/User.class.php');
				return new User($this->model);
				break;
			case 'GetDictionary':
				include_once($root.'/include/lib/GetDictionary.class.php');
				return new GetDictionary();
				break;
			case 'Login':
				include_once($root.'/include/lib/Login.class.php');
				return new Login($this->model);
				break;
			case 'Email':
				include_once($root.'/include/lib/Email.class.php');
				return new Email();
			case 'UserMessage':
				include_once($root.'/include/lib/UserMessage.class.php');
				return new UserMessage($this->model);
				break;
			case 'SubPages':
				require_once(dirname(dirname(__FILE__)).'/include/lib/SubPages.class.php');
				return new SubPages($fieldArr['page_size'],$fieldArr['all_size'],$fieldArr['pageCurrent'],$fieldArr['sub_pages'],$fieldArr['page_url'].'&p=');
			case 'InviteCode':
				include_once(dirname(dirname(__FILE__)).'/include/lib/InviteCode.class.php');
				return new InviteCode($this->model);
				break;
			case 'UserOAuth':
				include_once($root.'/include/lib/UserOAuth.class.php');
				return new UserOAuth($this->model,$this->config);
				break;
			case 'MandOAuth':
				include_once($root.'/include/lib/MandOAuth.class.php');
				return new MandOAuth($this->model,$this->config);
				break;
			case 'MandOAuthLog':
				include_once($root.'/include/lib/MandOAuthLog.class.php');
				return new MandOAuthLog($this->model);
				break;
			case 'TimeConfig':
				include_once($root.'/include/lib/MandOAuthLog.class.php');
				return new TimeConfig();
				break;
			case 'DBSoapExpandInviteCode':
				$this->config['DES']['type']  = 'Expand';
				$this->config['DES']['ident'] = 'private';
				include_once(dirname(dirname(__FILE__)).'/include/lib/DBSoapExpandInviteCode.class.php');
				return new DBSoapExpandInviteCode($this->config);
				break;
		}
	}
}
?>
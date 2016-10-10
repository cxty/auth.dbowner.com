<?php
/**
 *
 * @author wbqing405@sina.com
 *
 * 二维码登录处理
 *
 */
class loginMod extends commonMod{
	/**
	 * 手机二维码扫描处理
	 */
	public function phoneLogin(){
		$sessionkey = $_GET['sessionkey'];
		$user_id    = $_GET['user_id'];
		$_uCode     = $_GET['uCode'];
		$userCode   = $_GET['rCode'];
		$timestamp  = $_GET['tCode'];
	
		if(empty($sessionkey)){
			echo $this->__return ( array('state' => false, 'error' => 'pl101' ), 'private');exit;
		}
		if(empty($user_id)){
			echo $this->__return ( array('state' => false, 'error' => 'pl102' ), 'private');exit;
		}
		if(empty($userCode)){
			echo $this->__return ( array('state' => false, 'error' => 'pl103' ), 'private');exit;
		}
		
		if($timestamp < (time()-$this->config['DB']['QRCode']['clientOverTime']) || $timestamp > (time()+$this->config['DB']['QRCode']['clientOverTime'])){
			echo $this->__return ( array('state' => false, 'error' => 'pl106' ), 'private');exit;
		}		
		$dbTokenCode = $this->_getClass('DBTokenCode');
		$user = $this->_getClass('User');
		
		$mandOAuth = $this->_getClass('MandOAuth');
		$UserID = $mandOAuth->_getUserID($user_id);
		$uCode = $dbTokenCode->subUserCode($user->getUserCodeByUserID($UserID));
		
		if($dbTokenCode->CheckUserByUserCode($userCode, $uCode, $timestamp)){

			$dbQRCodeForPC = $this->_getClass('DBQRCodeForPC');
				
			if($dbQRCodeForPC->checkSessionValid($sessionkey)){
					
				$tArr['cUserID']    = $user_id;
				$tArr['cUserCode']  = $userCode;
				$tArr['cSessionID'] = $sessionkey;
					
				$dbQRCodeForPC->updateCheckResult($tArr);

				echo $this->__return ( array('state' => true, 'msg' => 'ok' ));exit;
			}else{
				echo $this->__return ( array('state' => false, 'error' => 'pl105' ), 'private');exit;
			}
		}else{
			echo $this->__return ( array('state' => false, 'error' => 'pl104' ), 'private');exit;
		}	
	}
	/**
	 * 脚本验证手机是否登录
	 */
	public function checkPhoneLogin(){	
		$dbQRCodeForPC = $this->_getClass('DBQRCodeForPC');
		$user_id = $dbQRCodeForPC->checkPhoneLogin($_COOKIE['PHPSESSID']);

		if($user_id){
			$mandOAuth = $this->_getClass('MandOAuth');
			$uStr = $mandOAuth->dodecrypt($user_id);
			$uArr = explode('|', $uStr);
			
			$user = $this->_getClass('User');
			$userInfo = $user->getUserInfo($uArr[0]);
		
			if($userInfo != -1){
				$t2Arr['uEmail'] = $userInfo[0]['uEmail'];
				$t2Arr['UserID'] = $userInfo[0]['UserID'];
				$t2Arr['uName']  = $userInfo[0]['uName'];
				
				$login = $this->_getClass('Login');
				$login->doSucessLogin($t2Arr);
				
				echo 1;
			}else{
				echo -1;
			}		
		}else{
			echo -1;
		}
		
		//设置初始化登录方式
		$cookies['loginNum'] = 3;
		$cookies['loginType'] = 'qr';
		ComFun::SetCookies($cookies);
	}
}
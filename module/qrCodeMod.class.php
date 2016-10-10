<?php
/**
 *
 * 具体插件处理 
 *
 * @author wbqing405@sina.com
 *
 */
class qrCodeMod extends commonMod {
	/**
	 * 二维码登录模拟  接口：存session值 
	 */
// 	public function test(){
// 		session_start();
		
// 		$sessionid = session_id();	
// 		$tArr['cSessionID'] = $sessionid;
		
// 		ComFun::pr($tArr);
		
// 		$dbQRCodeForPC = $this->_getClass('DBQRCodeForPC');
		
// 		$dbQRCodeForPC->saveSessionValue($tArr);
// 		$dbQRCodeForPC->deleteOverTimeSession();
		
// 		$url = strtolower(__ROOT__).'/qrCode/pull?sessionkey='.$sessionid;
		
// 		echo '<br>'.$url;
// 	}
	/**
	 * 手机端模拟  
	 */
// 	public function pull(){		
// 		$sessionkey = $_GET['sessionkey'];
// 		$UserID = '123';
// 		$userCode = 'c24ed2cd1e4a1330';
		
// 		$dbTokenCode = $this->_getClass('DBTokenCode');	
// 		$uCode = $dbTokenCode->getTokenCode($userCode);
		
// 		//$url = strtolower(__ROOT__).'/qrCode/push?sessionkey='.$sessionkey.'&user_id='.$UserID.'&uCode='.$uCode;
// 		$url = strtolower(__ROOT__).'/login/phoneLogin?sessionkey='.$sessionkey.'&user_id='.$UserID.'&uCode='.$uCode;
		
// 		echo '<br>'.$url;	
// 	}
	/**
	 * 服务端模拟  接口：验证写表
	 */
// 	public function push(){
// 		$sessionkey = $_GET['sessionkey'];
// 		$user_id    = $_GET['user_id'];
// 		$userCode   = $_GET['uCode'];
		
// 		$dbTokenCode = $this->_getClass('DBTokenCode');
		
// 		if($dbTokenCode->CheckUserByUserCode($user_id, $userCode)){
			
// 			$dbQRCodeForPC = $this->_getClass('DBQRCodeForPC');
			
// 			if($dbQRCodeForPC->checkSessionValid($sessionkey)){
					
// 				$tArr['cUserID']    = $user_id;
// 				$tArr['cUserCode']  = $userCode;
// 				$tArr['cSessionID'] = $sessionkey;
					
// 				$dbQRCodeForPC->updateCheckResult($tArr);

// 				ComFun::pr($tArr);
// 			}else{
// 				echo 'it is overtime!';
// 			}
// 		}else{
// 			echo 'check failed!';
// 		}
// 	}
	/**
	 * 个人中心验证登录  接口：验证登录
	 */
// 	public function checkLogin(){	
// 		$dbQRCodeForPC = $this->_getClass('DBQRCodeForPC');
// 		$rb = $dbQRCodeForPC->checkPhoneLogin($_COOKIE['PHPSESSID']);

// 		if($rb){
// 			echo 'login in success!';
// 		}else{
// 			echo 'login in failed!';
// 		}
// 	}
	/**
	 * 直接生成二维码
	 */
	public function dirGetQRCode(){
		$sessionid = session_id();
		$tArr['cSessionID'] = $sessionid;
		
		$dbQRCodeForPC = $this->_getClass('DBQRCodeForPC');
		
		$dbQRCodeForPC->saveSessionValue($tArr);
		$dbQRCodeForPC->deleteOverTimeSession();
		
		$url = $this->config['DB']['QRCode']['AuthUrl'].'?sessionkey='.$sessionid;
		//echo $url;exit;
		$dbQRCode = $this->_getClass('DBQRCode');
		echo $dbQRCode->getQRCode($url);
		exit;
		
// 		echo '<br>'.$url;

// 		if(is_array($_GET)){
// 			foreach($_GET as $key=>$val){
// 				if(!in_array($key, array('_module','_action'))){
// 					$get[$key] = $val;
// 				}		
// 			}
// 		}

// 		$params = '';
// 		if(is_array($get)){
// 			$params = '?'.http_build_query($get);
// 		}
	
// 		ComFun::pr($_GET);exit;
		
// 		$dbQRCode = $this->_getClass('DBQRCode');
// 		echo $dbQRCode->getQRCode($this->config['DB']['QRCode']['AuthUrl'].'?sessionkey='.$sessionid);
// 		exit;
	}
	/**
	 * 生成二维码
	 */
	public function set(){
		$dbQRCode = $this->_getClass('DBQRCode');
		echo $dbQRCode->getQRCode($_GET['url']);
	}
	/**
	 * 取二维码
	 */
	public function get(){
		if(isset($_GET['code'])){
			$img = dirname(dirname(__FILE__)).'/qrCode/'.$_GET['code'].'.png';
			if(file_exists($img)){
				$url = strtolower(__ROOT__).'/qrCode/'.$_GET['code'].'.png';
				$fh = fopen($url, "r");
				while (!feof($fh)) {
					$imgdata .= fgets($fh);
				}
				fclose($fh);
					
				$info = getimagesize($url);
					
				header('content-type:'.$info['mime']);
				echo $imgdata;exit;
			}else{
				echo json_encode(array('status' => 'false',
						  		   'state' => 'file had not existed!',
						    ));
			}
		}else{
			echo json_encode(array('status' => 'false',
						  		   'state' => 'picture code is empty!',
						    ));
		}
	}
	/**
	 * 删除二维码图片
	 */
	public function delete(){
		if(isset($_GET['code'])){
			$dbQRCode = $this->_getClass('DBQRCode');

			if($dbQRCode->deleteQRCode($_GET['code'])){
				$status = 'true';
				$state  = 'succuss';
			}else{
				$status = 'false';
				$state  = 'file had not existed!';
			}		
		}else{
			$status = 'false';
			$state  = 'picture code is empty!';
		}
		
		echo json_encode(array('status' => $status,
				'state' => $state,
		));
	}
}
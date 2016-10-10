<?php 
/**
 * 内部curl调用
 * 
 * @author BQ
 *
 */
class dbMod extends commonMod{
	/**
	 * 返回信息处理
	 */
	private function _return($data=null) {
		if(isset($data['error'])){
			$data['msg'] = ComFun::getErrorValue('private',$data['error']);
		}
	
		echo json_encode($data);exit;
	}
	/**
	 * auth平台鉴权调用
	 */
	private function checkUserValid($user_id){
		if(!$user_id){
			$this->_return(array('state' => false, 'error' => 'ai104'));
		}
		
		$MandOAuth = $this->_getClass('MandOAuth');
		$token = $MandOAuth->reAccessToken($user_id);
		
		if(!is_array($token)){
			$this->_return(array('state' => false, 'error' => 'ai106'));
		}else{
			$tokenInfo['UserID']    = $token[0];
			$tokenInfo['client_id'] = $token[1];
		}
		
		return $tokenInfo;
	}
	/**
	 * access_token鉴权(简单版)
	 */
	private function checkAccessToken($access_token){		
		if(!$access_token){		
			$this->_return(array('state' => false, 'error' => 'ai101'));
		}
		
		$MandOAuth = $this->_getClass('MandOAuth');
		$token = $MandOAuth->reAccessToken($access_token);

		$user = $this->_getClass('User');

		if(!$user->checkUserOnlineByOnLineID($token[0])){
			$this->_return(array('state' => false,'error' => 'ai108'));
		};
		
		$tokenInfo = $MandOAuth->getTokenInfoByCurl($token[1]);
		if(!$tokenInfo){
			$this->_return(array('state' => false,'error' => 'ai102'));
		}	

		return $tokenInfo;
	}
	/**
	 * 取用户信息
	 */
	public function getUserInfo(){
		$GetUserInfo = $this->_getClass('GetUserInfo',$_GET);
		$userInfo = $GetUserInfo->getUserInfo();
		$this->_return($userInfo);
	}
	/**
	 * 发送信息
	 */
	public function sendMsg () {
		$dbAddInformation = $this->_getClass('DBAddInformation', $_POST);
		$_rb = $dbAddInformation->addInformation(array(
													'content' => $_POST['content'],
													));
		$this->_return( array('state' => $_rb['state'], 'msg' => $_rb['msg'], 'data' => $_rb['data']) );
	}
	/**
	 * 关注
	 */
	public function attention () {
		if ( $_POST['name'] ) {
			$tArr['name'] = $_POST['name'];
		} else {
			$tArr['uid'] = $_POST['uid'];
		}
		
		$dbBeFriend = $this->_getClass('DBBeFriend', $_POST);
		$_rb = $dbBeFriend->beFriend( $tArr );
		
		$this->_return( array('state' => $_rb['state'], 'msg' => $_rb['msg'], 'data' => $_rb['data']) );
	}
	/**
	 * auth平台鉴权调用
	 */
	public function expAuthUserAndEncryptByAuth(){
		$client_id = isset($_GET['client_id']) ? $_GET['client_id'] : $_POST['client_id'];
		$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_POST['user_id'];
		if(!$user_id){
			$this->_return(array('state' => false, 'error' => 'ai104'));
		}
		if(!$client_id){
			$this->_return(array('state' => false,'error' => 'ai103'));
		}
		
		$token = $this->checkUserValid($user_id);
		
		$mandOAuth = $this->_getClass('MandOAuth');
		$user_id = $mandOAuth->doencrypt($token['UserID'].'|'.$client_id);
		
		$this->_return(array('state' => true, 'user_id' => $user_id));
	}
	/**
	 * 用户user_id换取用户名
	 */
	public function getUserNameByUserID(){
		$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_POST['user_id'];
		
		$token = $this->checkUserValid($user_id);
		
		$user = $this->_getClass('User');
		$userName = $user->getUserNameByID($token['UserID']);
		
		if($userName){
			$this->_return(array('state' => true, 'userName' => $userName));
		}else{
			$this->_return(array('state' => false, 'error' => 'ai107'));
		}
	}
	/**
	 * expand平台调用
	 */
	public function expAuthUserAndEncrypt(){
		$client_id    = isset($_GET['client_id']) ? $_GET['client_id'] : $_POST['client_id'];
		$access_token = isset($_GET['access_token']) ? $_GET['access_token'] : $_POST['access_token'];
		if(!$client_id){
			$this->_return(array('state' => false,'error' => 'ai103'));
		}
	
		$token = $this->checkAccessToken($access_token);
	
		$mandOAuth = $this->_getClass('MandOAuth');
		$user_id = $mandOAuth->doencrypt($token['UserID'].'|'.$client_id);
	
		$this->_return(array('state' => true, 'user_id' => $user_id));
	}
	/**
	 * pay平台调用
	 */
	public function checkAccountValid(){
		$client_id    = isset($_GET['client_id']) ? $_GET['client_id'] : $_POST['client_id'];
		$access_token = isset($_GET['access_token']) ? $_GET['access_token'] : $_POST['access_token'];
		if(!$client_id){
			$this->_return(array('state' => false,'error' => 'ai103'));
		}
		
		$token = $this->checkAccessToken($access_token);
		
		$mandOAuth = $this->_getClass('MandOAuth');
		$user_id = $mandOAuth->doencrypt($token['UserID'].'|'.$client_id);
		
		$this->_return(array('state' => true, 'user_id' => $user_id));
	}
	/**
	 * 取pay平台消费双方用户user_id
	 */
	public function getCoinUserID(){
		$access_token = isset($_GET['access_token']) ? $_GET['access_token'] : $_POST['access_token'];
		$client_id    = isset($_GET['client_id']) ? $_GET['client_id'] : $_POST['client_id'];
		$IdentCode    = isset($_GET['IdentCode']) ? $_GET['IdentCode'] : $_POST['IdentCode'];
		if(!$client_id){
			$this->_return(array('state' => false,'error' => 'ai103'));
		}
		if(!$IdentCode){
			$this->_return(array('state' => false,'error' => 'ai109'));
		}
		
		$token = $this->checkAccessToken($access_token);

		$mandOAuth = $this->_getClass('MandOAuth');
		$identList = $mandOAuth->dodecrypt(trim($IdentCode));
		
		$this->_return(array('state' => true, 
								  'tokenUser' => $mandOAuth->doencrypt($token['UserID'].'|'.$client_id),
								  'identUser' => $mandOAuth->doencrypt($identList[0].'|'.$client_id),
							));
	}
	/**
	 * 取pay平台消费双方user_id
	 */
	public function getCoinTokenID(){
		$TokenCode    = isset($_GET['TokenCode']) ? $_GET['TokenCode'] : $_POST['TokenCode'];
		$IdentCode    = isset($_GET['IdentCode']) ? $_GET['IdentCode'] : $_POST['IdentCode'];
		$client_id    = isset($_GET['client_id']) ? $_GET['client_id'] : $_POST['client_id'];
		
		if(!$TokenCode){
			$this->_return(array('state' => false,'error' => 'ai110'));
		}
		if(!$IdentCode){
			$this->_return(array('state' => false,'error' => 'ai109'));
		}
		if(!$client_id){
			$this->_return(array('state' => false,'error' => 'ai103'));
		}

		$mandOAuth = $this->_getClass('MandOAuth');
		$tokenList = $mandOAuth->dodecrypt(trim($TokenCode));
		$identList = $mandOAuth->dodecrypt(trim($IdentCode));
		
		$this->_return(array('state' => true,
								  'tokenUser' => $mandOAuth->doencrypt($tokenList[0].'|'.$client_id),
								  'identUser' => $mandOAuth->doencrypt($identList[0].'|'.$client_id),
							));
	}
	/**
	 * pay平台：取用户认证信息
	 */
	public function payAuthInfo(){
		$access_token = isset($_GET['access_token']) ? $_GET['access_token'] : $_POST['access_token'];
		
		$token = $this->checkAccessToken($access_token);
		
		$modifyProfile = $this->_getClass('ModifyProfile');
		$_re = $modifyProfile->getSafeInfoByUserID($token['UserID']);

		$rb['uRealName']  = $_re['uRealName'];
		$rb['uAuthName']  = $_re['uAuthName'];
		$rb['uSafeEmail'] = $_re['uSafeEmail'];
		$rb['uAuthEmail'] = $_re['uAuthEmail'];
		$rb['uSafePhone'] = $_re['uSafePhone'];
		
		$this->_return(array('state' => true, 'msg' => $rb));
	}
	/**
	 * ad广告平台生成加密验证码
	 */
	public function getAdsIdentID(){
		$access_token = isset($_GET['access_token']) ? $_GET['access_token'] : $_POST['access_token'];
		$ads_type     = isset($_GET['ads_type']) ? $_GET['ads_type'] : $_POST['ads_type'];
		
		$token = $this->checkAccessToken($access_token);
		
		$mandOAuth = $this->_getClass('MandOAuth');
		$IdentCode = $mandOAuth->doencrypt($token['UserID'].'|'.$ads_type.'|'.time());
		
		$this->_return(array('state' => true, 'IdentCode' => $IdentCode));
	}
	/**
	 * push平台取取应用用户列表
	 * soap有相应方法：GetUserListByClientID
	 */
	public function getUserListByClientID(){
		$access_token = isset($_GET['access_token']) ? $_GET['access_token'] : $_POST['access_token'];
		$client_id = isset($_GET['client_id']) ? $_GET['client_id'] : $_POST['client_id'];
		$uName = isset($_GET['uName']) ? $_GET['uName'] : $_POST['uName'];
		
		if(!$client_id){
			$this->_return(array('state' => false,'error' => 'ai103'));
		}
		
		$token = $this->checkAccessToken($access_token);
		
		$tArr['client_id'] = $client_id;
		$tArr['uName']     = $uName;
		
		$mandOAuthLog = $this->_getClass('MandOAuthLog');
		$list = $mandOAuthLog->getUserListByClientID($tArr);
		
		$this->_return(array('state' => true, 'data' => $list));
	}
	/**
	 * 值加密
	 */
	public function doencrypt(){
		$html = '';
		$html .= '<form method="get" action="/db/doencrypt">';
		$html .= '加密前：<input type="text" name="encrypt" value="'.$_GET['encrypt'].'" size="60" />';
		$html .= '&nbsp;&nbsp;<input type="submit" value="提交" />';
		$html .= '</form>';
		
		if($_GET['encrypt']){
			$mandOAuth = $this->_getClass('MandOAuth');
			$html .= '加密后：'.$mandOAuth->doencrypt(trim($_GET['encrypt']));
		}
		
		echo $html;
	}
	/**
	 * 值解密
	 */
	public function dodecrypt(){
		$html = '';
		$html .= '<form method="get" action="/db/dodecrypt">';
		$html .= '解密前：<input type="text" name="decrypt" value="'.$_GET['decrypt'].'" size="60" />';
		$html .= '&nbsp;&nbsp;<input type="submit" value="提交" />';
		$html .= '</form>';
		
		if($_GET['decrypt']){
			$mandOAuth = $this->_getClass('MandOAuth');
			$html .= '解密后：'.$mandOAuth->dodecrypt(trim($_GET['decrypt']));
		}
		
		echo $html;
	}
	/**
	 * 测试方法
	 */
	public function test(){
		$a = Array
		(
				'access_token' => 'Z0NuTlNlYlVUV3g5eHpISFNua0F3YXBZckVLd0xQV0I%3D',
				'client_id' => 'app15'
		);
		
		$url = '/db/expAuthUserAndEncrypt?'.http_build_query($a);
		
		echo $url;
		
		$this->redirect($url);
	}
	/**
	 * 上传图片测试
	 */
	public function loadPic(){
?>
	<html>
	<head></head>
	<body>
		<form method="post" enctype="multipart/form-data" action="http://expand.dbowner.com/file/up">
			<input type="file" name="filename" />
			<input type="submit" value="submit" />
		</form>
	</body>
	</html>
<?php
	}
}
?>
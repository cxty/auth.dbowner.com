<?php
/**
 *
 * @author wbqing405@sina.com
 *
 * 框架登录
 *
 */
class iframeMod extends commonMod{
	
	/**
	 * 第三方嵌入登录
	 */
	public function login () {
		$this->DBWriteTrace->write('login1');
		
		$this->assign ( 'title', Lang::get('Index_title') );
		
		//$this->_deLoginBefore();
		module('index')->_deLoginBefore();
		
		if ( strtolower($_GET['ident']) == 'oauthlogin' && $_COOKIE['UserID']) {
			$user = new User($this->model);
			if ( $user->getUserOnlineLogID(ComFun::getCookies('UserID')) == -1 ) {
				ComFun::destoryCookies();
				//$this->redirect('?' . ComFun::makeCallBack($_GET));
				$this->redirect($this->config['db_oauth']['host'] . '/index/loginCallBack?'.ComFun::makeCallBack($_GET));
			}
		}
		
		DBOError::write(' index-login-1 | ' . time() . ' | ' . json_encode($_GET));
		
		//二维码登录图
		$this->assign('qrCode', '/qrCode/dirGetQRCode');
		
		//登录显示初始化
		$this->assign('loginWay', json_encode(array(
				'loginNum' => $this->config['DB']['Login']['loginNum'],//isset($_COOKIE['loginNum']) ? (intval(ComFun::getCookies('loginNum'))-1) : $this->config['DB']['Login']['loginNum'],
				'loginType' => $this->config['DB']['Login']['loginType'],//isset($_COOKIE['loginType']) ? ComFun::getCookies('loginType') : $this->config['DB']['Login']['loginType'],
				'Auth_Platform' => $this->config['db_oauth']['host']
		)));
		
		$this->DBWriteTrace->write('login2');
		
		$redirect = ComFun::makeCallBack($_GET);
		
		$this->DBWriteTrace->write('login3');
		
		$this->assign('redirect',$redirect);
		$this->assign('clientSDK', $this->config['DB']['QRCode']['clientSDK']);
		
		if ( !isset($_COOKIE['ident']) ) {
			ComFun::SetCookies(ComFun::pickCallBack($_GET)); //登录成功后的回调地址
		}
		
		$this->DBWriteTrace->write('login4');
		
		//是否已经登录过，登录过直接跳转到回调地址
		if(ComFun::getCookies('UserID')){
			$this->redirect($this->config['db_oauth']['host'] . '/index/loginCallBack?'.$redirect);
		}
		
		$this->DBWriteTrace->write('login5');
		
		$apiArr = ComFun::getAPIConfig();
		
		$this->DBWriteTrace->write('login6');
		
		DBOError::write(' index-login-2 | ' . time() . ' | ' . json_encode($_GET));
		
		$showpro = $_GET['show_pro'] ? $_GET['show_pro'] : '';
		$i = 0;
		foreach($apiArr['providers'] as $key=>$val){
			if($val['enabled']){
				$isShow = false;
		
				if ( $showpro ) {
					if ( in_array(strtolower($key), explode(',', $showpro)) ) {
						$isShow = true;
					}
				} else {
					$isShow = true;
				}
		
				if ( $isShow === true ) {
					$JsonKeyArr[] = '"'.$key.'":{"txt":"'.$val['txt'].'","icon":"'.$val['icon'].'"}';
					$thirdLogin[$i]['partner'] = $key;
					$thirdLogin[$i]['txt']     = $val['txt'];
					$thirdLogin[$i]['icon']    = $val['icon'];
					$i++;
				}
			}
		}
		
		$this->DBWriteTrace->write('login7');
		
		DBOError::write(' index-login-3 | ' . time() . ' | ' . json_encode($_GET));
		
		$this->assign('thirdLogin', $thirdLogin);
		
		$Partners_json = implode(',',$JsonKeyArr);
		$Partners_json = '{'.$Partners_json.'}';
		
		$this->assign ('Partners_json',$Partners_json);
		
		DBOError::write(' index-login-4 | ' . time() . ' | ' . json_encode($_GET));
		
		$this->assign('vData', json_encode(array(
				'host' => $this->config['PLATFORM']['Auth_https'],
				'domain' => $this->config['PLATFORM']['Auth'],
				'type' => 'iframe',
		)));
		
		//错误信息，从checkLogin传过来的
		$this->assign('msgkey', $_GET['msgkey'] ? $this->_Lang['JS_LANG'][$_GET['msgkey']] : '');
		
		//登录验证地址
		$this->assign('checkLoginUrl', $this->config['PLATFORM']['Auth'] . '/iframe/checkLogin?' . $redirect);
		
		ComFun::SetCookies(array('redirectParentType' => true, 'ident' => 'iframe', 'iframe_client_id' => $_GET['client_id']));
		
		$this->assign('bg_trans', $_GET['bg_trans'] ? $_GET['bg_trans'] : 0);
		
		$this->assign('ismobile', ComFun::checkBrowseBool() ? 1 : 0);
		
		if ( ComFun::checkBrowseBool() ) {
			$this->display('index/login_yannyo_mo.html');
		} else {
			$this->display('index/login_yannyo.html');
			//$this->display('index/login_nature.html');
		}
	}
	
	/**
	 * 登录用户账户检测
	 */
	public function checkLogin () {
		$msgkey = 'Ex_Illegality';
		
		if ( $_POST ) {
			//$login = $this->getClass('Login');
			$login = new Login($this->model);
		
			$UserID = $login->doCheckLogin($_POST);
		
			if ( $UserID > 0 ) {
				echo '<script>history.back();</script>';exit;
			} else {
				$msgkey = 'LoginWrongRemind';
			}
			
			//设置初始化登录方式
			$cookies['loginNum'] = $this->config['DB']['Login']['loginNum'];
			$cookies['loginType'] = $this->config['DB']['Login']['loginType'];
			ComFun::SetCookies($cookies);
			
		}
		
		$url = '/iframe/login?' . ComFun::makeCallBack($_GET) . '&msgkey=' . $msgkey;
		
		$this->redirect($url);
	}
}
<?php
/**
 *
 * @author wbqing405@sina.com
 *
 * 分享插件接口
 *
 */
class shareMod extends commonMod {
	
	/**
	 * 分享插件数据初始化
	 */
	private function _init () {
		$this->dbPlugInShare = $this->_getClass( 'DB_PlugInShare' );
		
		$this->_requestLog();
	}
	
	/**
	 * 请求数据记录
	 */
	private function _requestLog () {
		$tArr['pslModuleName']  = $_GET['_module'];
		$tArr['pslActionName']  = $_GET['_action'];
		
		$_pslRequestData = '';
		unset($_GET['_module']);
		unset($_GET['_action']);
		if ( is_array($_GET) && count($_GET)>0 ) {
			$_pslRequestData = json_encode($_GET);
		} elseif ( is_array($_POST) && count($_POST)>0 ) {
			$_pslRequestData = json_encode($_POST);
		}
		
		$tArr['pslRequestData'] = $_pslRequestData;
		
		$this->PlugInShareLogID = $this->dbPlugInShare->addPlugInShareRequestLog($tArr);
	}
	
	/**
	 * 响应数据记录
	 */
	private function _respondLog ( $fieldArr ) {
		$tArr['PlugInShareLogID'] = $this->PlugInShareLogID;
		$tArr['pslRespondData']   = addslashes($fieldArr['pslRespondData']);
		
		$this->dbPlugInShare->addPlugInShareRespondLog($tArr);
	}
	
	/**
	 * 返回信息处理
	 */
	private function _return($state, $msg='', $data=null, $format='json') {
		if ( isset($data['error']) ) {
			$msg = ComFun::getErrorValue('client', $data['error']);
		}
		if(isset($format)){
			switch($format){
				case 'json':
					$_rb = array(
									'state' => $state, 
									'msg' => $msg , 
									'data' => $data 
								);
					break;
				default:
					$_rb = array(
									'state' => $state, 
									'msg' => $msg , 
									'data' => $data 
								);
					break;
			}
		}else{
			$_rb = array(
							'state' => $state, 
							'msg' => $msg , 
							'data' => $data 
						);
		}
		
		$_rb = json_encode($_rb);
		
		$this->_respondLog( array( 'pslRespondData' =>  $_rb ) );
		
		echo $_rb;exit;
	}
	/**
	 * 检验用户是否有权限访问接口
	 */
	private function checkUserIsValid($format, $token){
		//不是内部应用
		if ( !in_array($token['client_id'], $this->config['oauth']['login']) ) {
			//判断是否是默认权限
			$dbSoapInterface = $this->_getClass('DB_SoapInterface');
			$GLOBALS['config']['DB_Model']['DB_SoapInterface'] = $dbSoapInterface;
			$plugInArr = $dbSoapInterface->getPlugInListInfo();
			$_isDefault = false; //是否是默认插件
			if ( !$plugInArr ) {
				$this->_return(false, ComFun::getErrorValue('client', '307'), array('error'=>'307'));
			}else {
				foreach ( $plugInArr as $_k => $_v ) {
					if ( strtolower($_v['PlugInCode']) == strtolower($token['port']['plugin_share']['expand']) ) {
						$_isDefault = true;
						break;
					}
				}
			}
			
			//不是默认插件，判断用户授权是否已经授权（插件权限判断，判断的是访问Auth的相应接口权限）
			if ( !$_isDefault ) {
				$mandOAuthLog = $this->_getClass('MandOAuthLog');
				$permValue = $mandOAuthLog->getPermValue($token);
				if ( !$permValue ) {
					$this->_return(false, ComFun::getErrorValue('client', '305'), array('error'=>'305'));
				} else {
					$permArr = explode('|', substr($permValue, 1));
					$_isPerm = false;
					foreach ( $permArr as $_k => $_v ) {
						if ( strtolower($_v) == strtolower($token['port']['plugin_share']['auth']) ) {
							$_isPerm = true;
							break;
						}
					}
					if ( !$_isPerm ) {
						$this->_return(false, ComFun::getErrorValue('client', '304'), array('error'=>'304'));
					}
				}
			}
		}
	}
	/**
	 * access_token鉴权
	 */
	private function checkAccess($format,$access_token){
		$MandOAuth = $this->_getClass('MandOAuth');
		$token = $MandOAuth->reAccessToken($access_token);
		
		$tokenInfo = $MandOAuth->getTokenInfo($token[1]);
		
		if($tokenInfo == -1){
			$this->_return(false, ComFun::getErrorValue('client', '129'), array('error'=>'129'));
		}
	
		$re = $MandOAuth->checkAccessToken($tokenInfo);
	
		if($re['error'] != 'ok'){
			$reArr['error'] = $re['error'];
			$this->_return(false, 'failed', $reArr);
		}else{
			$tArr['UserID']    = $tokenInfo['UserID'];
			$tArr['client_id'] = $tokenInfo['client_id'];
			$backArr = $MandOAuth->checkAuthPastDue($tArr);
				
			if($backArr == -1){
				$this->_return(false, ComFun::getErrorValue('client', '127'), array('error'=>'127'));
			}elseif($backArr == -2){
				$this->_return(false, ComFun::getErrorValue('client', '128'), array('error'=>'128'));
			}else{
				return $tokenInfo;
			}
		}
	}
	
	/**
	 * 用户绑定了哪些第三方平台
	 */
	public function get_providers () {
		$this->_init();
		
		$format       = $_GET['format'] ? $_GET['format'] : $_POST['format'];
		$access_token = $_GET['access_token'] ? $_GET['access_token'] : $_POST['access_token'];
		
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		//用信息缓存
		$memKey_result = '|share|get_providers||' . $access_token . '-';
		$menVal = $this->_Cache2->get( $memKey_result );
		if ( $menVal ) {
			$this->_return(true, 'ok', $menVal );
		}
		
		//DBOwner所有第三方平台
		$_providers = array();
		$list = ComFun::getAPIConfig();
		if ( $list['providers'] ) {
			foreach ( $list['providers'] as $_k => $_v ) {
				if ( $_v['enabled'] ) {
					$_providers[] = $_k;
				}
			}
		}
		
		//若不存在access_token，则返回所有信息
		if ( !$access_token ) {
			$this->_return(true, 'ok', array(
					'providers' => $_providers,
					'banding' => array()
			));
		}
		
		//access_token合法性验证
		$token = $this->checkAccess($format,$access_token);
		
		//插件接口权限检验
		$token['port'] = $this->config['Expand']['AppPlugIn'];
		$this->checkUserIsValid($format, $token);
		
		$UserID = $token['UserID'];
		
		//access_token存在、则返回绑定的账号
		$banding = array();
		$userOAuth = $this->_getClass('UserOAuth');
		$banding = $userOAuth->getBindingThirdPartyInfo( array('UserID' => $UserID) );
		if ( $banding ) {
			foreach ( $banding as $_k => $_v ) {
				$_banding[] = $_v['uProvider'];
			}
		}
		
		$re = array(
						'providers' => $_providers,
						'banding' => $_banding
				);
		
		$this->_Cache2->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		
		$this->_return(true, 'ok', $re);
	}
	/**
	 * 发布信息到指定已绑定的第三方
	 */
	public function send_msg () {
		$this->_init();
		
		$format       = $_GET['format'] ? $_GET['format'] : $_POST['format'];
		$access_token = $_GET['access_token'] ? $_GET['access_token'] : $_POST['access_token'];
		$providers    = $_GET['providers'] ? $_GET['providers'] : $_POST['providers'];
		$content      = $_GET['content'] ? $_GET['content'] : $_POST['content'];
		
		if ( !$access_token ) {
			$this->_return(false, ComFun::getErrorValue('client', '109'), array('error'=>'109'));
		}
		
		//内容是否为空
		if ( !$content ) {
			$this->_return(false, 'content is empty', '');
		}
		
		//发送第三方检测
		if ( !$providers ) {
			$this->_return(false, 'providers is empty', '');
		} else {
			$_providers = json_decode($providers, true);
			
			//发送第三方平台请求值是否为空
			if ( empty($_providers) ) {
				$this->_return(false, 'providers is error', '');
			} else {
				foreach ( $_providers as $_v ) {
					$_tmp[] = '\'' . $_v . '\'';
				}
				$_uProvider = implode(',', $_tmp);
			}
		}
		
		//access_token合法性验证
		$token = $this->checkAccess($format,$access_token);
		
		//插件接口权限检验
		$token['port'] = $this->config['Expand']['AppPlugIn'];
		$this->checkUserIsValid($format, $token);

		$UserID = $token['UserID'];
		
		$tArr['UserID']    = $UserID;
		$tArr['uProvider'] = $_uProvider;
		
		$userOAuth = $this->_getClass('UserOAuth');
		//用信息缓存
		$memKey_result = '|share|send_msg|getBindingThirdPartyInfo-1|' . $access_token . '-';
		$menVal = $this->_Cache2->get( $memKey_result );
		if ( $menVal ) {
			$banding = $menVal;
		} else {
			$banding = $userOAuth->getBindingThirdPartyInfo( $tArr );
			
			$this->_Cache2->set( $memKey_result, $banding, $this->config['MEM_EXPIRE'] );
		}
		
		//发布信息处理
		if ( !is_array($banding)  ) {
			$this->_return(false, 'Your account had not banding thirdparty', '');
		} else {
			//发送第三方平台请求值是否为空
			foreach ( $_providers as $_k => $_v ) {
				foreach ( $banding as $_k_2 => $_v_2 ) {
					if ( strtolower($_v_2['uProvider']) == strtolower($_v) ) {
						$provider = $_v_2['uProvider'];
						$apiArr = ComFun::getNowApi($provider);
						$tArr2['partner']  = $provider;
						$tArr2['provider'] = $apiArr['provider'];
						if ( $_v_2['uPermissions'] ) {
							$tArr2['OAuthArr'] = json_decode($_v_2['uPermissions'], true);
						} else {
							$tArr2['OAuthArr'] = '';
						}
						$tArr2['content'] = $content;
						
						$_re = DBCurl::dbGet( $this->config['PLATFORM']['Auth_https'] . '/db/sendMsg', 'POST', $tArr2);
						
						$_rb[$_k]['provider'] = $_v;
						if ( !$_re['state'] ) {
							$_rb[$_k]['state'] = false;
							$_rb[$_k]['info']  = $_re['data'];
						} else {
							$_rb[$_k]['state'] = true;
							$_rb[$_k]['info']  = '';
						}
						break;
					} else {
						$_rb[$_k]['provider'] = $_v;
						$_rb[$_k]['state']    = false;
						$_rb[$_k]['info']     = 'not binding the third party';
					}
				}
			}	
			
			$tArr['psmAccessToken']   = $access_token;
			$tArr['psmSendFlatform']  = $providers;
			$tArr['psmContentData']   = $content;
			$tArr['psmSendRespond']   = json_encode($_rb);
			$this->dbPlugInShare->addPlugInShareMsgLog($tArr);
			
			$this->_return(true, 'ok', $_rb);
		}
	}
	/**
	 * 关注第三方账号
	 */
	public function attention () {
		$this->_init();
		
		$format       = $_GET['format'] ? $_GET['format'] : $_POST['format'];
		$access_token = $_GET['access_token'] ? $_GET['access_token'] : $_POST['access_token'];
		$providers    = $_GET['providers'] ? $_GET['providers'] : $_POST['providers'];
		$uid          = $_GET['uid'] ? $_GET['uid'] : $_POST['uid'];
		$name         = $_GET['name'] ? $_GET['name'] : $_POST['name'];
		
		//关注对象是否为空
		if ( !$uid ) {
			if ( !$name ) {
				$this->_return(false, 'uid or name is empty', '');
			}
		}
		
		//发送第三方检测
		if ( !$providers ) {
			$this->_return(false, 'providers is empty', '');
		} else {
			$_providers = json_decode($providers, true);
			
			//发送第三方平台请求值是否为空
			if ( empty($_providers) ) {
				$this->_return(false, 'providers is error', '');
			} else {
				foreach ( $_providers as $_v ) {
					$_tmp[] = '\'' . $_v . '\'';
				}
				$_uProvider = implode(',', $_tmp);
			}
		}
		
		//access_token合法性验证
		$token = $this->checkAccess($format,$access_token);
		
		//插件接口权限检验
		$token['port'] = $this->config['Expand']['AppPlugIn'];
		$this->checkUserIsValid($format, $token);

		$UserID = $token['UserID'];
		
		$tArr['UserID']    = $UserID;
		$tArr['uProvider'] = $_uProvider;
		
		$userOAuth = $this->_getClass('UserOAuth');
		//用信息缓存
		$memKey_result = '|share|attention|getBindingThirdPartyInfo-1|' . $access_token . '-';
		$menVal = $this->_Cache2->get( $memKey_result );
		if ( $menVal ) {
			$banding = $menVal;
		} else {
			$banding = $userOAuth->getBindingThirdPartyInfo( $tArr );
				
			$this->_Cache2->set( $memKey_result, $banding, $this->config['MEM_EXPIRE'] );
		}
		
		//关注第三方账号
		if ( !is_array($banding)  ) {
			$this->_return(false, 'Your account had not banding thirdparty', '');
		} else {
			//发送第三方平台请求值是否为空
			foreach ( $_providers as $_k => $_v ) {
				foreach ( $banding as $_k_2 => $_v_2 ) {
					if ( strtolower($_v_2['uProvider']) == strtolower($_v) ) {
						$provider = $_v_2['uProvider'];
						$apiArr = ComFun::getNowApi($provider);
						$tArr2['partner']  = $provider;
						$tArr2['provider'] = $apiArr['provider'];
						if ( $_v_2['uPermissions'] ) {
							$tArr2['OAuthArr'] = json_decode($_v_2['uPermissions'], true);
						} else {
							$tArr2['OAuthArr'] = '';
						}
						if ( $uid ) {
							$tArr2['uid'] = $uid;
						}
						if ( $name ) {
							$tArr2['name'] = $name;
						}
						
						$_re = DBCurl::dbGet( $this->config['PLATFORM']['Auth_https'] . '/db/attention', 'POST', $tArr2);
						
						$_rb[$_k]['provider'] = $_v;
						if ( !$_re['state'] ) {
							$_rb[$_k]['state'] = false;
							$_rb[$_k]['info']  = $_re['data'];
						} else {
							$_rb[$_k]['state'] = true;
							$_rb[$_k]['info']  = '';
						}
						
						$_rb[$_k]['provider'] = $_v;
						$_rb[$_k]['state']    = false;
						break;
					} else {
						$_rb[$_k]['provider'] = $_v;
						$_rb[$_k]['state']    = false;
						$_rb[$_k]['info']     = 'not binding the third party';
					}
				}
			}	
			
			$tArr['psaAccessToken']   = $access_token;
			$tArr['psaSendFlatform']  = $providers;
			$tArr['psaSendRespond']   = json_encode($_rb);
			$this->dbPlugInShare->addPlugInShareAttentionLog($tArr);
			
			$this->_return(true, 'ok', $_rb);
		}
	}
	
	
	/**
	 * 方法测试第一步
	 */
	public function test1(){
		exit;
		$tArr['access_token'] = 'b08za3R2OHlZTGR0TTJqV1dIR2s4U3FZUGtlU1NYb2g%3D';
		
		$url = 'https://auth.dbowner.com/share/get_providers';
		
		$re = DBCurl::dbGet($url, 'get', $tArr);
		
		ComFun::pr($re);
	}
	
	/**
	 * 方法测试分享
	 */
	public function test2(){
		exit;
		$tArr['access_token'] = 'b08za3R2OHlZTGR0TTJqV1dIR2s4U3FZUGtlU1NYb2g%3D';
		$tArr['providers'] = json_encode(array('sina'));
		$tArr['content'] = '分享内容测试';
		
		$url = 'https://auth.dbowner.com/share/send_msg';
		
		$re = DBCurl::dbGet($url, 'get', $tArr);
		
		ComFun::pr($re);
	}
}
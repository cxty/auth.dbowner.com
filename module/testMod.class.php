<?php
/**
 *
 * CURL处理D币
 *
 * @author wbqing405@sina.com
 *
 */
class testMod extends commonMod {
	public function _empty () {
		echo '空模板';
	}
	
	/**
	 * 返回信息处理
	 */
	private function _return($state, $msg='', $data=null, $format='json') {
		//方法改造后返回参数进行变动（为了兼容改造前接口规范，特此处理）
		if ( isset($data['error']) ) {
			$_rb['data']  = $data;
			$_rb['error'] = $data['error'];
		} else {
			$_rb = $data;
		}
	
		$_rb['state'] = $state;
		$_rb['msg']   = $msg;
	
		$_rb = json_encode($_rb);
	
		//$this->_respondLog( array( 'pslRespondData' =>  $_rb ) );
	
		echo $_rb;exit;
	}
	
	public function test () {
		$key = 'aaa';
		$val = 'bbb';
		$DBCacheServer = new DBCacheServer($this->config);
	//	$a = $DBCacheServer->set($key, $val);
		//ComFun::pr($a);
		$b = $DBCacheServer->get($key);
		ComFun::pr($b);exit;
		
		
		ComFun::pr(ComFun::getCookies());exit;
		ComFun::SetCookies(array('me' => true));//exit;
		ComFun::pr(ComFun::getCookies());
		$identArr = $this->config['Login']['Cookies'];
		
		//清空作为第三方登录授权cookies信息
		$unsetArr = array();
		if ( $identArr && $_COOKIE ) {
			foreach ( $identArr as $k => $v ) {
				if ( ComFun::getCookies('ident') == $v ) {
					$unsetArr['ident'] = ComFun::getCookies('ident');
				}
				foreach ( $_COOKIE as $k2 => $v2 ) {
					if ( strstr($k2, $v) ) {
						$unsetArr[$k2] = $v2;
					}
				}
			}
			
			if ( $unsetArr ) {
				//ComFun::destoryCookies($unsetArr);
			}
		}
		
		ComFun::pr($unsetArr);
		exit;
		$msg = 'bb[product]cc'; 
		echo $msg;
		echo '<br>';
		//$msg = preg_replace("/<span>.+<\/span>/is", "_eee_", $msg);
		//$msg = preg_replace("/<[^>]+>/", "", $msg);
		$msg = preg_replace("/\[product\]/", "bb_product_1", $msg);
		echo $msg;
		exit;
		
		$_sql_Str = 'product product_1 bb_product_1 ,product product, ,product,';
		
		$_sql_Str = 'bb_product_1';
		
		echo preg_replace("/product/", "product_aaa", $_sql_Str);
		
		exit;
		
		
// 		$_sql_obj_re = '/\.sql\(\"(.*?)\"\)/is';
// 		$_sql_Str = '';
// 		if ( $c=preg_match_all($_sql_obj_re, $_sql_Str, $matches) ) {
// 			$_sql_Str = $matches[1][0];
// 		}
// 		ComFun::pr($_sql_Str);
// 		exit;
		
		$_patternsTableArr = '^(product)$';
		
		$_aliasTableArr = 'product_abc';
		
		$_sql_Str = preg_match($_patternsTableArr, $_aliasTableArr, $_sql_Str);
		ComFun::pr($_sql_Str);
		echo $_sql_Str . '<br>';
		exit;
		
		
// 		$string = 'The quick brown fox jumped over the lazy dog.';
// 		//echo $string . '<br>';
// 		$patterns = array();
// 		$patterns[0] = '/quick/';
// 		$patterns[1] = '/brown/';
// 		$patterns[2] = '/fox/';
// 		ComFun::pr($patterns);
// 		$replacements = array();
// 		$replacements[2] = 'bear';
// 		$replacements[1] = 'black';
// 		$replacements[0] = 'slow';
// 		//echo preg_replace($patterns, $replacements, $string);
// 		//echo '<br>';
// 		//exit;
		
		$_sql_Str = 'select product1_test_Name from product1,mytext';
		$_originTableArr = array(
				'mytext',
				'product1'
		);
		$_aliasTableArr = array(
				"mytext_2f6d7bda05ce2c4472674a275fcb0595",
				"product1_710b09a338059c837c7cc9e6c6f25c10"
		);
		if ( $_originTableArr && $_aliasTableArr ) {
			foreach ( $_originTableArr as $k => $v ) {
				$_patternsTableArr[$k] = '/' . $v . '/';
			}
			
			$_sql_Str = preg_replace($_patternsTableArr, $_aliasTableArr, $_sql_Str);
			
			echo $_sql_Str;
			
			foreach ( $_aliasTableArr as $k => $v ) {
				$_patternsTableArr[$k] = '/' . $v . '/';
			}
			
			echo '<br>';
			
			$_sql_Str = preg_replace($_patternsTableArr, $_originTableArr, $_sql_Str);
		}
		
		
		
		echo $_sql_Str;exit;
		
		echo $sql . '<br>';
		if ( $_originTableArr ) {
			foreach ( $_originTableArr as $k => $v ) {
				echo $v . '<br>';
				echo $_aliasTableArr[$k] . '<br>';
				$sql = preg_replace('(' . $v . '[^_])', $_aliasTableArr[$k], $sql);
				
			}
		}
		
		echo $sql;exit;
		
		echo  preg_replace($_originTableArr, $_aliasTableArr, $sql);
		
		ComFun::pr($newSql);
		
		echo $newSql;
		
		exit;
		
		$number = 'string%s50%s default ""';
		
		$txt = sprintf($number, '(', ')');
		echo $txt;
		
		echo '<br>';
		echo str_replace('.', '_', 'test.Name');
		exit;
		
		$var=sprintf("%04d", 2);//生成4位数，不足前面补0
		echo $var;//结果为0002
		
		exit;
		$_data = 'app39.find([{"product1":{"condition":{"test.Name":"名称"},"field":{"test.Name":"string(512)","test.BottleColor":1},"limit":10,"page":[0,10]}},{"mytext":{}}]).sql("select * from product1")';
		$_Appid_reg = '/(.*?)\.(find|save|update|remove)\((.*?)\)/is'; //by wbq 20140718
		//$_Appid_reg = '/(.*?)\.(find|save|update|remove)\((.*?)\)\.(\{.*?\})/is';
		if ($c=preg_match_all ($_Appid_reg, $_data, $matches)){
			if($matches)
			{
				
				if(count($matches)>=1){
					if($matches[1][0])
					{
						$_dataobj = $matches[1][0];
						
						$_appid_dataobj_array = explode('.',$_dataobj);
						
						if(count($_appid_dataobj_array)>0){
							$_appid = $_appid_dataobj_array[0];
						}
					}
					if($matches[2][0]){
						$_commandTag = $matches[2][0];
					}
					if($matches[3][0]){
						$_commandStr = $matches[3][0];
					}
				}
			}
		}
		
		ComFun::pr($_commandStr);
		
		exit;
		
		
		//$this->mongodb
		
		$a = '{"a":{"bb":"bbb"}}';
		echo $a;
		$b = json_decode($a,true);
		ComFun::pr($b);
		foreach ( $b as $k => $v ) {
			echo $k;			
		}
		exit;
		$data = '[{"obj":"AppDataObjName","condition":{"keyname1":"value1","keyname2":{"$gt":"value2"},"$or":[{"keyname3":"value3"},{"keyname4":"value4"}],"$like":[{"keyname5":"/value5/"},{"keyname6":"/^value6/"}],"keyname7":{"$in":[1,2,3,4]},"keyname8":{"$nin":[1,2,3,4]}},"field":{"field1":0,"field2":1},"sort":{"DataTypeAttributeName":"DESC"},"limit":10,"page":[1,10],user:{"$in":["userkey1","userkey2","userkey3","userkey4"],"$nin":["userkey5","userkey6","userkey7","userkey8"]},"id":{"$in":["_id1","_id2","_id3","_id4"],"$nin":["_id5","_id6","_id7","_id8"]}}]';
		$data = '{"obj":"AppDataObjName","condition":{"keyname1":"value1","keyname2":{"$gt":"value2"},"$or":[{"keyname3":"value3"},{"keyname4":"value4"}],"$like":[{"keyname5":"/value5/"},{"keyname6":"/^value6/"}],"keyname7":{"$in":[1,2,3,4]},"keyname8":{"$nin":[1,2,3,4]}},"field":{"field1":0,"field2":1},"sort":{"DataTypeAttributeName":"DESC"},"limit":10,"page":[1,10],user:{"$in":["userkey1","userkey2","userkey3","userkey4"],"$nin":["userkey5","userkey6","userkey7","userkey8"]},"id":{"$in":["_id1","_id2","_id3","_id4"],"$nin":["_id5","_id6","_id7","_id8"]}}';
		
		$data = '{"keyname1":"value1","keyname2":{"$gt":"value2"},"$or":[{"keyname3":"value3"},{"keyname4":"value4"}],"$like":[{"keyname5":"/value5/"},{"keyname6":"/^value6/"}],"keyname7":{"$in":[1,2,3,4]},"keyname8":{"$nin":[1,2,3,4]}}';
		
		$data = '[{"objname":"AppDataObjName","condition":{"keyname1":"value1","keyname2":{"$gt":"value2"},"$or":[{"keyname3":"value3"},{"keyname4":"value4"}],"$like":[{"keyname5":"/value5/"},{"keyname6":"/^value6/"}],"keyname7":{"$in":[1,2,3,4]},"keyname8":{"$nin":[1,2,3,4]}},"field":{"field1":0,"field2":1},"sort":{"DataTypeAttributeName":"DESC"},"limit":10,"page":[1,10],user:{"$in":["userkey1","userkey2","userkey3","userkey4"],"$nin":["userkey5","userkey6","userkey7","userkey8"]},"id":{"$in":["_id1","_id2","_id3","_id4"],"$nin":["_id5","_id6","_id7","_id8"]}}]';
		
		$data = '[{"objname":"AppDataObjName","condition":{"keyname1":"value1","keyname2":{"$gt":"value2"},"$or":[{"keyname3":"value3"},{"keyname4":"value4"}],"$like":[{"keyname5":"/value5/"},{"keyname6":"/^value6/"}],"keyname7":{"$in":[1,2,3,4]},"keyname8":{"$nin":[1,2,3,4]}},"field":{"field1":0,"field2":1},"sort":{"DataTypeAttributeName":"DESC"},"limit":10,"page":[1,10],"user":{"$in":["userkey1","userkey2","userkey3","userkey4"],"$nin":["userkey5","userkey6","userkey7","userkey8"]},"id":{"$in":["_id1","_id2","_id3","_id4"],"$nin":["_id5","_id6","_id7","_id8"]}}]';
		
		echo $data;
		
		ComFun::pr(json_decode($data));
		
		//e10adc3949ba59abbe56e057f20f883e
		exit;
		echo md5('dbo1q2w3e');exit;
		$email = '379182261@qqq.com';
		$username     = '吴本清';
		$password     = '123456';
		$isEmail = true;
		
		$User = new User($this->model);
		$re = $User->getEmailUserIDNew($email);
		
		if ( $re ) {
			$this->_return(true, 'OK', array(
					'email' => $re['uEmail'],
					'username' => $re['uName']
			));
		} else {
			$emailArr = ComFun::getEmail($email);
		
			if ( $emailArr['state'] ) {
				$Login = new Login($this->model);

				$uName = $Login->checkRepeatName($username ? $username : $emailArr['str']);
				
				$Login->addNewUserDir(array(
						'uEmail' => $email,
						'uName' => $uName,
						'uPWD' => $password ? $password : $this->config['Default_Pwd'],
						'isEmail' => $isEmail ? true : false
				));
				
				$this->_return(true, 'OK', array(
						'email' => $email,
						'username' => $uName
				));
			} else {
				$this->_return(false, ComFun::getErrorValue('client', '319'), array( 'error' => '319' ) );
			}
		}
		exit;
		ComFun::pr($re);exit;
		
		$redis = new Redis();
		ComFun::pr($redis);
		exit;
		
		$like_obj = array(
				
		);
		$like_array = array();
		
		$sessionid = session_id();
		$tArr['cSessionID'] = $sessionid;
		
		echo $sessionid;exit;
		ini_set('display_errors', true);
		error_reporting(E_ALL);
		
		$key = 'ddddd';
		$val = array('a' => 'b');
		//$val = 'ccccc';
		
		$re = $this->DBCacheServer->get($key);
		if ( $re['data'] ) {
			$msg = 1;
		} else {
			$msg = 2;
				
			$this->DBCacheServer->set($key, json_encode($val));
		}
ComFun::pr($re);
		echo $msg;exit;
		$DBOwnerSoapClient_Dev = new DBOwnerSoapClient_Dev($this->config);
		
		exit;
		
		//$re = $this->DBCacheServer->set('dbtest','tete');
		//ComFun::pr($re);
		
		$re = $this->DBCacheServer->get('dbtest');
		ComFun::pr($re);
		exit;
		
		$Http_Cache = 'http://push.dbo.so:8090/get';
		//$Http_Cache = 'http://127.0.0.1:8088/get';
		//$Http_Cache = 'http://192.168.0.123:8090/set';
		
		$fields['key']     = "tests";
		//$fields['key']     = json_encode(array('test','tests'));
		//$fields['key']     = "tests";
		$fields['value']   =  ' ###'; 
		//$fields['time']   =  time() + 60;
		
		ComFun::pr($fields);
		
		echo $Http_Cache . '<br>';
		
		echo date('Y-m-d H:i:s') . '<br>';
		
		$re = DBCurl::dbGet($Http_Cache, 'post', $fields);
		
		echo date('Y-m-d H:i:s') . '<br>';
		
		ComFun::pr($re);exit;
		
		$ci = curl_init();
		curl_setopt($ci, CURLOPT_URL, $Http_Cache );
		curl_setopt($ci, CURLOPT_POST, 1 );
		curl_setopt($ci, CURLOPT_POSTFIELDS, $fields );

		$response = curl_exec($ci);
		
		curl_close ($ci);
		
		echo $response;exit;
		
		
// 		ob_start();
// 		curl_exec($ch);
// 		$body = ob_get_contents();
// 		ob_end_clean();
// 		curl_close($ch);
		
		echo $body;exit;
		
		exit;
		
		$value = '测试信息';
		$this->DBWriteTrace->write($value);
		echo $value;
// 		$value = '循环测试';
// 		for ( $i=1; $i<5000; $i++ ) {
// 			$this->DBWriteTrace->write($value);
// 		}
		
		
		exit;
		$aa = 'aaaaa';
		$key = 'dbowner_test';
		
		echo $aa . '<br />';
		
		$value = $this->_Cache->get($key);
		
		if ( $value ) {
			echo '@<br>';
		} else {
			echo '#<br>';
			$value = $this->_Cache->set($key, $aa);
		}
		
		
		echo $value . '<br />';
		
		$aa = 'bbbb';
		$key = 'dbowner_test_2';
		
		echo $aa . '<br />';
		
		$value = $this->_Cache->get($key);
		
		if ( $value ) {
			echo '@<br>';
		} else {
			echo '#<br>';
			$value = $this->_Cache->set($key, $aa);
		}
		
		
		echo $value . '<br />';
		
		exit;
		
		
		if ( $_GET['access_token'] ) {
			echo 1;exit;
		} else {
			
		}
		$this->display('test/test.html');
		exit;
		ini_set('display_errors', true);
		error_reporting(E_ALL);
		
		$Type = 2;
		$FromAddress = '嘀嗒哆咪';
		$Parameters[0]['mSender'] = '嘀嗒哆咪';
		$Parameters[0]['mAccepter'] = '吴本清';
		$Parameters[0]['mTitle'] = '邮件发送标题';
		$Parameters[0]['uContent'] = '邮件发送内容';
		
		$SendTime = time();
		ComFun::pr($Parameters);
		$DBOwnerSoapClient_MsgQueue = new DBOwnerSoapClient_MsgQueue( $this->config );
		
		$rb = $DBOwnerSoapClient_MsgQueue->AddMessageTask ( $Type, $FromAddress, $Parameters, $SendTime );
		ComFun::pr($rb);
		exit;
		
		
		$mandOAuth = new MandOAuth($this->model);
		
		$UserID = ComFun::getCookies('UserID');
		$client_id = ComFun::getCookies('client_id');
		
		$LogArr['UserID']    = $UserID;
		$LogArr['client_id'] = $client_id;
		
		//对应用调用进行鉴权
		$user_id = $mandOAuth->getUserID($LogArr);
		
		$tArr['user_id']     = $user_id;
		$tArr['client_id']   = $client_id;
		$tArr['AppPlugInID'] = $this->config['Expand']['AppPlugIn']['InviteCode'];
		
		
		$dbApiAuth = $this->_getClass('DBApiAuth');
		$plugInfo = $dbApiAuth->checkValid($tArr);
		
		if($plugInfo['result']){
			$atArr['user_id']   = $user_id;
			$atArr['client_id'] = $client_id;
			//用户是否已经激活过
			$reIc = $dbApiAuth->checkUserHadDone($atArr);
				
			if ( $reIc['state'] && !$reIc['result'] ) {
				$furl = $this->config['PLATFORM']['Plus'].'/inviteCode/check?'.http_build_query($atArr);
				$this->assign ( 'furl', $furl );
				$this->assign ( 'pageModel', 'inviteCode' );
				$this->display ('index/index.html'); //输出模板
				exit;
			}
		}
		
		ComFun::pr($plugInfo);exit;
		exit;
		//ComFun::pr(ComFun::getCookies());
		$url = 'http://auth.dbowner.com/index/loginCallBack';
		
		echo $url;
		
		//ComFun::pr($this->config['DBOwner']['UserCenterTurn']);
		
		ComFun::pr(ComFun::getCookies());
		
		$parseUrl = parse_url($url);
		
		ComFun::pr($parseUrl);
		
		if ( $parseUrl['host'] != $this->config['Domain']['Auth'] ) {
			if ( isset($_COOKIE['client_id']) ) {
				$checkInvite = false;
				if ( isset($_COOKIE['invitecheck']) ) {
					if ( ComFun::getCookies('invitecheck') ===  true ) { 
						$checkInvite = true;
					}
				} else {
					$checkInvite = true;
				}
				if ( $checkInvite ) {
					$this->redirect('/index/loginCallBack');
				}
			} 
		} else {
			echo 2;
		}
		exit;
		
		if ( $parseUrl ) {
			if ( $parseUrl['host'] ) {
				if ( !in_array($parseUrl['host'], $this->config['DBOwner']['UserCenterTurn']) ) {
					 ComFun::SetCookies(array('_callbackurl' => $url));
				}
			}
		}
		
		exit;
		$DBOwnerSoapClient_Dev = new DBOwnerSoapClient_Dev($this->config);
		$re  = $DBOwnerSoapClient_Dev->GetAppByID('app39');
		ComFun::pr($re);exit;
		$client_id = 'app38';
		$MandOAuth = new MandOAuth($this->model);
		$appInfo = $MandOAuth->getAuthAppInfo($client_id);
		ComFun::pr($appInfo);exit;
		
		exit;
		ini_set('display_errors', true);
		error_reporting(E_ALL);
		
		$code   = ComFun::getRandom();
		
		$tArr['uEmail'] = '629022474@qq.com';
		$tArr['uName'] = ComFun::getCookies('uName');
		
		$emArr['uName']   = $tArr['uName'];
		$emArr['uEmail']  = $tArr['uEmail'];
		$emArr['uCode']   = $code;
		$emArr['type']    = 'register';
		
		ComFun::toSendMail($emArr);
		
		exit;
		
		$tArr['uEmail'] = '629022474@qq.com';
		$tArr['uName'] = ComFun::getCookies('uName');
		$user = new User($this->model);
		$user->activateAgain($tArr);
		
		exit;
		
		
		$uEmail = '379182261@qq.com';
		
		$login = new Login($this->model);
		$user = new User($this->model);
		$authInfo = $login->checkOAuthInfo();
		$userInfo = $user->getEmailUserID($uEmail); //用户信息表是否存在
		ComFun::pr($authInfo);
		ComFun::pr($userInfo);
		exit;
		$MandOAuth = new MandOAuth($this->model);
		$DBOwnerSoapClient_Dev = new DBOwnerSoapClient_Dev($this->config);
		$DBOwnerSoapClient_Pay = new DBOwnerSoapClient_Pay($this->config);
		$DBOwnerSoapClient_Push = new DBOwnerSoapClient_Push($this->config);
		$DBOwnerSoapClient_Ads = new DBOwnerSoapClient_Ads($this->config);
		$DBOwnerSoapClient_Expand = new DBOwnerSoapClient_Expand($this->config);
		
		if ( $authInfo ) {
			//D币总额
			$user_id = $MandOAuth->getUserOAuthID($authInfo['UserID'], $this->config['oauth']['platform_clientid']['pay']);
			$authDBTotal = $DBOwnerSoapClient_Pay->GetDBTotal($user_id);
			$authInfo['db_total'] = $authDBTotal['state'] ? $authDBTotal['data'] : 0;
			
			//app列表
			$user_id = $MandOAuth->getUserOAuthID($authInfo['UserID'], $this->config['oauth']['platform_clientid']['dev']);
			$authAppList = $DBOwnerSoapClient_Dev->GetAppAllInfoListByUserID($user_id);
			$authInfo['list_app'] = $authAppList['count'] > 0 ? $authAppList['list'] : array();
		
			//push列表
			$user_id = $MandOAuth->getUserOAuthID($authInfo['UserID'], $this->config['oauth']['platform_clientid']['push']);
			$authPushList = $DBOwnerSoapClient_Push->GetUserAppList($user_id);
			$authInfo['list_push'] = $authPushList['state'] ? $authPushList['data'] : array();
			
			//广告列表
			$user_id = $MandOAuth->getUserOAuthID($authInfo['UserID'], $this->config['oauth']['platform_clientid']['ads']);
			$authADList = $DBOwnerSoapClient_Ads->GetUserAppList($user_id);
			$authInfo['list_ad'] = $authADList['state'] ? $authADList['data'] : array();
			
			//扩展列表
			$user_id = $MandOAuth->getUserOAuthID($authInfo['UserID'], $this->config['oauth']['platform_clientid']['expand']);
			$authExpandList = $DBOwnerSoapClient_Expand->GetUserExpandList($user_id);
			$authInfo['list_expand'] = $authExpandList['state'] ? $authExpandList['data'] : array();
		}
		
		if ( $userInfo ) {
			//D币总额
			$user_id = $MandOAuth->getUserOAuthID($userInfo['UserID'], $this->config['oauth']['platform_clientid']['pay']);
			$userDBTotal = $DBOwnerSoapClient_Pay->GetDBTotal($user_id);
			$userInfo['db_total'] = $userDBTotal['state'] ? $userDBTotal['data'] : 0;
			
			//app列表
			$user_id = $MandOAuth->getUserOAuthID($userInfo['UserID'], $this->config['oauth']['platform_clientid']['dev']);
			$userAppList = $DBOwnerSoapClient_Dev->GetAppAllInfoListByUserID($user_id);
			$userInfo['list_app'] = $userAppList['count'] > 0 ? $userAppList['list'] : array();
			
			//push列表
			$user_id = $MandOAuth->getUserOAuthID($userInfo['UserID'], $this->config['oauth']['platform_clientid']['push']);
			$userPushList = $DBOwnerSoapClient_Push->GetUserAppList($user_id);
			$userInfo['list_push'] = $userPushList['state'] ? $userPushList['data'] : array();
			
			//广告列表
			$user_id = $MandOAuth->getUserOAuthID($userInfo['UserID'], $this->config['oauth']['platform_clientid']['ads']);
			$userADList = $DBOwnerSoapClient_Ads->GetUserAppList($user_id);
			$userInfo['list_ad'] = $userADList['state'] ? $userADList['data'] : array();
			
			//扩展列表
			$user_id = $MandOAuth->getUserOAuthID($userInfo['UserID'], $this->config['oauth']['platform_clientid']['expand']);
			$userExpandList = $DBOwnerSoapClient_Expand->GetUserExpandList($user_id);
			$userInfo['list_expand'] = $userExpandList['state'] ? $userExpandList['data'] : array();
		}
		
		ComFun::pr($authInfo);
		ComFun::pr($userInfo);
		exit;
		$emArr['uName']   = '吴本清';
		$emArr['uEmail']  = '379182261@qq.com';
		$emArr['uCode']   = '0cdb5f893bb9b81eab2198c7a';
		$emArr['type']    = 'invitecode';
		ComFun::pr($emArr);
	
		ComFun::toSendMail($emArr);
		
		exit;
		$client_id = 'app36';
		$MandOAuth = new MandOAuth($this->model);
		$appInfo = $MandOAuth->getAuthAppInfo($client_id);
		
		ComFun::pr($appInfo);
	}
	public function cache () {
		//echo 1;exit;
		exit;
		$url = 'http://192.168.0.182:8090/get';
		
		$key = 'test';
		
		$re = DBCurl::dbGet($url, 'POST', array(
				'key' => $key
		));
		ComFun::pr($re);
		
		$DBCacheServer = new DBCacheServer($this->config);
		
		$key = 'test';
		$value = 'aaa';
		$ssre = $DBCacheServer->get($key);
		ComFun::pr($ssre);
		//exit;
		if ( $ssre['data'] ) {
			echo 1 . ' ' . $ssre;
		} else {
			echo 2 . ' ';
			$DBCacheServer->set($key, $value);
		}
		
		
	}
	public function server(){
		exit;
		$DBOwnerSoapClient_Dev = new DBOwnerSoapClient_Dev($this->config);
		$re = $DBOwnerSoapClient_Dev->GetIsUserOwnApp('NzRZRVZRdWdTMVQ5QWRjYThWYWRXQT09','app13');
		ComFun::pr($re);
		exit;
		include(dirname(dirname(__FILE__)).'/include/api/DBOwnerPay.php');
		$DBOwnerPay = new DBOwnerPay();
		$info = $DBOwnerPay->SelectUserInfo('aaaa');
		ComFun::pr($info);
	}
	public function soap(){
		exit;
// 		$client_id = 'app39';
// 		$soapc = new soapc($this->config['DES'], $client_id);
// 		$AppInfo = $soapc->run();
// 		ComFun::pr($AppInfo);
// 		exit;
		
		$this->appid = 'app39';
		
		// 应用扩展列表 取应用选择的插件列表
// 		$this->DBOwnerSoapClient_Expand = new DBOwnerSoapClient_Expand ( $this->config );
// 		echo date('Y-m-d H:i:s') . '<br>';
// 		$this->AppPlusList = $this->DBOwnerSoapClient_Expand->GetAppPlusListFromApp($this->appid);
// 		echo date('Y-m-d H:i:s') . '<br>';
// 		ComFun::pr($this->AppPlusList);
// 		exit;
		
		$type = 'auth';
		$tableName = 'UserAuthenticationsInfo';
		$condition = '';
		$DBSoap = new DBSoap();
		
		echo '==========table=========<br>';
		echo $tableName;
		
		echo '<br>=========insert=========<br>';
		
		
		$a = '{"raAppID":"app5","raDriveID":"3c8c6ld9fv7jqqukbwfrop69cb9ntkw43eh0qoxg","raDriveType":"2","raAdpCode":"gj3gqbfzbqqxfwzsngirpdgt9wonelbi","raAdType":"1","raIP":"019.112.190.020","raAdCode":"DBOwnerAdCode","raShowed":"1","raClicked":"0","raDel":"0","AppendTime":"1389647748","DeleteTime":"1389647758"}';
		//ComFun::pr();exit;
		
		
		$idata = json_decode($a, true);
		
// 		$idata['UserID'] = ComFun::getCookies('UserID');
// 		$idata['uName'] = '吴本清844';
// 		$ire = $DBSoap->InsertTableInfo($type, 'Insert'.$tableName, $idata);
// 		ComFun::pr($ire);
//  		exit;
		
		echo '<br>=========update=========<br>';
// 		$udata['AutoID'] = 5;
// 		$udata['Status'] = 2;
// 		$ure = $DBSoap->UpdateTableInfo($type, 'Update'.$tableName, $udata);
// 		ComFun::pr($ure);
// 		exit;

		echo '<br>=========Delete=========<br>';
// 		$ddata['AutoID'] = 1;
// 		$ure = $DBSoap->DeleteTableInfo($type, 'Delete'.$tableName, $ddata);
// 		ComFun::pr($ure);
// 		exit;
		
		echo '<br>==========select==========<br>';
// 		$where = "imName = \"QQ\"";
// 		$cond['UserID'] = 'aVB2bXpudC9yOWs9';
// 		$cond['client_id'] = '80002001';
// 		$tableName = 'UserInfo';
// 		echo date('Y-m-d H:i:s') . '<br>';
// 		$sre = $DBSoap->SelectTableInfo($type, 'Select'.$tableName, $cond);
// 		echo date('Y-m-d H:i:s') . '<br>';
// 		ComFun::pr($sre);
		
// 		exit;
		
		echo '<br>==========list==========<br>';
// 		$page=3; 
// 		$pagesize=2;
// 		$lre = $DBSoap->GetTableList($type, 'Get'.$tableName.'List', $page, $pagesize, $where);
// 		ComFun::pr($lre);
//exit;
		echo '<br>==========GET==========<br>';
	
		
		$gdata = array(
				'user_id' => 'd0Y4azBHaXJpNDg9',
				'client_id' => 'app13',
		);
		$gdata = array();
		
		$userlist = '"[\"WnJ3cGkyeDQ0QmY0OWJXeGdwQ1ZxZz09\",\"aTRESGllMTU2U013OE50TWVFTDN2Zz09\",\"b1BBeFQ2UHJtenM9\",\"QlNEajhndHdPc013OE50TWVFTDN2Zz09\",\"OVk3eW9rdWpnV0F3OE50TWVFTDN2Zz09\",\"cHZRWTZRMFhmaEV3OE50TWVFTDN2Zz09\",\"MGxWdmVQK2w0V1ZTWHZwRlpKZHRVUT09\",\"ME1XbnhKbGJZK3d3OE50TWVFTDN2Zz09\",\"SCtrL0paT1BBQ2JiUlVZWmR4L1VyUT09\",\"NVc4c3Q1SXMrSVF4ZlgxM0llNjl6QT09\",\"K0s1OU5Nb2tSQ2d3OE50TWVFTDN2Zz09\",\"U3NheTJaNjhaZ2s9\",\"TzNDeWJqcTJYazg9\",\"R0hRL0twTnNPZlU9\",\"VElFZUEvQnFwR0k9\",\"V2xORHR2MDRFakF3OE50TWVFTDN2Zz09\",\"aVB2bXpudC9yOWs9\",\"RUZqWUowV1Z5aTQ9\",\"MTVqK1pTRHk5QnBTWHZwRlpKZHRVUT09\",\"eWhncDN0c3p4TkV2KzRvSndtSjVxdz09\",\"UXVRN3RPcjF4c293OE50TWVFTDN2Zz09\",\"ak83cWRMNWJzMWd3OE50TWVFTDN2Zz09\",\"NmhlQ0FQRnUzT1F3OE50TWVFTDN2Zz09\",\"WnRaZ1pEVmdZaVE9\",\"cVJVbkZ6TDM1eTlTUit6L0d3MThmdz09\",\"OVUwM3pWMDZDZUV3OE50TWVFTDN2Zz09\",\"UGlPQ3BET29VYUV3OE50TWVFTDN2Zz09\",\"ZWRXbnE0L1lUZjB3OE50TWVFTDN2Zz09\",\"Y1lSWWJWQU9YK2t3OE50TWVFTDN2Zz09\",\"NHFTZzM5dklUaXc9\",\"SnpvTHlFL2c2L1F3OE50TWVFTDN2Zz09\",\"eUN3dGpPNlRGQ1F3OE50TWVFTDN2Zz09\",\"Z0dGR3d4Zmc3bWlGaHh0NGp5RlRWQT09\",\"TWVLWjB3Wll5dmN3OE50TWVFTDN2Zz09\",\"dys1VGR2a0NuNGN3OE50TWVFTDN2Zz09\",\"K3Q2WkVGTzFhbmc9\",\"UUhIeFBaVC9iKzB3OE50TWVFTDN2Zz09\",\"V21oa2htcFhVVFV3OE50TWVFTDN2Zz09\",\"OEdtRVdGQjNYWW93OE50TWVFTDN2Zz09\",\"YWl5NVNaaU82Vk13OE50TWVFTDN2Zz09\",\"UzF3KzE3dFA2MlU9\",\"NC9VTDZIbDFHaFh3WU9uVm1yWmRRZz09\",\"RnpGbWY1aUwrdnN3OE50TWVFTDN2Zz09\",\"VU1oczVMTjlvNmt3OE50TWVFTDN2Zz09\",\"UkZPRUNYeHdQRG89\",\"dGQ2ekhYUFM4SEl3OE50TWVFTDN2Zz09\",\"MTMxcnpoY2VzY013OE50TWVFTDN2Zz09\",\"cm0xd3lmWUtqQll3OE50TWVFTDN2Zz09\",\"cTJLRWZoaDNKKzR3OE50TWVFTDN2Zz09\",\"TGIrQmo3bXB6dEF3OE50TWVFTDN2Zz09\",\"dXpvRWxidnNndmN3OE50TWVFTDN2Zz09\",\"YTVQaVA3S3Z4WkJTUit6L0d3MThmdz09\",\"S3Rnbk0rSGc3azR3OE50TWVFTDN2Zz09\",\"WURWNVl5MXk1dGN3OE50TWVFTDN2Zz09\",\"TkNJTThvd2tJUmN3OE50TWVFTDN2Zz09\",\"emVJaSsxQmticUF3OE50TWVFTDN2Zz09\",\"MTVqK1pTRHk5Qm92KzRvSndtSjVxdz09\",\"Q0drTjN6SE1zMFE9\",\"MTVqK1pTRHk5Qm94ZlgxM0llNjl6QT09\",\"MTVqK1pTRHk5QnFmQ0MwNE5ndlVQQT09\",\"RkFIdVFXRU9ZWmN3OE50TWVFTDN2Zz09\",\"T2RKdEtNbFhpakk9\",\"N0tLUkoxZ1IzL0F3OE50TWVFTDN2Zz09\",\"SWtvb3RiNWVjaG89\",\"S2V0SlpVd1orTjVTUit6L0d3MThmdz09\",\"ektiUzkzYnpVZk5TUit6L0d3MThmdz09\",\"Q0IxVUVlOTVIa0U9\",\"UlFwRkk0ZzU5QUV3OE50TWVFTDN2Zz09\",\"Um95S2laSGNzeDh3OE50TWVFTDN2Zz09\",\"aldjZGJjN0pBdnBTUit6L0d3MThmdz09\",\"NE43enFsQ3VGcTFTUit6L0d3MThmdz09\",\"OEF3Z2k4N1g4R2M9\",\"NThXT1QrNVlyQ0U9\",\"eW5oQlVVZytDTmN3OE50TWVFTDN2Zz09\",\"eGRmV0xybWZoOXR6MERQNkthRlVMQT09\",\"MWQyRG5Vd0dobG93OE50TWVFTDN2Zz09\",\"cHhCWXpmNE1zbzh3OE50TWVFTDN2Zz09\",\"eG9QUXE0c3V3YTR3OE50TWVFTDN2Zz09\",\"K3F5OWYvYkE0N2R6MERQNkthRlVMQT09\",\"Ry82Q1Qra0ZKTEZTUit6L0d3MThmdz09\",\"N3hxZXh5YllDYjV6MERQNkthRlVMQT09\",\"bVRtWWwxK2t2UUk9\",\"Ymh2Zzd3cWtSdGN3OE50TWVFTDN2Zz09\",\"V1FGaStvMVUxTlE9\",\"eTNBSUkzT1YxOXc9\",\"K3dVUXFJWGlPZGc9\",\"bXhxd3NrV1lpaUk9\",\"WUdWUjhWQ29BTkp6MERQNkthRlVMQT09\",\"TFNDQTY0dXR3aDV6MERQNkthRlVMQT09\",\"OVUvSFZSd2c5K0p6MERQNkthRlVMQT09\",\"ODMzSnRIVzBic2x6MERQNkthRlVMQT09\",\"eTgyVW5BdGo3cW9tWGRPaSs4VmQvQT09\",\"UkJYNHVPRGxjOW89\",\"WlVWNW9FeGFnZVZ6MERQNkthRlVMQT09\",\"ZDFFVHRDeWNOSnh6MERQNkthRlVMQT09\",\"S29HdVhnQ1Q5UHR6MERQNkthRlVMQT09\",\"dzczMGZDM2gwckp6MERQNkthRlVMQT09\",\"NTNFZWN5MGtPcVp6MERQNkthRlVMQT09\",\"MHF4OEhzZStJWnB6MERQNkthRlVMQT09\",\"SVA0MDVZdWc0eHh6MERQNkthRlVMQT09\",\"ZFVrMXZQT21XVk56MERQNkthRlVMQT09\",\"YmJVTmZNbEErYlJ6MERQNkthRlVMQT09\",\"VWllc2FMNlpjWlZ6MERQNkthRlVMQT09\",\"QkxxVWlWQkRLeHB6MERQNkthRlVMQT09\",\"dzhBMHA0WHNMOVJ6MERQNkthRlVMQT09\",\"anh5OW5nSCt5VjF6MERQNkthRlVMQT09\",\"NThVdWEzdEFMUFJ6MERQNkthRlVMQT09\",\"QUswNzNGTW1ZN1J6MERQNkthRlVMQT09\",\"a1Fudkt5US8vUFZ6MERQNkthRlVMQT09\",\"WTNCQ1ZNVFhLVEZ6MERQNkthRlVMQT09\",\"RkNjNlVoY1RCODl6MERQNkthRlVMQT09\",\"aHl6RnNuK2xlMU56MERQNkthRlVMQT09\",\"ejRQSW1CUUc5L0p6MERQNkthRlVMQT09\",\"UHZVWi9nV1hubGR6MERQNkthRlVMQT09\",\"bUVxbWFmNktZbHR6MERQNkthRlVMQT09\",\"NWJBTGxXSk5zQXR6MERQNkthRlVMQT09\",\"Z2laZjBsTWZuZ2xxZzhZM05MNHBKdz09\",\"Z2laZjBsTWZuZ2x4WHlhWWZtSXhGQT09\",\"Z2laZjBsTWZuZ245QWRjYThWYWRXQT09\",\"Z2laZjBsTWZuZ2ttWGRPaSs4VmQvQT09\",\"d0Y4azBHaXJpNDg9\",\"OXBSdU95eFpqVGc9\",\"RithVWhjUGx3dkJ6MERQNkthRlVMQT09\",\"\",\"a3RkbnpPdzNFbUU9\",\"RE5LUnlsWk9TUUE9\",\"c2xhYkFPaThFUTM5QWRjYThWYWRXQT09\",\"eTgyVW5BdGo3cXI5QWRjYThWYWRXQT09\",\"NzRZRVZRdWdTMVQ5QWRjYThWYWRXQT09\"]"';
		
		$userlist = '["WnJ3cGkyeDQ0QmY0OWJXeGdwQ1ZxZz09","aTRESGllMTU2U013OE50TWVFTDN2Zz09","b1BBeFQ2UHJtenM9","QlNEajhndHdPc013OE50TWVFTDN2Zz09","OVk3eW9rdWpnV0F3OE50TWVFTDN2Zz09","cHZRWTZRMFhmaEV3OE50TWVFTDN2Zz09","MGxWdmVQK2w0V1ZTWHZwRlpKZHRVUT09","ME1XbnhKbGJZK3d3OE50TWVFTDN2Zz09","SCtrL0paT1BBQ2JiUlVZWmR4L1VyUT09","NVc4c3Q1SXMrSVF4ZlgxM0llNjl6QT09","K0s1OU5Nb2tSQ2d3OE50TWVFTDN2Zz09","U3NheTJaNjhaZ2s9","TzNDeWJqcTJYazg9","R0hRL0twTnNPZlU9","VElFZUEvQnFwR0k9","V2xORHR2MDRFakF3OE50TWVFTDN2Zz09","aVB2bXpudC9yOWs9","RUZqWUowV1Z5aTQ9","MTVqK1pTRHk5QnBTWHZwRlpKZHRVUT09","eWhncDN0c3p4TkV2KzRvSndtSjVxdz09","UXVRN3RPcjF4c293OE50TWVFTDN2Zz09","ak83cWRMNWJzMWd3OE50TWVFTDN2Zz09","NmhlQ0FQRnUzT1F3OE50TWVFTDN2Zz09","WnRaZ1pEVmdZaVE9","cVJVbkZ6TDM1eTlTUit6L0d3MThmdz09","OVUwM3pWMDZDZUV3OE50TWVFTDN2Zz09","UGlPQ3BET29VYUV3OE50TWVFTDN2Zz09","ZWRXbnE0L1lUZjB3OE50TWVFTDN2Zz09","Y1lSWWJWQU9YK2t3OE50TWVFTDN2Zz09","NHFTZzM5dklUaXc9","SnpvTHlFL2c2L1F3OE50TWVFTDN2Zz09","eUN3dGpPNlRGQ1F3OE50TWVFTDN2Zz09","Z0dGR3d4Zmc3bWlGaHh0NGp5RlRWQT09","TWVLWjB3Wll5dmN3OE50TWVFTDN2Zz09","dys1VGR2a0NuNGN3OE50TWVFTDN2Zz09","K3Q2WkVGTzFhbmc9","UUhIeFBaVC9iKzB3OE50TWVFTDN2Zz09","V21oa2htcFhVVFV3OE50TWVFTDN2Zz09","OEdtRVdGQjNYWW93OE50TWVFTDN2Zz09","YWl5NVNaaU82Vk13OE50TWVFTDN2Zz09","UzF3KzE3dFA2MlU9","NC9VTDZIbDFHaFh3WU9uVm1yWmRRZz09","RnpGbWY1aUwrdnN3OE50TWVFTDN2Zz09","VU1oczVMTjlvNmt3OE50TWVFTDN2Zz09","UkZPRUNYeHdQRG89","dGQ2ekhYUFM4SEl3OE50TWVFTDN2Zz09","MTMxcnpoY2VzY013OE50TWVFTDN2Zz09","cm0xd3lmWUtqQll3OE50TWVFTDN2Zz09","cTJLRWZoaDNKKzR3OE50TWVFTDN2Zz09","TGIrQmo3bXB6dEF3OE50TWVFTDN2Zz09","dXpvRWxidnNndmN3OE50TWVFTDN2Zz09","YTVQaVA3S3Z4WkJTUit6L0d3MThmdz09","S3Rnbk0rSGc3azR3OE50TWVFTDN2Zz09","WURWNVl5MXk1dGN3OE50TWVFTDN2Zz09","TkNJTThvd2tJUmN3OE50TWVFTDN2Zz09","emVJaSsxQmticUF3OE50TWVFTDN2Zz09","MTVqK1pTRHk5Qm92KzRvSndtSjVxdz09","Q0drTjN6SE1zMFE9","MTVqK1pTRHk5Qm94ZlgxM0llNjl6QT09","MTVqK1pTRHk5QnFmQ0MwNE5ndlVQQT09","RkFIdVFXRU9ZWmN3OE50TWVFTDN2Zz09","T2RKdEtNbFhpakk9","N0tLUkoxZ1IzL0F3OE50TWVFTDN2Zz09","SWtvb3RiNWVjaG89","S2V0SlpVd1orTjVTUit6L0d3MThmdz09","ektiUzkzYnpVZk5TUit6L0d3MThmdz09","Q0IxVUVlOTVIa0U9","UlFwRkk0ZzU5QUV3OE50TWVFTDN2Zz09","Um95S2laSGNzeDh3OE50TWVFTDN2Zz09","aldjZGJjN0pBdnBTUit6L0d3MThmdz09","NE43enFsQ3VGcTFTUit6L0d3MThmdz09","OEF3Z2k4N1g4R2M9","NThXT1QrNVlyQ0U9","eW5oQlVVZytDTmN3OE50TWVFTDN2Zz09","eGRmV0xybWZoOXR6MERQNkthRlVMQT09","MWQyRG5Vd0dobG93OE50TWVFTDN2Zz09","cHhCWXpmNE1zbzh3OE50TWVFTDN2Zz09","eG9QUXE0c3V3YTR3OE50TWVFTDN2Zz09","K3F5OWYvYkE0N2R6MERQNkthRlVMQT09","Ry82Q1Qra0ZKTEZTUit6L0d3MThmdz09","N3hxZXh5YllDYjV6MERQNkthRlVMQT09","bVRtWWwxK2t2UUk9","Ymh2Zzd3cWtSdGN3OE50TWVFTDN2Zz09","V1FGaStvMVUxTlE9","eTNBSUkzT1YxOXc9","K3dVUXFJWGlPZGc9","bXhxd3NrV1lpaUk9","WUdWUjhWQ29BTkp6MERQNkthRlVMQT09","TFNDQTY0dXR3aDV6MERQNkthRlVMQT09","OVUvSFZSd2c5K0p6MERQNkthRlVMQT09","ODMzSnRIVzBic2x6MERQNkthRlVMQT09","eTgyVW5BdGo3cW9tWGRPaSs4VmQvQT09","UkJYNHVPRGxjOW89","WlVWNW9FeGFnZVZ6MERQNkthRlVMQT09","ZDFFVHRDeWNOSnh6MERQNkthRlVMQT09","S29HdVhnQ1Q5UHR6MERQNkthRlVMQT09","dzczMGZDM2gwckp6MERQNkthRlVMQT09","NTNFZWN5MGtPcVp6MERQNkthRlVMQT09","MHF4OEhzZStJWnB6MERQNkthRlVMQT09","SVA0MDVZdWc0eHh6MERQNkthRlVMQT09","ZFVrMXZQT21XVk56MERQNkthRlVMQT09","YmJVTmZNbEErYlJ6MERQNkthRlVMQT09","VWllc2FMNlpjWlZ6MERQNkthRlVMQT09","QkxxVWlWQkRLeHB6MERQNkthRlVMQT09","dzhBMHA0WHNMOVJ6MERQNkthRlVMQT09","anh5OW5nSCt5VjF6MERQNkthRlVMQT09","NThVdWEzdEFMUFJ6MERQNkthRlVMQT09","QUswNzNGTW1ZN1J6MERQNkthRlVMQT09","a1Fudkt5US8vUFZ6MERQNkthRlVMQT09","WTNCQ1ZNVFhLVEZ6MERQNkthRlVMQT09","RkNjNlVoY1RCODl6MERQNkthRlVMQT09","aHl6RnNuK2xlMU56MERQNkthRlVMQT09","ejRQSW1CUUc5L0p6MERQNkthRlVMQT09","UHZVWi9nV1hubGR6MERQNkthRlVMQT09","bUVxbWFmNktZbHR6MERQNkthRlVMQT09","NWJBTGxXSk5zQXR6MERQNkthRlVMQT09","Z2laZjBsTWZuZ2xxZzhZM05MNHBKdz09","Z2laZjBsTWZuZ2x4WHlhWWZtSXhGQT09","Z2laZjBsTWZuZ245QWRjYThWYWRXQT09","Z2laZjBsTWZuZ2ttWGRPaSs4VmQvQT09","d0Y4azBHaXJpNDg9","OXBSdU95eFpqVGc9","RithVWhjUGx3dkJ6MERQNkthRlVMQT09","","a3RkbnpPdzNFbUU9","RE5LUnlsWk9TUUE9","c2xhYkFPaThFUTM5QWRjYThWYWRXQT09","eTgyVW5BdGo3cXI5QWRjYThWYWRXQT09","NzRZRVZRdWdTMVQ5QWRjYThWYWRXQT09"]';
		
		//ComFun::pr(json_decode($userlist));exit;
		
		$gdata = array(
				'AppID' => 'app13',
				'uEmail' => '379182261a@qq.com',
				'uName' => '嘀嗒哆咪',
		);
		$gdata = array(
				'AppID' => 'app26',
				'uEmail' => '379182261@qq.com',
				'uName' => 'Kais',
				'client_id' => 'app26',
		);
ComFun::pr($gdata);
		$type = 'auth';
		//$type = 'user';
		$tableName = 'GetUserInfoByEmail';
		echo date('Y-m-d H:i:s') . '<br>';
		$sre = $DBSoap->GetTableInfo($type, $tableName, $gdata);
		echo date('Y-m-d H:i:s') . '<br>';
		ComFun::pr($sre);
	}
	public function curl(){
		//exit;
		//用信息缓存
// 		$access_token   = 'SWd1RlAzMldvOWk3dEh0ZW1TWG56YW9BdEI5ZFJkZ2U';
// 		$memKey_result = '|users|checkAccess||' . $access_token . '-';
// 		$menVal = $this->_Cache->get( $memKey_result );
// 		if ( $menVal ) {
// 			$re = $menVal;
// 			$msg = 1;
// 		} else {
// 			//$re = $MandOAuth->checkAccessToken($tokenInfo);
// 			//$re = 'aaa';
// 			$msg = 2;
// 			//$this->_Cache->set( $memKey_result, $re );
// 		}
// 		$this->_Cache->set( $memKey_result, '' );
		
// 		echo $msg;
// 		ComFun::pr($re);
// 		exit;
		//exit;
		//fresh_token
		$module = 'users';
		$method = 'show';
		//$method = 'add';
		//$method = 'show_by_name';
		//$method = 'getapplist';
		//$method = 'istimeout';
		//exit;
		
		//$module = 'account';
		//$method = 'register_user';
		$url = 'https://auth.dbowner.com';
		//$url = 'http://user.dbowner.com';
		$url .= '/' . $module . '/' . $method;

		echo $url . '<br>';
		
		$tArr['access_token']   = 'SWd1RlAzMldvOWk3dEh0ZW1TWG56YW9BdEI5ZFJkZ2U';
		//$access_token = 'aaa';
		//$tArr['providers']      = json_encode(array('QQ','Sina'));
		//$tArr['content'] = 'DBOwner信息发布测试	http://www.dbowner.com';
		$tArr['name'] = '嘀嗒哆咪';
		$tArr['user_id'] = 'R0hRL0twTnNPZlU9';
		$tArr['AppID'] = 'app45';
		$tArr['email'] = '379182261@qqq.com';
		$tArr['username'] = 'app45';
		$tArr['password'] = 'app45';
		$tArr['isemail'] = true;
		///$tArr['partner'] = 'QQ';
		//$tArr['id'] = 9;
		//$tArr['accepter'] = '嘀嗒哆咪';
		//$tArr['content'] = '馒头无价内容';
		//$tArr['partner'] = 'Sina';
		
		
		//$memKey_result = '|' . $module . '|' . $method . '||' . $tArr['access_token'] . '-';
		//echo $this->config['MEM_GROUP'] . '_0_' . md5($memKey_result) . '<br>';
		//$this->_Cache->del( $memKey_result );
		
		//PLATFORM_Auth_0_|users|show_by_name||bElPVUZab3M5L3N2S2FqdzZzajV3OFVNYUg0cDlwWkg=-
		//PLATFORM_Auth_0_|users|show_by_name||bElPVUZab3M5L3N2S2FqdzZzajV3OFVNYUg0cDlwWkg=-
		//PLATFORM_Auth_0_|users|show_by_name||bElPVUZab3M5L3N2S2FqdzZzajV3OFVNYUg0cDlwWkg%3D-
		//PLATFORM_Auth_0_|users|show_by_name||bElPVUZab3M5L3N2S2FqdzZzajV3OFVNYUg0cDlwWkg%3D-
		
		//$menVal = $this->_Cache->get( $memKey_result );
		//exit;
		/*ComFun::pr($menVal);
		echo '<br>';
		ComFun::pr($tArr);
		$re = array('error' => 'this is a test');
		$this->_Cache->set( $memKey_result, $re, $this->config['MEM_EXPIRE'] );
		exit;
		*/
		
		//echo $url.'?'.http_build_query($tArr);exit;
		$tArr = array(
				'format' => 'json',
				'access_token' => 'YTVqSjhTZHViUnh1RERoYmFjdkE5dGl6M3VCRm1ZaUY%3D',
				'user_id' => 'NzRZRVZRdWdTMVQ5QWRjYThWYWRXQT09',
				'client_id' => 'app63',
		);
		
		ComFun::pr($tArr);
		echo date('Y-m-d H:i:s') . '<br>';
		$re = DBCurl::dbGet($url, 'post', $tArr);
		echo date('Y-m-d H:i:s') . '<br>';
		ComFun::pr($re);
		
		
		
		//ComFun::pr(json_decode($re['data'], true));
	}
	/**
	 * 登录验证
	 */
	public function oauth(){
		exit;
		$tArr['client_id']     = '80022003';
		$tArr['redirect_uri']  = 'http://open.dbowner.com/index/callback.html';
		$tArr['response_type'] = 'code';
		$tArr['scope'] = 'auth_thirdparty';
		$tArr['display']       = 'mobile';
 		
		//$url = __ROOT__.'/oauth/authorize?'.http_build_query($tArr);
		$url = 'http://user.dbowner.com/oauth/authorize?'.http_build_query($tArr);
		
		echo $url;exit;
		$this->redirect($url);
	}
	public function upfile () {
		exit;
		$this->assign('host', 'http://user.dbowner.com');
		$this->display('throwMessage/upfile.html');
	}
	public function get_token () {
		//exit;
		$login = $this->_getClass('Login');
		$UserOnlineLogID = $login->checkOnLineID();
		
		$tArr['UserID'] = ComFun::getCookies('UserID');
		$tArr['client_id'] = 'app13';
		$mandOAuthLog = $this->_getClass('MandOAuthLog');
		$TokenID = $mandOAuthLog->getTokenIDByUserIDAndAppID( $tArr );
	ComFun::pr($tArr);
	ComFun::pr($TokenID);
		$mandOAuth = $this->_getClass('MandOAuth');
		echo $mandOAuth->doencrypt($UserOnlineLogID . '|' . $TokenID . '|' . time());
	}
}
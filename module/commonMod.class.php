<?php
//公共模块

class commonMod {
	public $model; //数据库模型对象
	public $tpl; //模板对象
	public $config; //全局配置
	static $global; //静态变量，用来实现单例模式
	public function __construct() {
		global $DBClassObj;
		
		global $config;
		$this->config = $config; //配置
	
		$GLOBALS["config"] = $this->config;
		
		global $error;
		$this->error = $error; //错误信息配置

		//数据库模型初始化
		if (! isset ( self::$global ['model'] )) {
			self::$global ['model'] = new DBOModel ( $this->config ); //实例化数据库模型类 
		}
		$this->model = self::$global ['model']; //数据库模型对象
		
		//缓存类初始化-加密
		if (! isset ( self::$global ['_Cache'] )) {
			self::$global ['_Cache'] = new DBOCache($this->config, 'Memcache', true);
		}
		$this->_Cache = self::$global ['_Cache'];
		
		//缓存类初始化-不加密
		if (! isset ( self::$global ['_Cache2'] )) {
			self::$global ['_Cache2'] = new DBOCache($this->config, 'Memcache');
		}
		$this->_Cache2 = self::$global ['_Cache2'];
		
		if (! isset ( self::$global ['DBCacheServer'] )) {
			self::$global ['DBCacheServer'] = new DBCacheServer($this->config); // 缓存服务
		}
		$this->DBCacheServer = self::$global ['DBCacheServer']; // 数据库模型对象
		
		$DBClassObj['DBCacheServer'] = $this->DBCacheServer;
		
		//写调试信息类
		if ( ! isset ( self::$global ['DBWriteTrace'] ) ) {
			include(dirname(dirname(__FILE__)).'/include/lib/DBWriteTrace.class.php');
			self::$global ['DBWriteTrace'] = new DBWriteTrace($this->config['DBWriteTrace']);
		}
		$this->DBWriteTrace = self::$global ['DBWriteTrace'];
		

		//模板初始化
		//if (! isset ( self::$global ['tpl'] )) {
		//	self::$global ['tpl'] = new DBOTemplate ( $this->config ); //实例化模板类 
		//}
		//加载并实例化smarty类  
        if (! (file_exists($this->config['SMARTY_TEMPLATE_DIR']) && is_dir($this->config['SMARTY_TEMPLATE_DIR'])) ) {
            mkdir($this->config['SMARTY_TEMPLATE_DIR'], 0755, true);
        }
        if (! (file_exists($this->config['SMARTY_COMPILE_DIR']) && is_dir($this->config['SMARTY_COMPILE_DIR'])) ) {
            mkdir($this->config['SMARTY_COMPILE_DIR'], 0755, true);
        }
        if (! (file_exists($this->config['SMARTY_CACHE_DIR']) && is_dir($this->config['SMARTY_CACHE_DIR'])) ) {
            mkdir($this->config['SMARTY_CACHE_DIR'], 0755, true);
        }
        require_once(DBO_PATH . 'ext/smarty/Smarty.class.php');         
        $smarty                 =   new Smarty();         
        $smarty->debugging      =   $this->config['SMARTY_DEBUGGING'];              
        $smarty->caching        =   $this->config['SMARTY_CACHING'];              
        $smarty->cache_lifetime =   $this->config['SMARTY_CACHE_LIFETIME'];
        $smarty->template_dir   =   $this->config['SMARTY_TEMPLATE_DIR'];           
        $smarty->compile_dir    =   $this->config['SMARTY_COMPILE_DIR'];      
        $smarty->cache_dir      =   $this->config['SMARTY_CACHE_DIR'];   
        $smarty->left_delimiter =   $this->config['SMARTY_LEFT_DELIMITER'];
        $smarty->right_delimiter=   $this->config['SMARTY_RIGHT_DELIMITER'];
         
        self::$global['tpl']    =   $smarty;
		
		$this->tpl = self::$global ['tpl']; //模板类对象
		
		$this->getComFun(); //引入公共类方法
		
		Lang::init();
		
		Lang::init();
		
		//语言包
		$this->_Lang = Lang::getPack ();

		//语言包
		$this->assign (Lang,$this->_Lang);
		$this->assign(JS_LANG,json_encode(Lang::get('JS_LANG')));
		$this->assign(PH_LANG,json_encode(Lang::get('PH_LANG')));
		
		//随机数
		$this->assign(rand,rand());
		
		//检查是否登录
		$this->AuthCheckLogin();	
	}
	//模板变量解析
	protected function assign($name, $value) {
		return $this->tpl->assign ( $name, $value );
		
	}
	//模板输出
	protected function display($tpl = '') {
		//在模板中使用定义的常量,使用方式如{$__ROOT__} {$__APP__}
        $this->assign("__ROOT__",__ROOT__);
        $this->assign("__APP__",__APP__);
        $this->assign("__URL__",__URL__);
        $this->assign("__PUBLIC__",__PUBLIC__);
        
        //实现不加参数时，自动加载相应的模板
        $tpl=empty($tpl)?$_GET['_module'].'/'.$_GET['_action'].$this->config['TPL_TEMPLATE_SUFFIX'] : $tpl;     
        return $this->tpl->display($tpl); 
	}
	
	//直接跳转//20140521
	protected function redirect($url) {
		$callbackUrl = '';
		
		if(is_string($url)){
			$callbackUrl = $url;
		}else if(is_array($url)){
			$callbackUrl = $url['data'];
		}else if(is_object($url)){
			$callbackUrl = $url->data;
		}else{
			$callbackUrl = $url;
		}
		
		$parseUrl = parse_url($url);
		if ( $parseUrl ) {
			//对app
			if ( $parseUrl['host'] ) {
				if ( !in_array($parseUrl['host'], $this->config['DBOwner']['UserCenterTurn']) ) {
					ComFun::SetCookies(array('_callbackurl' => $url));
				}
			}
		}
		
		if(ComFun::getCookies('UserID') == 3){
			//ComFun::pr(ComFun::getCookies());
			//echo $url;exit;
		}
		
		header ( 'Location: ' . $callbackUrl, false, 301 );
		exit ();
	}
	
	//js刷新父页面
	protected function jsParentRedirect ( $url ) {
		echo '<script type="text/javascript">window.parent.location.href="' . $url . '"</script>';exit;
	}
	
	//出错之后跳转，后退到前一页
	protected function error($msg) {
		header ( "Content-type: text/html; charset=utf-8" );
		$msg = "alert('$msg');";
		echo "<script>$msg history.go(-1);</script>";
		exit ();
	}	
	/**
	 * 分页
	 */
	protected function showpage($url, $total, $perpage = 10, $pagebarnum = 5, $mode = 2){
		$page = new ShowPage();
		return $page->show($url, $total, $perpage, $pagebarnum, $mode);
	}
	
/*
功能:分页
$url，基准网址，若为空，将会自动获取，不建议设置为空 
$total，信息总条数 
$perpage，每页显示行数 
$pagebarnum，分页栏每页显示的页数 
$mode，显示风格，参数可为整数1，2，3，4任意一个 
*/
	protected function page($url, $total, $perpage = 10, $pagebarnum = 5, $mode = 1) {
		$page = new page ();
		return $page->show ( $url, $total, $perpage, $pagebarnum, $mode );
	}
	
	/*
	 * 检查是否登录
	 */
	protected function AuthCheckLogin(){
		include_once dirname(dirname(__FILE__)).'/include/lib/Login.class.php';
		$login = new Login($this->model);
		
		//全局验证，只有在登录的情况下才进行，为了提高速度
		if ( !in_array( strtolower($_GET['_module']), $this->config['NoNeedLogin'] ) ) {
			$login->updateSysDate($this->config['EXPIRE_TIME']); //更新系统信息表
		} 
		
		$UserID = ComFun::getCookies('UserID') ? ComFun::getCookies('UserID') : false;
		$OnlineID = $this->checkOnLineID();
		
		//取得头像信息
		$this->getPortrait();
		
		if($UserID && $OnlineID == 1){		
			$this->assign('urlStr',$UserID); //视图显示控制参数
			
			$login->updateLastTime(); //更新个人日志信息
			
			if($_GET['_module'] == 'main'){	
				$this->getUnreadNum();
			}		
		}
		/* //先注释，若没问题，直接删除
		 elseif($UserID && $OnlineID == -1){	
			if ( !in_array( strtolower($_GET['_module']), $this->config['NoNeedLogin'] ) ) {
				//ComFun::destoryCookies();
			}
				
			if(in_array($_GET['_module'], $this->config['DBOwner']['TurnLogin']) && !in_array($_GET['_action'], $this->config['DBOwner']['NotNeedTurnLogin'])){
				//$this->redirect('/index/index');
			}
		}
		
		 else {
			if ( !in_array( strtolower($_GET['_module']), $this->config['NoNeedLogin'] ) ) {
				//ComFun::delNoLoginCookies();
				$this->redirect('/index/index');
		 	}
		}*/
		elseif ($UserID && $OnlineID == -1) {
			ComFun::destoryCookies();
			$this->redirect(ComFun::GetThisURL());exit;
			/*
			if ( !(strtolower($_GET['_module']) == 'oauth' && strtolower($_GET['_action']) == 'authorize') ) {
				$this->redirect('/');exit;
				echo "<script>location:reload();</script>";exit;
			}
			*/
		}
	}
	/**
	 * 取未读短信息条数
	 */
	protected function getUnreadNum(){
		if ( $_COOKIE['UserID'] ) {
			include_once dirname(dirname(__FILE__)).'/include/lib/UserMessage.class.php';
			$UserMessage = new UserMessage($this->model);
			$tArr['UserID'] = ComFun::getCookies('UserID');
			$tArr['type'] = 'unreadMsg';
			$unread = $UserMessage->getUnreadNum($tArr);
			
			$this->assign('unread',$unread ? $unread : 0);
			$this->assign('uName',ComFun::getCookies('uName'));
			
			return $unread;
		} else {
			return 0;	
		}
	}
	/*
	 * 取得头像信息
	 */
	protected function getPortrait(){
		if($_COOKIE['UserID']){
			include_once dirname(dirname(__FILE__)).'/include/lib/ModifyProfile.class.php';

			$this->modifyProfile = new ModifyProfile($this->model,$this->config);
			$imagesUrl = $this->modifyProfile->getPortrait();

			$this->assign('imagesUrl_1',$imagesUrl['imagesUrl_1']);
			$this->assign('imagesUrl_2',$imagesUrl['imagesUrl_2']);
			$this->assign('imagesUrl_3',$imagesUrl['imagesUrl_3']);
			
			$this->assign('images',json_encode($this->config['IMAGES']));
			
			return $imagesUrl['imagesUrl_2'];
		} else {
			return '';
		}
	}
	/**
	 * 验证用户在线表的信息是否存在
	 */
	function checkOnLineID(){
		if ( $_COOKIE['UserID'] ) {
			include_once dirname(dirname(__FILE__)).'/include/lib/Login.class.php';
			$login = new Login($this->model);
			if($login->checkOnLineID() != -1){
				return 1;
			}else{
				return -1;
			}	
		} else {
			return -1;
		}
	}
	/**
	 * 包含公共类
	 */
	public function getComFun(){
		include_once dirname(dirname(__FILE__)).'/include/lib/ComFun.class.php';
	}
	
	protected function GetString($key, $len = 0, $def = null) {
		$_val = $_GET [$key] ? $_GET [$key] : $_POST [$key];
		if ($_val) {
			if ($len > 0) {
				return substr ( $_val, 0, $len );
			} else {
				return $_val;
			}
		} else if ($def) {
			return $def;
		} else {
			return null;
		}
	}
	
	protected function GetStringAddslashes ( $value, $default=true ) {
		if ( $default === true ) {
			return ComFun::_addslashes($this->GetString($value));
		} else {
			return ComFun::_addslashes($value);
		}
	}
	
	/*
	 * 打印类
	 */
// 	public function pr($arr=null){
// 		echo '<pre>';
// 		print_r($arr);
// 		echo '</pre>';
// 	}
	/**
	 * 返回信息处理
	 */
	protected function __return($data=null, $key='private') {
		if(isset($data['error'])){
			$data['msg'] = ComFun::getErrorValue($key, $data['error']);
		}
	
		return json_encode($data);
	}
	/**
	 * 取类
	 */
	protected function _getClass($className,$fieldArr=''){
		switch($className){
			case 'soapu':
				include(dirname(dirname(__FILE__)).'/include/lib/soapu.class.php');
				$this->config['DES']['ident'] = 'private';
				return new soapu($this->config);
				break;
			case 'DBSoap':
				include(dirname(dirname(__FILE__)).'/include/lib/DBSoap.class.php');
				return new DBSoap();
				break;	
			case 'Login':
				include_once(dirname(dirname(__FILE__)).'/include/lib/Login.class.php');
				return new Login($this->model);
				break;
			case 'User':
				include_once(dirname(dirname(__FILE__)).'/include/lib/User.class.php');
				return new User($this->model);
				break;
			case 'MandOAuth':
				include_once(dirname(dirname(__FILE__)).'/include/lib/MandOAuth.class.php');
				return new MandOAuth($this->model,$this->config);
				break;
			case 'MandOAuthLog':
				include_once(dirname(dirname(__FILE__)).'/include/lib/MandOAuthLog.class.php');
				return new MandOAuthLog($this->model);
				break;	
			case 'DBSoapExpandOauthPerm':
				$this->config['DES']['type'] = 'Expand';
				$this->config['DES']['ident'] = 'private';
				include_once(dirname(dirname(__FILE__)).'/include/lib/DBSoapExpandOauthPerm.class.php');
				return new DBSoapExpandOauthPerm($this->config);
				break;
			case 'DBQRCode':
				include(dirname(dirname(__FILE__)).'/include/lib/DBQRCode.class.php');
				return new DBQRCode();
				break;
			case 'DBQRCodeForPC':
				include(dirname(dirname(__FILE__)).'/include/lib/DBQRCodeForPC.class.php');
				return new DBQRCodeForPC($this->model, $this->config);
				break;
			case 'DBTokenCode':
				include(dirname(dirname(__FILE__)).'/include/lib/DBTokenCode.class.php');
				return new DBTokenCode();
				break;
			case 'DBApiAuth':
				include(dirname(dirname(__FILE__)).'/include/lib/DBApiAuth.class.php');
				return new DBApiAuth($this->config);
				break;	
			case 'ModifyProfile':
				include(dirname(dirname(__FILE__)).'/include/lib/ModifyProfile.class.php');
				return new ModifyProfile($this->model);
			case 'DBUserWork':
				include(dirname(dirname(__FILE__)).'/include/lib/DBUserWork.class.php');
				return new DBUserWork($this->model);
				break;
			case 'UserOAuth':
				include(dirname(dirname(__FILE__)).'/include/lib/UserOAuth.class.php');
				return new UserOAuth($this->model, $this->config);
				break;
			case 'GetUserInfo':
				include_once(dirname(dirname(__FILE__)).'/include/ext/partner/common/GetUserInfo.php');
				return new GetUserInfo($fieldArr['partner'],$fieldArr['provider'], $fieldArr['OAuthArr']);
				break;
			case 'DBGetUserInfo':
				include_once(dirname(dirname(__FILE__)).'/include/ext/partner/port/DBGetUserInfo.class.php');
				return new DBGetUserInfo($fieldArr['partner'], $fieldArr['provider'], $fieldArr['OAuthArr']);
				break;
			case 'DBAddInformation':
				include_once(dirname(dirname(__FILE__)).'/include/ext/partner/port/DBAddInformation.class.php');
				return new DBAddInformation($fieldArr['partner'],$fieldArr['provider'], $fieldArr['OAuthArr']);
				break;
			case 'DBBeFriend':
				include_once(dirname(dirname(__FILE__)).'/include/ext/partner/port/DBBeFriend.class.php');
				return new DBBeFriend($fieldArr['partner'],$fieldArr['provider'], $fieldArr['OAuthArr']);
				break;
			case 'DBGetFriendList':
				include_once(dirname(dirname(__FILE__)).'/include/ext/partner/port/DBGetFriendList.class.php');
				return new DBGetFriendList($fieldArr['partner'],$fieldArr['provider'], $fieldArr['OAuthArr']);
				break;
			case 'DB_PlugInShare':
				include(dirname(dirname(__FILE__)).'/include/lib/DB_PlugInShare.class.php');
				return new DB_PlugInShare($this->model);
				break;
			case 'DB_SoapInterface':
				include(dirname(dirname(__FILE__)).'/include/lib/DB_SoapInterface.class.php');
				return new DB_SoapInterface();
				break;
			case 'DBInnerSoap':
				include(dirname(dirname(__FILE__)).'/include/lib/DBInnerSoap.class.php');
				return new DBInnerSoap();
				break;
		}
	}
}
?>
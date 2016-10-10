<?php
include('conf.php');
//网站全局配置
$config['ver']									='1.0';

//网站全局配置结束

//日志和错误调试配置
$config['DEBUG']								=true;								//是否开启调试模式，true开启，false关闭
$config['LOG_ON']								=true;								//是否开启出错信息保存到文件，true开启，false不开启
$config['LOG_PATH']								='./data/log/';					//出错信息存放的目录，出错信息以天为单位存放
$config['ERROR_URL']							='';							//出错信息重定向页面，为空采用默认的出错页面
$config['ERROR_HANDLE']							=false;

//应用配置
		//网址配置
$config['URL_REWRITE_ON']						=true;						//是否开启重写，true开启重写,false关闭重写
$config['URL_MODULE_DEPR']						='/';						//模块分隔符
$config['URL_ACTION_DEPR']						='-';						//操作分隔符
$config['URL_PARAM_DEPR']						='-';						//参数分隔符
$config['URL_HTML_SUFFIX']						='.html';					//伪静态后缀设置，，例如 .html 
		
		//模块配置
$config['MODULE_PATH']							='./module/';					//模块存放目录
$config['MODULE_SUFFIX']						='Mod.class.php';			//模块后缀
$config['MODULE_INIT']							='init.php';					//初始程序
$config['MODULE_DEFAULT']						='index';					//默认模块
$config['MODULE_EMPTY']							='empty';					//空模块		
		
		//操作配置
$config['ACTION_DEFAULT']						='index';					//默认操作
$config['ACTION_EMPTY']							='_empty';					//空操作

		//静态页面缓存
$config['HTML_CACHE_ON']						=false;						//是否开启静态页面缓存，true开启.false关闭
$config['HTML_CACHE_PATH']						='./cache/html_cache/';	//静态页面缓存目录
$config['HTML_CACHE_SUFFIX']					='.html';				//静态页面缓存后缀
$config['HTML_CACHE_RULE']['index']['index']	=1000;	//缓存时间,单位：秒

//模板配置
$config['TPL_TEMPLATE_PATH']		='./templates/';			//模板目录
$config['TPL_TEMPLATE_SUFFIX']		='.html';					//模板后缀
$config['TPL_CACHE_ON']				=false;						//是否开启模板缓存，true开启,false不开启
$config['TPL_CACHE_PATH']			='./cache/tpl_cache/';		//模板缓存目录
$config['TPL_CACHE_SUFFIX']			='.php';					//模板缓存后缀,一般不需要修改

// smarty 配置
$config['SMARTY_DEBUGGING']         = false;              		//是否开启调试模式
$config['SMARTY_CACHING']           = FALSE;              		//是否开启缓存
$config['SMARTY_TEMPLATE_DIR']      = './templates/';      		//缓存时间
$config['SMARTY_CACHE_LIFETIME']    = 30;                 		//缓存时间
$config['SMARTY_COMPILE_DIR']       = './data/smarty/compile_dir'; //smarty模板编译文件存放的目录
$config['SMARTY_CACHE_DIR']         = './data/smarty/cache_dir';   //smarty模板缓存文件存放的目录
$config['SMARTY_LEFT_DELIMITER']    = '{';                         //左定界符
$config['SMARTY_RIGHT_DELIMITER']   = '}';                         //右定界符

//多语言配置 
$config['LANG_DEFAULT']				='zh';//'zh-cn';       	//默认语言
$config['LANG_PACK_PATH']			='./lang/';      	//语言包目录
$config['LANG_PACK_SUFFIX']			='.lang.php';    	//语言包后缀 
$config['LANG_PACK_COMMON']			='common';   		//公用语言包，默认会自动加载

//MemCache配置
$config['MEM_SERVER']	     = array( array('192.168.0.189', 11211),  array('192.168.0.189', 11212) );
$config['MEM_GROUP']	     = 'PLATFORM_AUTH';
$config['SAE_MEM_GROUP']	 = 'PLATFORM_AUTH';
$config['MEM_EXPIRE']        = 60*5; //过期时间，设置为5分钟
$config['public_key']        = '1q2w3e4r';
$config['public_iv']         = '4r3e2w1q';

//缓存服务器配置  nodejs
$config['MEM_SERVER_HOST']						= 'http://192.168.0.182:8090/';
$config['MEM_SERVER_EXPIRE']				    = 30*60;
$config['MEM_SERVER_WORK']				        = true;

//邮件配置
$config['EMAIL']['url']            = 'http://192.168.0.252:8083/MailQueueService.asmx?WSDL';
$config['EMAIL']['UserName']       = 'Yannyo_Local_PassPort';
$config['EMAIL']['UserPWD']        = '1q2w3e@1q2w3e';
$config['EMAIL']['mSendMail']      = 'support@yannyo.com';
$config['EMAIL']['mIsHTML']        = true;
$config['EMAIL']['SetSendTime']    = strtotime(date('Y-m-d h:i:s',strtotime('1 minute')));

//DES加密配置
$config['DES']['SOAP_USER']				 ='soap_server';  //DES 用户名	
$config['DES']['SOAP_PWD']				 ='soap_pwd';   //DES 密码
$config['DES']['SOAP_IV']				 ='12345678';   //DES 偏移量	
$config['DES']['Soap_Client']	   		 ='http://dev.dbowner.com/soap/manage?wsdl';//'http://192.168.0.197:9050/soap/manage?wsdl';   //SoapClient地址
$config['DES']['Soap_Header']			 ='http://dev.dbowner.com';   //SoapHeader地址
$config['DES']['Soap_Client_Dev']	     ='http://dev.dbowner.com/soap/manage?wsdl';   //SoapClient地址
$config['DES']['Soap_Header_Dev']		 ='http://dev.dbowner.com';   //SoapHeader地址
$config['DES']['Soap_Client_Auth']	     ='http://auth.dbowner.com/soap/userInfoSoap?wsdl';//'http://192.168.0.197:9002/soap/userInfoSoap?wsdl';   //SoapClient地址
$config['DES']['Soap_Header_Auth']		 ='http://auth.dbowner.com';   //SoapHeader地址
$config['DES']['Soap_Client_User']	     ='http://user.dbowner.com/soap/userInfoSoap?wsdl';   //SoapClient地址
$config['DES']['Soap_Header_User']		 ='http://user.dbowner.com';   //SoapHeader地址
$config['DES']['Soap_Client_Expand']	 ='http://expand.dbowner.com/soap/extendSoap?wsdl';//'http://192.168.0.197:9020/soap/extendSoap?wsdl'; //SoapClient地址
$config['DES']['Soap_Header_Expand']	 ='http://expand.dbowner.com';   //SoapHeader地址
$config['DES']['Soap_Client_Plus']	     ='http://plus.dbowner.com/soap/plugInSoap?wsdl'; //SoapClient地址
$config['DES']['Soap_Header_Plus']	     ='http://plus.dbowner.com';   //SoapHeader地址
$config['DES']['Soap_Client_Pay']	     ='http://pay.dbowner.com/soap/paySoap?wsdl';
$config['DES']['Soap_Header_Pay']	     ='http://pay.dbowner.com/soap';   //SoapHeader地址
$config['DES']['Soap_Client_Ads']	     ='http://ad.dbowner.com/soap/adsSoap?wsdl';
$config['DES']['Soap_Header_Ads']	     ='http://ad.dbowner.com/soap';   //SoapHeader地址
$config['DES']['Soap_Client_Push']	     ='http://push.dbowner.com/soap/pushSoap?wsdl';
$config['DES']['Soap_Header_Push']	     ='http://push.dbowner.com/soap';   //SoapHeader地址
$config['DES']['Soap_Client_Analysis']	 ='http://analysis.dbowner.com/soap/analysisSoap?wsdl';
$config['DES']['Soap_Header_Analysis']	 ='http://analysis.dbowner.com/soap';   //SoapHeader地址
$config['DES']['Soap_Client_MsgQueue']   ='http://messagequeue.dbowner.com/soap.php?wsdl'; //信息队列
$config['DES']['Soap_Header_MsgQueue']   ='http://messagequeue.dbowner.com'; //信息队列

$config['DES']['Soap_Client_tAuth']	     ='http://user.dbowner.com/soap/userInfoSoap?wsdl';   //SoapClient地址
$config['DES']['Soap_Header_tAuth']		 ='http://user.dbowner.com';   //SoapHeader地址
$config['DES']['SOAP_SERVER_CLIENTIP']	 = array('127.0.0.1','192.168.0.1','192.168.0.33','192.168.0.195','192.168.0.197');    //SoapHeader地址

//用户中心client_id
$config['Auth']['client_id'] = 'DB_Auth'; 

//登录接口网址配置
$config['oauth']['login']	 = array(
		'80002001', //dev
		'80002002', //dev test
		'app38', //dev test
		'80022002', //wiki 
		//'80022003', //wiki test
		'app15', //expand test
		'app16', //expand 
		'app17', //plus test
		'app18', //plus
		'app19', //ads test
		'app20', //ads
		'app21', //pay test
		'app22', //pay
		//'app24', //push test
		'app25', //push
		'app27', //analysis test
		'app28', //analysis 
		'app50', //club
		);

//成功登录后清空授权值写入的cookies
$config['Login']['Ident'] = array(
		'client_id', //框架登录
		'redirect_uri',  //手机登录
		'response_type', //正常授权登录
		'src_forget', //框架登录忘记密码
		'src_register', //框架登录注册
		'oa_tpl', //
		'ident', //鉴别类型
		'display', //浏览类型 手机 web
		'provider', //第三方类型
);
$config['Login']['Cookies'] = array(
		'iframe', //框架登录 
		'mobileLogin',  //手机登录
		'oauthlogin', //正常授权登录
		'userbox', //登录框
);

//界定跟内部平台一样直接跳转登录界面
$config['oauth']['login_direct'] = array(
		'app16', 
		'app61', //喝酒平台 app61
		'app54',
		'app62',
		'app63', //协作系统 本地测试
		'app64', //协作系统
		'app66', //港口 本地
		'app67', //港口
		'app71', //供应商 本地
		'app72', //供应商
);

$config['oauth']['platform_clientid']	 = array(
		'dev' => '80002001', //dev
		'dev_test' => '80002002', //dev test
		'dev_test' => 'app38', //dev test
		'wiki' => '80022002', //wiki
		'wiki_test' => '80022003', //wiki test
		'expand_test' => 'app15', //expand test
		'expand' => 'app16', //expand
		'plus_test' => 'app17', //plus test
		'plus' => 'app18', //plus
		'ads_test' => 'app19', //ads test
		'ads' => 'app20', //ads
		'pay_test' => 'app21', //pay test
		'pay' => 'app22', //pay
		//'push_test' => 'app24', //push test
		'push' => 'app25', //push
		'analysis_test' => 'app27', //analysis test
		'analysis' => 'app28', //analysis
		'club' =>'app50', //club
);  

//应用是否需要激活应用之后才能继续访问
$config['oauth']['isCheck']   = true; //是否进行应用激活检验
$config['oauth']['appActive'] = array('80002001','80002002','80022002','80022003'); //不检验数组中的应用

$config['oauth']['invitecode'] = array('80002001','80002002','800220011');//生成激活码引用client_id

//文件服务器
$config['FILE_SERVER_UP'] ='http://file.dbowner.com:80/index.php?act=up';  //保存文件
$config['FILE_SERVER_GET']='http://file.dbowner.com:80/index.php?act=get'; //读取文件

//过期时间   以秒为单位
$config['EXPIRE_TIME']['ONLINE']                = 2*60*60;  // 用户在线过期时长
$config['EXPIRE_TIME']['FAILlOGIN']			    = 24*60*60;  //  用户登录失败过期时长
$config['EXPIRE_TIME']['APPCONNACT']		    = 6*60*60;  //  应用连接过期时长
$config['EXPIRE_TIME']['USERCONNACT']		    = 6*60*60;  //  用户连接应用过期时长
$config['EXPIRE_TIME']['USERFAILLOGIN']		    = 24*60*60;  //  用户连接应用登录失败过期时长
$config['EXPIRE_TIME']['Access_EffectTime']		= 5*60*60;  //  access_token过期时间
$config['EXPIRE_TIME']['Refresh_EffectTime']    = 30*24*60*60;  //  refresh_token过期时间
$config['EXPIRE_TIME']['DBOCache']              = 60*5;  //  缓存过期时间

//头像大小
$config['IMAGES']['FILECODES'] = '099a1dffe5828cf95e0b16c3fa869118'; //默认图像编码
$config['IMAGES']['BIG'] = 128;
$config['IMAGES']['MID'] = 64;
$config['IMAGES']['SMA'] = 32;

//不需要登录，或js插件、接口不需要进行全局验证，为了提高速度
$config['NoNeedLogin'] = array(
		'common','throwmessage','index','file','soap','test','qrcode','iframe' //公用的
		,'account','appplus','db' //私用的
		,'provitejs','comment' //js
		,'oauth','share','statuses','users','content'   //oauth
);
$config['DBOwner']['TurnLogin'] = array('index','main'); //用户ID不存在，需转向登录页面;针对模版
$config['DBOwner']['NotNeedTurnLogin'] = array('phoneLogin'); //用户ID不存在，不需要转向登录页面;针对方法
$config['DBOwner']['UserCenterTurn'] = array('auth.dbowner.com'); //跳转到用户中心之后，再进行判断是否需要跳转

//用户授权权限控制
$config['oauth']['DES_PWD']    ='123123qw';   //DES 密码
$config['oauth']['DES_IV']	   ='qweqwe12';   //DES 偏移量
$config['oauth']['Default_Callback']    = '/oauth/callback';   //DES 密码
$config['oauth']['permission'] = array('show','getmsg','friends','update');
$config['oauth']['googleLogin'] = false;

//默认登录方式--loginNum和loginType需配套
$config['DB']['Login']['loginNum'] = 0; //登录框选项 '0'密码登陆 '1'第三方登陆'2'
$config['DB']['Login']['loginType'] = 'pwd'; //登录框别名  'pwd'密码登陆 'tl'第三方登陆'qr'

//expand平台插件ID
$config['Expand']['AppPlugIn'] = array(
		'InviteCode' => '1', //邀请码
		'InviteCode_En' => 'inviteCode', //邀请码
		'QRCode' => '2', //二维码
		'plugin_share' => array(   //插件分享
				'auth' => 'auth_thirdparty',
				'expand' => 'share'
				) 
		);

//plus平台相应值配置
$config['DB']['Platform_Plus']['app_url'] = array(
							array('client_id' => '80002001', 'url' => 'http://dev.dbowner.com/account/ReCall')
					);

$config['DB']['Plus']['QRCode'] = 'http://plus.dbowner.com/qrCode/dirGetQRCode';

$config['DB']['QRCode']['AuthUrl'] = 'http://auth.dbowner.com/login/phoneLogin'; //个人中心登录页面
$config['DB']['QRCode']['OverTime'] = 60*60; //session过期值
$config['DB']['QRCode']['clientOverTime'] = 90; //手机端时间有效性验证
$config['DB']['QRCode']['clientSDK'] = 'http://wiki.dbowner.com/index/document-download-qrdownload'; //手机端时间有效性验证

$config['Domain']['Auth']         = 'auth.dbowner.com'; //用户中心
$config['PLATFORM']['Auth_https'] = 'https://auth.dbowner.com'; //用户中心
$config['PLATFORM']['Auth']       = 'http://auth.dbowner.com'; //用户中心
$config['PLATFORM']['Wiki']       = 'http://wiki.dbowner.com'; //DBOwner维基
$config['PLATFORM']['Expand']     = 'http://expand.dbowner.com';  //应用扩展平台
$config['PLATFORM']['Plus']       = 'http://plus.dbowner.com';  //插件平台
$config['PLATFORM']['Ad']         = 'http://ad.dbowner.com'; //广告联盟
$config['PLATFORM']['Union']      = 'http://union.dbowner.com'; //广告展示
$config['PLATFORM']['Pay']        = 'http://pay.dbowner.com'; //支付中心
$config['PLATFORM']['Analysis']   = 'http://analysis.dbowner.com'; //数据分析中心
$config['PLATFORM']['Datamarket'] = 'http://datamarket.dbowner.com'; //数据交易中心
$config['PLATFORM']['Dev']        = 'http://dev.dbowner.com'; //应用中心

$config['PLATFORM']['Pay_client_id'] = 'app22';
$config['PLATFORM']['Auth_client_id'] = '80002000';

$config['DBWriteTrace'] = array(
		'DBWriteTrace_FilePath' => './data/trace/', //调试信息存储位置
		'DBWriteTrace_IsTrace' => true, //是否写调试信息
);

$config['Default_Pwd'] = '666888'; //默认密码
?>
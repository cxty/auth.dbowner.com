<?php 
/**
 * 邮件发送类
 * 
 * @author wbqing405@sina.com
 */

include_once('Config.class.php'); //引入处理类的编码格式 utf-8
include_once('ComFun.class.php'); //公共方法

class Email{
	
	public function __construct(){	
		$this->contentUrl = dirname(dirname(dirname(__FILE__)));
		
		include_once($this->contentUrl.'/include/lib/Lang.class.php');
		$this->EmailLang = Lang::get('EmailLang');

		global $config;
		
		$this->config = $config;

		$this->auth_host      = $config['PLATFORM']['Auth'];
		$this->url            = $config['EMAIL']['url'];
		$this->UserName       = $config['EMAIL']['UserName'];
		$this->UserPWD        = $config['EMAIL']['UserPWD'];	
		$this->mSender        = $this->EmailLang['EmSender'];
		$this->mSendMail      = $config['EMAIL']['mSendMail'];
		$this->mIsHTML        = $config['EMAIL']['mIsHTML'];
		$this->SetSendTime    = $config['EMAIL']['SetSendTime'];		
	}
	/**
	 * 取用户信息
	 */
	private function getReceiverName(){
		$userInfo = ComFun::getThirdInfoByGet('/db/getUserInfo',ComFun::getTConditionByCurl($this->provider));
		
		$this->Receiver = $userInfo['uDisplay_name'];
	}
	/**
	 * 选择发送邮件的格式
	 * 
	 * @param unknown_type $fieldArr 发送邮件所需信息
	 * @param unknown_type $type 发送邮件类型  
	 */
	public function sendMail($fieldArr){
		if($fieldArr['uName']){
			$this->mAddressee     = $fieldArr['uName']; //收件人
		}else{
			$this->mAddressee     = $fieldArr['uEmail']; //收件人
		}

		$this->mAddresseeMail = $fieldArr['uEmail']; //收件人邮箱
			
		$this->provider = ComFun::getCookies('provider');
		$this->uCode     = $fieldArr['uCode'];
		
		//获取第三方账号登录者用户名
		$apiArr = ComFun::getApiByParter($this->provider);
		$this->thirdName = $apiArr['txt'];
		
		switch(strtolower($fieldArr['type'])){
			case 'register': //注册账号	

				$rArr['uEmail'] =  $this->mAddresseeMail;
				$rArr['uCode']  =  $this->uCode;
				$rArr['type']   =  'register';
				
				$str = ComFun::_encodeArr($rArr);
						
				$this->EmailUrl  = $this->auth_host . '/index/activate?data='.$str;
				
				$backEmailArr = include_once($this->contentUrl.'/conf/Email/register.php');	
				break;
			case 'bandaccount': //绑定第三方帐号到指定本站指定帐号
				$this->getReceiverName(); //获取第三方账号的用户信息
				
				$rArr['uEmail'] =  $this->mAddresseeMail;
				$rArr['uCode']  =  $this->uCode;
				
				$rArr['type']   =  'bandAccounts';
				$str = ComFun::_encodeArr($rArr);			
				
				$this->EmailUrl[0]  = $this->auth_host.'/index/activate?data='.$str;
				
				$rArr['type']   =  'bandAccountf';
				$str = ComFun::_encodeArr($rArr);
				$this->EmailUrl[1]  = $this->auth_host.'/index/activate?data='.$str;
				
				$backEmailArr = include_once($this->contentUrl.'/conf/Email/jointhird.php');	
				break;			
			case 'rebandaccount': //重新绑定第三方帐号到指定本站指定帐号
				$this->getReceiverName(); //获取第三方账号的用户信息
				
				$rArr['uEmail'] =  $this->mAddresseeMail;
				$rArr['uCode']  =  $this->uCode;
				
				$rArr['type']   =  'reBandAccounts';
				$str = ComFun::_encodeArr($rArr);
				$this->EmailUrl[0]  = $this->auth_host.'/index/activate?data='.$str;
				
				$rArr['type']   =  'reBandAccountf';
				$str = ComFun::_encodeArr($rArr);
				$this->EmailUrl[1]  = $this->auth_host.'/index/activate?data='.$str;
				
				$backEmailArr = include_once($this->contentUrl.'/conf/Email/reBandAccount.php');
				break;
			case 'retakepwd': //找回密码		
				$rArr['uEmail'] =  $this->mAddresseeMail;
				$rArr['uCode']  =  $this->uCode;
				$rArr['type']   =  'retakepwd';

				$str = ComFun::_encodeArr($rArr);
						
				$this->EmailUrl  = $this->auth_host.'/index/activate?data='.$str;
				
				$backEmailArr = include_once($this->contentUrl.'/conf/Email/retakePwd.php');
				break;
			case 'invitecode': //应用激活邮件
				$rArr['uEmail'] =  $this->mAddresseeMail;
				$rArr['uCode']  =  $this->uCode;
				$rArr['type']   =  'inviteCode';
				
				$aName = $fieldArr['aName'];
				
				$str = ComFun::_encodeArr($rArr);
				echo $str;
				$this->EmailUrl  = $this->auth_host.'/index/activate?data='.$str;
				
				$backEmailArr = include_once($this->contentUrl.'/conf/Email/inviteCode.php');
				break;
			case 'authaccount': //找回密码
				$rArr['uEmail'] =  $this->mAddresseeMail;
				$rArr['uCode']  =  $this->uCode;
				$rArr['type']   =  'authaccount';
			
				$str = ComFun::_encodeArr($rArr);
			
				$this->EmailUrl  = $this->auth_host.'/index/activate?data='.$str;
			
				$backEmailArr = include_once($this->contentUrl.'/conf/Email/authaccount.php');
				break;
		}
	
		$this->mTitle      = $backEmailArr['mTitle'];
		$this->mContent    = $backEmailArr['mContent'];
			
		$this->toSend();
	}
	/**
	 * 用soap方式发送邮件
	 */
	private function toSend() {
		$DBOwnerSoapClient_MsgQueue = new DBOwnerSoapClient_MsgQueue();
		$re = $DBOwnerSoapClient_MsgQueue->AddMessageTask(
					0, 
					$this->mSender, 
					array(
						'FromAddress' => $this->mSendMail,
						'ToAddress'   => array( $this->mAddresseeMail ),
						'Title'       => $this->mTitle,
						'Content'     => addslashes($this->mContent)
						),
					time()
					);
		
		/*
		$sendArr = array(
						'UserName'        => $this->UserName,
						'UserPWD'         => $this->UserPWD,
						'mTitle'          => $this->mTitle,
						'mContent'        => $this->mContent,
						'mSender'         => $this->mSender,
						'mSendMail'       => $this->mSendMail,
						'mAddressee'      => $this->mAddressee,
						'mAddresseeMail'  => $this->mAddresseeMail,
						'mIsHTML'         => $this->mIsHTML,
						'SetSendTime'     => $this->SetSendTime
						);

		$client = new SoapClient($this->url);
		$re = $client->SendMail($sendArr);	

		foreach($re as $key=>$val){
			if(strtolower($key) == 'sendmailresult'){
				return $val;
			}else{
				return -1;
			}
		}

		return $re['SendMailResult'];
		*/
	}
}
?>

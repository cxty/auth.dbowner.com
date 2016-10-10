<?php
class throwMessageMod extends commonMod{
	
	/**
	 * 错误提示
	 */
	public function throwMsg(){
		$client_id = $_GET['client_id'];
		if($_GET['msg']){
			$msgkey = $_GET['msgkey'] ? $_GET['msgkey'] : Lang::get('Ex_UnknowError');
			$msgValue = ComFun::__decrypt($_GET['msg']);
		}else{
			$msgkey = $_GET['msgkey'] ? $_GET['msgkey'] : 'Ex_UnknowError';
			$msgValue = Lang::get($msgkey);
		}

		if(!empty($client_id)){
			$MandOAuth = $this->getClass('MandOAuth');
			$appInfo = $MandOAuth->getAuthAppInfo($client_id);
			if($appInfo){
				$picArr  = explode(',',$appInfo['data']['appinfo']['aIcoCode']);
				$picArr2 = explode('|', $picArr[0]);
				
				$backArr['pic']      = $this->config['FILE_SERVER_GET'].'&filecode='.$picArr2[0].'&w=120';
				$backArr['aName']    = $appInfo['data']['appinfo']['aName'];
				$backArr['url']      = 'http://'.$client_id.'.dev.dbowner.com';
				$backArr['msg']      = $msgValue;
			}else{
				$backArr['msg'] = Lang::get('Ex_ErrorValueUsed506');
			}
			$backArr['appshow'] = true;
			$backArr['urlTurn'] =  'http://'.$client_id.'.dev.dbowner.com';
		}else{
			$backArr['appshow'] = false;
			$backArr['urlTurn'] = isset($_GET['urlTurn']) ? ComFun::__decrypt($_GET['urlTurn']) : '';
			$backArr['url']     = '/main/index';
			$backArr['retry']   = $_SERVER['PHP_SELF'];
			$backArr['msg']     = $msgValue;
		}
		
		$this->assign('backArr',$backArr);
		
		if ( ComFun::isMobileClient() ) {
			$this->display('throwMessage/message_wap.html');
		} else {
			$this->display('throwMessage/message.html');
		}
		
	}
	/**
	 * 取得类
	 */
	private function getClass($className,$fieldArr=null){
		switch($className){
			case 'MandOAuth':
				include_once(dirname(dirname(__FILE__)).'/include/lib/MandOAuth.class.php');
				return new MandOAuth($this->model,$this->config);
				break;
		}
	}
}
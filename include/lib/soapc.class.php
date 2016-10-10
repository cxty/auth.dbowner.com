<?php
/**
 * SOAP调用例子
 * @author Cxty
 *
 */
class soapc {

	public function __construct($DES,$AppID=''){
		$this->SOAP_USER   = $DES['SOAP_USER'];
		$this->DES_PWD     = $DES['SOAP_PWD'];
		$this->DES_IV      = $DES['SOAP_IV'];
		$this->Soap_Client = $DES['Soap_Client'];
		$this->Soap_Header = $DES['Soap_Header'];
		$this->AppID       = $AppID;
		
		$this->init();
	}
	/**
	 * 初始化
	 */
	private function init(){
		$this->des = new DES($this->DES_PWD,$this->DES_IV);
	}
	/**
	 * 单独为GetAppByID构造的方法
	 */
	public function run(){
		try{	
			//DBOError::write(' class-soapc-run-1 | ' . time() . ' | ' . json_encode($_GET));
			
			$client = new SoapClient($this->Soap_Client);
			//增加SoapHeader验证
			$soap_var = new SoapVar(array('user'=>$this->SOAP_USER), SOAP_ENC_OBJECT,'Auth');
			$header = new SoapHeader($this->Soap_Header, 'Auth', $soap_var, true);
			$client->__setSoapHeaders($header);

			//DBOError::write(' class-soapc-run-2 | ' . time() . ' | ' . json_encode($_GET));
			
			$val = $this->des->encrypt(json_encode(array('AppID'=>$this->AppID)));
			
			//DBOError::write(' class-soapc-run-3 | ' . time() . ' | ' . json_encode($_GET));
			
			//$val = $this->des->encrypt(json_encode(array()));
			$_re =  json_decode($client->GetAppByID(array('data'=>json_encode(array('data'=>$val))))->return);
			
			//DBOError::write(' class-soapc-run-4 | ' . time() . ' | ' . json_encode($_GET));
			
			//$_re =  json_decode($client->GetAppList(array('data'=>json_encode(array('data'=>$val))))->return);

			if(isset($_re->data)){			
				return json_decode($this->des->decrypt($_re->data),true);
			}else{
				return false;
			}
			
		}catch (Exception $e) {
			printf("Message = %s",$e->__toString());
		}
	}
	/**
	 * get方法构造
	 */
	public function getMethod($requestName,$fieldArr){
		$methodName = 'Get'.$requestName;
		
		return $this->runSoap($methodName,$fieldArr);
	}
	/**
	 * soap请求
	 */
	private function runSoap($requestName,$fieldArr){
		try{	
			$client = new SoapClient($this->Soap_Client);
			//增加SoapHeader验证
			$soap_var = new SoapVar(array('user'=>$this->SOAP_USER), SOAP_ENC_OBJECT,'Auth');
			$header = new SoapHeader($this->Soap_Header, 'Auth', $soap_var, true);
			$client->__setSoapHeaders($header);
		
			$val = $this->des->encrypt(json_encode($fieldArr));
				
			$_re =  json_decode($client->$requestName(array('data'=>json_encode(array('data'=>$val))))->return) ;
		
			if(isset($_re->data)){
				return json_decode($this->des->decrypt($_re->data),true);
			}else{
				return false;
			}
				
		}catch (Exception $e) {
			printf("Message = %s",$e->__toString());
		}
	}
}
?>
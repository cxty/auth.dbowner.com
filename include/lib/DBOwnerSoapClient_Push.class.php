<?php
/**
 * SOAP客户端
 * @author Cxty
 *
 */

class DBOwnerSoapClient_Push {
	public $_ExpandWsdl = '';
	
	public $config; // 全局配置
	public $_user;
	public $_pwd;
	public $_des_vi;
	public $_host;
	public $_soap_client;
	public $_des;
	public $_Cache;
	
	public function __construct($config) {
		require_once (dirname ( __FILE__ ) . "/DES.class.php");
	
		$this->config = $config; // 配置
	
		$this->_user = $this->config['DES']['SOAP_USER'];
		$this->_pwd  = $this->config['DES']['SOAP_PWD'];
		$this->_vi   = $this->config['DES']['SOAP_IV'];
		$this->_Wsdl = $this->config['DES']['Soap_Client_Push'];
		$this->_host = $this->config['DES']['Soap_Header_Push'];
	
		$this->_des = new DES ( $this->_pwd, $this->_des_iv );
	
		$this->_Cache = new DBOCache($this->config);
		$this->_soap_client =$this->SOAP_Client();
	
	}
	
	/**
	 * 初始化SOAP
	 * @return SoapClient
	 */
	public function SOAP_Client(){
	
		$client = new SoapClient ( $this->_Wsdl,array('compression'=>true));
		// 增加SoapHeader验证
		$soap_var = new SoapVar ( array (
				'user' => $this->_user
		), SOAP_ENC_OBJECT, 'Auth' );
		$header = new SoapHeader ( $this->_host, 'Auth', $soap_var, true );
		$client->__setSoapHeaders ( $header );
		return $client;
	}

	/**
	 * user_id用户应用列表
	 */
	public function GetUserAppList ( $user_id ) {
		$da = array(
				'state' => false,
				'msg'   => 'system error',
				'data'  => 0,
		);
		try{
			$_val = $this->_des->encrypt ( json_encode ( array (
					'user_id' => $user_id
			) ) );
		
			$_re = json_decode ( $this->_soap_client->GetUserAppList ( array (
					'data' => json_encode ( array (
							'data' => $_val
					) )
			) )->return );
			
			if ( $_re->state ) {
				$da['state']  = true;
				$da['data']   = null;
				if ( $_re->data ) {
					$data = json_decode($this->_des->decrypt ( $_re->data ), true);
					$da['data']   = $data['data'];
				}
			} else {
				$da['msg'] = $_re->msg;
			}
		}catch ( Exception $e ) {
			//printf ( "Message = %s", $e->__toString () );
		}
		
		$da['msg'] = $da->state === true ? 'ok' : $_re->msg;
		
		return $da;
	} 
}
?>
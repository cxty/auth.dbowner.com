<?php
/**
 * SOAP客户端
 * @author Cxty
 *
 */

class DBOwnerSoapClient_Dev {
	public $_ExpandWsdl = '';
	
	public $config; // 全局配置
	public $_user;
	public $_pwd;
	public $_des_vi;
	public $_host;
	public $_soap_client;
	public $_des;
	public $_Cache;
	
	private $iscache = true; //是否开启缓存
	
	public function __construct($config) {
		require_once (dirname ( __FILE__ ) . "/DES.class.php");
	
		$this->config = $config; // 配置
	
		$this->_user = $this->config['DES']['SOAP_USER'];
		$this->_pwd  = $this->config['DES']['SOAP_PWD'];
		$this->_vi   = $this->config['DES']['SOAP_IV'];
		$this->_Wsdl = $this->config['DES']['Soap_Client'];
		$this->_host = $this->config['DES']['Soap_Header'];
	
		$this->_des = new DES ( $this->_pwd, $this->_des_iv );
	
		$this->_Cache = new DBOCache($this->config, 'Memcache', true);
		
		$this->_soap_client =$this->SOAP_Client();
	
		//缓存
		//global $DBClassObj;
		//$this->DBCacheServer = $DBClassObj['DBCacheServer']; 
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

	public function GetAppAllInfoListByUserID ( $user_id, $order='', $page=1, $pagesize=10000 ) {
		if ( $this->iscache === true ) {
			$_key = md5('GetAppAllInfoListByUserID|' . $user_id . '|' . $order . '|' . $page . '|' . $pagesize);
				
			$rb = $this->_Cache->get($_key);
			if ( $rb ) {
				return $rb;
			}
		
			$rb = $this->_GetAppAllInfoListByUserID($user_id, $order, $page, $pagesize);
		
			$this->_Cache->set($_key, $rb);
		
			return $rb;
		} else {
			return $this->_GetAppAllInfoListByUserID($user_id, $order, $page, $pagesize);
		}
	}
	
	/**
	 * 取用户应用完整信息列表
	 */
	private function _GetAppAllInfoListByUserID ( $user_id, $order='', $page=1, $pagesize=10000 ) {
		$da = array();
		try{
			$_val = $this->_des->encrypt ( json_encode ( array (
					'condition' => $user_id,
					'order'     => '',
					'page'      => $page,
					'pagesize'  => $pagesize,
			) ) );
		
			$_re = json_decode ( $this->_soap_client->GetAppAllInfoListByUserID ( array (
					'data' => json_encode ( array (
							'data' => $_val
					) )
			) )->return );
			
			if (isset ( $_re->data )) {
				$_re_data = json_decode($this->_des->decrypt ( $_re->data ), true);
				if($_re_data['data']){
					$da = $_re_data['data'];
				}
			}
		}catch ( Exception $e ) {
			//printf ( "Message = %s", $e->__toString () );
		}
		
		return $da;
	} 
	
	public function GetAppByID ( $AppID ) {
		if ( $this->iscache === true ) {
			$_key = md5('GetAppByID|' . $AppID);
			
			$rb = $this->_Cache->get($_key);
			if ( $rb ) {
				return $rb;
			}
		
			$rb = $this->_GetAppByID($AppID);
		
			$this->_Cache->set($_key, $rb);
		
			return $rb;
		} else {
			return $this->_GetAppByID($AppID);
		}
	}
	
	/**
	 * 取应用具体信息
	 */
	private function _GetAppByID ( $AppID ) {
		$da = array();
		try{
			$_val = $this->_des->encrypt ( json_encode ( array (
					'AppID' => $AppID
			) ) );
	
			$_re = json_decode ( $this->_soap_client->GetAppByID ( array (
					'data' => json_encode ( array (
							'data' => $_val
					) )
			) )->return );
				
			if (isset ( $_re->data )) {
				$_re_data = json_decode($this->_des->decrypt ( $_re->data ), true);
				if($_re_data['data']){
					$da = $_re_data['data'];
				}
			}
		}catch ( Exception $e ) {
			//printf ( "Message = %s", $e->__toString () );
		}
	
		return $da;
	}
	
	public function GetIsUserOwnApp ( $user_id, $order='', $page=1, $pagesize=10000 ) {
		if ( $this->iscache === true ) {
			$_key = md5('GetIsUserOwnApp|' . $user_id . '|' . $order . '|' . $page . '|' . $pagesize);
	
			$rb = $this->_Cache->get($_key);
			if ( $rb ) {
				return $rb;
			}
	
			$rb = $this->_GetIsUserOwnApp($user_id, $order, $page, $pagesize);
	
			$this->_Cache->set($_key, $rb);
	
			return $rb;
		} else {
			return $this->_GetIsUserOwnApp($user_id, $order, $page, $pagesize);
		}
	}
	
	/**
	 * 判断用户是否是应用开发者
	 */
	private function _GetIsUserOwnApp ( $user_id, $AppID ) {
		$da = array();
		try{
			$_val = $this->_des->encrypt ( json_encode ( array (
					'user_id' => $user_id,
					'AppID' => $AppID
			) ) );

			$_re = json_decode ( $this->_soap_client->GetIsUserOwnApp ( array (
					'data' => json_encode ( array (
							'data' => $_val
					) )
			) )->return );
	
			if (isset ( $_re->data )) {
				$_re_data = json_decode($this->_des->decrypt ( $_re->data ), true);
				if($_re_data['data']){
					$da = $_re_data['data'];
				}
			}
		}catch ( Exception $e ) {
			//printf ( "Message = %s", $e->__toString () );
		}
	
		return $da;
	}
}


?>
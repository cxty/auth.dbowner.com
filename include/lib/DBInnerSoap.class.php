<?php
/**
 * DB内部soap调用类
 *
 * @author wbqing405@sina.com
 */
class DBInnerSoap {
	
	public function __construct () {
		
		$this->_init();
	}
	
	/**
	 * 初始化
	 */
	public function _init () {
		//DBSoap类
		if ( !isset($GLOBALS['config']['DB_Model']['DBSoap']) ) {
			include(dirname(__FILE__).'/DBSoap.class.php');
			$this->dbSoap = new DBSoap();
			
			$GLOBALS['config']['DB_Model']['DBSoap'] = $this->dbSoap;
		} else {
			$this->dbSoap = $GLOBALS['config']['DB_Model']['DBSoap'];
		}
	}
	
//dev平台
	/**
	 * 调用指定appid字符串（以","分割）
	 */
	public function devGetAppByIDList ( $AppIDList ) {
		$da = array();
		try {
			if ( $AppIDList ) {
				$_cond['AppIDList'] = $AppIDList;
				return $this->dbSoap->GetTableInfo('dev', 'GetAppByIDList', $_cond);
			} else {
				return array();
			}
		} catch ( Exception $e ) {
			return $da;
		}
	}
	

//expand平台
	/**
	 * 取当前用户所有的应用扩展列表
	 */
	public function getExpandListByUserID () {
		
	}
	
//plus
	/**
	 * 取邀请码信息
	 */
	public function getInviteCode ( $_cond='' ) {
		$da = false;
		try {
			$re = $this->dbSoap->SelectTableInfo('plus', 'SelectInviteCodeInfo', $_cond);
			
			if ( $re['data'] > 0 ) {
				return true;
			} else {
				return $da;
			}
		} catch ( Exception $e ) {
			return $da;
		}
	}
}
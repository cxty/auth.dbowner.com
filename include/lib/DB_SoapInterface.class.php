<?php
/**
 * SOAP内部调用方法
 *
 * @author wbqing405@sina.com
 */
class DB_SoapInterface {
	
	public function __construct () {
		
		$this->_init();
	}
	/**
	 * 初始化类
	 */
	private function _init () {
		//DBSoap类
		if ( !isset($GLOBALS['config']['DB_Model']['DBSoap']) ) {
			include(dirname(__FILE__).'/DBSoap.class.php');
			$this->dbSoap = new DBSoap();
				
			$GLOBALS['config']['DB_Model']['DBSoap'] = $this->dbSoap;
		} else {
			$this->dbSoap = $GLOBALS['config']['DB_Model']['DBSoap'];
		}
	}
	
//expand平台方法
	/**
	 * 选查件列表
	 */
	public function getPlugInSimplifyInfo () {
		$_da = '';
		try {
			$permArr = ComFun::getPermissionInfo();
			$_rb['Auth'] = $permArr;
			
			$_re = $this->dbSoap->SelectTableInfo('Expand', 'SelectAppPlugInInfo', 'pStatues = 0');
		
			if ( $_re['data'] ) {
				$i = 0;
				$j = 1;
				$_rb['Expand'][0]['permName']  = 'expand_default';
				$_rb['Expand'][0]['permInfo']  = Lang::get('ExpandDefault');
				$_rb['Expand'][0]['contains']  = array();
				$_rb['Expand'][0]['isAuth']    = true;
				$_rb['Expand'][0]['isDefault'] = true;
				$_rb['Expand'][0]['isDisable'] = false;
				foreach ( $_re['data'] as $_k => $_v ) {
					if ( $_v['pDefault'] == 1 ) {
						$_Expand[$i]['oauthName']  = 'expand_' . strtolower($_v['PlugInCode']);
						$_Expand[$i]['permInfo']  = $_v['PlugInName'];
						$_Expand[$i]['isDefault'] = $_v['pDefault'] == 1 ? true : false;
						$_Expand[$i]['isOpen'] = true;
						$i++;
					} else {
						$_rb['Expand'][$j]['permName']  = 'expand_' . strtolower($_v['PlugInCode']);
						$_rb['Expand'][$j]['permInfo']  = $_v['PlugInName'];
						$_rb['Expand'][$j]['contains']  = array();
						$_rb['Expand'][$j]['isAuth']    = true;
						$_rb['Expand'][$j]['isDefault'] = $_v['pDefault'] == 1 ? true : false;
						$_rb['Expand'][$j]['isDisable'] = true;
						$j++;
					}
				}
				$_rb['Expand'][0]['contains']  = $_Expand;
				
				return $_rb;
			} else {
				return $_da;
			}
		} catch ( Exception $e ) {
			return $_da;
		}
	}
	
	/**
	 * Expand插件列表
	 */
	public function getPlugInListInfo () {
		$_da = '';
		try {
			$_re = $this->dbSoap->SelectTableInfo('Expand', 'SelectAppPlugInInfo', 'pStatues = 0');
		
			if ( $_re['data'] ) {
				return $_re['data'];
			} else {
				return $_da;
			}
		} catch ( Exception $e ) {
			return $_da;
		}
	}
	
	/**
	 * Auth权限列表
	 */
	public function getAuthListInfo () {
		$_da = '';
		try {
			return ComFun::getPermissionInfo();
		} catch ( Exception $e ) {
			return $_da;
		}
	}
}
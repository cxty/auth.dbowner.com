<?php
/**
 * 分享插件数据记录
 *
 * @author wbqing405@sina.com
 */
class DB_PlugInShare {
	
	var $tbPlugInShareLogInfo = 'tbPlugInShareLogInfo'; //请求响应数据记录
	var $tbPlugInShareMsgInfo = 'tbPlugInShareMsgInfo'; //信息发送信息记录
	var $tbPlugInShareAttentionInfo = 'tbPlugInShareAttentionInfo'; //关注第三方平台记录
	
	
	public function __construct ( $model ) {
		$this->model = $model;
	}
	
	/**
	 * 请求数据记录
	 */
	public function addPlugInShareRequestLog ( $fieldArr ) {
		try {
			$_data['pslModuleName']  = $fieldArr['pslModuleName'];
			$_data['pslActionName']  = $fieldArr['pslActionName'];
			$_data['pslRequestData'] = $fieldArr['pslRequestData'];
			$_data['pslRequestTime'] = time();
			
			return $this->model->table($this->tbPlugInShareLogInfo)->data($_data)->insert();
		} catch ( Exception $e ) {
			return false;			
		}	
	}
	
	/**
	 * 响应数据记录
	 */
	public function addPlugInShareRespondLog ( $fieldArr ) {
		try {
			$_cond['PlugInShareLogID'] = $fieldArr['PlugInShareLogID'];
			
			$_data['pslRespondData'] = $fieldArr['pslRespondData'];
			$_data['pslRespondTime'] = time();
			 
			$this->model->table($this->tbPlugInShareLogInfo)->data($_data)->where($_cond)->update();
			
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	
	/**
	 * 发送信息记录
	 */
	public function addPlugInShareMsgLog ( $fieldArr ) {
		try {
			$_data['psmAccessToken']   = $fieldArr['psmAccessToken'];
			$_data['psmSendFlatform']  = $fieldArr['psmSendFlatform'];
			$_data['psmContentData']   = $fieldArr['psmContentData'];
			$_data['psmSendRespond']   = $fieldArr['psmSendRespond'];
			$_data['psmAppendTime']    = time();
				
			return $this->model->table($this->tbPlugInShareMsgInfo)->data($_data)->insert();
		} catch ( Exception $e ) {
			return false;
		}
	}
	
	/**
	 * 关注信息记录
	 */
	public function addPlugInShareAttentionLog ( $fieldArr ) {
		try {
			$_data['psaAccessToken']   = $fieldArr['psaAccessToken'];
			$_data['psaSendFlatform']  = $fieldArr['psaSendFlatform'];
			$_data['psaSendRespond']   = $fieldArr['psaSendRespond'];
			$_data['psaAppendTime']    = time();
			
			return $this->model->table($this->tbPlugInShareAttentionInfo)->data($_data)->insert();
		} catch ( Exception $e ) {
			return false;
		}
	}
}
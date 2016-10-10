<?php 
/**
 * 处理用户短信息类
 * 
 * @author wbqing405@sina.com
 */

include_once('Config.class.php'); //引入处理类的编码格式 utf-8
include_once('Addslashes.class.php'); //数据过滤类
include_once('ComFun.class.php'); //公共方法

class UserMessage{
	
	var $tbUserInfo = 'tbUserInfo'; //用户基础信息表
	
	var $tbUserMsgSituationInfo = 'tbUserMsgSituationInfo'; //用户短信息发送情况表 
	
	var $tbUserMsgInfo = 'tbUserMsgInfo'; //用户短信息表 
	
	public function __construct($base){
		$this->model = $base;		
		
		$this->init();
	}	
	/**
	 * 初始化
	 */
	private function init(){
		$this->Addslashes = new Addslashes();
	}
	/**
	 * 取个人未读短信息条数
	 */
	public function getUnreadNum($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);

		switch($fieldArr['type']){
			case 'unreadMsg':
				$where = ' where uEstate = 2 and uFormID = \''.$fieldArr['UserID'].'\'';
				return $this->getRecordNum($where);
				break;
			case 'readMsg':
				$where = ' where uEstate = 1 and uFormID = \''.$fieldArr['UserID'].'\'';
				return $this->getRecordNum($where);
				break;
			case 'sendMsg':
				$where = ' where uEstate <> 0 and uToID = \''.$fieldArr['UserID'].'\'';
				return $this->getSendRecordNum($where);
				break;
			case 'delMsg':
				$where[0] = ' where uEstate = 0 and uFormID = \''.$fieldArr['UserID'].'\'';
				$where[1] = ' where uEstate = 0 and uToID = \''.$fieldArr['UserID'].'\'';
				return $this->getDelRecordNum($where);
				break;
		}	
	}
	/**
	 * 操作查询条数
	 */
	public function getRecordNum($where){
		$sql = 'select count(Autoid) as count from '.$this->tbUserMsgSituationInfo.$where;

		$re = $this->model->query($sql);

		return $re[0]['count'];
	}
	/**
	 * 操作已发送查询条数
	 */
	public function getSendRecordNum($where){
		$sql = 'select count(Autoid) as count from '.$this->tbUserMsgInfo.$where;
		
		$re = $this->model->query($sql);
		
		return $re[0]['count'];
	}
	/**
	 * 操作已删除条目
	 */
	public function getDelRecordNum($where){
		$sql = 'select sum(count) as count from (';
		$sql .= ' select count(Autoid) as count from '.$this->tbUserMsgSituationInfo.$where[0];	
		$sql .= ' union all ';		
		$sql .= ' select count(Autoid) as count from '.$this->tbUserMsgInfo.$where[1].') as msgTable';
		
		$re = $this->model->query($sql);
		
		return $re[0]['count'];
	}
	/**
	 * 取个人未读短信息条数
	 */
	public function getMsgRecord($fieldArr,$pagesize=30,$page=1){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);

		switch(strtolower($fieldArr['type'])){
			case 'unreadmsg':
				$where = ' where a.uEstate = 2 and a.uFormID = \''.$fieldArr['UserID'].'\'';
				$order = ' order by b.uAppendTime desc';
				return $this->getCommonMsg($where,$pagesize,$page,$order);
				break;
			case 'readmsg':
				$where = ' where a.uEstate = 1 and a.uFormID = \''.$fieldArr['UserID'].'\'';
				$order = ' order by b.uAppendTime desc';
				return $this->getCommonMsg($where,$pagesize,$page,$order);
				break;
			case 'sendmsg':
				$where = ' where a.uEstate <> 0 and a.uToID = \''.$fieldArr['UserID'].'\'';
				$order = ' order by a.uAppendTime desc';
				return $this->getSendMsg($where,$pagesize,$page,$order);
				break;
			case 'delmsg':
				$where[0] = ' where a.uEstate = 0 and a.uFormID = \''.$fieldArr['UserID'].'\'';
				$where[1] = ' where a.uEstate = 0 and a.uToID = \''.$fieldArr['UserID'].'\'';
				$order = ' order by uAppendTime desc';
				return $this->getDelMsg($where,$pagesize,$page,$order);
				break;
		}
	}
	/**
	 * 返回记录信息
	 */
	public function getCommonMsg($where,$pagesize,$page,$order=''){
		$sql = 'select a.Autoid as selfid,a.uMsgID as uMsgID,a.uToID as UserID,b.uContent,b.uAppendTime,\'receive\' as ident  from '.$this->tbUserMsgSituationInfo.' a left join '.$this->tbUserMsgInfo.' b on a.uMsgID = b.Autoid '.$where.$order;
		
		return $this->getPage($sql,$pagesize,$page);
	}
	/**
	 * 返回已发送记录信息
	 */
	public function getSendMsg($where,$pagesize,$page,$order=''){
		$field = 'a.Autoid as selfid,a.Autoid as uMsgID,a.uToID as UserID,a.uContent,a.uAppendTime,\'send\' as ident';
		$field .= ',(select GROUP_CONCAT(c.uName) from '.$this->tbUserMsgSituationInfo.' as b left join '.$this->tbUserInfo.' as c on b.uFormID = c.UserID where a.Autoid = b.uMsgID) as userList';
		$field .= ',(select GROUP_CONCAT(b.uFormID) from '.$this->tbUserMsgSituationInfo.' as b where a.Autoid = b.uMsgID) as idList';
		
		$sql = 'select '.$field.' from '.$this->tbUserMsgInfo.' a '.$where.$order;

		return $this->getPage($sql,$pagesize,$page);
	}
	/**
	 * 取删除记录信息
	 */
	public function getDelMsg($where,$pagesize,$page,$order=''){
		$sql = ' select * from (';
		$sql .= ' select a.Autoid as selfid,a.uMsgID as uMsgID,a.uFormID as UserID,b.uContent,b.uAppendTime,\'receive\' as ident  from '.$this->tbUserMsgSituationInfo.' a left join '.$this->tbUserMsgInfo.' b on a.uMsgID = b.Autoid '.$where[0];
		$sql .= ' union all ';
		$sql .= ' select a.Autoid as selfid,a.Autoid as uMsgID,a.uToID as UserID,a.uContent,a.uAppendTime,\'send\' as ident  from '.$this->tbUserMsgInfo.' a '.$where[1].') as msgTable '.$order;
		
		return $this->getPage($sql,$pagesize,$page);
	}
	/**
	 * 分页处理
	 */
	public function getPage($sql,$pagesize,$page){
		$countArr = $this->model->query($sql);	
	
		if($page <= 1){
			$page = 1;
		}
		$limit = ' limit '.($page-1)*$pagesize.','.$pagesize;
		$backArr['record'] = $this->model->query($sql.$limit);
		
		if($backArr['record']){
			$backArr['count'] = count($countArr);
		}else{
			$backArr['count'] = 0;
		}

		return $backArr;
	}
	/**
	 * 取个人未读短信息详细条目
	 */
	public function getDetailMsg($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);

		if($fieldArr['type'] == 'unreadMsg'){
			$condition['uFormID'] = $fieldArr['UserID'];
			$condition['uMsgID']  = $fieldArr['uMsgID'];
			
			$udate['uEstate'] = 1;
			
			$this->model->table($this->tbUserMsgSituationInfo)->data($udate)->where($condition)->update();
		}

		switch($fieldArr['ident']){
			case 'receive':	
				$where = ' where a.uFormID = \'' . $fieldArr['UserID'] . '\' and b.Autoid = \''.$fieldArr['uMsgID'].'\'';
				return $this->getDetailCommonMsg($where);
				break;
			case 'send':
				$where[0] = ' where a.uToID =  \'' . $fieldArr['UserID'] . '\' and a.Autoid = \''.$fieldArr['uMsgID'].'\'';
				$where[1] = ' where a.uToID =  \'' . $fieldArr['UserID'] . '\' and a.uMsgID = \''.$fieldArr['uMsgID'].'\'';
				return $this->getDetailSendMsg($where);
				break;
				break;
		}
	}
	/**
	 * 取短信息详细信息
	 */
	public function getDetailCommonMsg($where){
		$sql = 'select c.UserID,c.uName as people,b.uContent as content,b.uAncMsgID,b.uPreMsgID,b.Autoid as uMsgID,b.uAppendTime from '.$this->tbUserMsgSituationInfo.' a left join '.$this->tbUserMsgInfo.' b on a.uMsgID = b.Autoid left join '.$this->tbUserInfo.' c on a.uToID = c.UserID '.$where;

		$re = $this->model->query($sql);
		
		if($re){
			return $re[0];
		}else{
			return '';
		}
	}
	/**
	 * 取已发送短信息详细记录
	 */
	public function getDetailSendMsg($where){
		$field = 'a.Autoid as selfid,a.Autoid as uMsgID,a.uToID as UserID,a.uContent as content,a.uAppendTime,\'send\' as ident';
		$field .= ',(select GROUP_CONCAT(c.uName) from '.$this->tbUserMsgSituationInfo.' as b left join '.$this->tbUserInfo.' as c on b.uFormID = c.UserID where a.Autoid = b.uMsgID) as userList';
		$field .= ',(select GROUP_CONCAT(b.uFormID) from '.$this->tbUserMsgSituationInfo.' as b where a.Autoid = b.uMsgID) as idList';
		
		$sql = 'select '.$field.' from '.$this->tbUserMsgInfo.' a '.$where[0];

		$sql = 'select a.uContent as content,a.uAppendTime from '.$this->tbUserMsgInfo.' a '.$where[0];
		
		$re = $this->model->query($sql);
		
// 		if($re){
// 			return $re[0];
// 		}else{
// 			return '';
// 		}
// 		return $re;

		if($re){
			$backArr['title']       = $re[0]['title'];
			$backArr['content']     = $re[0]['content'];
			$backArr['uAppendTime'] = $re[0]['uAppendTime'];
		}
		
		$sql = 'select b.uName,b.UserID from '.$this->tbUserMsgSituationInfo.' a left join '.$this->tbUserInfo.' b on a.uFormID = b.UserID '.$where[1];

		
		$rec = $this->model->query($sql);

		$backArr['list'] = $rec;
		
		return $backArr;		
	}
	/**
	 * 保存短信息
	 * UserID：发送者UserID
	 * uName：接收者名称
	 * accepter：接收者姓名字以","分割的字符串
	 * uContent：接收内容
	 * 
	 */
	public function doSaveMsg($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);

		$UserID = $fieldArr['UserID'];
		
		$mdata['uToID']       = $UserID;
		$mdata['uAncMsgID']   = 0;
		$mdata['uPreMsgID']   = 0;
		$mdata['uTitle']      = $fieldArr['uTitle'];
		$mdata['uContent']    = ComFun::preg_html($fieldArr['uContent']);
		$mdata['uEstate']     = 1;
		$mdata['uAppendTime'] = time();

		$msgID = $this->model->table($this->tbUserMsgInfo)->data($mdata)->insert();
		
		$accepter = preg_replace( '/，/', ',', $fieldArr['accepter']);
		
		$accepterArr = explode(',',$accepter);
	
		if($accepterArr){
			foreach($accepterArr as $val){
				if(trim($val) != trim($fieldArr['uName'])){
					$uFormID = $this->getUserMsg($val);
					if($uFormID){
						$udata['uFormID']     = $uFormID;
						$udata['uToID']       = $UserID;
						$udata['uMsgID']      = $msgID;
						$udata['uEstate']     = 2;
						$udata['uAppendTime'] = time();
					
						$this->model->table($this->tbUserMsgSituationInfo)->data($udata)->insert();
					}
				}		
			}
		}	
		
		return $msgID;
	}
	
	/**
	 * 保存短信息与队列平台对接方法
	 * uToID：发送者UserID
	 * uName：接收者名称
	 * accepter：接收者姓名字以","分割的字符串
	 * uContent：接收内容
	 *
	 */
	public function doSaveShortMsgWithName ($fieldArr) {
		$_da = 0;
		try {
			$mdata['uToID']       = $fieldArr['uToID'];
			$mdata['uAncMsgID']   = 0;
			$mdata['uPreMsgID']   = 0;
			if ( $fieldArr['uTitle'] ) {
				$mdata['uTitle']  = $fieldArr['uTitle'];
			}
			$mdata['uContent']    = ComFun::preg_html($fieldArr['uContent']);
			$mdata['uEstate']     = 1;
			$mdata['uAppendTime'] = time();
			
			$msgID = $this->model->table($this->tbUserMsgInfo)->data($mdata)->insert();
			
			$accepter = preg_replace( '/，/', ',', $fieldArr['accepter']);
			$accepterArr = explode(',',$accepter);
			
			if($accepterArr){
				foreach($accepterArr as $val){
					if(trim($val) != trim($fieldArr['uName'])){
						$uFormID = $this->getUserMsg($val);
						if($uFormID){
							$udata['uFormID']     = $uFormID;
							$udata['uToID']       = $fieldArr['uToID'];
							$udata['uMsgID']      = $msgID;
							$udata['uEstate']     = 2;
							$udata['uAppendTime'] = time();
							
							$this->model->table($this->tbUserMsgSituationInfo)->data($udata)->insert();
						}
					}
				}
			}
			
			return $msgID;
		} catch ( Exception $e ) {
			return $_da;
		}
	}
	
	/**
	 * 保存短信息与队列平台对接方法
	 * uToID：发送者UserID
	 * uName：接收者名称
	 * accepter：接收者姓名字以","分割的字符串
	 * uContent：接收内容
	 *
	 */
	public function doSaveShortMsgWithID ($fieldArr) {
		$_da = 0;
		try {
			$mdata['uToID']       = $fieldArr['uToID'];
			$mdata['uAncMsgID']   = 0;
			$mdata['uPreMsgID']   = 0;
			if ( $fieldArr['uTitle'] ) {
				$mdata['uTitle']  = $fieldArr['uTitle'];
			}
			$mdata['uContent']    = ComFun::preg_html($fieldArr['uContent']);
			$mdata['uEstate']     = 1;
			$mdata['uAppendTime'] = time();
				
			$msgID = $this->model->table($this->tbUserMsgInfo)->data($mdata)->insert();
				
			$accepter = preg_replace( '/，/', ',', $fieldArr['accepter']);
			$accepterArr = explode(',',$accepter);
				
			if($accepterArr){
				foreach($accepterArr as $val){
					if( $val && trim($val) != trim($fieldArr['uToID'])){
						$udata['uFormID']     = $val;
						$udata['uToID']       = $fieldArr['uToID'];
						$udata['uMsgID']      = $msgID;
						$udata['uEstate']     = 2;
						$udata['uAppendTime'] = time();
							
						$this->model->table($this->tbUserMsgSituationInfo)->data($udata)->insert();
					}
				}
			}
				
			return $msgID;
		} catch ( Exception $e ) {
			return $_da;
		}
	}
	
	/**
	 * 回复短信息
	 */
	public function doAnswerMsg($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
		
		$UserID = ComFun::getCookies('UserID');
	
		$uMsgID = $fieldArr['uMsgID'];
		$uAncMsgID = $fieldArr['uAncMsgID'];
		
		if($uAncMsgID == 0){
			$uAncMsgID = $uMsgID;
		}else{
			$uAncMsgID = $fieldArr['uAncMsgID'];
		}
		
		$mdata['uToID']       = $UserID;
		$mdata['uAncMsgID']   = $uAncMsgID;
		$mdata['uPreMsgID']   = $uMsgID;
		$mdata['uContent']    = ComFun::preg_html($fieldArr['uContent']);
		$mdata['uEstate']     = 1;
		$mdata['uAppendTime'] = time();

		$msgID = $this->model->table($this->tbUserMsgInfo)->data($mdata)->insert();
		
		$udata['uFormID']     = $fieldArr['uFormID'];
		$udata['uToID']       = $UserID;
		$udata['uMsgID']      = $msgID;
		$udata['uEstate']     = 2;
		$udata['uAppendTime'] = time();
		
		$this->model->table($this->tbUserMsgSituationInfo)->data($udata)->insert();	
	}
	/**
	 * 根据条件，取用户信息
	 */
	public function getUserMsg($uName){		
		$condition['uName'] = $uName;
		
		$re = $this->model->table($this->tbUserInfo)->field('UserID')->where($condition)->select();
		
		if($re){
			return $re[0]['UserID'];
		}else{
			return 0;
		}		
	}
	/**
	 * 删除短信息处理方法二
	 */
	public function doDelMsg($fieldArr){	
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
	
		$idstr = $fieldArr['idstr'];
		
		if($idstr){
			$idArr = explode(',', substr($idstr,1));
			//sendMsg
			
			if($fieldArr['type'] == 'sendMsg'){
				$tableName = $this->tbUserMsgInfo;					
			}else{
				$tableName = $this->tbUserMsgSituationInfo;	
			}
		
			$udate['uEstate'] = 0;
		
			foreach($idArr as $val){				
				$condition['Autoid'] = $val;
				$this->model->table($tableName)->data($udate)->where($condition)->update();
			}	
		}
	}
	/**
	 * 删除短信息处理方法二
	 */
	public function doDelMsg_w2($fieldArr){
		$fieldArr = $this->Addslashes->get_addslashes($fieldArr);
	
		$where = 'uEstate != 0 and Autoid = \''.$fieldArr['id'].'\'';
		
		if($fieldArr['type'] == 'sendMsg'){
			$tableName = $this->tbUserMsgInfo;
			$where .= ' and uToID = \''.$fieldArr['UserID'].'\'';
		}else{
			$tableName = $this->tbUserMsgSituationInfo;
			$where .= ' and uFormID = \''.$fieldArr['UserID'].'\'';
		}
		
		if($this->model->table($tableName)->where($where)->select()){		
			$udate['uEstate'] = 0;		
			$this->model->table($tableName)->data($udate)->where($where)->update();		

			return 1;
		}else{
			return -1;
		}				
	}
	
	/**
	 * 取类
	 */
	protected function getClass($className,$fieldArr=''){
		switch($className){
			case 'ModifyProfile':
				include(dirname(__FILE__).'/ModifyProfile.class.php');
				return new ModifyProfile($this->model);
				break;
		}
	}
}
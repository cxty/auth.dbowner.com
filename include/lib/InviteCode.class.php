<?php
/**
 * 邀请码处理
 * 
 * @author wbqing405@sina.com
 */
class InviteCode{
	
	var $tbUserInviteCode = 'tbUserInviteCode'; //邀请码
	
	public function __construct($model){
		$this->model = $model;
	}	
	/**
	 * 生成邀请码
	 */
	public function getInviteCode($fieldArr){
		if(empty($fieldArr['UserID'])){
			ComFun::throwMsg('Ex_EmptyUserID');
		}
		if(empty($fieldArr['client_id'])){
			ComFun::throwMsg('Ex_LostParam102');
		}

		$inviteCode = $this->createInviteCode(); //激活码
		
		$data['client_id']  = $fieldArr['client_id'];
		$data['inviteCode'] = $inviteCode;
		$data['fromUserID'] = $fieldArr['UserID'];
		$data['appendTime'] = time();
	
		$this->model->table($this->tbUserInviteCode)->data($data)->insert();
		
		return $inviteCode;
	}
	/**
	 * 随机邀请码生成
	 */
	private function createInviteCode(){
		$inviteCode = ComFun::getRandom(20,3,25); //激活码
		
		$condition['inviteCode'] = $inviteCode;
		
		if($this->model->table($this->tbUserInviteCode)->field('autoid')->where($condition)->select()){
			return self::createInviteCode();
		}else{
			return $inviteCode;
		}
	} 
	/**
	 * 判断邀请码是否使用  
	 * return: 1未使用 -1使用
	 */
	public function IsUseInviteCode($fieldArr){
		if(empty($fieldArr['inviteCode'])){
			ComFun::throwMsg('Ex_EmptyInviteCode');
		}

		$condition['inviteCode'] = $fieldArr['inviteCode'];
		$condition['status']     = 0;
		
		if($this->model->table($this->tbUserInviteCode)->where($condition)->select()){
			return 1;
		}else{
			return -1;
		}
	}
	/**
	 * 判断邀请码是否使用（条件为传进来的数组）
	 * return: 1未使用 -1使用
	 */
	public function IsUserInviteCodeStri($condition){
		if(!is_array($condition)){
			ComFun::throwMsg('Ex_ErrorCondition');
		}
		
		$condition['status'] = 0;
		
		if($this->model->table($this->tbUserInviteCode)->where($condition)->select()){
			return 1;
		}else{
			return -1;
		}
	}
	/**
	 * 使用邀请码
	 */
	public function UseInviteCode($fieldArr){
		if(empty($fieldArr['inviteCode'])){
			ComFun::throwMsg('Ex_EmptyInviteCode');
		}
		if(empty($fieldArr['UserID'])){
			ComFun::throwMsg('Ex_EmptyUserID');
		}
		
		$condition['inviteCode'] = $fieldArr['inviteCode'];	
		
		$data['status']    = 1;
		$data['toUserID']  = $fieldArr['UserID'];
		$data['useTime']   = time();

		$this->model->table($this->tbUserInviteCode)->data($data)->where($condition)->update();
	}
	/**
	 * 使用邀请码（条件为传进来的数组）
	 */
	public function UseInviteCodeStri($condition,$data){
		$data['status']   = 1;
		$data['useTime']  = time();
		
		$this->model->table($this->tbUserInviteCode)->data($data)->where($condition)->update();
	}
	/**
	 * 判断激活码是否有效
	 */
	public function IsInviteCodeValid($fieldArr){
		$condition['inviteCode']   = $fieldArr['inviteCode'];
		
		$rbArr = $this->model->table($this->tbUserInviteCode)->where($condition)->select();
		
		if($rbArr){
			return 1;
		}else{
			return -1;
		}
	}
// 	/**
// 	 * 激活码是否已经存在
// 	 */
// 	private function IsActiveCodeUsed($fieldArr){
// 		$condition['inviteCode']   = $fieldArr['inviteCode'];
		
// 		$rbArr = $this->model->table($this->tbUserInviteCode)->where($condition)->select();
		
// 		if($rbArr){
// 			return 1;
// 		}else{
// 			return -1;
// 		}
// 	}
	/**
	 * 用户是否已经激活过
	 * return 1已经激活过 -1还未激活
	 */
	public function IsUserActive($fieldArr){			
		if($this->getUserActive($fieldArr) != -1){
			return 1;
		}else{
			return -1;
		}
	}
	/**
	 * 取用户激活信息
	 * return 1已经激活过 -1还未激活
	 */
	public function getUserActive($fieldArr){
		if(empty($fieldArr['UserID'])){
			ComFun::throwMsg('Ex_EmptyUserID');
		}
	
		$condition['toUserID']  = $fieldArr['UserID'];
		$condition['status']    = 1;
	
		$tbArr = $this->model->table($this->tbUserInviteCode)->field('autoid,client_id')->where($condition)->select();
		
		if($tbArr){
			return $tbArr[0];
		}else{
			return -1;
		}
	}
	/**
	 * 用户已经生成了几个激活码
	 */
	public function getInviteCodeCount($fieldArr){		
		$table = $this->getAllInviteCode($fieldArr);
		
		if($table){
			return count($table);
		}else{
			return -1;
		}
	}
	/**
	 * 取用户对应用所有的邀请码
	 */
	public function getAllInviteCode($fieldArr){
		$condition['client_id']  = $fieldArr['client_id'];
		$condition['fromUserID'] = $fieldArr['UserID'];
		
		return $this->model->table($this->tbUserInviteCode)->where($condition)->select();
	}
}
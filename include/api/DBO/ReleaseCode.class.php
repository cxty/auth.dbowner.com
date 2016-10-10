<?php
/**
 * Oauth数据解密
 *
 * @author wbqing405@sina.com
 */
class ReleaseCode{
	var $tbUserInfo = 'tbUserInfo'; //用户信息表
	
	function __construct($model){
		$this->model = $model;
	}
	/**
	 * user_id换取用户名
	 */
	function getUserNameByListUserID($fieldArr){
		if(is_array($fieldArr)){
			include(dirname(dirname(dirname(dirname(__FILE__)))).'/conf/config.php');
			include_once(dirname(dirname(dirname(__FILE__))).'/lib/MandOAuth.class.php');
			$MandOAuth = new MandOAuth($this->model, $config);
			foreach($fieldArr as $key=>$val){	
				if($val){
					try{
						$openidStr = $MandOAuth->releaseStr($val);
					}catch(Exception $e){
						//return $e->getMessage();
						$openidStr = '';
					}
					
					if ( $openidStr ) {
						$openidArr = explode('|',$openidStr);
						if($openidArr){
							$UserID = 	$openidArr[0];
							$ListID[$key] = $UserID;
							if($UserID){
								$newListID[] = $UserID;
							}
						}
						$nArr[$key]['original'] = $val;
						$nArr[$key]['release'] = $openidStr;
						$nArr[$key]['openidArr']  = $openidArr;
					} else {
						$nArr[$key]['original'] = '';
						$nArr[$key]['release'] = '';
						$nArr[$key]['openidArr']  = '';
					}
				}else{
					$nArr[$key]['original'] = '';
					$nArr[$key]['release'] = '';
					$nArr[$key]['openidArr']  = '';
				}		
				
			}

			$idStr = implode(',',array_unique($newListID));
			
			if($newListID){
				$condition = 'UserID in ('.implode(',',array_unique($newListID)).')';
			
				$rbArr = $this->model->table($this->tbUserInfo)->field('UserID,uName')->where($condition)->select();
				//return $nArr;
				foreach($nArr as $key=>$val){
					if($val['original']){
						$re[$key]['original'] = $val['original'];
						
						if($val['release']){
							if(is_array($rbArr)){
								foreach($rbArr as $va){
									if( strstr( '|' . $val['release'], '|' . $va['UserID'].'|' ) ){
										$re[$key]['username'] = $va['uName'];
										break;
									}else{
										$re[$key]['username'] = -2;
									}
								}
							}else{
								$re[$key]['username'] = -2;
							}
						}else{
							$re[$key]['username'] = -1;
						}
					}else{
						$re[$key]['original'] = '';
						$re[$key]['username'] = '';
					}
								
				}
			}
			
			return $re;
		}else{
			return -1;
		}
	}
}
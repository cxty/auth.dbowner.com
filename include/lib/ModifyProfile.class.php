<?php
/**
 * main处理类
 *
 * @author wbqing405@sina.com
 */

include_once('Config.class.php'); //引入处理类的编码格式 utf-8
include_once('Addslashes.class.php'); //数据过滤类
include_once('ComFun.class.php'); //取$_COOKIES值

class ModifyProfile{
	var $tbuserheadinfo  = 'tbuserheadinfo'; //用户头像信息
	var $tbUserInfo = 'tbUserInfo'; //用户基础信息表
	var $tbUserAccountSafeInfo = 'tbUserAccountSafeInfo'; //用户账户安全信息 
	var $tbUserAuthInfo = 'tbUserAuthInfo'; //用户应用授权 
	var $tbUserCharacterInfo = 'tbUserCharacterInfo'; //用户个性设置 
	var $tbUserPointInfo = 'tbUserPointInfo'; //用户积分记录 
	var $tbUserAuthenticationsInfo = 'tbUserAuthenticationsInfo'; //第三方平台登录信息 方
	
	public function __construct($model,$config=''){
		$this->model = $model;
		
		global $config;
		$this->config = $config;

		$this->init();
	}
	
	/**
	 * 初始化
	 */
	private function init(){
		$this->Addslashes = new Addslashes();
	}

	/**
	 * 取加密的$_COOKIE值
	 */
	public function getCookies($value=false){
		return ComFun::getCookies($value);
	}
	
	/**
	 * 保存头像信息
	 * @param unknown_type $picUrl 头像地址
	 */
	public function savePortrait($picUrl){
		$this->uhURL  = basename($picUrl);	

		$this->UserID = $this->getCookies('UserID');
		
		$condition['UserID'] = $this->UserID;

		if($this->model->table($this->tbuserheadinfo)->where($condition)->select()){
			$updateArr['uhURL']        = $this->uhURL;
			$updateArr['uhAppendTime'] = time();
	
			$this->model->table($this->tbuserheadinfo)->data($updateArr)->where($condition)->update();
		}else{
			$insertArr['UserID']       = $this->UserID;
			$insertArr['uhURL']        = $this->uhURL;
			$insertArr['uhState']      = 0;
			$insertArr['uhAppendTime'] = time();
			
			$this->model->table($this->tbuserheadinfo)->data($insertArr)->insert();
		}		
	}
	
	/**
	 * 取当前用户的头像信息
	 */
	public function getPortrait($UserID=0){
		if($UserID === 0){
			$UserID = $this->getCookies('UserID');
		}else{
			$UserID = $UserID;
		}
		$condition['UserID'] = $UserID;
		$imagesInfo = $this->model->table($this->tbuserheadinfo)->where($condition)->select();
		
		if($imagesInfo){
			$imagesUrl = $imagesInfo[0]['uhURL'];
			
			$re['imagesUrl_1'] = $this->config['FILE_SERVER_GET'].'&filecode='.$imagesUrl.'&w='.$this->config['IMAGES']['BIG'];
			$re['imagesUrl_2'] = $this->config['FILE_SERVER_GET'].'&filecode='.$imagesUrl.'&w='.$this->config['IMAGES']['MID'];
			$re['imagesUrl_3'] = $this->config['FILE_SERVER_GET'].'&filecode='.$imagesUrl.'&w='.$this->config['IMAGES']['SMA'];
		}else{			
			$thirdImagesUrl = $this->getThirdPortrait($UserID);
			if($thirdImagesUrl){
				$this->thirdImagesUrl = $thirdImagesUrl;
				$re['imagesUrl_1'] = $this->thirdImagesUrl;
				$re['imagesUrl_2'] = $this->thirdImagesUrl;
				$re['imagesUrl_3'] = $this->thirdImagesUrl;
			}else{
				$base = $this->config['PLATFORM']['Auth'] . '/cache/images/'; 
				$re['imagesUrl_1'] = $base.'default_b.png';
				$re['imagesUrl_2'] = $base.'default_m.png';
				$re['imagesUrl_3'] = $base.'default_s.png';
			}
		}
		
		return $re;
	}
	/**
	 * 剪切图片
	 */
	public function getImages($type,$len){	
		if($type != 1){
			$src_img = __PUBLIC__.'/images/mod-avatar-custom.png';
		}else{
			$src_img = __PUBLIC__.'/images/mod-avatar-custom.png';
		}

		$base = '/cache/images/default_bb.png';
		
		$dst_img = dirname(dirname(__FILE__)).$base;
	
		include('CutPicture.class.php');
	
		$CutPicture = new CutPicture();
	
		$picture = $CutPicture->img2thumb($src_img,$dst_img,$len,$len);
	echo __ROOT__.$base;exit;
		return __ROOT__.$base;
	}
	/**
	 * 取第三方绑定的头像信息
	 */
	public function getThirdPortrait($UserID=false){
		if($UserID){
			$UserID = $UserID;
		}else{
			$UserID = $this->getCookies('UserID');
		}

		$where = 'UserID = \''.$UserID.'\' and uImages != \'\'';
		$order = 'uCreatedDateTime desc';
		$imagesInfo = $this->model->table($this->tbUserAuthenticationsInfo)->where($where)->order($order)->select();

		if($imagesInfo){
			return $imagesInfo[0]['uImages'];
		}else{
			return false;
		}
	}
	
	/**
	 * 取密码信息
	 */
	public function getPwd(){
		//$condition['UserID'] = $this->getCookies('UserID');
		$condition = 'uEstate != 2 and UserID = \''.$this->getCookies('UserID').'\'';
		
		$userInfo = $this->model->table($this->tbUserInfo)->where($condition)->select();
		
		return $userInfo[0];
	}

	/**
	 * 获取用户安全信息
	 */
	public function getSafeInfo(){
		$condition['UserID'] = $this->getCookies('UserID');
		$userSafeInfo = $this->model->table($this->tbUserAccountSafeInfo)->where($condition)->select();
		return $userSafeInfo[0];
	}
	/**
	 * 获取用户安全信息
	 */
	public function getSafeInfoByUserID($UserID){
		try{
			$condition['UserID'] = $UserID;
			
			$field = 'UserID,uRealName,uAuthName,uSafeEmail,uAuthEmail,uSafePhone';
			
			$re = $this->model->table($this->tbUserAccountSafeInfo)->field($field)->where($condition)->select();
			
			if($re){
				return $re[0];
			}else{
				return '';
			}
		}catch(Exception $e){
			return '';
		}
		
	}
	/**
	 * 保存用户真实姓名
	 */
	public function addRealName($fieldArr){
		try{
			$condition['UserID'] = $fieldArr['UserID'];
			
			$data['uRealName'] = $fieldArr['uRealName'];
			$data['uAuthType'] = $fieldArr['uAuthType'];
			$data['uAuthNum']  = $fieldArr['uAuthNum'];
			
			if($this->model->table($this->tbUserAccountSafeInfo)->field('AutoID')->where($condition)->select() ){
				$this->model->table($this->tbUserAccountSafeInfo)->data($data)->where($condition)->update();
			}else{
				$data['UserID'] = $fieldArr['UserID'];
				
				$this->model->table($this->tbUserAccountSafeInfo)->data($data)->insert();
			}
			
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 保存安全邮箱
	 */
	public function checkSafeEmail($params){
		$this->params = $this->Addslashes->get_addslashes($params);
		$this->UserID     = $this->getCookies('UserID');
		
		$condition['UserID'] = $this->UserID;

		$data['uSafeEmail'] = $this->params['uSafeEmail'];

		if($this->model->table($this->tbUserAccountSafeInfo)->where($condition)->select()){
			$this->model->table($this->tbUserAccountSafeInfo)->data($data)->where($condition)->update();
		}else{
			$data['UserID'] = $this->UserID;
			$this->model->table($this->tbUserAccountSafeInfo)->data($data)->insert();
		}
	}
	/**
	 * 新
	 * 保存安全认证邮箱
	 */
	public function addSafeEmail($fieldArr){
		try{
			$condition['UserID'] = $fieldArr['UserID'];
			
			$data['uSafeEmail']  = $fieldArr['uSafeEmail'];
			$data['uOSafeEmail'] = $fieldArr['uOSafeEmail'];
			$data['uEmailCode']  = $fieldArr['uEmailCode'];
			$data['uEmailTime']  = time();
			
			if($this->model->table($this->tbUserAccountSafeInfo)->field('AutoID')->where($condition)->select() ){
				$data['uAuthEmail']  = 1;
				
				$this->model->table($this->tbUserAccountSafeInfo)->data($data)->where($condition)->update();
			}else{
				$data['UserID'] = $fieldArr['UserID'];
				
				$this->model->table($this->tbUserAccountSafeInfo)->data($data)->insert();
			}
			
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 激活安全邮箱
	 */
	public function updateSafeEmail($fieldArr){
		try{
			$where = '(uSafeEmail = \''.$fieldArr['uSafeEmail'].'\' or uOSafeEmail = \''.$fieldArr['uSafeEmail'].'\') and uEmailCode = \''.$fieldArr['uEmailCode'].'\'';
			$field = 'AutoID,uSafeEmail,uOSafeEmail,uAuthEmail';

			$_re = $this->model->table($this->tbUserAccountSafeInfo)->field($field)->where($where)->select();

			if($_re){
				if($_re[0]['uAuthEmail'] == 1){
					if($_re[0]['uOSafeEmail']){
						$data['uSafeEmail']  = $_re[0]['uOSafeEmail'];
						$data['uOSafeEmail'] = $_re[0]['uSafeEmail'];
					}
					$data['uAuthEmail'] = 0;
					
					$this->model->table($this->tbUserAccountSafeInfo)->data($data)->where($where)->update();
					
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 检查安全邮箱是否已经注册过
	 */
	public function checkExistEmail($fieldArr){
		try{
			$where = '(uSafeEmail = \''.$fieldArr['uSafeEmail'].'\' or uOSafeEmail = \''.$fieldArr['uSafeEmail'].'\')';
			$where .= ' and UserID != \''.$fieldArr['UserID'].'\'';
			
			if($this->model->table($this->tbUserAccountSafeInfo)->field('AutoID')->where($where)->select()){
				return true;
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 选出指定用户的安全邮箱相关信息
	 */
	public function getAuthEmailByUserID($UserID){
		try{
			$condition['UserID'] = $UserID;
			
			$field = 'uSafeEmail,uOSafeEmail,uEmailCode,uAuthEmail,uEmailTime';
			
			$_re = $this->model->table($this->tbUserAccountSafeInfo)->field($field)->where($condition)->select();
			
			if($_re){
				return $_re[0];
			}else{
				return '';
			}
		}catch(Exception $e){
			return '';
		}
	}
	/**
	 * 保存安全认证邮箱
	 */
	public function addSafePhone($fieldArr){
		try{
			$condition['UserID'] = $fieldArr['UserID'];
				
			$data['uSafePhone']  = $fieldArr['uSafePhone'];
			$data['uPhoneTime']  = time();
				
			if($this->model->table($this->tbUserAccountSafeInfo)->field('AutoID')->where($condition)->select() ){
				$this->model->table($this->tbUserAccountSafeInfo)->data($data)->where($condition)->update();
			}else{
				$data['UserID'] = $fieldArr['UserID'];
	
				$this->model->table($this->tbUserAccountSafeInfo)->data($data)->insert();
			}
				
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 保存安全手机号
	 */
	public function checkSafePhone($params){
		$this->UserID     = $this->getCookies('UserID');
		
		$condition['UserID'] = $this->UserID;
		
		$data['uSafePhone'] = trim($params['uSafePhone']);

		if($this->model->table($this->tbUserAccountSafeInfo)->where($condition)->select()){
			$this->model->table($this->tbUserAccountSafeInfo)->data($data)->where($condition)->update();
		}else{
			$data['UserID'] = $this->UserID;
			$this->model->table($this->tbUserAccountSafeInfo)->data($data)->insert();
		}
	}	
	/**
	 * 选出指定用户的安全手机相关信息
	 */
	public function getAuthPhoneByUserID($UserID){
		try{
			$condition['UserID'] = $UserID;
				
			$field = 'uSafePhone,uPhoneTime';
				
			$_re = $this->model->table($this->tbUserAccountSafeInfo)->field($field)->where($condition)->select();
				
			if($_re){
				return $_re[0];
			}else{
				return '';
			}
		}catch(Exception $e){
			return '';
		}
	}
	/**
	 * 检验手机号码是否已经注册
	 */
	public function checkExistPhone($fieldArr){
		try{
			$where = 'uSafePhone = \''.$fieldArr['uSafePhone'].'\'';
			$where .= ' and UserID != \''.$fieldArr['UserID'].'\'';
			
			if($this->model->table($this->tbUserAccountSafeInfo)->field('AutoID')->where($where)->select()){
				return true;
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * 取用户积分
	 */
	public function getUserInter($user_id){
		try{
			if($user_id){
				$dbSoap = $this->getClass('DBSoap');
				$where = 'user_id = \''.$user_id.'\'';
				$_re = $dbSoap->SelectTableInfo('Pay', 'SelectUserInfo', $where);

				if($_re['data']){
					return $_re['data'][0]['CoinDB'];
				}else{
					return 0;
				}
			}else{
				return 0;
			}
			
		}catch(Exception $e){
			return 0;
		}
	}
	
	/**
	 * 获取授权信息
	 */
	public function getAuthApp(){
		$condition['UserID'] = $this->getCookies('UserID');
		$ba = $this->model->table($this->tbUserAuthInfo)->where($condition)->select();
		return $ba[0];
	}
	
	/**
	 * 授权信息修改
	 */
	public function updateAuthApp($params){
		$this->params = $this->Addslashes->get_addslashes($params);
		
		$this->UserID     = $this->getCookies('UserID');
		$condition['UserID'] = $this->UserID;
		
		$this->type = $this->params['type'];
		switch(intval($this->type)){
			case 0:
				$data['uAuthDefault'] = $this->params['tValue'];			
				break;
			case 1:
				$data['uAuthApp'] = $this->params['tValue'];
				break;
			case 2:
				$data['uAuthWay'] = $this->params['tValue'];
				break;
		}

		if($this->model->table($this->tbUserAuthInfo)->where($condition)->select()){
			$this->model->table($this->tbUserAuthInfo)->data($data)->where($condition)->update();
		}else{
			$data['UserID'] = $this->UserID;
			$this->model->table($this->tbUserAuthInfo)->data($data)->insert();
		}
	}
	
	/**
	 * 获取用户个性信息
	 */
	public function getCharacterInfo(){
		$condition['UserID'] = $this->getCookies('UserID');
		$ba = $this->model->table($this->tbUserCharacterInfo)->where($condition)->select();
		return $ba[0];
	}
	
	/**
	 * 个性设置更新
	 */
	public function updatePrivate($params){
		$this->params = $this->Addslashes->get_addslashes($params);
		
		$this->UserID     = $this->getCookies('UserID');
		$condition['UserID'] = $this->UserID;
		
		$this->type = $this->params['type'];
		switch(intval($this->type)){
			case 0:
				$data['uLanguage'] = $this->params['tValue'];
				break;
			case 1:
				$data['uCountrySpace'] = $this->params['tValue'];
				break;
			case 2:
				$data['uNowTimeZong'] = $this->params['tValue'];
				break;
			case 3:
				$data['uAuthRecordGeo'] = $this->params['tValue'];
				break;
		}
		
		if($this->model->table($this->tbUserCharacterInfo)->where($condition)->select()){
			$this->model->table($this->tbUserCharacterInfo)->data($data)->where($condition)->update();
		}else{
			$data['UserID'] = $this->UserID;
			$this->model->table($this->tbUserCharacterInfo)->data($data)->insert();
		}
	}
	
	/**
	 * 获得用户剩余积分
	 */
	public function getSurplurPoint(){
		$this->UserID     = $this->getCookies('UserID');
		$sql = 'select (select sum(uPoints) from '.$this->tbUserPointInfo.' where uPointCome = 1 and UserID = '.$this->UserID.')-(select sum(uPoints) from '.$this->tbUserPointInfo.' where uPointCome = 2 and UserID = '.$this->UserID.') as SurplusPoint';
		
		$point = $this->model->query($sql);
		
		return $point[0]['SurplusPoint'];
	}
	/**
	 * 取得类
	 */
	private function getClass($className,$fieldArr=null){
		switch($className){
			case 'DBSoap':
				include_once('DBSoap.class.php');
				return new DBSoap();
				break;
		}
	}
}
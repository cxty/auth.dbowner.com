<?php
/**
 *
 * @author wbqing405@sina.com
 *
 * 评论脚本接口
 *
 */
class commentMod extends commonMod{
	/**
	 * 取总的评论情况
	 */
	public function getCommentStar(){
		header ( "content-type: text/javascript; charset=utf-8" );
		
		$lang = $_GET['lang'] ? $_GET['lang'] : 'zh' ;
		
		$UserID = ComFun::getCookies('UserID');
		
		$client_id = $_GET['client_id'];
		
		if(!$client_id){
			echo 'document.write(\'<div id="js_appscoreBox">缺少client_id,重新刷新.</div>\');';exit;
		}
		
		$tArr['UserID']    = $UserID;
		$tArr['client_id'] = $client_id;
		
		$appScore = $this->getClass('AppScore');
		$appstar = $appScore->getAllStarAnal($tArr);
		
		$appstar['StarAve'] = $appstar['StarCount'] !== 0 ?  $appstar['StarSum']/$appstar['StarCount'] : 0;
		
		$public = $this->config['PLATFORM']['Auth'] . '/public';
		
		$html = 'var js_u_star={
						css : "'.$public.'/css/js_commentstar.css",
						JS_LANG : '.json_encode(Lang::getAssign('JS_LANG',$lang)).',
						appstar : '.json_encode($appstar).',
					};';
		
		$js = array(
				array('js'=>$public.'/js/raty/jquery.raty.js','class'=>'raty'),
				array('js'=>$public.'/js/js_commentstar.js','class'=>'star')
		);
		
		$html .='(function() {  ';
		$html .= 'document.write(\'<div id="js_comment_starShell"></div>\'); ';
		$html .= ' var ga = null;';
		$html .= ' var s = null;';
		foreach ($js as $key=>$val){
			$html .= 'ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true; ';
			$html .= 'ga.src = "'.$val['js'].'"; ';
			$html .= 's = document.getElementsByTagName(\'script\')['.$key.']; s.parentNode.appendChild(ga, s); ';
		}
		$html .= 'ga = null;';
		$html .= 's = null;';
		$html .='})();';
		
		echo $html;exit;
	}
	/**
	 * 取页面评论列表
	 */
	public function getcomment(){
		header ( "content-type: text/javascript; charset=utf-8" );
		
		$lang = $_GET['lang'] ? $_GET['lang'] : 'zh' ;
		
		$UserID = ComFun::getCookies('UserID');

		$client_id = $_GET['client_id'];
		
		if(!$client_id){
			echo 'document.write(\'<div id="js_appscoreBox">缺少client_id,重新刷新.</div>\');';exit;
		}
		
		$root = $this->config['PLATFORM']['Auth'];
		$public = $root . '/public';
		$isrc =  $root . '/comment/showcomment?lang='.$lang.'&client_id='.$client_id;
		
		$html = 'var js_u={
						root : "'.$root.'",
					};';
		
		$html .='(function() {  ';
		$html .= 'document.write(\'<div id="js_appscore_box" style="width:820px;margin:0 auto;">\'); ';
		$html .= 'document.write(\'<Iframe  src="'.$isrc.'" name="js_appscore_iframe"  id="js_appscore_iframe" width="100%" height="100%"  marginheight="0" marginwidth="0" frameborder="0" scrolling="no" ></iframe>\'); ';
		$html .= 'document.write(\'</div>\');';
		$html .= 'var ga = null;';
		$html .= 'var s = null;';
		$html .= 'ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true; ';
		$html .= 'ga.src = "' . $public . '/js/js_commentframe.js"; ';
		$html .= 's = document.getElementsByTagName(\'script\')[0]; s.parentNode.appendChild(ga, s); ';
		$html .= 'ga = null;';
		$html .= 's = null;';

		$html .='})();';	
		
		echo $html;exit;
		
	}
	/**
	 * 中间代理页面
	 */
	public function changeComment(){
		$this->display ('jsapp/changeComment.html');
	}
	
	/**
	 * 改变内容高度
	 */
	public function changeCommentHeight(){
		$jsonp_callback=$_GET['callback'];//...//如果正确
		
		$rb = array();
		if ( $_GET['type'] == 'set' ) {
			$params['ctHeight'] = $_GET['ctHeight'];
			ComFun::SetCookies($params);
		} else {
			$rb = array(
					'ctHeight' => $_COOKIE['ctHeight'] ? ComFun::getCookies('ctHeight') : 300,
			);
		}
		
		echo $jsonp_callback."(".json_encode($rb).")"; return;
	}
	
	/**
	 * 显示评论列表
	 */
	public function showComment(){
		$lang = $_GET['lang'] ? $_GET['lang'] : 'zh' ;
		
		$UserID = ComFun::getCookies('UserID');
		
		$client_id = $_GET['client_id'];
		
		if(!$client_id){
			echo 'document.write(\'<div id="js_appscoreBox">缺少client_id,重新刷新.</div>\');';exit;
		}
		
		//是否已经登录
		if(empty($UserID)){
			$comInfo['show'] = false;
		}else{
			$tArr['UserID']    = $UserID;
			$tArr['client_id'] = $client_id;
		
			$mandOAuthLog = $this->getClass('MandOAuthLog');
			$rbArr = $mandOAuthLog->IsExistAppInfo($tArr);
		
			//用户是否已经使用过应用
			if($rbArr == -1 || $rbArr == -2){
				$comInfo['show'] = false;
			}else{
				$appScore = $this->getClass('AppScore');
		
				//用户是否已经评论过
				if($appScore->IsExistRecord($tArr)){
					$comInfo['show'] = false;
				}else{
					$comInfo['show'] = true;
				}
			}
		}
		
		$comInfo['client_id'] = $client_id;
		$comInfo['root'] = $this->config['PLATFORM']['Auth'];
		$comInfo['public'] = $comInfo['root'] . '/public';
		
		$this->assign('comInfo',$comInfo);
		$this->assign('JData',json_encode($comInfo));
		$this->assign('HTML_LANG',Lang::getAssign('JS_LANG',$lang));
		$this->assign('APP_LANG',json_encode(Lang::getAssign('JS_LANG',$lang)));
		$this->display ('jsapp/showComment.html');
	}
	/**
	 * 取评论列表(js版,需解决跨域问题)
	 */
	public function getshowcomment(){
		header ( "content-type: text/javascript; charset=utf-8" );
		
		$lang = $_GET['lang'] ? $_GET['lang'] : 'zh' ;
		
		$UserID = ComFun::getCookies('UserID');

		$client_id = $_GET['client_id'];
		
		if(!$client_id){
			echo 'document.write(\'<div id="js_appscoreBox">缺少client_id,重新刷新.</div>\');';exit;
		}
		
		//是否已经登录
		if(empty($UserID)){
			$show = false;
		}else{
			$tArr['UserID']    = $UserID;
			$tArr['client_id'] = $client_id;
				
			$mandOAuthLog = $this->getClass('MandOAuthLog');
			$rbArr = $mandOAuthLog->IsExistAppInfo($tArr);
				
			//用户是否已经使用过应用
			if($rbArr == -1 || $rbArr == -2){
				$show = false;
			}else{
				$appScore = $this->getClass('AppScore');
		
				//用户是否已经评论过
				if($appScore->IsExistRecord($tArr)){
					$show = false;
				}else{
					$show = true;
				}
			}
		}

		$root = $this->config['PLATFORM']['Auth'];
		$public = $root . '/public';
		
		$html = 'var js_u_starlist={
						js_root : "'.$root.'",
						css1 : "'.$root.'/include/ext/editor/kindeditor/themes/default/default.css",
						css2 : "'.$root.'/include/ext/editor/kindeditor/plugins/code/prettify.css",
						css3 : "'.$public.'/js/boxy/boxy.css",
						css4 : "'.$public.'/js/pagination/pagination.css",
						css5 : "'.$public.'/js/fancybox/jquery.fancybox.css?v=2.1.2",
						css6 : "'.$public.'/css/js_appScoreInfo.css",
						client_id : "'.$client_id.'",
						show : "'.$show.'",
						JS_LANG : '.json_encode(Lang::getAssign('JS_LANG',$lang)).',
					};';
		
		$js = array(
				array('js'=>$public.'/js/raty/jquery.raty.js','class'=>'raty'),
				array('js'=>$root.'/include/ext/editor/kindeditor/kindeditor-min.js','class'=>'window.KindEditor'),
				array('js'=>$root.'/include/ext/editor/kindeditor/lang/zh_CN.js','class'=>'KindEditor.lang'),
				array('js'=>$root.'/include/ext/editor/kindeditor/plugins/code/prettify.js','class'=>'prettify'),
				array('js'=>$public.'/js/jquery.idrop.js','class'=>'idrop'),
				array('js'=>$public.'/js/jquery.boxy.js','class'=>'boxy'),
				array('js'=>$public.'/js/pagination/jquery.pagination.js','class'=>'pagination'),
				array('js'=>$public.'/js/fancybox/jquery.fancybox.js?v=2.1.3','class'=>'fancybox'),
				array('js'=>$public.'/js/fancybox/jquery.fancybox.pack.js?v=2.1.3','class'=>'fancybox.pack'),
				array('js'=>$public.'/js/js_star.js','class'=>'star')
				);

		$html .='(function() {';
		$html .= 'document.write(\'<div id="js_appscoreBox"></div>\');';
		$html .= ' var ga = null;';
		$html .= ' var s = null;';
		foreach ($js as $key=>$val){
			$html .= 'ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true; ';
			$html .= 'ga.src = "'.$val['js'].'"; ';
			$html .= 's = document.getElementsByTagName(\'script\')['.$key.']; s.parentNode.appendChild(ga, s); ';
		}
		$html .= 'ga = null;';
		$html .= 's = null;';
		$html .='})();';
		
		echo $html;exit;	
	}
	/**
	 * 保存评论
	 */
	public function saveComment(){	
		$UserID = ComFun::getCookies('UserID');
		
		if(empty($UserID)){
			echo -1;exit;
		}

		if(strlen($_POST['comment']) > 120){
			echo -3;exit;
		}
		
		$tArr['UserID']    = $UserID;
		$tArr['client_id'] = $_POST['client_id'];
		$mandOAuthLog = $this->getClass('MandOAuthLog');
		$rbArr = $mandOAuthLog->IsExistAppInfo($tArr);
		if($rbArr == -1 || $rbArr == -2){
			echo -2;exit;
		}
		
		$_POST['UserID'] = $UserID;

		$appScore = $this->getClass('AppScore');
		
		echo $appScore->addAppScore($_POST);exit;
	}
	/**
	 * 取评论字数
	 */
	public function getwordcount(){
		echo strlen($_GET['comment']);exit;
	}
	/**
	 * 取评论列表
	 */
	public function getmemberlist(){
		$client_id = $_POST['client_id'];
		$tArr['UserID'] = ComFun::getCookies('UserID');
		$tArr['client_id'] = $client_id;
		$appScore = $this->getClass('AppScore');
		$pagesize = $_POST['items_per_page'] ? $_POST['items_per_page'] : 10;
		$page = $_POST['pageIndex'] ? $_POST['pageIndex'] : 0;
		$applist = $appScore->getAppScoreList($tArr,$pagesize,$page,'a.aAppendTime desc');
		
		if($applist['list']){
			foreach($applist['list'] as $key=>$val){			
				if(date('Y:m:d',$val['aAppendTime']) == date('Y:m:d')){
					$applist['list'][$key]['aAppendTime'] = Lang::get('Today').' '.date('H:i',$val['aAppendTime']);
				}elseif(date('Y:m:d',($val['aAppendTime']+24*60*60)) == date('Y:m:d')){
					$applist['list'][$key]['aAppendTime'] = Lang::get('Yestoday').' '.date('H:i',$val['aAppendTime']);
				}else{
					$applist['list'][$key]['aAppendTime'] = date('Y-m-d H:i',$val['aAppendTime']);
				}

				/* 
				 * ====个人头像处理======
				 * 
				if($val['uhURL']){
					$applist['list'][$key]['uhURL'] = $this->config['FILE_SERVER_GET'].'&filecode='.$val['uhURL'].'&w=32';
				}else{
					$applist['list'][$key]['uhURL'] = $root.'/cache/images/default_s.png';
				}
				*/
				switch($val['aStar']){
					case '1':
						$applist['list'][$key]['score'] = '10'.Lang::get('Score');
						$applist['list'][$key]['aStar'] = 'stars-1';
						break;
					case '2':
						$applist['list'][$key]['score'] = '20'.Lang::get('Score');
						$applist['list'][$key]['aStar'] = 'stars-2';
						break;
					case '3':
						$applist['list'][$key]['score'] = '30'.Lang::get('Score');
						$applist['list'][$key]['aStar'] = 'stars-3';
						break;
					case '4':
						$applist['list'][$key]['score'] = '40'.Lang::get('Score');
						$applist['list'][$key]['aStar'] = 'stars-4';
						break;
					case '5':
						$applist['list'][$key]['score'] = '50'.Lang::get('Score');
						$applist['list'][$key]['aStar'] = 'stars-5';
						break;
					default:
						$applist['list'][$key]['score'] = '10'.Lang::get('Score');
						$applist['list'][$key]['aStar'] = 'stars-1';
						break;
				}
				
				$applist['list'][$key]['floor'] = $applist['count']-$_POST['items_per_page']*$_POST['pageIndex'];
			}
		}
		
		$applist['star'] = $appScore->GetStarList($tArr);
		
		echo json_encode($applist); exit;
	}
	/**
	 * 取得类
	 */
	private function getClass($className,$fieldArr=null){
		switch($className){
			case 'AppScore':
				include_once(dirname(dirname(__FILE__)).'/include/lib/AppScore.class.php');
				return new AppScore($this->model);
				break;
			case 'MandOAuthLog':
				include_once(dirname(dirname(__FILE__)).'/include/lib/MandOAuthLog.class.php');
				return new MandOAuthLog($this->model);
				break;
		}
	}
}
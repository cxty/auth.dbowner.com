<?php /* Smarty version Smarty-3.0.8, created on 2015-12-23 16:47:58
         compiled from "./templates/index/login_yannyo.html" */ ?>
<?php /*%%SmartyHeaderCode:1650878873567a5fbe9123a9-65290478%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ad69c401b34d99e98e88eb68d5289e990c7c3e82' => 
    array (
      0 => './templates/index/login_yannyo.html',
      1 => 1450860476,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1650878873567a5fbe9123a9-65290478',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/css/login_yannyo.css">

<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jquery.js" ></script>
<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/loginFormNature.js"></script>

</head>
<body>
<div class="content">
	<form method="post" id="login_form" action="<?php echo $_smarty_tpl->getVariable('checkLoginUrl')->value;?>
">
		<ul>
			<li class="introduction"><span>VinSCM账号</span>&nbsp;<span><a href="javascript:void(0)">这是什么？</a></span></li>
			<li><input type="text" id="uEmail_nature"  name="uEmail"></li>
			<li><input type="password" id="passwords_nature" name="uPWD"></li>
			<li><input type="checkbox">使我保持登录状态</li>
			<li><input type="button" value="登录" id="sub_btn"></li>
			<li class="CannotAccess"><span><a href="javascript:void(0)">无法访问你的账号？</a></span></li>
			<li class="remind_info"><?php echo $_smarty_tpl->getVariable('msgkey')->value;?>
</li>
		</ul>
	</form>
</div>
   
   
<script type="text/javascript">
$(function(){
	function GetRequest() {
	   var url = window.location.href; //获取url中"?"符后的字串
	   var theRequest = new Object();
	   if (url.indexOf("?") != -1) {
	      var str = url.substr(1);
	      strs = str.split("&");
	      for(var i = 0; i < strs.length; i ++) {
	         theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
	      }
	   }
	   return theRequest;
	}

	var phref = window.location.href;
	if ( phref ) {
		var request = new Object(); 
		request = GetRequest(); 
		var src_forget = request['src_forget'];
		
		if ( typeof(src_forget) != 'undefined' ) {
			$('#btn_forget').attr('href', src_forget);
		}
		src_forget = null;
		
		var src_register = request['src_register'];
		if ( typeof(src_register) != 'undefined' ) {
			$('#btn_register').attr('href', src_register);
		} 
		src_register = null; 
	}
	phref = null;
	
});

var ismobile = <?php echo $_smarty_tpl->getVariable('ismobile')->value;?>
;
var jdata = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
/*
var loginForm = new TloginForm();
loginForm.ns = '';
loginForm.loginWay = <?php echo $_smarty_tpl->getVariable('loginWay')->value;?>
,
loginForm.Partners_json = <?php echo $_smarty_tpl->getVariable('Partners_json')->value;?>
;
loginForm.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
loginForm.vData = <?php echo $_smarty_tpl->getVariable('vData')->value;?>
;
loginForm.page_type = 2;
//页面完全再入后初始化
$(document).ready(function(){
	loginForm.init();
});
//释放
$(window).unload(function(){
	loginForm = null;
});
*/
</script>

</body>
</html>

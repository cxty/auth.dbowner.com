<?php /* Smarty version Smarty-3.0.8, created on 2015-11-26 18:24:55
         compiled from "./templates/index/login_yannyo_mo.html" */ ?>
<?php /*%%SmartyHeaderCode:9542350055656ddf70e28f1-75559346%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1531f1d104934c513832497648c6e1377aa59f6d' => 
    array (
      0 => './templates/index/login_yannyo_mo.html',
      1 => 1448533491,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9542350055656ddf70e28f1-75559346',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>登录页面</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
<link href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/css/login_mo_yannyo.css" type="text/css" rel="stylesheet">

<link href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/bootstrap/css/bootstrap.css"  type="text/css" rel="stylesheet">
<link href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/css/login_mo_yannyo.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/bootstrap/js/bootstrap.min.js"></script>

<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/loginFormNature.js"></script>

</head>

<body>
<form method="post" id="login_form" action="<?php echo $_smarty_tpl->getVariable('checkLoginUrl')->value;?>
">
   <div class="content_top">
   <div class="content_top_right">
   	 
     <ul>
        <li class="introduction"><span>VinSCM账号</span>&nbsp;<span><a href="javascript:void(0)">这是什么？</a></span></li>
        
        <li> 
           <input type="text" class="form-control" id="uEmail_nature" name="uEmail" placeholder="账号"></input>
        </li>
         
         <li> 
          <input type="password" class="form-control" id="passwords_nature" name="uPWD" placeholder="密码"></input>
         </li>
         
        <!--<li><input type="text" id="txt_name"></li>
        <li><input type="password" id="txt_password"></li>-->
        
        <li><input type="checkbox">使我保持登录状态</li>
        
        <li> <button class="btn btn-lg btn-primary btn-block" id="sub_btn" type="button">登录</button></li>
        
        <li class="CannotAccess"><span><a href="javascript:void(0)">无法访问你的账号？</a></span></li>
        
     </ul> 
   </div>
 </div>
 

 
 </form>
 
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

<?php /* Smarty version Smarty-3.0.8, created on 2015-09-10 14:12:55
         compiled from "./templates/main/profileContact.html" */ ?>
<?php /*%%SmartyHeaderCode:170720657755f11f67e71f36-18769586%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cdab52728aeb743a45b4071bc63f7ba7f5e4bfa1' => 
    array (
      0 => './templates/main/profileContact.html',
      1 => 1430127787,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '170720657755f11f67e71f36-18769586',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>

<link href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/css/pangu_fancy.css" rel="stylesheet" type="text/css" />
	
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jquery.js" ></script>

<link href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/boxy/boxy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jquery.boxy.js" ></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/fancybox/jquery.fancybox.js?v=2.1.3"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/fancybox/jquery.fancybox.css?v=2.1.2" media="screen" />

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/DB.profileContact.js" ></script>

</head>
<body>

<div id="reg_box" style="height:320px;">
    <div class="reset_pwd">
        <div class="input_box">
            <div class="input_box_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['ContactAddr'];?>
：</div>
            <div class="input_big l_input_box">
            	<input type="text" size="20" id="uComeFrom" value="<?php echo $_smarty_tpl->getVariable('userInfo')->value['uComeFrom'];?>
">
            </div>
        </div>
        <div class="input_box">
            <div class="input_box_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['ContactEmail'];?>
：</div>
            <div class="input_big l_input_box">
            	<input type="text" size="20" id="uEmail" value="<?php echo $_smarty_tpl->getVariable('userInfo')->value['uEmail'];?>
">
            </div>
        </div>
        <div class="input_box_login">
            <div class="input_sub" id="submit_btn"><span> <?php echo $_smarty_tpl->getVariable('Lang')->value['ComfirmSubmit'];?>
 </span></div>
        </div>
    </div>
</div>

<script type="text/javascript">
var profileContact = new TprofileContact();
profileContact.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	profileContact.init();
});
//释放
$(window).unload(function(){
	profileContact = null;
});
</script>

</body>
</html>
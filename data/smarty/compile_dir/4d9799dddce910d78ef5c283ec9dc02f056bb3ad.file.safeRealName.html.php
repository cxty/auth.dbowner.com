<?php /* Smarty version Smarty-3.0.8, created on 2015-09-16 15:54:39
         compiled from "./templates/main/safeRealName.html" */ ?>
<?php /*%%SmartyHeaderCode:119711502455f9203f378873-37148081%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4d9799dddce910d78ef5c283ec9dc02f056bb3ad' => 
    array (
      0 => './templates/main/safeRealName.html',
      1 => 1430127788,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '119711502455f9203f378873-37148081',
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
/js/DB.fun.IdCardValidate.js" ></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/DB.safeRealName.js" ></script>

</head>
<body>

<div id="reg_box" style="height:400px;">
    <div class="reset_pwd">
        <div class="input_box">
            <div class="input_box_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['SaftRealName'];?>
：</div>
            <div class="input_big l_input_box">
            	<input type="text" size="20" id="safeRealName">
            </div>
        </div>
        <div class="input_box">
            <div class="input_box_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['AuthType'];?>
：</div>
            <div class="select_big l_input_box"><?php echo $_smarty_tpl->getVariable('authType')->value;?>
</div>
        </div> 
        <div class="input_box">
            <div class="input_box_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['AuthNum'];?>
：</div>
            <div class="input_big l_input_box">
            	<input type="text" size="20" id="safeAuthNum">
            </div>
        </div>
        <div class="input_box_login">
            <div class="input_sub" id="submit_btn"><span> <?php echo $_smarty_tpl->getVariable('Lang')->value['ComfirmSubmit'];?>
 </span></div>
        </div>
    </div>
</div>

<script type="text/javascript">
var safeRealName = new TsafeRealName();
safeRealName.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	safeRealName.init();
});
//释放
$(window).unload(function(){
	safeRealName = null;
});
</script>

</body>
</html>
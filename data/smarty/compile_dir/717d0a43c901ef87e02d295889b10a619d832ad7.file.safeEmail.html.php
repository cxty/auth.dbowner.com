<?php /* Smarty version Smarty-3.0.8, created on 2015-08-19 17:36:41
         compiled from "./templates/main/safeEmail.html" */ ?>
<?php /*%%SmartyHeaderCode:70554508455d44e29d09726-81449583%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '717d0a43c901ef87e02d295889b10a619d832ad7' => 
    array (
      0 => './templates/main/safeEmail.html',
      1 => 1430127788,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '70554508455d44e29d09726-81449583',
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
/js/DB.safeEmail.js" ></script>

</head>
<body>

<div id="reg_box" style="height:250px;">
    <div class="reset_pwd">
        <div class="input_box">
            <div class="input_box_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['EmailAddr'];?>
：</div>
            <div class="input_big l_input_box">
            	<input type="text" size="20" id="safeEmail" value="<?php echo $_smarty_tpl->getVariable('listInfo')->value['uSafeEmail'];?>
">
            </div>
        </div>
        <div class="input_box_login">
            <div class="input_sub" id="submit_btn"><span> <?php if ($_smarty_tpl->getVariable('listInfo')->value['uSafeEmail']){?><?php echo $_smarty_tpl->getVariable('Lang')->value['ComfirmReActive'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('Lang')->value['ComfirmSubmit'];?>
<?php }?> </span></div>
        </div>
    </div>
</div>

<script type="text/javascript">
var safeEmail = new TsafeEmail();
safeEmail.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	safeEmail.init();
});
//释放
$(window).unload(function(){
	safeEmail = null;
});
</script>


</body>
</html>
<?php /* Smarty version Smarty-3.0.8, created on 2015-05-08 14:32:45
         compiled from "./templates/header_auth.html" */ ?>
<?php /*%%SmartyHeaderCode:1064839703554c588db13ce5-71463184%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '749f6432cb5e2417be84d113c141b3305f225768' => 
    array (
      0 => './templates/header_auth.html',
      1 => 1430127792,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1064839703554c588db13ce5-71463184',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta http-equiv="expires" content="Sunday 26 October 2008 01:00 GMT" />
<meta id="viewport" name="viewport" content="width=device-width; initial-scale=1.0;maximum-scale=1.0; user-scalable=no;"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $_smarty_tpl->getVariable('title')->value;?>
</title>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/favicon.ico" />
<link href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/css/pangu_auth.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/css/third_logo.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/boxy/boxy.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jquery.js" ></script>
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jquery.idrop.js" ></script>

<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jquery.boxy.js" ></script>
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jQueryRotate.2.1.js" ></script>
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/pangu_ui.js" ></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jScrollPane/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jScrollPane/jquery.jscrollpane.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jScrollPane/jquery.jscrollpane.css" />
</head>

<body>
<div class="container_box <?php if ($_smarty_tpl->getVariable('css_cont')->value){?><?php echo $_smarty_tpl->getVariable('css_cont')->value;?>
<?php }else{ ?>web_wide<?php }?>" vtype="<?php if ($_smarty_tpl->getVariable('css_cont')->value){?><?php echo $_smarty_tpl->getVariable('css_cont')->value;?>
<?php }else{ ?>web_wide<?php }?>">
	<div class="login_box">
		<?php if (!$_smarty_tpl->getVariable('vdata')->value['showLogo']){?>
		<div class="login_top">
			<div class="logo"><a><img src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/ico_2.png"  height="35" /></a></div>
		<?php }?>
			
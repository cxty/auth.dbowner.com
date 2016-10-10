<?php /* Smarty version Smarty-3.0.8, created on 2015-09-10 13:45:02
         compiled from "./templates/index/resetpwd.html" */ ?>
<?php /*%%SmartyHeaderCode:131492511155f118de3c6259-98752502%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5022847fc66339b150a904b674fec68a4bb07067' => 
    array (
      0 => './templates/index/resetpwd.html',
      1 => 1441184129,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '131492511155f118de3c6259-98752502',
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

<?php if ($_smarty_tpl->getVariable('backArr')->value['view']=='byCenter'){?>
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
	
<?php }?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/resetpwd.js"></script>

</head>
<body>

<?php $_template = new Smarty_Internal_Template('complexify.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<div id="reg_box" style="height:440px;">
    <div class="reset_pwd">
   		<input type="hidden" id="type" value="<?php echo $_smarty_tpl->getVariable('backArr')->value['view'];?>
" />
    	<?php if ($_smarty_tpl->getVariable('backArr')->value['view']=='byEmail'){?>
	   	    <input type="hidden" id="suEmail" value="<?php echo $_smarty_tpl->getVariable('backArr')->value['uEmail'];?>
" />
	   	    <div class="input_box">
	            <div class="input_box_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['AccountEmail'];?>
：</div><div class="input_big l_input_box"><INPUT type="text" size="20" id="uEmail" value=""></div>
	        </div>
	    <?php }else{ ?>
	    	<div class="input_box">
	            <div class="input_box_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['AccountOrigPwd'];?>
：</div><div class="input_big l_input_box"><INPUT type="password" size="20" id="opwd" value=""></div>
	        </div>
        <?php }?>
        <div class="input_box">
            <div class="input_box_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['AccountPwd'];?>
：</div><div class="input_big l_input_box"><INPUT type="password" size="20" id="pwd" value=""></div>
        </div>
        <div class="reset_comp">
	        <div  id="complexitywrap">
				<div id="complexity">0%</div>
			</div>
        </div> 
        <div class="input_box">
            <div class="input_box_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['AccountSPwd'];?>
：</div><div class="input_big l_input_box"><INPUT type="password" size="20" id="spwd" value=""></div>
        </div>
        <div class="input_box_login">
            <div class="input_sub" id="submit_btn"><span> <?php echo $_smarty_tpl->getVariable('Lang')->value['ResetPwd'];?>
 </span></div>
        </div>
    </div>
</div>

<script type="text/javascript">
var resetpwd = new Tresetpwd();
resetpwd.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	resetpwd.init();
});
//释放
$(window).unload(function(){
	resetpwd = null;
});
</script>

</body>
</html>
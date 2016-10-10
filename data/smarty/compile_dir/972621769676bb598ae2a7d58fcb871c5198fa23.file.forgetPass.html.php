<?php /* Smarty version Smarty-3.0.8, created on 2015-09-03 09:49:59
         compiled from "./templates/index/forgetPass.html" */ ?>
<?php /*%%SmartyHeaderCode:101099746755e7a747391048-13971815%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '972621769676bb598ae2a7d58fcb871c5198fa23' => 
    array (
      0 => './templates/index/forgetPass.html',
      1 => 1441184128,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '101099746755e7a747391048-13971815',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/forgetPass.js"></script>

<div class="forgetpwd_box">
	<div class="fg_title"><span class="cl_49ACCF"><?php echo $_smarty_tpl->getVariable('Lang')->value['RetakePwd'];?>
</span> => <?php echo $_smarty_tpl->getVariable('Lang')->value['CheckMail'];?>
 => <?php echo $_smarty_tpl->getVariable('Lang')->value['SetNewPwd'];?>
</div>
	<div class="fg_cont">
		<div class="fg_col"><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['AccountEmail'];?>
</dl><dt><input type="text" class="input" id="uEmail" /></dt></div>
	</div>
	<div class="input_sub fg_btn" id="submit_btn"><span><?php echo $_smarty_tpl->getVariable('Lang')->value['Submint'];?>
</span></div>
</div>

<div class="content_box_c"></div>

<script type="text/javascript">
var forgetPass = new TforgetPass();
forgetPass.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	forgetPass.init();
});
//释放
$(window).unload(function(){
	forgetPass = null;
});
</script>

<?php $_template = new Smarty_Internal_Template('footer.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
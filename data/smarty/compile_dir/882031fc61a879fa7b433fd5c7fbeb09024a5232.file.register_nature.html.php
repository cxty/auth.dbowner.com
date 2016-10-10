<?php /* Smarty version Smarty-3.0.8, created on 2015-09-06 11:56:16
         compiled from "./templates/index/register_nature.html" */ ?>
<?php /*%%SmartyHeaderCode:109132010955ebb960376f60-96690865%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '882031fc61a879fa7b433fd5c7fbeb09024a5232' => 
    array (
      0 => './templates/index/register_nature.html',
      1 => 1441184129,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '109132010955ebb960376f60-96690865',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header_nature.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/regFrom.js"></script>

<div class="reg_warp">
	<div class="reg_box" id="reg_box">
	    <form method="POST" id="regform" name="regform" action="<?php echo $_smarty_tpl->getVariable('__ROOT__')->value;?>
/index/doregister.html">
	    	<div class="l_box title"><?php echo $_smarty_tpl->getVariable('Lang')->value['WelcomeJoin'];?>
:</div>
	        <div class="r_box">
	        	<div class="input_box">
	                <div class="input_big l_input_box">
	                	<INPUT type="text" size="20" name="uEmail" id="uEmail" value=""></div><div id="uEmail_box" class="r_input_box"><?php echo $_smarty_tpl->getVariable('Lang')->value['ActiveEmail'];?>

	                </div>
	            </div>
	            <div class="input_box">
	            	<div class="input_big l_input_box">
	            		<INPUT type="password" size="20" name="uPWD" id="password"></div><div id="password_box" class="r_input_box"><?php echo $_smarty_tpl->getVariable('Lang')->value['remindPass'];?>

	            	</div>
	            </div>
	            <div class="input_box">
	                <div class="input_big l_input_box">
	                	<INPUT type="text" size="20" name="uName" id="uName" value=""></div><div id="uName_box" class="r_input_box"><?php echo $_smarty_tpl->getVariable('Lang')->value['LoginNickName'];?>

	                </div>
	            </div>
	            <div class="input_box_b">
	            	<div class="box_002"></div>
	            	<div class="box_body"><?php echo $_smarty_tpl->getVariable('Lang')->value['UseRule'];?>
<div id="box_body_msg"><?php $_template = new Smarty_Internal_Template('index/regFrom.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?></div></div>
	                <div class="box_001"><div></div><div class="input_sub input_sub_w360" id="submit_btn"><span> <?php echo $_smarty_tpl->getVariable('Lang')->value['ComfirmRegister'];?>
 </span></div></div>              
	            </div>
	        </div>
	    </form>
	</div>
</div>

<script language="javascript" type="text/javascript">
var regFrom = new TregFrom();
regFrom.ns = '';
regFrom.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
//页面完全再入后初始化
$(document).ready(function(){
	regFrom.init();
});
//释放
$(window).unload(function(){
	regFrom = null;
});
</script>

<?php $_template = new Smarty_Internal_Template('footer_nature.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
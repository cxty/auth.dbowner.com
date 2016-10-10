<?php /* Smarty version Smarty-3.0.8, created on 2015-09-06 01:41:57
         compiled from "./templates/index/forgetPass_nature.html" */ ?>
<?php /*%%SmartyHeaderCode:132875611555eb296592ff60-75352933%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dd83631f5aa6be9448562bf602148b839c3a6ba1' => 
    array (
      0 => './templates/index/forgetPass_nature.html',
      1 => 1441184129,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '132875611555eb296592ff60-75352933',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header_nature.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/forgetPass.js"></script>
<div class="reg_warp">
	<div class="reg_box" id="reg_box">
		<div class="forgetpwd_box">
			<div class="fg_title"><span class="cl_49ACCF"><?php echo $_smarty_tpl->getVariable('Lang')->value['RetakePwd'];?>
</span> => <?php echo $_smarty_tpl->getVariable('Lang')->value['CheckMail'];?>
 => <?php echo $_smarty_tpl->getVariable('Lang')->value['SetNewPwd'];?>
</div>
			<div class="fg_cont">
				<div class="fg_col"><dl><?php echo $_smarty_tpl->getVariable('Lang')->value['AccountEmail'];?>
</dl><dt><input type="text" class="input" id="uEmail" /></dt></div>
			</div>
			<div class="fg_box_b" >
				<div class="input_sub input_sub_w200" id="submit_btn"><span><?php echo $_smarty_tpl->getVariable('Lang')->value['Submint'];?>
</span></div>
			</div>
		</div>
	</div>
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

<?php $_template = new Smarty_Internal_Template('footer_nature.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
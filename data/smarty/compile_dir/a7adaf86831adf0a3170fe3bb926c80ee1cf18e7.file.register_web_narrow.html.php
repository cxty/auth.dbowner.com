<?php /* Smarty version Smarty-3.0.8, created on 2015-09-06 16:59:44
         compiled from "./templates/index/register_web_narrow.html" */ ?>
<?php /*%%SmartyHeaderCode:114678796255ec0080444244-14836957%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a7adaf86831adf0a3170fe3bb926c80ee1cf18e7' => 
    array (
      0 => './templates/index/register_web_narrow.html',
      1 => 1441184129,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '114678796255ec0080444244-14836957',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header_auth.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/regFrom_auth.js"></script>
	
		<?php if (!$_smarty_tpl->getVariable('vdata')->value['showLogo']){?>
			<div class="login_right"><a href="javascript:history.back()" id="login"><?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Back'];?>
</a></div>
		</div>
		<?php }?>
	</div>
	
	<div id="reg_form">
	<section>
		<div class="line"></div>
		<form method="POST" id="regform" name="regform" action="/index/doregister.html">
			
			<div class="narrow_sp_top"></div>		
					
			<div class="form_input">
				<div><input type="text" size="20" name="uEmail" id="uEmail" /></div>		
				<div><input type="password" size="20" name="uPWD" id="password" /></div>
				<div><input type="text" size="20" name="uName" id="uName" /></div>
			</div>
					
			<div class="reg_useclause"><a class="check_btn" href="javascript:void(0);"><?php echo $_smarty_tpl->getVariable('Lang')->value['CheckClause'];?>
</a></div>
								
			<div class="form_button">
				<input type="button" id="submit_btn" class="mar_left" size="20" value="<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['ComfirmRegister'];?>
" />
				<input type="button" id="button_btn" class="mar_left form_btn_clo" size="20" value="<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Reset'];?>
" />
			</div>
		</form>
		
		<?php if ($_smarty_tpl->getVariable('vdata')->value['showLogo']){?>
			<div class="login_btn"><a href="javascript:history.back()" id="login"><?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Back'];?>
</a></div>
		<?php }?>
	</section>	
	</div>
	
	<section style="display:none;">
		<div id="use_rule"><?php $_template = new Smarty_Internal_Template('index/regFrom.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?></div>
	</section>


<script language="javascript" type="text/javascript">
var regFromAuth = new TregFromAuth();
regFromAuth.ns = '';
regFromAuth.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
regFromAuth.sData = <?php echo $_smarty_tpl->getVariable('sData')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	regFromAuth.init();
});
//释放
$(window).unload(function(){
	regFromAuth = null;
});
</script>

<?php $_template = new Smarty_Internal_Template('footer_auth.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
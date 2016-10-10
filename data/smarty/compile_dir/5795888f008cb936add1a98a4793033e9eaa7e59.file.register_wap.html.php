<?php /* Smarty version Smarty-3.0.8, created on 2015-11-03 20:37:47
         compiled from "./templates/index/register_wap.html" */ ?>
<?php /*%%SmartyHeaderCode:12135841015638aa9b5fc049-89124936%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5795888f008cb936add1a98a4793033e9eaa7e59' => 
    array (
      0 => './templates/index/register_wap.html',
      1 => 1441184129,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12135841015638aa9b5fc049-89124936',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header_wap_new.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/regFrom_wap.js"></script>
	
				<div class="login_right"><a id="reg"><?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Back'];?>
</a><a href="javascript:history.back()" id="login"><?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Back'];?>
</a></div>
			</div>
		</aside>
	</header>
	
	<div id="reg_form">
	<section>
		<div class="line"></div>
		<form method="POST" id="regform" name="regform" action="/index/doregister.html">
			
			<div class="sp_top_10"></div>		
					
			<div class="form_input">
				<div><input type="text" size="20" name="uEmail" id="uEmail" /></div>		
				<div><input type="password" size="20" name="uPWD" id="password" /></div>
				<div><input type="text" size="20" name="uName" id="uName" /></div>
			</div>
								
			<div class="form_button">
				<input type="button" id="reg_rule_btn" size="20" value="<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['UseRule'];?>
" />
				<input type="button" id="submit_btn" class="mar_left" size="20" value="<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['ComfirmRegister'];?>
" />
				<input type="button" id="button_btn" class="mar_left form_btn_clo" size="20" value="<?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['Reset'];?>
" />
			</div>
		</form>
	</section>	
	</div>
	
	<section>
		<div id="use_rule"><?php $_template = new Smarty_Internal_Template('index/regFrom.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?></div>
	</section>


<script language="javascript" type="text/javascript">
var regFrom = new TregFrom();
regFrom.ns = '';
regFrom.PH_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
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

<?php $_template = new Smarty_Internal_Template('footer_wap_new.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
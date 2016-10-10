<?php /* Smarty version Smarty-3.0.8, created on 2015-09-02 17:51:20
         compiled from "./templates/index/login_new.html" */ ?>
<?php /*%%SmartyHeaderCode:110219195055e6c6983f6680-85278074%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cd112dc5bb12df0b958e33f2a5496e6c2904e738' => 
    array (
      0 => './templates/index/login_new.html',
      1 => 1441184128,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '110219195055e6c6983f6680-85278074',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/loginForm.js"></script>

<div class="content_box">
    <div class="content_box_c">
           <div class="content">
           		
<div class="lp_box">
   <div class="lp_l_box">
   		<div class="title"><?php echo $_smarty_tpl->getVariable('Lang')->value['WelcomeBack'];?>
</div>
   </div>
   <div class="lp_c_box">
   		<div class="lg_box">
   			<div class="title">
   				<ul id="lg_type">
   					<li class="b_r"><a class="pwd pwd_cur" href="javascript:loginForm.changeLoginWay(0,'pwd')"><?php echo $_smarty_tpl->getVariable('Lang')->value['Login_Pwd'];?>
</a></li>
   					<li class="b_r b_l"><i class="flow_icon"></i><a class="tl" href="javascript:loginForm.changeLoginWay(1,'tl')"><?php echo $_smarty_tpl->getVariable('Lang')->value['Login_Third'];?>
</a></li>
   					<li class="b_l"><a class="qr" href="javascript:loginForm.changeLoginWay(2,'qr')"><?php echo $_smarty_tpl->getVariable('Lang')->value['Login_Qr'];?>
</a></li>
   				</ul>	
   			</div>
   			<div class="lg_ct" id="lg_ct_0">
   				<form method="post" id="login_form" action="<?php echo $_smarty_tpl->getVariable('checkLoginUrl')->value;?>
">
	   				<div class="lg_ct_ip"><input type="text" class="input" name="uEmail" id="uEmail"></div>
	   				<div class="lg_ct_ip"><input type="password" class="input" name="uPWD" id="password"></div>
	   				<?php if ($_smarty_tpl->getVariable('msgkey')->value){?><div class="lg_ct_exp"><?php echo $_smarty_tpl->getVariable('msgkey')->value;?>
</div><?php }?>
	   				<div class="lg_ct_ck">
		                <div ><input type="checkbox" value="yes" name="remusrname" id="remusrname" /><label><?php echo $_smarty_tpl->getVariable('Lang')->value['LoginTwoWeek'];?>
</label></div>
		            </div>
		            <div class="lg_ct_btn">
		            	<div class="input_sub lg_ct_btn_lg" id="submit_btn"><span> <?php echo $_smarty_tpl->getVariable('Lang')->value['Login'];?>
 </span></div>
		            	<div class="lg_ct_btn_fg"><a href="/index/forgetPass"><?php echo $_smarty_tpl->getVariable('Lang')->value['ForgetPass'];?>
</a></div>
		            </div>  
	            </form>
   			</div>
   			<div class="lg_ct" id="lg_ct_1">
   				<?php if ($_smarty_tpl->getVariable('thirdLogin')->value){?>
   					<ul id="lg_ct_list_box">
	   					<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('thirdLogin')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
	   						<li id="<?php echo $_smarty_tpl->tpl_vars['item']->value['partner'];?>
" class="def_partners_t"><dt class="<?php echo $_smarty_tpl->tpl_vars['item']->value['icon'];?>
"><?php if (!$_smarty_tpl->tpl_vars['item']->value['icon']){?><?php echo $_smarty_tpl->tpl_vars['item']->value['txt'];?>
<?php }?></dt></li>
	   					<?php }} ?>
   					</ul>
   				<?php }?>
   			</div>
   			<div class="lg_ct" id="lg_ct_2">
   				<div class="lg_ct_img"><img src="<?php echo $_smarty_tpl->getVariable('qrCode')->value;?>
" /></div>
   				<div class="lg_ct_bw"><?php echo $_smarty_tpl->getVariable('Lang')->value['QrLogin'];?>
<a href="<?php echo $_smarty_tpl->getVariable('clientSDK')->value;?>
" target="_blank"><?php echo $_smarty_tpl->getVariable('Lang')->value['Install'];?>
</a></div>
   			</div>
   		</div>
   </div>
   <div class="lp_r_box">
   		<div class="exp_ct" id="exp_ct"></div>
   </div>
</div>
           		
           </div>
    </div>
</div>

<script type="text/javascript">
var loginForm = new TloginForm();
loginForm.ns = '';
loginForm.loginWay = <?php echo $_smarty_tpl->getVariable('loginWay')->value;?>
,
loginForm.Partners_json = <?php echo $_smarty_tpl->getVariable('Partners_json')->value;?>
;
loginForm.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
loginForm.vData = <?php echo $_smarty_tpl->getVariable('vData')->value;?>
;
//页面完全再入后初始化
$(document).ready(function(){
	loginForm.init();
});
//释放
$(window).unload(function(){
	loginForm = null;
});
</script>

<?php $_template = new Smarty_Internal_Template('footer.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
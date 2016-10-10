<?php /* Smarty version Smarty-3.0.8, created on 2015-05-29 17:41:29
         compiled from "./templates/main/profile_u_private.html" */ ?>
<?php /*%%SmartyHeaderCode:19818812645568344901d173-37205839%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b594af794bdf7ca574a656201d4c088228f85b62' => 
    array (
      0 => './templates/main/profile_u_private.html',
      1 => 1430127787,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19818812645568344901d173-37205839',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/profile_u_private.js"></script>

<div class="user_profile_right_box">

	<div class="user_profile_right_box_b" style="display:none;">
        <div class="b_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['BandAccount'];?>
</div>     
        <div class="b_qr">
        	<img src="<?php echo $_smarty_tpl->getVariable('qrUrl')->value;?>
" />
        	<div class="b_exp"><?php echo $_smarty_tpl->getVariable('Lang')->value['QRExplain'];?>
.&nbsp;&nbsp;<a href="<?php echo $_smarty_tpl->getVariable('clientSDK')->value;?>
" target="_blank"><?php echo $_smarty_tpl->getVariable('Lang')->value['Download'];?>
</a></div>
        </div>
    </div>

	<div class="user_profile_right_box_b">
        <div class="b_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['ContactSocial'];?>
</div>
        <div class="b_content" id="contactSocial">
            <ul>
            	<?php if ($_smarty_tpl->getVariable('socialInfo')->value){?>
	            	<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('socialInfo')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
?>
	            		<li><dl><?php echo $_smarty_tpl->tpl_vars['item']->value['txt'];?>
:</dl><dt id="contactSocial_<?php echo $_smarty_tpl->tpl_vars['item']->value['UserAuthenticationsID'];?>
"><?php if ($_smarty_tpl->tpl_vars['item']->value['uDisplay_name']){?><?php echo $_smarty_tpl->tpl_vars['item']->value['uDisplay_name'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['item']->value['txt'];?>
<?php echo $_smarty_tpl->getVariable('Lang')->value['Account'];?>
<?php }?></dt><dt>&nbsp;&nbsp;<a href="javascript:Uprivate.unBinding('<?php echo $_smarty_tpl->tpl_vars['item']->value['uProvider'];?>
')"><?php echo $_smarty_tpl->getVariable('Lang')->value['UnBinding'];?>
</a></dt></li>
	            	<?php }} ?>
	            <?php }?>
	            <?php if ($_smarty_tpl->getVariable('bindArr')->value){?>
	            	<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('bindArr')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
?>
	            		<li><dl><?php echo $_smarty_tpl->tpl_vars['item']->value['txt'];?>
:</dl><dt><a href="/index/loginThirdParty?provider=<?php echo $_smarty_tpl->tpl_vars['item']->value['provider'];?>
&idProvider=dirJoin"><?php echo $_smarty_tpl->getVariable('Lang')->value['Binding'];?>
</a></dt></li>
	            	<?php }} ?>
            	<?php }?>
            </ul>
        </div>
        <div class="b_tool"><a href="#"></a></div>
    </div>
</div>

<script type="text/javascript">
var Uprivate = new TUprivate();
Uprivate.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	Uprivate.init();
});
//释放
$(window).unload(function(){
	Uprivate = null;
});
</script>
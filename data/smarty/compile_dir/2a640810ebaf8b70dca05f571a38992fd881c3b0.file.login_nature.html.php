<?php /* Smarty version Smarty-3.0.8, created on 2016-01-10 05:33:03
         compiled from "./templates/index/login_nature.html" */ ?>
<?php /*%%SmartyHeaderCode:35640296656917c8fe1c891-73331207%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2a640810ebaf8b70dca05f571a38992fd881c3b0' => 
    array (
      0 => './templates/index/login_nature.html',
      1 => 1450860474,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '35640296656917c8fe1c891-73331207',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template('header_nature.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<link href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/css/third_logo_nature.css" rel="stylesheet" type="text/css" />
<!-- 
<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/loginForm.js"></script>
 -->
<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/loginFormNature.js"></script>

<div class="login_wrap <?php if ($_smarty_tpl->getVariable('bg_trans')->value==1){?>bg_trans<?php }else{ ?>bg_ntrans<?php }?>"><div class="login_box">
	<!-- 
	<div class="lg_top" id="lg_type">
		<div class="lg_top_item lg_top_on" ident="pwd">
			<div class="lg_top_text lg_top_line"><?php echo $_smarty_tpl->getVariable('Lang')->value['Login_Account'];?>
</div>
			<div class="lg_top_blank">
				<?php if ($_smarty_tpl->getVariable('bg_trans')->value!=1){?>
					<img src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/tl_trias.png" />
				<?php }?>
			</div>
		</div>
		<div class="lg_top_item" ident="tl">
			<div class="lg_top_text lg_top_line"><?php echo $_smarty_tpl->getVariable('Lang')->value['Login_Third'];?>
</div>
			<div class="lg_top_blank">
				<?php if ($_smarty_tpl->getVariable('bg_trans')->value!=1){?>
					<img src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/tl_trias.png" />
				<?php }?>
			</div>
		</div>
		<div class="lg_top_item" ident="qr">
			<div class="lg_top_text"><?php echo $_smarty_tpl->getVariable('Lang')->value['Login_Qr'];?>
</div>
			<div class="lg_top_blank">
				<?php if ($_smarty_tpl->getVariable('bg_trans')->value!=1){?>
					<img src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/tl_trias.png" />
				<?php }?>
			</div>
		</div>
	</div>
	 -->
	<div class="lg_middle">
		<div class="lg_ct" id="lg_ct_0">
			<form method="post" id="login_form" action="<?php echo $_smarty_tpl->getVariable('checkLoginUrl')->value;?>
">
				<div class="lg_login_box">
					<div class="lo_input_box">
						<input type="text" name="uEmail" id="uEmail_nature" />
						<input type="password" name="uPWD" id="passwords_nature" />
					</div>
					<div class="lo_btn_msg"><?php echo $_smarty_tpl->getVariable('msgkey')->value;?>
</div>
					<div class="lo_btn_box">
						<div class="lo_btn_sub" id="sub_btn"></div>
					</div>
				</div>
			</form>
		</div>
		<!-- 
		<div class="lg_ct" id="lg_ct_0" style="display:none;">
			<form method="post" id="login_form" action="<?php echo $_smarty_tpl->getVariable('checkLoginUrl')->value;?>
">
				<div class="lg_ct_ip">
					<div class="lg_ct_exp"><img src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/tl_email.png" /></div>
					<input type="text" class="input" name="uEmail" id="uEmail">
				</div>
				<div class="lg_ct_ip">
					<div class="lg_ct_exp"><img src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/tl_pwd.png" /></div>
					<input type="password" class="input" name="uPWD" id="password">
				</div>
				<div class="lg_ct_pw">
					<span><?php echo $_smarty_tpl->getVariable('msgkey')->value;?>
</span>
					<a id="btn_forget" href="javascript:void(0);" target="_blank"><?php echo $_smarty_tpl->getVariable('Lang')->value['ForgetPass'];?>
</a>
				</div>
		        <div class="lg_ct_btn"><div class="input_sub input_sub_w380" id="submit_btn"><span> <?php echo $_smarty_tpl->getVariable('Lang')->value['Login'];?>
 </span></div></div>  
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
		 -->
	</div>
	<!-- 
	<div class="lg_bottom">
		<div class="lgb_left">
			<img src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/tl_go.png" />
			<div class="lgb_lbtn"><a id="btn_register" href="javascript:void(0);" target="_blank"><?php echo $_smarty_tpl->getVariable('Lang')->value['Register_Free2'];?>
</a></div>
		</div>
		<div class="lgb_right"><a href="javascript:window.parent.location.href='https://auth.dbowner.com'" target="_blank"><img src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/tl_logo.png" /></a></div>
	</div>
	 -->
</div></div>

<script type="text/javascript">
$(function(){
	function GetRequest() {
	   var url = window.location.href; //获取url中"?"符后的字串
	   var theRequest = new Object();
	   if (url.indexOf("?") != -1) {
	      var str = url.substr(1);
	      strs = str.split("&");
	      for(var i = 0; i < strs.length; i ++) {
	         theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
	      }
	   }
	   return theRequest;
	}

	var phref = window.location.href;
	if ( phref ) {
		var request = new Object(); 
		request = GetRequest(); 
		var src_forget = request['src_forget'];
		
		if ( typeof(src_forget) != 'undefined' ) {
			$('#btn_forget').attr('href', src_forget);
		}
		src_forget = null;
		
		var src_register = request['src_register'];
		if ( typeof(src_register) != 'undefined' ) {
			$('#btn_register').attr('href', src_register);
		} 
		src_register = null; 
	}
	phref = null;
	
});


var jdata = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;
/*
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
loginForm.page_type = 2;
//页面完全再入后初始化
$(document).ready(function(){
	loginForm.init();
});
//释放
$(window).unload(function(){
	loginForm = null;
});
*/
</script>

<?php $_template = new Smarty_Internal_Template('footer_nature.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
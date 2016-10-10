<?php /* Smarty version Smarty-3.0.8, created on 2015-09-16 11:18:57
         compiled from "./templates/index/loginThirdParty.html" */ ?>
<?php /*%%SmartyHeaderCode:54993537455f8dfa1d84545-94065771%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bcfe9eac36b3ab8255cbc2a685e972b5fc280410' => 
    array (
      0 => './templates/index/loginThirdParty.html',
      1 => 1441184128,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '54993537455f8dfa1d84545-94065771',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/joinForm.js"></script>

<div id="join_box_u">
<div class="u_ico"><img src="<?php echo $_smarty_tpl->getVariable('userInfo')->value['uImages'];?>
" width="50" height="50" /></div>
<div class="u_profile">
<ul>
<li><?php echo $_smarty_tpl->getVariable('userInfo')->value['uDisplay_name'];?>
</li>
<li><?php echo $_smarty_tpl->getVariable('userInfo')->value['uEmail'];?>
</li>
</ul>
</div>
</div>
<div id="join_box">
    <form method="POST" id="loginform" name="joinform" action="/index/joinfrom">
    <div class="box_top"></div>
    <div class="box_body">
    	<div class="l_box title"><?php echo $_smarty_tpl->getVariable('Lang')->value['JoinFrom'];?>
</div>
        <div class="r_box">
	    	<div class="input_box">
                <div class="input_big l_input_box"><INPUT type="text" size="20" name="uDisplay_name" id="uName" value="<?php echo $_smarty_tpl->getVariable('userInfo')->value['uDisplay_name'];?>
"></div><div id="uName_box" class="r_input_box"><?php echo $_smarty_tpl->getVariable('Lang')->value['LoginUpdate'];?>
</div>
	        </div>
		    <div class="input_box">
	                <div class="input_big l_input_box"><INPUT type="text" size="20" name="uEmail" id="uEmail" value=""></div><div id="uEmail_box" class="r_input_box"><?php echo $_smarty_tpl->getVariable('Lang')->value['RegisterEmail'];?>
</div>
	        </div>
	            
	        <div id="thirdParty"></div>
	            
	        <div class="input_box_b">
	           <div ><input type="checkbox" name="agree" id="agree" /><label><?php echo $_smarty_tpl->getVariable('Lang')->value['HavedRead'];?>
 <a href="javascript:void(0);" id="to_m"><?php echo $_smarty_tpl->getVariable('Lang')->value['JoinRule'];?>
</a></label></div>
	           <div class="input_l"></div>
	           <div class="input_sub input_sub_r" id="submit_btn"><span> <?php echo $_smarty_tpl->getVariable('Lang')->value['JS_LANG']['JoinUser'];?>
 </span></div>	   
	           <div class="input_l2"></div>
	           <div class="input_sub input_sub_r2" id="submit_btn_Skip"><span> <?php echo $_smarty_tpl->getVariable('Lang')->value['SkipJoin'];?>
 </span></div>	          
	        </div>

        </div>
        </div>
    	<div class="box_bottom"></div>
    </form>
      
</div>

<div class="box_body" style="display:none"><?php echo $_smarty_tpl->getVariable('Lang')->value['UseRule'];?>
<div id="box_body_msg"><?php $_template = new Smarty_Internal_Template('index/regFrom.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?></div></div>
<script language="javascript" type="text/javascript">
var joinForm = new TjoinForm();
joinForm.ns = '';
joinForm.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	joinForm.init();
});
//释放
$(window).unload(function(){
	joinForm = null;
});
</script>
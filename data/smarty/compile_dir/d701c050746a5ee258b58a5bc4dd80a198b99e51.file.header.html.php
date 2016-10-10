<?php /* Smarty version Smarty-3.0.8, created on 2015-05-08 14:38:00
         compiled from "./templates/header.html" */ ?>
<?php /*%%SmartyHeaderCode:1741893107554c59c849c331-88330511%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd701c050746a5ee258b58a5bc4dd80a198b99e51' => 
    array (
      0 => './templates/header.html',
      1 => 1430127792,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1741893107554c59c849c331-88330511',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_smarty_tpl->getVariable('title')->value;?>
 <?php echo $_smarty_tpl->getVariable('SysName')->value;?>
</title>
<?php $_template = new Smarty_Internal_Template('link.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

</head>

<body id="top">


<script lauguage="javascript">
<?php if ($_smarty_tpl->getVariable('show')->value!='auth'&&$_smarty_tpl->getVariable('urlStr')->value&&$_smarty_tpl->getVariable('uName')->value){?>
var headjs = new Theadjs();
headjs.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	headjs.init();
});
//释放
$(window).unload(function(){
	headjs = null;
});
<?php }?>
</script>

<div class="container_box">
        <div class="header_box">
        	<div class="header_box_c">
              <div class="header">
              <div class="header_inner">
              	  <div class="h_img"><a href="/" ><img src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/images/ico_2.png"  height="35" /></a>	</div>  
                  <div class="h_left">
                 	     
                  	  <?php if ($_smarty_tpl->getVariable('show')->value!='auth'){?>                
		                  <?php if ($_smarty_tpl->getVariable('urlStr')->value){?>
		                  	 | <a href="<?php echo $_smarty_tpl->getVariable('__ROOT__')->value;?>
/main/index.html"><?php echo $_smarty_tpl->getVariable('Lang')->value['Index'];?>
</a>
		                  <?php }else{ ?>
		                  	| <a href="<?php echo $_smarty_tpl->getVariable('__ROOT__')->value;?>
/index/index.html"><?php echo $_smarty_tpl->getVariable('Lang')->value['Index'];?>
</a>
		                  	| <a href="<?php echo $_smarty_tpl->getVariable('__ROOT__')->value;?>
/index/register.html"><?php echo $_smarty_tpl->getVariable('Lang')->value['Register'];?>
</a>
		                  <?php }?>
		               <?php }?>
                  </div>
                  <div class="r_left">
                  	  <?php if ($_smarty_tpl->getVariable('show')->value!='auth'){?>
		                  <?php if ($_smarty_tpl->getVariable('urlStr')->value){?>                	   
		                	   <?php if ($_smarty_tpl->getVariable('uName')->value){?>
		                	   <div id="header_tog">
		                	   		<span><?php echo $_smarty_tpl->getVariable('uName')->value;?>
</span>
		                	   		<div style="display:none;">
		                	   			<img id="header_img" src="<?php echo $_smarty_tpl->getVariable('imagesUrl_3')->value;?>
" />	 
		                	   			<a class="header_lo" href="<?php echo $_smarty_tpl->getVariable('__ROOT__')->value;?>
/index/loginOut.html" id="loginout"><?php echo $_smarty_tpl->getVariable('Lang')->value['LoginOut'];?>
</a>
		                	   			<ul>
		                	   				<li><a href="/main/index"><?php echo $_smarty_tpl->getVariable('Lang')->value['PensonalPage'];?>
</a></li>
		                	   				<li><a href="/main/message"><?php echo $_smarty_tpl->getVariable('Lang')->value['PensonalMsg'];?>
<span id="header_num">(<?php echo $_smarty_tpl->getVariable('unread')->value;?>
)</span></a></li> 
		                	   				<li></li>	   				         	   			
		                	   			</ul>		                	   			               	   			
		                	   		</div>
		                	   </div>
		                	   <?php }?>
		                  <?php }else{ ?>
							   <a href="<?php echo $_smarty_tpl->getVariable('__ROOT__')->value;?>
/index/login.html"><?php echo $_smarty_tpl->getVariable('Lang')->value['Login'];?>
</a>
						  <?php }?>
		                  | <a href="#"><?php echo $_smarty_tpl->getVariable('Lang')->value['Help'];?>
</a>
	                  <?php }else{ ?>
	                  	 <a href="javascript:history.back()"><?php echo $_smarty_tpl->getVariable('Lang')->value['Back'];?>
</a> 
	                  <?php }?>
                  </div>
              </div>
          </div>
        </div>
        </div>
        
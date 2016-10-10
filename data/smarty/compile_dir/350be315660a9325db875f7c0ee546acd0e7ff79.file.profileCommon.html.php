<?php /* Smarty version Smarty-3.0.8, created on 2015-07-13 17:42:23
         compiled from "./templates/main/profileCommon.html" */ ?>
<?php /*%%SmartyHeaderCode:81091504255a387ff2cea56-56216163%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '350be315660a9325db875f7c0ee546acd0e7ff79' => 
    array (
      0 => './templates/main/profileCommon.html',
      1 => 1430127787,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '81091504255a387ff2cea56-56216163',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>

<link href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/css/pangu_fancy.css" rel="stylesheet" type="text/css" />
	
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jquery.js" ></script>

<link href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/boxy/boxy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/jquery.boxy.js" ></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/fancybox/jquery.fancybox.js?v=2.1.3"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/fancybox/jquery.fancybox.css?v=2.1.2" media="screen" />

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/DB.profileCommon.js" ></script>

</head>
<body>

<div id="reg_box" style="height:400px;">
    <div class="reset_pwd">
        <div class="input_box">
            <div class="input_box_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['ContactNickname'];?>
：</div>
            <div class="input_big l_input_box">
            	<input type="text" size="20" id="nickName" value="<?php echo $_smarty_tpl->getVariable('userInfo')->value['uName'];?>
">
            </div>
        </div>
        <div class="input_box">
            <div class="input_box_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['Sex'];?>
：</div>
            <div class="select_big l_input_box">
            	<select id="sex">
            		<option value="0"><?php echo $_smarty_tpl->getVariable('Lang')->value['Male'];?>
</option>
            		<option value="1" <?php if ($_smarty_tpl->getVariable('userInfo')->value['uSex']==1){?>selected<?php }?>><?php echo $_smarty_tpl->getVariable('Lang')->value['Female'];?>
</option>
            	</select>
            </div>
        </div>
        <div class="input_box">
            <div class="input_box_title"><?php echo $_smarty_tpl->getVariable('Lang')->value['Birthday'];?>
：</div>
            <div class="select_big l_input_box" id="chooseBirthday">
            	<?php echo $_smarty_tpl->getVariable('year')->value;?>
<?php echo $_smarty_tpl->getVariable('Lang')->value['Year'];?>
<?php echo $_smarty_tpl->getVariable('month')->value;?>
<?php echo $_smarty_tpl->getVariable('Lang')->value['Month'];?>
<?php echo $_smarty_tpl->getVariable('day')->value;?>
<?php echo $_smarty_tpl->getVariable('Lang')->value['Day'];?>

            </div>
        </div>
        <div class="input_box_login">
            <div class="input_sub" id="submit_btn"><span> <?php echo $_smarty_tpl->getVariable('Lang')->value['ComfirmSubmit'];?>
 </span></div>
        </div>
    </div>
</div>

<script type="text/javascript">
var profileCommon = new TprofileCommon();
profileCommon.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	profileCommon.init();
});
//释放
$(window).unload(function(){
	profileCommon = null;
});
</script>

</body>
</html>
<?php /* Smarty version Smarty-3.0.8, created on 2015-09-16 15:59:53
         compiled from "./templates/main/profileWork.html" */ ?>
<?php /*%%SmartyHeaderCode:203412155355f92179385b67-18340007%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a53331abd485ec26dc20bd205d183191ba19d63d' => 
    array (
      0 => './templates/main/profileWork.html',
      1 => 1430127787,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '203412155355f92179385b67-18340007',
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
/js/DB.profileWork.js" ></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('__PUBLIC__')->value;?>
/js/DB.Fun.local.js" ></script>

</head>
<body>

<div id="reg_box" style="height:450px;">
    <div class="reset_pwd">
    	<input type="hidden" id="AutoID" value="<?php echo $_smarty_tpl->getVariable('listInfo')->value['AutoID'];?>
" />
        <div class="input_box">
            <div class="input_box_title_fance"><span class="color_red">*</span><?php echo $_smarty_tpl->getVariable('Lang')->value['CompanyName'];?>
：</div>
            <div class="input_big l_input_box">
            	<input type="text" size="20" id="wCompanyName" value="<?php echo $_smarty_tpl->getVariable('listInfo')->value['wCompanyName'];?>
">
            </div>
        </div>
        <div class="input_box">
            <div class="input_box_title_fance"><?php echo $_smarty_tpl->getVariable('Lang')->value['DepartmentPosition'];?>
：</div>
            <div class="input_big l_input_box">
            	<input type="text" size="20" id="wDepartment" value="<?php echo $_smarty_tpl->getVariable('listInfo')->value['wDepartment'];?>
">
            </div>
        </div>
        <div class="input_box">
            <div class="input_box_title_fance"><?php echo $_smarty_tpl->getVariable('Lang')->value['WorkTime'];?>
：</div>
            <div class="select_big l_input_box">
            	<?php echo $_smarty_tpl->getVariable('startYear')->value;?>
&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('Lang')->value['Until'];?>
&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('endYear')->value;?>

            </div>
        </div>
        <div class="input_box">
            <div class="input_box_title_fance"><?php echo $_smarty_tpl->getVariable('Lang')->value['WorkPlace'];?>
：</div>
            <div class="select_big l_input_box">
            	<?php echo $_smarty_tpl->getVariable('state')->value;?>
&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('provice')->value;?>
&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('city')->value;?>

            </div>
        </div>
        <div class="input_box_login">
            <div class="input_sub" id="submit_btn"><span> <?php echo $_smarty_tpl->getVariable('Lang')->value['ComfirmSubmit'];?>
 </span></div>
        </div>
    </div>
</div>

<script type="text/javascript">
var profileWork = new TprofileWork();
profileWork.JS_LANG = <?php echo $_smarty_tpl->getVariable('JS_LANG')->value;?>
;

//页面完全再入后初始化
$(document).ready(function(){
	profileWork.init();
});
//释放
$(window).unload(function(){
	profileWork = null;
});

var local = new Tlocal();
local.cJson = <?php echo $_smarty_tpl->getVariable('cJson')->value;?>
;
local.proviceName = 'wProvice';
local.cityName = 'wCity';

//页面完全再入后初始化
$(document).ready(function(){
	local.init();
});
//释放
$(window).unload(function(){
	local = null;
});
</script>

</body>
</html>
<?php /* Smarty version Smarty-3.0.8, created on 2015-05-29 18:29:58
         compiled from "./templates/jsapp/showComment.html" */ ?>
<?php /*%%SmartyHeaderCode:99311790455683fa6328882-10074844%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3424af3a7bd3e03e30ec39b379a14892fe3eb10a' => 
    array (
      0 => './templates/jsapp/showComment.html',
      1 => 1430127786,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '99311790455683fa6328882-10074844',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('comInfo')->value['public'];?>
/js/jquery.js" ></script>

<link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('comInfo')->value['root'];?>
/include/ext/editor/kindeditor/themes/default/default.css" />
<link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('comInfo')->value['root'];?>
/include/ext/editor/kindeditor/plugins/code/prettify.css" />
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('comInfo')->value['root'];?>
/include/ext/editor/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('comInfo')->value['root'];?>
/include/ext/editor/kindeditor/lang/zh_CN.js"></script>
<script charset="utf-8" src="<?php echo $_smarty_tpl->getVariable('comInfo')->value['root'];?>
/include/ext/editor/kindeditor/plugins/code/prettify.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('comInfo')->value['public'];?>
/js/jquery.idrop.js"></script>

<link href="<?php echo $_smarty_tpl->getVariable('comInfo')->value['public'];?>
/js/boxy/boxy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('comInfo')->value['public'];?>
/js/jquery.boxy.js" ></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('comInfo')->value['public'];?>
/js/raty/jquery.raty.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('comInfo')->value['public'];?>
/js/pagination/jquery.pagination.js"></script>
<link href="<?php echo $_smarty_tpl->getVariable('comInfo')->value['public'];?>
/js/pagination/pagination.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('comInfo')->value['public'];?>
/js/fancybox/jquery.fancybox.js?v=2.1.3"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('comInfo')->value['public'];?>
/js/fancybox/jquery.fancybox.pack.js?v=2.1.3"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('comInfo')->value['public'];?>
/js/fancybox/jquery.fancybox.css?v=2.1.2" media="screen" />

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('comInfo')->value['public'];?>
/js/js_comment.js"></script>

<link href="<?php echo $_smarty_tpl->getVariable('comInfo')->value['public'];?>
/css/js_appScoreInfo.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="js_appscore_box">
	<div id="js_appscore_editor">
		<input type="hidden" id="js_client_id" value="<?php echo $_smarty_tpl->getVariable('comInfo')->value['client_id'];?>
" />
		<?php if ($_smarty_tpl->getVariable('comInfo')->value['show']){?>
			<div id="js_appscore_com"><a class="js_appscore_href" id="socre_comment" href="javascript:void(0)"><?php echo $_smarty_tpl->getVariable('HTML_LANG')->value['Score_Comment'];?>
</a></div>
			<div id="js_appscore_tea" style="display:none">
				<div id="js_appscore_star"></div>
				<textarea name="comment" id="editor" style="width:100%;height:150px"></textarea>
				<div class="js_appscore_remind"><?php echo $_smarty_tpl->getVariable('HTML_LANG')->value['Score_Word'];?>
:<span id="word_count">0</span>/<span id="word_total">120</span>.&nbsp;&nbsp;<a class="js_appscore_href" id="socre_submit" href="javascript:void(0)"><?php echo $_smarty_tpl->getVariable('HTML_LANG')->value['Score_Submit'];?>
</a></div>
			</div>
		<?php }?>
	</div>
	<div class="js_appscore_detail" id="app_table_cont"></div>
	<div class="js_appscore_page"><div id="pagination" class="pagination" style="display:none"></div></div>
</div>

<iframe id="js_changecoment_iframe" height="0" width="0" src="#" style="display:none" ></iframe>

<script language="javascript" type="text/javascript">
var comment = new Tcomment();
comment.APP_LANG = <?php echo $_smarty_tpl->getVariable('APP_LANG')->value;?>
;
comment.JData = <?php echo $_smarty_tpl->getVariable('JData')->value;?>
;
//页面完全再入后初始化
$(document).ready(function(){
	comment.init();
	comment.editor();
});
//释放
$(window).unload(function(){
	comment = null;
});
</script>
</body>
</html>
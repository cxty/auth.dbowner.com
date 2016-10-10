
function Tresetpwd(){
	this.JS_LANG = '';
};
Tresetpwd.prototype.init = function(){	
	resetpwd.resetpwd();
	
	/*
	$.fancybox({
        type: 'iframe',
        href: 'http://www.baidu.com',
        scrolling: 'no',
        width: 760,
        height: 480,
        autoScale: false,
        centerOnScroll: true,
        hideOnOverlayClick: false,
    });*/
	
	$.fancybox.close();
	$('#submit_btn').click(function(){
		 if($('#uEmail').val() == ''){
			 Boxy.alert( resetpwd.JS_LANG.IsNotNullEmail ,function(){$('#uEmail').focus();},{title: resetpwd.JS_LANG.Remind ,modal:true,unloadOnHide:true});
		 }else if($('#uEmail').val() != $('#suEmail').val()){
			 Boxy.alert( resetpwd.JS_LANG.NotSameEmail ,function(){$('#uEmail').val('').focus();},{title: resetpwd.JS_LANG.Remind ,modal:true,unloadOnHide:true});
		 }else if($('#pwd').val() == ''){
			 Boxy.alert( resetpwd.JS_LANG.IsNotNullNewPwd ,function(){$('#pwd').focus();},{title: resetpwd.JS_LANG.Remind ,modal:true,unloadOnHide:true});
		 }else if($('#spwd').val() == ''){
			 Boxy.alert( resetpwd.JS_LANG.IsNotNullSecondPwd ,function(){$('#spwd').focus();},{title: resetpwd.JS_LANG.Remind ,modal:true,unloadOnHide:true});
		 }else if($('#pwd').val() != $('#spwd').val()){
			 Boxy.alert( resetpwd.JS_LANG.PwdNotSame ,function(){$('#pwd').val('').focus();$('#spwd').val('');},{title: resetpwd.JS_LANG.Remind ,modal:true,unloadOnHide:true});
		 }else{
			 var type = $('#type').val();
			 $.post('/main/resetPwdSave',{type:type,uEmail:$('#uEmail').val(),opwd:$('#opwd').val(),pwd:$('#pwd').val(),rnd:Math.random()},function(data){
				 if(data == -1){
					 Boxy.alert( resetpwd.JS_LANG.Ex_IsExistUser ,function(){$('#uEmail').val('').focus();$('#pwd').val('');$('#spwd').val('');},{title: resetpwd.JS_LANG.Remind ,modal:true,unloadOnHide:true});				 
				 }else if(data == -2){
					 Boxy.alert( resetpwd.JS_LANG.Ex_Relogin ,function(){},{title: resetpwd.JS_LANG.Remind ,modal:true,unloadOnHide:true});
				 }else if(data == -3){
					 Boxy.alert( resetpwd.JS_LANG.Ex_OrigPwdWrong ,function(){$('#opwd').val('').focus();},{title: resetpwd.JS_LANG.Remind ,modal:true,unloadOnHide:true});
				 }else{
					 if(type == 'byCenter'){
						 Boxy.alert( resetpwd.JS_LANG.Ex_Remind204 ,function(){window.parent.userSafe.closeFancybox();},{title: resetpwd.JS_LANG.Remind ,modal:true,unloadOnHide:true});						 
					 }else{
						 location = '/main/index'; 
					 }		 
				 }		 
			 });
		 }
	 });
};
Tresetpwd.prototype.resetpwd = function(){
	$('input[placeholder]').placeholder();
	$("#pwd").complexify({}, function(valid, complexity){
		if (!valid) {
			$('#complexity').animate({'width':complexity + '%'}).removeClass('valid').addClass('invalid');
		} else {
			$('#complexity').animate({'width':complexity + '%'}).removeClass('invalid').addClass('valid');
		}
		$('#complexity').html(Math.round(complexity) + '%');
	});
}
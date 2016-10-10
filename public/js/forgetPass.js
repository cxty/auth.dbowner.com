
function TforgetPass(){
	this.JS_LANG = '';
};
TforgetPass.prototype.init = function(){
	
	//邮箱上层提示信息
	$('#uEmail').val('').before(jQuery('<div class="def_txt">'+this.JS_LANG.Email+'</div>').click(function(){
        $(this).hide();
        $('#uEmail').focus();
    }));
   
	$('#uEmail').focusin(function(){
        $(this).prevAll('.def_txt').hide();
	});
	$('#uEmail').focusout(function(){
	    if($(this).val()==''){
	        $(this).prevAll('.def_txt').show();
	    }else{
	        $(this).prevAll('.def_txt').hide();
	    }
	});
	
	$('#submit_btn').bind('click',forgetPass.sendEmail);
};
TforgetPass.prototype.sendEmail = function(){
	$.fancybox.showLoading();
	$.get('/index/sendforgetPassMail',{uEmail:$('#uEmail').val(),rnd:Math.random()},function(data){	
		$.fancybox.hideLoading();
		switch(parseInt(data)){
			case -1:
				Boxy.alert(forgetPass.JS_LANG.IsNotNullEmail,
						function(){$('#uEmail').val('').focus();},
						{title: forgetPass.JS_LANG.Remind,modal:true,unloadOnHide:true}
						);
				break;
			case -2:
				Boxy.alert(forgetPass.JS_LANG.EffectiveEmail,
						function(){$('#uEmail').val('').focus();},
						{title: forgetPass.JS_LANG.Remind,modal:true,unloadOnHide:true}
						);
				break;
			case -3:
				Boxy.alert(forgetPass.JS_LANG.Ex_IsNotValidUser,
						function(){$('#uEmail').val('').focus();},
						{title: forgetPass.JS_LANG.Remind,modal:true,unloadOnHide:true}
						);
				break;
			case 1:
				Boxy.alert(forgetPass.JS_LANG.Ex_SuccessEmail,
						function(){
							$('#submit_btn').unbind('click').removeClass('input_sub').addClass('input_sub_ed');
							$('#submit_btn span').text(forgetPass.JS_LANG.HadSend);
						},
						{title: forgetPass.JS_LANG.Remind,modal:true,unloadOnHide:true}
						);
				break;
		}
	});
};
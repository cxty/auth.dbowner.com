

function TloginFormNature () {
	this.jdata = '';
	
	this.ismobile = false; //是否手机端
	
	this.isClick = true; //避免重复点击
}
TloginFormNature.prototype.init = function () {
	document.onkeydown = function(e){ 
	    var ev = document.all ? window.event : e;
	     
	    if(ev.keyCode == 13 && loginFormNature.isClick == true ) {
	    //if ( ev.keyCode == 13 ) {
	    	loginFormNature.isClick = false;
	    	loginFormNature.btnClick();
	     }
	};
	
	if ( !loginFormNature.ismobile ) {
		this.initView();
	}
	
	
	$('#sub_btn').click(function(){
		loginFormNature.btnClick();
	});
};
TloginFormNature.prototype.initView = function () {
	//邮箱上层提示信息
    $('#uEmail_nature').val('').before(jQuery('<div class="def_txt">' + this.jdata.Please_Input_Email + '</div>').click(function(){
        $(this).hide();
        $('#uEmail_nature').focus();
    }));
    $('#uEmail_nature').focusin(function(){
        $(this).prev('.def_txt').hide();
	});
	$('#uEmail_nature').focusout(function(){
	    if($(this).val()=='')
	    {
	        $(this).prev('.def_txt').show();
	    }else{
	        $(this).prev('.def_txt').hide();
	    }
	});
	
	//密码上层提示信息
    $('#passwords_nature').val('').before(jQuery('<div class="def_txt">' + this.jdata.Please_Input_Pwd + '</div>').click(function(){
        $(this).hide();
        $('#passwords_nature').focus();
    }));
    $('#passwords_nature').focusin(function(){
        $(this).prev('.def_txt').hide();
	});
	$('#passwords_nature').focusout(function(){
	    if($(this).val()=='')
	    {
	        $(this).prev('.def_txt').show();
	    }else{
	        $(this).prev('.def_txt').hide();
	    }
	});
    
    return;
    
	$('#uEmail_nature').val(this.jdata.Please_Input_Email).focus(function(){
		if ( $(this).val() == loginFormNature.jdata.Please_Input_Email ) {
			$(this).val('');
		}
	}).blur(function(){
		if ( $(this).val() == '' ) {
			$(this).val(loginFormNature.jdata.Please_Input_Email);
		}
	});
	$('#passwords_nature').val(this.jdata.Please_Input_Pwd).focus(function(){
		if ( $(this).val() == loginFormNature.jdata.Please_Input_Pwd ) {
			$(this).val('');
		}
	}).blur(function(){
		if ( $(this).val() == '' ) {
			$(this).val(loginFormNature.jdata.Please_Input_Pwd);
		}
	});
};
TloginFormNature.prototype.btnClick = function () {
	if ( $('#uEmail_nature').val() == '' ) {
		$('#uEmail_nature').focus();
		//alert(loginFormNature.jdata.RightEmail);
		
		loginFormNature.isClick=true;
		/*
        Boxy.alert(loginForm.JS_LANG.RightEmail,function(){
        		$('#'+loginForm.ns+'uEmail').focus();
        	},
        	{title: loginForm.JS_LANG.Remind,modal:true,unloadOnHide:true,afterHide:function(){
        		loginForm.isClick=true;
        	}});      
        	*/  
     } else if ( $('#passwords_nature').val() == '' ) {
    	 $('#passwords_nature').focus();
    	 //alert(loginFormNature.jdata.RightPwd);
    	 
         //$('#'+loginForm.ns+'password').focus();
    	 loginFormNature.isClick=true;
     }else{   
    	//var url = this.vData.type == 'iframe' ? loginForm.vData.domain + '/iframe/checkLogin' : loginForm.vData.host + '/index/checkLogin';
    	 loginFormNature.isClick=true;
    	
    	$('#login_form').submit();
    	
    	return;
    	
     }
};

var loginFormNature = new TloginFormNature();
$(document).ready(function(){
	loginFormNature.jdata = jdata;
	loginFormNature.ismobile = ismobile;
	loginFormNature.init();
});
$(window).unload(function(){
	loginFormNature = null;
});

//author:wbqing405@sina.com

function TloginWap(){
    this.ns = '';
    this.PH_LANG = ''; //语言包
    this.jdata = '';
};
TloginWap.prototype.init = function(){
	$("#loginform").keydown(function(e){
		 var e = e || event,
		 keycode = e.which || e.keyCode;
		 if (keycode==13) {
			 loginWap.subClick();
		 }
	});
	
	if(loginWap.jdata.apiCount > 0){
		$('.login_third ul').css({'width' : loginWap.jdata.apiCount*100+'px'});
	}
	
	$("#login_third").mCustomScrollbar({
		autoHideScrollbar:true,
		horizontalScroll:true,
		scrollButtons:{
			enable:true
		},
		theme:"dark-thin"
	});
	
	loginWap.doinit();
	
	$('#submit_btn').click(function(){
		loginWap.subClick();
    });
	
	$('#button_btn').click(function(){
		loginWap.cancelClick();	
	});
};
TloginWap.prototype.subClick = function(){
	if($.trim($('#uEmail').val()) == ''){
        alert(loginWap.PH_LANG.RightEmail);
        $('#uEmail').focus();           
     }else if($.trim($('#password').val()) == ''){
     	alert(loginWap.PH_LANG.WrongPwd);
     	$('#password').focus();
     }else{   
    	//$.QshowLoading('login_common');
     	$.get('/index/checkLogin',{uEmail:$('#uEmail').val(),uPWD:$('#password').val(),rnd:Math.random()},
     			function(data){ 
     		    //$.QhideLoading('login_common');
 					if(data > 0){
 						$('#UserID').val(data);
 						
 						$('#loginform').submit();					
 					}else{    						
 						alert(loginWap.PH_LANG.LoginWrongRemind);
 						$('#uEmail').val('').focus();
 						$('#password').val('').prevAll('.def_txt').show();
 				    }	
     			}
     	);
         
     }
};
TloginWap.prototype.cancelClick = function(){
	$('#uEmail').val('');
	$('#password').val('');
	$('#uEmail').prevAll('.def_txt').show();
	$('#password').prevAll('.def_txt').show();	
};
TloginWap.prototype.doinit = function(){
	$('#uEmail').val('');
	$('#password').val('');
	
	$('#uEmail').before(jQuery('<div class="def_txt">'+loginWap.PH_LANG.PhAccount+'</div>').click(function(){
        $(this).hide();
        $('#uEmail').focus();
    }));
	
	$('#uEmail').focusin(function(){
        $(this).prevAll('.def_txt').hide();
	});
	$('#uEmail').focusout(function(){
		if($(this).val() == ''){
	        $(this).prevAll('.def_txt').show();
	    }else{		
	        $(this).prevAll('.def_txt').hide();
	    }
	});
	
	$('#password').before(jQuery('<div class="def_txt">'+loginWap.PH_LANG.PhPassword+'</div>').click(function(){
        $(this).hide();
        $('#password').focus();
    }));
	
	$('#password').focusin(function(){
        $(this).prevAll('.def_txt').hide();
	});
	$('#password').focusout(function(){	
	    if($(this).val()==''){
	        $(this).prevAll('.def_txt').show();
	    }else{
	        $(this).prevAll('.def_txt').hide();
	    }
	});
};
TloginWap.prototype.dothirdlogin = function(key){
	var url = '/index/loginThirdParty?provider='+key+'&idProvider=dirauth&display=mobile';
	location = url;
};
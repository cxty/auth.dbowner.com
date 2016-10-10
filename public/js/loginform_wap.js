function TloginForm(){
    this.ns = '';
    this.PH_LANG = ''; //语言包
    this.def_type = '';
};
TloginForm.prototype.init = function(){
	$('#uEmail').val('');
	$('#password').val('');
	
	$('#login_thirdparty').hide();
	$('#login_explain_tog div:eq(0)').text( this.PH_LANG.LoginOther );

	$('#login_explain_tog div:eq(0)').click(function(){
		if($('#login_explain_tog div:eq(0)').text() == loginForm.PH_LANG.LoginOther){
			$('.def_txt' + this.def_type).hide();
			$('#login_form').slideUp("slow");	
			$('#login_thirdparty').slideDown("slow");				
			$('#login_explain_tog div:eq(0)').text(loginForm.PH_LANG.LoginCommon);
		}else if($('#login_explain_tog div:eq(0)').text() == loginForm.PH_LANG.LoginCommon){
			$('.def_txt' + this.def_type).slideDown("slow");
			$('#login_form').slideDown("slow");	
			$('#login_thirdparty').slideUp("slow");				
			$('#login_explain_tog div:eq(0)').text(loginForm.PH_LANG.LoginOther);
		}
	});
	
	$('#'+loginForm.ns+'uEmail').change(function(){
		var uEmail = $('#'+loginForm.ns+'uEmail').val();
		var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/; 
    	if(!reg.test(uEmail)){
    		alert(loginForm.PH_LANG.EmailForm);
    		$('#uEmail').val('').focus();
    	}
    });
	
	$('#'+this.ns+'submit_btn').click(function(){
        if($.trim($('#'+loginForm.ns+'uEmail').val()) == ''){
           alert(loginForm.PH_LANG.RightEmail);
           $('#'+loginForm.ns+'uEmail').focus();           
        }else if($.trim($('#'+loginForm.ns+'password').val()) == ''){
        	alert(loginForm.PH_LANG.WrongPwd);
        	$('#'+loginForm.ns+'password').focus();
        }else{    	
        	$.post('/index/checkLogin',{uEmail:$('#'+loginForm.ns+'uEmail').val(),uPWD:$('#'+loginForm.ns+'password').val(),rnd:Math.random()},
        			function(data){ 
    					if(data > 0){
    						$('#'+loginForm.ns+'UserID').val(data);
    						
    						$('#loginform').submit();					
    					}else{    						
    						alert(loginForm.PH_LANG.LoginWrongRemind);
    						$('#'+loginForm.ns+'uEmail').val('').focus();
    						$('#'+loginForm.ns+'password').val(''); 
    				    }	
        			}
        	);
            
        }
    });
	
	$('#'+this.ns+'button_btn').click(function(){
		$('#'+loginForm.ns+'uEmail').val('');
		$('#'+loginForm.ns+'password').val('');
		$('#'+loginForm.ns+'uEmail').prevAll('.def_txt' + this.def_type).show();
		$('#'+loginForm.ns+'password').prevAll('.def_txt' + this.def_type).show();		
	});

	$('#uEmail').val('').before(jQuery('<div class="def_txt'  + this.def_type + '">'+loginForm.PH_LANG.PhAccount+'</div>').click(function(){
        $(this).hide();
        $('#uEmail').focus();
    }));
	
	$('#uEmail').focusin(function(){
        $(this).prevAll('.def_txt' + this.def_type).hide();
	});
	$('#uEmail').focusout(function(){
		if($(this).val() == ''){
	        $(this).prevAll('.def_txt' + this.def_type).show();
	    }else{		
	        $(this).prevAll('.def_txt' + this.def_type).hide();
	    }
	});
	
	$('#password').val('').before(jQuery('<div class="def_txt'  + this.def_type + '">'+loginForm.PH_LANG.PhPassword+'</div>').click(function(){
        $(this).hide();
        $('#password').focus();
    }));
	
	$('#password').focusin(function(){
        $(this).prevAll('.def_txt' + this.def_type).hide();
	});
	$('#password').focusout(function(){	
	    if($(this).val()==''){
	        $(this).prevAll('.def_txt' + this.def_type).show();
	    }else{
	        $(this).prevAll('.def_txt' + this.def_type).hide();
	    }
	});
};
TloginForm.prototype.dothirdlogin = function(key){
	var url = '/index/loginThirdParty?provider='+key+'&idProvider=dirauth&display=mobile';
	location = url;
};
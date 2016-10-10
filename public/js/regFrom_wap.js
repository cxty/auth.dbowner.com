function TregFrom(){
    this.ns = '';
    this.def_uEmail_txt = '';
    this.def_password_txt = '';
    this.def_uName_txt = '';
    this.PH_LANG = ''; //语言包
    //this.init(); 
}
TregFrom.prototype.init = function(){
	$("#use_rule").mCustomScrollbar({
		autoHideScrollbar:false,
		scrollButtons:{
			enable:true
		},
		theme:"dark-thin"
	});
	
	$('#use_rule').hide();
	$('#reg').hide();
	regFrom.doint();
	
	$('#reg_rule_btn').click(function(){
		$('#login').hide();
		$('#reg').show();
		$('.def_txt').hide();
		$('#reg_form').slideUp("slow");
		$('#use_rule').slideDown("slow");
	});
	
	$('#reg').click(function(){
		$('#login').show();
		$('#reg').hide();
		$('#reg_form').slideDown("slow");
		$('#use_rule').slideUp("slow");
		$('.def_txt').slideDown();
	});
	
	$('#submit_btn').click(function(){
        if($.trim($('#uEmail').val()) == ''){
        	alert(regFrom.PH_LANG.RightEmail);
        	$('#uEmail').focus();
        }else if($.trim($('#password').val()) == ''){
        	alert(regFrom.PH_LANG.RightPwd);
        	$('#password').focus();
        }else if($.trim($('#uName').val()) == ''){
        	alert(regFrom.PH_LANG.RightNickname);
        	$('#uName').focus();
        }else{
        	var uEmail = $('#uEmail').val();
        	var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/; 
        	var uName = $.trim($('#uName').val());
        	
        	if(reg.test(uEmail)){
        		$.post('/index/checkEmail.html',{uEmail:uEmail,uName:uName,rnd:Math.random()},
            			function(data){  
        					if(data.uEmail != -1){
        						alert(uEmail + '  ' + regFrom.PH_LANG.Registered);
        						$('#uEmail').val('').focus();
        					}else if(data.uName != -1){
        						alert(uName + '  ' + regFrom.PH_LANG.Registered);
        						$('#uName').val('').focus();
        					}else{
        						$('#regform').submit();
        					}
            			},'json'
            	);
        	}else{
        		alert(regFrom.PH_LANG.EmailForm);
        		$('#uEmail').val('').focus();
        	}
        }
    });
	
	$('#button_btn').click(function(){
		$('#uEmail').val('').focusout();
		$('#uName').val('').focusout();
		$('#password').val('').focusout();
	});	
};
TregFrom.prototype.doint = function(){
	$('#uEmail').val('');
	$('#uName').val('');
	$('#password').val('');
	
	$('#uEmail').focusout();
	$('#uName').focusout();
	$('#password').focusout();
	
	$('#uEmail').before(jQuery('<div class="def_txt">'+regFrom.PH_LANG.PhAccount+'</div>').click(function(){
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
	
	$('#password').before(jQuery('<div class="def_txt">'+regFrom.PH_LANG.PhPassword+'</div>').click(function(){
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
	
	$('#uName').before(jQuery('<div class="def_txt">'+regFrom.PH_LANG.PhNickname+'</div>').click(function(){
        $(this).hide();
        $('#uName').focus();
    }));
	
	$('#uName').focusin(function(){
        $(this).prevAll('.def_txt').hide();
	});
	$('#uName').focusout(function(){	
	    if($(this).val()==''){
	        $(this).prevAll('.def_txt').show();
	    }else{
	        $(this).prevAll('.def_txt').hide();
	    }
	});
};
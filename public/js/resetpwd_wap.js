function TresetFrom(){
    this.PH_LANG = ''; //语言包
};
TresetFrom.prototype.init = function(){
	resetFrom.doinit();
	
	$('#submit_btn').click(function(){
		if($.trim($('#oldPWD').val()) == '' && $('#pwd').val() == 1){
			alert(resetFrom.PH_LANG.OldPwd+resetFrom.PH_LANG.NotNull);
			$('#oldPWD').focus();
		}else if($.trim($('#newPWD').val()) == ''){
			alert(resetFrom.PH_LANG.NewPwd+resetFrom.PH_LANG.NotNull);
			$('#newPWD').focus();
		}else if($.trim($('#seNewPWD').val()) == ''){
			alert(resetFrom.PH_LANG.SeNewPwd+resetFrom.PH_LANG.NotNull);
			$('#seNewPWD').focus();
		}else{
			if($('#pwd').val() == 1){
				$.get('/account/checkPwd',{uPWD:$.trim($('#oldPWD').val()),UserID:$('#UserID').val(),rnd:Math.random()},function(data){
					if(data == 1){
						resetFrom.checkPwd();
					}else{
						alert(regFrom.PH_LANG.ComfirmOldPwd);
						$('#oldPWD').val('').focus();
					}
				});
			}else{
				resetFrom.checkPwd();
			}
		}
	});
	
	$('#button_btn').click(function(){
		$('#oldPWD').val('').focusout();
		$('#newPWD').val('').focusout();
		$('#seNewPWD').val('').focusout();
	});
};
TresetFrom.prototype.doinit = function(){
	$('#oldPWD').before(jQuery('<div class="def_txt">'+resetFrom.PH_LANG.OldPwd+'</div>').click(function(){
        $(this).hide();
        $('#oldPWD').focus();
    }));
	
	$('#oldPWD').focusin(function(){
        $(this).prevAll('.def_txt').hide();
	});
	$('#oldPWD').focusout(function(){
	    if($(this).val()==''){
	        $(this).prevAll('.def_txt').show();
	    }else{		
	        $(this).prevAll('.def_txt').hide();
	    }
	});
	
	$('#newPWD').before(jQuery('<div class="def_txt">'+resetFrom.PH_LANG.NewPwd+'</div>').click(function(){
        $(this).hide();
        $('#newPWD').focus();
    }));
	
	$('#newPWD').focusin(function(){
        $(this).prevAll('.def_txt').hide();
	});
	$('#newPWD').focusout(function(){
	    if($(this).val()==''){
	        $(this).prevAll('.def_txt').show();
	    }else{		
	        $(this).prevAll('.def_txt').hide();
	    }
	});
	
	$('#seNewPWD').before(jQuery('<div class="def_txt">'+resetFrom.PH_LANG.SeNewPwd+'</div>').click(function(){
        $(this).hide();
        $('#seNewPWD').focus();
    }));
	
	$('#seNewPWD').focusin(function(){
        $(this).prevAll('.def_txt').hide();
	});
	$('#seNewPWD').focusout(function(){
	    if($(this).val()==''){
	        $(this).prevAll('.def_txt').show();
	    }else{		
	        $(this).prevAll('.def_txt').hide();
	    }
	});
};
TresetFrom.prototype.checkPwd = function(){
	if($.trim($('#newPWD').val()) != $.trim($('#seNewPWD').val())){
		alert(resetFrom.PH_LANG.ComfirmNewPwd);
		$('#newPWD').val('').focus();
		$('#seNewPWD').val('');
	}else if($.trim($('#newPWD').val()) == $.trim($('#oldPWD').val())){
		alert(resetFrom.PH_LANG.ComfirmOtherPwd);
		$('#newPWD').val('').focus();
		$('#seNewPWD').val('');
	}else{
		$('#reset').submit();
	}
};
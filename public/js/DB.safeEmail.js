
//@author wbqing405@sina.com

function TsafeEmail(){
	this.JS_LANG = '';
};
TsafeEmail.prototype.init = function(){
	$('#submit_btn').click(function(){
		safeEmail.subClick();
	});
};
TsafeEmail.prototype.subClick = function(){
	$.get('/main/doSafeEmail', {safeEmail:$('#safeEmail').val(),rnd:Math.random()}, function(data){
		if(data == -1){
			Boxy.alert(
					safeEmail.JS_LANG.Ex_ValidEmail, 
	    			function(){
	    				$('#safeEmail').val('').focus();
	    			}, 
	    			{title: safeEmail.JS_LANG.RemindMsg }
	    	);
		}else if(data == -2){
			Boxy.alert(
					safeEmail.JS_LANG.Ex_ValueEmailExist, 
	    			function(){
	    				$('#safeEmail').val('').focus();
	    			}, 
	    			{title: safeEmail.JS_LANG.RemindMsg }
	    	);
		}else{
			Boxy.confirm(
					safeEmail.JS_LANG.Ex_ValueActivedEmail_B + '  ' + $('#safeEmail').val() + '  ' + safeEmail.JS_LANG.Ex_ValueActivedEmail_A, 
	    			function(){
						window.parent.location = '/main/index-u_safe';
	    			}, 
	    			{title: safeEmail.JS_LANG.RemindMsg }
	    	);
		}
	});
};
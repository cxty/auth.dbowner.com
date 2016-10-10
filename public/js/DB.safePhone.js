
//@author wbqing405@sina.com

function TsafePhone(){
	this.JS_LANG = '';
};
TsafePhone.prototype.init = function(){
	$('#generateAuthCode').click(function(data){
		safePhone.gerateClick();
	});
	$('#submit_btn').click(function(){
		safePhone.subClick();
	});
};
TsafePhone.prototype.gerateClick = function(){
	$.get('/main/gerateAuthCode',{rnd:Math.random()},function(data){
		alert(data);
	});
};
TsafePhone.prototype.subClick = function(){
	$.get('/main/doSafePhone', {safePhone:$('#safePhone').val(),uAuthPhone:$('#uAuthPhone').val(),rnd:Math.random()}, function(data){
		if(data == -1){
			Boxy.alert(
					safePhone.JS_LANG.Ex_ValidPhone, 
	    			function(){
	    				$('#safePhone').val('').focus();
	    			}, 
	    			{title: safePhone.JS_LANG.RemindMsg }
	    	);
		}else if(data == -2){
			Boxy.alert(
					safePhone.JS_LANG.Ex_ValuePhoneExist, 
	    			function(){
	    				$('#safePhone').val('').focus();
	    			}, 
	    			{title: safePhone.JS_LANG.RemindMsg }
	    	);
		}else if(data == -3){
			Boxy.alert(
					safePhone.JS_LANG.Ex_ValuePhoneCode, 
	    			function(){
	    				$('#uAuthPhone').val('').focus();
	    			}, 
	    			{title: safePhone.JS_LANG.RemindMsg }
	    	);
		}else{
			window.parent.location = '/main/index-u_safe';
		}
	});
};

//@author wbqing405@sina.com

function TsafeRealName(){
	this.JS_LANG = '';
};
TsafeRealName.prototype.init = function(){
	$('#submit_btn').click(function(){
		safeRealName.subClick();
	});
};
TsafeRealName.prototype.subClick = function(){
	$.get('/main/doSaveRealName', {safeRealName:$('#safeRealName').val(),saveAuthType:$('#saveAuthType').val(),safeAuthNum:$('#safeAuthNum').val(),rnd:Math.random()}, function(data){
		if(data == -1){
			Boxy.alert(
					safeRealName.JS_LANG.Ex_ValidIDCardNum, 
	    			function(){
	    				$('#safeAuthNum').val('').focus();
	    			}, 
	    			{title: safeRealName.JS_LANG.RemindMsg }
	    	);
		}else{
			//window.parent.userSafe.closeFancybox(); 
			window.parent.location = '/main/index-u_safe';
		}
	});
};
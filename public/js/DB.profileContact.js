
//author:wbqing405@sina.com

function TprofileContact(){
	this.JS_LANG = '';
};
TprofileContact.prototype.init = function(){
	$('#submit_btn').click(function(){
		profileContact.subClick();
	});
};
TprofileContact.prototype.subClick = function(){
	$.get('/main/saveContact', {uComeFrom:$('#uComeFrom').val(), uEmail:$('#uEmail').val(), rnd:Math.random()}, function(data){
		if(data == -1){
			Boxy.alert(
					profileContact.JS_LANG.Ex_NotEmptyComefrom, 
		   			function(){
						$('#uComeFrom').val('').focus();
		   			}, 
		   			{title: profileContact.JS_LANG.RemindMsg }
				);
		}else if(data == -2){
			Boxy.alert(
					profileContact.JS_LANG.Ex_NotEmptyEmail, 
		   			function(){
						$('#uEmail').val('').focus();
		   			}, 
		   			{title: profileContact.JS_LANG.RemindMsg }
				);
		}else if(data == -3){
			Boxy.alert(
					profileContact.JS_LANG.Ex_SystemUserTimeout, 
		   			function(){

		   			}, 
		   			{title: profileContact.JS_LANG.RemindMsg }
				);
		}else if(data == -4){
			Boxy.alert(
					profileContact.JS_LANG.Ex_ValidEmail, 
		   			function(){
						$('#uEmail').val('').focus();
		   			}, 
		   			{title: profileContact.JS_LANG.RemindMsg }
				);	
		}else if(data == -5){
			Boxy.alert(
					profileContact.JS_LANG.Ex_ExsitEmail, 
		   			function(){
						$('#uEmail').val('').focus();
		   			}, 
		   			{title: profileContact.JS_LANG.RemindMsg }
				);
		}else{
			parent.location.reload();
		}	
	});
};
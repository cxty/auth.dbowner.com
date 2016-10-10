
//author:wbqing405@sina.com

function TprofileWork(){
	this.JS_LANG = '';
};
TprofileWork.prototype.init = function(){
	$('#submit_btn').click(function(){
		profileWork.subClick();
	});
};
TprofileWork.prototype.subClick = function(){
	$.post('/main/saveWorkInfo', {
			AutoID : $('#AutoID').val(),
			wCompanyName : $ ('#wCompanyName').val(), 
			wDepartment: $('#wDepartment').val(),
			wStartYear : $('#wStartYear').val(),
			wEndYear : $('#wEndYear').val(),
			wState : $('#wState').val(),
			wProvice : $('#wProvice').val(),
			wCity : $('#wCity').val(),
			rnd : Math.random()
		}, 
		function(data){
			switch( parseInt(data) ){
				case -1:
					Boxy.alert(
							profileWork.JS_LANG.Ex_SystemUserTimeout, 
				   			function(){
								
				   			}, 
				   			{title: profileWork.JS_LANG.RemindMsg }
						);
					break;
				case -2:
					Boxy.alert(
							profileWork.JS_LANG.Ex_NotEmptyCompanyName, 
				   			function(){
								$('#uComeFrom').val('').focus();
				   			}, 
				   			{title: profileWork.JS_LANG.RemindMsg }
						);
					break;
				case -3:
					Boxy.alert(
							profileWork.JS_LANG.Ex_ExsitValue, 
				   			function(){
								$('#uComeFrom').val('').focus();
				   			}, 
				   			{title: profileWork.JS_LANG.RemindMsg }
						);
					break;
				default:
					parent.location.reload();
					break;
			}
	});
};
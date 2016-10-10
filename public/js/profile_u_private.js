
function TUprivate(){
	this.JS_LANG = '';
};
TUprivate.prototype.init = function(){
	/*
	$(function(){
		$('li select').change(function(){
			var index = $('li select').index(this);
			var tValue = $('select').eq(index).val();

			$.get('/main/updatePrivate/',{type:index,tValue:tValue,rnd:Math.random()},function(data){
				
			});
		});
	});
	*/
	var contactSocial = $('#contactSocial input');
	contactSocial.change(function(){
		var index = contactSocial.index(this);

		Uprivate.checkSocialEmail(index);
	});
};
TUprivate.prototype.checkSocialEmail = function(index){
	var contactSocial = $('#contactSocial input');
	var tValue = contactSocial.eq(index).val();	
	var attr = contactSocial.eq(index).attr('id');
	
	var rules = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;//验证Mail的正则表达式,^[a-zA-Z0-9_-]:开头必须为字母,下划线,数字,
	
	if(rules.test(tValue)){
		Boxy.confirm( Uprivate.JS_LANG.ConfirmEmail,
				function(){
					$.get('/main/saveSocailEmail',{id:attr,tValue:tValue,rnd:Math.random()},function(data){
						$('#contactSocial_'+attr).text(tValue);
					});
				},
				{title: Uprivate.JS_LANG.RemindMsg }
		);
	}else{
		Boxy.alert(
   			 Uprivate.JS_LANG.EffectiveEmail, 
   			function(){
   				contactSocial.eq(index).val('').focus();
   			}, 
   			{title: Uprivate.JS_LANG.RemindMsg }
		);
	}
};
TUprivate.prototype.unBinding = function(partner){
	Boxy.confirm( Uprivate.JS_LANG.ConfirmLoginNoMore,
			function(){
				$.get('/main/unBinding',{partner:partner,rnd:Math.random()},function(data){
					window.location.reload();
				});
			},
			{title: Uprivate.JS_LANG.RemindMsg }
	);
};
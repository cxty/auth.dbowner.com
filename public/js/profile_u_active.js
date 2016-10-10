
function Tactive(){
	this.JS_LANG = '';
	this.furl = '';
};
Tactive.prototype.init = function(){
	$(".tiptip_plus").tipTip({maxWidth: "auto", edgeOffset: 10});
	
	$('.del_img').hide();
	$('.user_applist_plusinfo').hide();
	
	var appContObj = ".user_applist_content";
	$(appContObj).hover(
		function(){
			var index = $(this).index(appContObj);
			$('#plusinfo_'+index).show();
			$('#del_'+index).show();
		},
		function(){
			var index = $(this).index(appContObj);
			$('#plusinfo_'+index).hide();
			$('#del_'+index).hide();
		}
	);
};
Tactive.prototype.getInviteCode = function(client_id,count,user_id){
	$.fancybox({
        type: 'iframe',
        href: active.furl+'?client_id='+client_id+'&count='+count+'&user_id='+user_id,
        scrolling: 'no',
        width: 760,
        height: 480,
        autoScale: false,
        centerOnScroll: true,
        hideOnOverlayClick: false,
        onClosed: function(){
            //location = location;
        }
    });
};
Tactive.prototype.cancelApp = function(AppID){
	Boxy.confirm( active.JS_LANG.ConfirmDelAppID,
			function(){
				$.get('/main/delOauthInfoLog',{AppID:AppID,rnd:Math.random()},function(data){
					if(data == -1){
						Boxy.alert( active.JS_LANG.Ex_Relogin,
								function(){},
								{title: active.JS_LANG.Remind ,modal:true,unloadOnHide:true}
								);	
					}else{
						$('#now_'+AppID).remove();
					}
				});
			},
			{title: active.JS_LANG.RemindMsg }
	);
};

function Tckeditor(){
	this.JS_LANG = ''; //语言包
};
Tckeditor.prototype.init = function(){
	var sendObj = $('#msgForm input').eq(0);
	sendObj.before(jQuery('<div class="msg_txt">'+this.JS_LANG.MultityUser+'</div>').click(function(){
        $(this).hide();
        sendObj.focus();
    }));
	
	$(function(){
		if(sendObj.val() != ''){
			sendObj.prevAll('.msg_txt').hide();
		}
	});
    
	sendObj.focusin(function(){
            $(this).prevAll('.msg_txt').hide();
    });
	sendObj.focusout(function(){
        if($(this).val()=='')
        {
            $(this).prevAll('.msg_txt').show();
        }else{
            $(this).prevAll('.msg_txt').hide();
        }
    }); 
};
Tckeditor.prototype.edit = function(){
	KindEditor.ready(function(K) {
		keditor = K.create('textarea[name="uContent"]', {
			themeType : 'simple',
			filterMode : true,
			resizeType : 1,
			allowFileManager : true,
			items : [
			//			'source','|','fontname', 'fontsize', '|', 'forecolor', 'bold', 'italic', 'underline',
			//			'removeformat','|','preview','image','fullscreen'
					]
		});
	});	
	prettyPrint();
};
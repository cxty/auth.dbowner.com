function TcheckInCodeBox(){
	this.JS_LANG = '';
	this.furl = '';
	this.host = '';
	this.recall = '';
	this.ShowBox = null;
};
TcheckInCodeBox.prototype.init = function(){
	checkInCodeBox.showFancybox();
};
TcheckInCodeBox.prototype.showFancybox = function(){
	$.fancybox({
	    type: 'iframe',
	    href: checkInCodeBox.furl,
	    scrolling: 'no',
	    width: 500,
	    height: 250,
	    modal : true,
	    onClosed: function(){
	    	location = checkInCodeBox.recall;
	    } 
	});
	
	checkInCodeBox.doloop();
};
TcheckInCodeBox.prototype.doloop = function(){
	$.get(checkInCodeBox.host + '/index/checkInviCode',{'ident':'plus_inviteCode'},function(data){
		if ( data == 1 ) {
			checkInCodeBox.close();
		}
		setTimeout("checkInCodeBox.doloop()", 3000 );
	});
};
TcheckInCodeBox.prototype.close = function(){
	$.fancybox.close();
	//window.location.reload();
	location = checkInCodeBox.recall;
};

function Tactivate(){
	this.JS_LANG = '';
};
Tactivate.prototype.init = function(){
	 $('#do_login').click(function(e){
		window.location='/index/login';
	 });
	 
	 activate.urlTurn();
};
Tactivate.prototype.urlTurn = function(){
	if($('#urlTurn').val() != ''){
		 activate.autoTure();
	 }
};
var theTime = 6;
Tactivate.prototype.autoTure = function(){	
	theTime=theTime-1;
	$('#time_out').text(this.JS_LANG.ActivateSystem + theTime + this.JS_LANG.ActivateTurn);
	if(theTime>1){
		setTimeout("activate.autoTure();",1000);
	}else{
		window.location= $('#urlTurn').val();
	}	
};
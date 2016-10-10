
function TchangePass () {
	this.jdata = '';
	
	this.isClick = true; //避免重复点击
}
TchangePass.prototype.init = function () {
	this.initView();
	
	document.onkeydown = function(e){ 
	    var ev = document.all ? window.event : e;
	     
	    if(ev.keyCode == 13 && changePass.isClick == true ) {
	    	changePass.btnClick();
	     }
	};
	
	$('#sub_btn').click(function(){
		changePass.btnClick();
	});
};
TchangePass.prototype.initView = function () {
	//旧密码上层提示信息
    $('#oPwd').val('').before(jQuery('<div class="def_txt">' + this.jdata.Please_Input_oPwd + '</div>').click(function(){
        $(this).hide();
        $('#oPwd').focus();
    }));
    $('#oPwd').focusin(function(){
        $(this).prev('.def_txt').hide();
	});
	$('#oPwd').focusout(function(){
	    if($(this).val()=='')
	    {
	        $(this).prev('.def_txt').show();
	    }else{
	        $(this).prev('.def_txt').hide();
	    }
	});
	
	//新密码上层提示信息
    $('#nPwd').val('').before(jQuery('<div class="def_txt">' + this.jdata.Please_Input_nPwd + '</div>').click(function(){
        $(this).hide();
        $('#nPwd').focus();
    }));
    $('#nPwd').focusin(function(){
        $(this).prev('.def_txt').hide();
	});
	$('#nPwd').focusout(function(){
	    if($(this).val()=='')
	    {
	        $(this).prev('.def_txt').show();
	    }else{
	        $(this).prev('.def_txt').hide();
	    }
	});
	
	//确认新密码上层提示信息
    $('#aPwd').val('').before(jQuery('<div class="def_txt">' + this.jdata.Please_Input_aPwd + '</div>').click(function(){
        $(this).hide();
        $('#aPwd').focus();
    }));
    $('#aPwd').focusin(function(){
        $(this).prev('.def_txt').hide();
	});
	$('#aPwd').focusout(function(){
	    if($(this).val()=='')
	    {
	        $(this).prev('.def_txt').show();
	    }else{
	        $(this).prev('.def_txt').hide();
	    }
	});
};
TchangePass.prototype.btnClick = function () {
	this.isClick = false;
	
	if ( $('#oPwd').val() == '' ) {
		$('#oPwd').focus();
		
		alert(this.jdata.Please_Input_oPwd);
		
		this.isClick=true;
		
		return;
	}
	
	if ( $('#nPwd').val() == '' ) {
		$('#nPwd').focus();
		
		alert(this.jdata.Please_Input_nPwd);
		
		this.isClick=true;
		
		return;
	}
	
	if ( $('#aPwd').val() == '' ) {
		$('#aPwd').focus();
		
		alert(this.jdata.Please_Input_aPwd);
		
		this.isClick=true;
		
		return;
	}
	
	if ( $('#nPwd').val() != $('#aPwd').val() ) {
		$('#nPwd').val('').focus();
		$('#aPwd').val('').prev('.def_txt').show();
		
		alert(this.jdata.Error_Pwd_Difference);
		
		this.isClick=true;
		
		return;
	}
		
	this.isClick=true;

	$('#login_form').submit();
};

var changePass = new TchangePass();
$(document).ready(function(){
	changePass.jdata = jdata;
	changePass.init();
});
$(window).unload(function(){
	changePass = null;
});
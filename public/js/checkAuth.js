
function TcheckAuth(){
	this.sData = '';
};
TcheckAuth.prototype.init = function(){
	//初始化界面
	if ( this.sData.width ) {
		$('.container_box').css({'width': this.sData.width + 'px'});
		this.rule_width = this.sData.width - 60;
	}
	if ( this.sData.showLogo === true ) {
		$('.container_box').css({'margin-top':'20px'});
	}
	
	$(function(){
		$('#submit_btn').click(function(){
			var ulimit="";
			$('#checkbox input[type="checkbox"]:checkbox:checked').each(function() {
		        ulimit=ulimit+"|"+$(this).val();
		    });

			$('#limit').val(ulimit);
			$('#type').val('auth');

			$('#loginform').submit();
		});
		
		$('#button_btn').click(function(){
			$('#type').val('cancel');

			$('#loginform').submit();
		});
	});
	
	if ( $('#checkbox li').length > 4 ) {
		$('#clickMore').parent().css('display','');
		$('#clickMore').click(function(){
			checkAuth.clickMore();
		});
	}
};
TcheckAuth.prototype.clickMore = function() {
	if ( $('#clickMore span').attr('class') == 'hand_show' ) {
		$('#clickMore span').attr('class','hand_hide');
		$('#checkbox li').css('display','');
	} else {
		$('#clickMore span').attr('class','hand_show');
		for ( var i=4; i<$('#checkbox li').length; i++ ) {
			$('#checkbox li').eq(i).css('display','none');
		}
	}
};
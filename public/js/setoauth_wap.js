function TresetFrom(){
    this.PH_LANG = ''; //语言包
};
TresetFrom.prototype.init = function(){
	$('#submit_btn').click(function(){
		var ulimit="";
		$('#set_oauth ul input[type="checkbox"]:checkbox:checked').each(function() {
	        ulimit=ulimit+"|"+$(this).val();
	    });
		$('#ulimit').val(ulimit);
		$('#setform').submit();
	});
};
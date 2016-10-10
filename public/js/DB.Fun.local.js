/**
 * wbqing405@sina.com
 * 
 * 城市选择
 */
function Tlocal(){
	this.cJson = '';
	this.proviceName = '';
	this.cityName = '';
}
Tlocal.prototype.init = function(){
	$('#' + this.proviceName ).change(function(){
		local.provice();
	});
};
Tlocal.prototype.provice = function(){
	var html = '';
	for(var i=0;i<local.cJson.length;i++){
		if(local.cJson[i]['code'] == $('#' + this.proviceName ).val()){			
			for(var j=0;j<local.cJson[i]['city'].length;j++){
				html += '<option value="' + local.cJson[i]['city'][j]['code'] + '">' + local.cJson[i]['city'][j]['name'] + '</option>';
			}
		}
	}

	$('#' + this.cityName ).html(html);
};
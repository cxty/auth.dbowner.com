 
function TuplodPortrait(){ 
	this.nx = '';
	this.fileType = 'pic';
	this.fileNum = 'one';
	this.action = 'upload.php?action=ori';
	this.name = 'userfile';	
	this.root = '/';
	
	
}

TuplodPortrait.prototype.init=function(){
	var root = this.root;
	$("#showPic").css({'display':'none'});
	 $(document).ready(function(){
     var button = $('#upload_button'), interval;
     //alert('main/upload.html');return;
    // var fileType = this.fileType,fileNum = "one"; 
     new AjaxUpload(button,{
         action: 'upload',
         name: 'userfile',
         onSubmit : function(file, ext){
             if(this.fileType == "pic")
             {
                 if (ext && /^(jpg|png|jpeg|gif)$/.test(ext)){
                     this.setData({
                         'info': '文件类型为图片'
                     });
                 } else {
                     button.text('非图片类型文件，重新上传');
                     return false;               
                }
             }
             $("#pic_path").val(file);            
            button.text('文件上传中');
             
            if(this.fileNum == 'one')
                 this.disable();
                 interval = window.setInterval(function(){
					 var text = button.text();
					 if (text.length < 14){
						 button.text(text + '.');                    
					} else {
						 button.text('文件上传中');             
					}
				 }, 200);
         },
         onComplete: function(file, response){
             $("#cropbox").attr("src",root+response);
             $("#preview").attr("src",root+response);
             $("#showPic").css({'display':''});
			 $('#bigImage').val(root+response);
             $("#ima").show();
             doJcrop();       
             window.clearInterval(interval);         
             this.enable();
         }
     });
  
 });
}

function doJcrop(){
	jQuery('#cropbox').Jcrop({
		onChange: showPreview,
		onSelect: showPreview,
		onSelect: updateCoords,
		aspectRatio: 1
	});
}

function updateCoords(c)
{
	$('#xlen').val(c.x);
	$('#ylen').val(c.y);
	$('#wlen').val(c.w);
	$('#hlen').val(c.h);

};

// Our simple event handler, called from onChange and onSelect
// event handlers, as per the Jcrop invocation above
function showPreview(coords)
{
	if (parseInt(coords.w) > 0)
	{
		var rx = 100 / coords.w;
		var ry = 100 / coords.h;

		jQuery('#preview').css({
			width: Math.round(rx * 500) + 'px',
			height: Math.round(ry * 370) + 'px',
			marginLeft: '-' + Math.round(rx * coords.x) + 'px',
			marginTop: '-' + Math.round(ry * coords.y) + 'px'
		});
	}
}

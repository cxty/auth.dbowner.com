
function TEditor(){

}
TEditor.prototype.init = function(){
	
};
var keditor;
TEditor.prototype.editor = function(){	
	KindEditor.ready(function(K) {
		keditor = K.create('textarea[id="editor"]', {
			filterMode : true,
			resizeType : 1,
			allowFileManager : true,
			allowPreviewEmoticons : false,
			allowImageUpload : false,
			filterMode : true ,
			items : [
				'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
				'removeformat', '|', 'emoticons'],
			afterChange : function() {
				K('#word_count').html(this.count('text'));
			}
		});
	});	
};
TEditor.prototype.show = function(){	
	prettyPrint();
};
TEditor.prototype.editortext = function(){	
	return keditor.text();
};
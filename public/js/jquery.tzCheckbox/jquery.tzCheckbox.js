(function($){
	$.fn.tzCheckbox = function(options){

		//Ĭ����ON��OFF:

		options = $.extend({
			labels : ['ON','OFF']
		},options);

		return this.each(function(){
			var originalCheckBox = $(this),
				labels = [];

			// ���data-on��data-off����:
			if(originalCheckBox.data('on')){
				labels[0] = originalCheckBox.data('on');
				labels[1] = originalCheckBox.data('off');
			}
			else labels = options.labels;

			// ��ɿ���HTML����
			var checkBox = $('<span>',{
				className	: 'tzCheckBox '+(this.checked?'checked':''),
				html:	'<span class="tzCBContent">'+labels[this.checked?0:1]+
						'</span><span class="tzCBPart"></span>'
			});

			//���뿪�ش��룬������ԭʼ��checkbox 
			checkBox.insertAfter(originalCheckBox.hide());

			checkBox.click(function(){
				if(!originalCheckBox.attr('disabled')){
					checkBox.toggleClass('checked');
	
					var isChecked = checkBox.hasClass('checked');
	
					// ��¼���ر仯��ԭʼ��checkbox��
					originalCheckBox.attr('checked',isChecked);
					checkBox.find('.tzCBContent').html(labels[isChecked?0:1]);
				}
			});

			// ����ԭʼcheckbox�ĸı䣬������ģ����
			originalCheckBox.bind('change',function(){
				checkBox.click();
			});
		});
	};
})(jQuery);
$(function(){
	"use strict";
	$('select:not([data-list]):not(.hide)').select2();
	$('select:not([data-list])').on('create',function(){
		var $this = $(this);
		if(!$this.hasClass('hide') && !$this.data('list')) {
			var format = $this.data('format');
			if(format) {
				$this.select2({
					formatResult: format,
					formatSelection: format,
					escapeMarkup: function(m) { return m; }
				});
			} else {
				$this.select2();
			}
		}
	});
});
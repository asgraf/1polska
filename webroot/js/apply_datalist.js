$(function(){
	"use strict";
	if(!Modernizr.input.list || (parseInt($.browser.version) > 400)) {
		$('input[list]').relevantDropdown();
	} else if(window.chrome) {
		$('input[list][pattern]').relevantDropdown();
	}
});
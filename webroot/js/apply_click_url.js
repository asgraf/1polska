$(function(){
	"use strict";
	$('[data-click-url]').click(function(){
		window.location = $(this).data('clickUrl');
	})
});
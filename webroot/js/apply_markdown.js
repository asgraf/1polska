$(function(){
	"use strict";

	function getYoutubeId(url){
		if(!url) return false;
		var match = url.match(/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/);
		return match&&match[7].length==11?match[7]:false;
	}

	if(typeof markdown == 'object') {
		var mobile_browser = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
		$('[data-markdown]').each(function(idx,el){
			var $el = $(el);
			var content = $el.text();
			content = markdown.toHTML(content);
			$el.html(content);
			if(!mobile_browser) {
				$el.find('a').each(function(aidx,a){
					var youtube_id = getYoutubeId(a.href);
					if(youtube_id) {
						$(a).replaceWith('<iframe width="640" height="360" src="https://www.youtube.com/embed/'+youtube_id+'" frameborder="0" allowfullscreen></iframe>');
					}
				});
			}
		});
		$('[data-markdown-editor]').markdown({
			additionalButtons: [
				[{
					name: 'groupCustom',
					data: [{
						name: 'Youtube',
						title: 'Youtube',
						icon: 'youtube-icon',
						callback: function(e){
							var chunk, cursor, selected = e.getSelection(),youtube_id,youtube_data;
							youtube_id = getYoutubeId(selected.text);
							if(!youtube_id) {
								youtube_id = getYoutubeId(prompt('Podaj link do filmiku na youtube'));
							}
							if(!youtube_id) {
								alert('To nie jest prawidłowy link do filmiku na youtube');
								return;
							}
							youtube_data = $.ajax({url:'https://gdata.youtube.com/feeds/api/videos/'+youtube_id+'?v=2&alt=json',async:false,dataType:'json'}).responseJSON;
							var alt = youtube_data.entry.title.$t;
							chunk = '[!['+alt+'](https://img.youtube.com/vi/'+youtube_id+'/0.jpg)]( https://youtube.com/watch?v='+youtube_id+' "'+alt+'")';

							e.replaceSelection(chunk);
							cursor = selected.start;
							e.setSelection(cursor,cursor+chunk.length);
						}
					}]
				}]
			]
		});
	}
});
$(function(){
	$('.postulate_vote_overflow').click(function(e){
		e.preventDefault();
		var $this = $(this), $form = $this.prev();
		$.post($form.attr('action')+'.json',$form.serialize()).success(function(){
			var $voteblock = $this.parents('.vote_block');
			$voteblock.hide('slow', function(){
				$voteblock.remove();
				if($('.postulate_vote_overflow').length<=12) {
					document.location.reload();
				}
			});
		})
	}).removeAttr('onclick');
});

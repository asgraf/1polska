<?php
/**
 * @var $tagged
 * @var \App\View\AppView $this
 */
?>
<section class="tags index">
	<h2>Tagi:</h2>
	<?php
	echo $this->TagCloud->display(
		$tagged,
		[
			'shuffle' => false,
			'named' => 'slug',
			'url' => ['action' => 'view']
		],
		['class' => 'tag-cloud']
	);
	?>
</section>
<?php
/**
 * @var \App\View\AppView $this
 */
if ($this->getRequest()->is('ajax')) return;
?>
<div class="row">
	<div class="col-md-12 pagination-wrapper">
		<?= $this->element('index/download_formats', compact('indexFormats')); ?>
		<?php
		if ($this->Paginator->hasPage(2)) {
			echo $this->Paginator->numbers([
				'first' => '<<',
				'prev' => '<',
				'next' => '>',
				'last' => '>>',
			]);
		}
		?>
	</div>
</div>

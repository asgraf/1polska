<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="newsletters form">
	<?= $this->Form->create($newsletter); ?>
	<fieldset id="form" class="form_page">
		<legend><?= __('Newsletter'); ?></legend>
		<?php
		echo $this->Form->control('subject', ['label' => __('Subject')]);
		echo $this->Form->control('txt', ['label' => __('Content')]);
		?>
	</fieldset>
	<?= $this->Form->button(__('Submit'), ['class' => 'submit']); ?>
	<?= $this->Form->end(); ?>
</div>
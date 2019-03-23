<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Postulate $postulate
 */
?>
<div class="postulates form">
	<?= $this->Form->create($postulate); ?>
	<fieldset id="form" class="form_page">
		<legend><?= __('Postulat'); ?></legend>
		<?php
		echo $this->Form->control('name', ['label' => __('Tytuł'), 'maxlength' => 50]);
		echo $this->Form->control('description', ['label' => __('Krótki opis'), 'maxlength' => 160]);
		echo $this->Form->control('content', ['label' => __('Treść'), 'data-markdown-editor']);
		echo $this->Form->control('status', ['options' => ['not_active' => 'niewidoczny', 'active' => 'widoczny', 'deleted' => 'zablokowany']]);
		echo $this->Form->control('Tag', ['label' => 'Tagi']);
		echo $this->element('recaptcha');
		?>
	</fieldset>
	<?= $this->Form->button(__('Wyślij'), ['class' => 'submit', 'data-icon' => 'ok']); ?>
	<?= $this->Form->end(); ?>
</div>
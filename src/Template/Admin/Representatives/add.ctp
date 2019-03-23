<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Representative $representative
 */
$this->set('title', 'Nowy Reprezentant');
?>

<div class="representatives form">
	<?= $this->Form->create($representative',array('role'=>'form','type'=>'file)); ?>
	<fieldset id="form" class="form_page">
		<legend><?= __('Reprezentant'); ?></legend>
		<?php
		echo $this->Form->control('firstname', ['', 'label' => __('Imię'), 'maxlength' => 20]);
		echo $this->Form->control('lastname', ['label' => __('Nazwisko'), 'maxlength' => 20]);
		echo $this->Form->control('photo', ['type' => 'file', 'label' => __('Zdjęcie')]);
		echo $this->Form->control('description', ['label' => __('Krótki opis'), 'maxlength' => 160]);
		echo $this->Form->control('content', ['label' => __('Pełny opis'), 'data-markdown-editor']);
		echo $this->Form->control('status', ['options' => ['not_active' => 'niewidoczny', 'active' => 'widoczny', 'deleted' => 'zablokowany']]);
		echo $this->Form->control('Tag', ['label' => 'Tagi']);
		echo $this->element('recaptcha');
		?>
	</fieldset>
	<?= $this->Form->button(__('Wyślij'), ['class' => 'btn btn-default submit', 'data-icon' => 'ok']); ?>
	<?= $this->Form->end(); ?>
</div>

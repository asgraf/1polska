<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Representative $representative
 */
?>

<div class="representatives form">
	<?= $this->Form->create($representative',array('role'=>'form','type'=>'file)); ?>
	<fieldset id="form" class="form_page">
		<legend><?= __('Reprezentant'); ?></legend>
		<?php
		if ($representative . connected_user->id) {
			echo $this->Html->link('Ten reprezentant już jest powiązany z kontem użytkownika', ['controller' => 'Users', 'action' => 'edit', 'id' => $representative . connected_user->id]);
	} else {
			echo 'Ten reprezentant nie jest powiązany z żadnym kontem użytkownika';
		}
	echo $this->Form->control('firstname', ['', 'label' => __('Imię'), 'maxlength' => 20]);
	echo $this->Form->control('lastname', ['label' => __('Nazwisko'), 'maxlength' => 20]);
	echo $this->Form->control('photo', ['type' => 'file', 'label' => __('Zdjęcie')]);
	echo $this->Form->control('description', ['label' => __('Krótki opis'), 'maxlength' => 160]);
	echo $this->Form->control('content', ['label' => __('Treść'), 'data-markdown-editor']);
	echo $this->Form->control('status', ['options' => ['not_active' => 'niewidoczny', 'active' => 'widoczny', 'deleted' => 'zablokowany']]);
	echo $this->Form->control('Tag', ['label' => 'Tagi']);
	echo $this->element('recaptcha');
	?>
	</fieldset>
	<?php
	echo $this->Form->button(__('Submit'), ['class' => 'submit', 'data-icon' => 'ok']);
	echo $this->Form->end();
	?>
</div>
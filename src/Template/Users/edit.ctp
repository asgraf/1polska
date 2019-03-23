<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->set('title', 'Edycja profilu');
?>
<div class="users form">
	<?= $this->Form->create($user); ?>
	<fieldset id="form" class="form_page">
		<legend><?= $this->get('title'); ?></legend>
		<?php
		echo $this->Form->control('name', ['label' => 'Nazwa Użytkownika']);
		echo $this->Form->control('new_password', ['type' => 'password', 'label' => 'Nowe hasło', 'placeholder' => 'Pozostaw puste jeśli nie chcesz zmieniać hasła']);
		echo $this->element('recaptcha');
		?>
	</fieldset>
	<?php
	echo $this->Form->button('Zapisz');
	echo $this->Html->link('Usuń moje konto', ['action' => 'delete'], ['class' => 'pull-right text-danger']);
	echo $this->Form->end();
	?>
</div>
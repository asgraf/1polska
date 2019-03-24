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
	echo $this->Html->tag(
		'span',
		implode(' ', [
			$this->Html->link(
				'Eksport moich danych',
				[
					'action' => 'export',
					'_ext' => 'json'
				],
				[
					'title' => 'Za pomocą tego przycisku możesz pobrać wszelkie dane powiązane z twoim kontem jakimi tylko dysponujemy',
					'class' => 'btn btn-info',
					'download'=>'Moje wyeksportowane dane z serwisu 1Polska.json'
				]),
			$this->Html->link(
				'Usuń moje konto',
				[
					'action' => 'delete'
				],
				[
					'class' => 'btn btn-danger'
				]
			),
		]),
		['class' => 'pull-right']
	);
	echo $this->Form->end();
	?>
</div>
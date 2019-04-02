<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->set('title', 'Rejestracja');
echo $this->Form->create($user, ['horizontal' => true]);
echo $this->Html->tag('h3', 'Rejestracja');
echo $this->Form->control('name', ['required', 'label' => 'Nazwa użytkownika']);
echo $this->Form->control('email', ['required']);
echo $this->Form->control('password', ['label' => 'Hasło', 'required', 'pattern' => '.{8,}', 'title' => 'Hasło nie może być krótszę niż 8 znaków']);
echo $this->element('recaptcha');
echo $this->element('Register/terms_and_policy');
?>
<div class="form-group">
	<div class="col-md-offset-2 col-sm-offset-6 col-md-8 col-sm-6">
		<?= $this->Form->button('Zarejestruj', ['class' => 'btn btn-success']); ?>
	</div>
</div>
<?= $this->Form->end(); ?>

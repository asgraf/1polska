<?php
/**
 * @var $user
 * @var $status
 * @var $representatives
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users form">
	<?= $this->Form->create($user); ?>
	<fieldset id="form" class="form_page">
		<legend><?= __d('user', 'User'); ?></legend>
		<?php
		echo $this->Form->control('username', ['label' => __d('user', 'Username')]);
		echo $this->Form->control('password', ['required' => false, 'value' => false, 'label' => __d('user', 'New Password')]);
		echo $this->Form->control('email', ['required' => false, 'value' => false, 'label' => __d('user', 'New Email')]);
		echo $this->Form->control('status', ['options' => $status, 'label' => __d('user', 'Status')]);
		echo $this->Form->control('representative_id', ['empty' => '-==Brak==-', 'options' => $representatives, 'label' => 'Reprezentant', 'value' => Hash::get($user, 'ConnectedRepresentative.id')]);
		echo $this->element('recaptcha');
		?>
	</fieldset>
	<?= $this->Form->button(__d('user', 'Submit'), ['class' => 'submit', 'data-icon' => 'ok']); ?>
	<?= $this->Form->end(); ?>
</div>
<nav class="actions">
	<ul>
		<li><?= $this->Html->link(__d('user', 'List Users'), ['action' => 'index'], ['class' => 'index', 'data-role' => 'button', 'data-inline' => 'true', 'data-icon' => 'list-ol']); ?></li>
	</ul>
</nav>
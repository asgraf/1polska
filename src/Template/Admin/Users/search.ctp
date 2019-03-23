<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<?php unset($this->getRequest()['_Token']); ?>
<div class="users form">
	<?= $this->Form->create($user',array('action'=>'index','type'=>'get)); ?>
	<?php $this->Form->controlDefaults(['required' => 'false']); ?>
	<fieldset id="form" class="form_page">
		<legend><?= __d('user', 'User'); ?></legend>
		<?php
		echo $this->Form->control('username', ['label' => __d('user', 'Username')]);
		echo $this->Form->control('status', ['multiple' => 'multiple', 'options' => $status, 'label' => __d('user', 'Status')]);
		?>
	</fieldset>
	<?= $this->Form->submit(__d('cake', 'Search'), ['class' => 'submit', 'data-role' => 'button', 'data-icon' => 'search']); ?>
	<?= $this->Form->end(); ?>
</div>
<nav class="actions">
	<ul>
		<li><?= $this->Html->link(__d('user', 'List Users'), ['action' => 'index'], ['class' => 'index', 'data-role' => 'button', 'data-inline' => 'true', 'data-icon' => 'list-ol']); ?></li>
	</ul>
</nav>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users view">
	<h2><?= __d('user', 'User'); ?></h2>
	<dl>
		<dt><?= __d('user', 'Username'); ?></dt>
		<dd class="username">
			<?= $this->Html->link($user['username'], $user['User'], ['admin' => false, 'action' => 'view', 'slug' => slug($user['username']), 'emailhash' => $user['emailhash']], ['escape' => false]); ?>
		</dd>
		<dt><?= __d('user', 'Email'); ?></dt>
		<dd class="email"><?= $user['email']; ?></dd>
		<dt><?= __d('user', 'Admin'); ?></dt>
		<dd class="admin"><?= $user['admin']; ?></dd>
		<dt><?= __d('user', 'Status'); ?></dt>
		<dd class="status"><?= $user['status']; ?></dd>
		<dt><?= __d('user', 'Created'); ?></dt>
		<dd class="created"><?= $user['created']; ?></dd>
		<dt><?= __d('user', 'Modified'); ?></dt>
		<dd class="modified"><?= $user['modified']; ?></dd>
	</dl>
</div>
<nav class="actions">
	<?= $this->Html->link(__d('user', 'Edit User'), ['action' => 'edit', 'slug' => slug($user['username']), 'id' => $user['id'], '?' => array_merge($this->getRequest()->query, ['redirect' => $_SERVER['REQUEST_URI']])], ['class' => 'edit', 'data-role' => 'button', 'data-inline' => 'true', 'data-icon' => 'edit']); ?>
	<?= $this->Form->postLink(__d('user', 'Delete User'), ['action' => 'delete', 'slug' => slug($user['username']), 'id' => $user['id']], ['class' => 'delete', 'data-role' => 'button', 'data-inline' => 'true', 'data-icon' => 'trash'], __d('user', 'Are you sure you want to delete {0} user?', $user['username'])); ?>
	<?= $this->Html->link(__d('user', 'List Users'), ['action' => 'index'], ['class' => 'index', 'data-role' => 'button', 'data-inline' => 'true', 'data-icon' => 'list-ol']); ?>
	<?= $this->Html->link(__d('user', 'New User'), ['action' => 'add'], ['class' => 'add', 'data-role' => 'button', 'data-inline' => 'true', 'data-icon' => 'plus']); ?>
</nav>

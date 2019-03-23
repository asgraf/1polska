<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>


	<section class="users index">
<?php if (empty($users)) {
	echo __('No items to show');
} else {
	foreach ($users as $user) {
		?>
		<table class="table well">
			<thead>
			<tr>
				<th class="id"><?= __d('user', 'ID'); ?></th>
				<?php if (!$this->getRequest()->query('username')) { ?>
					<th class="username"><?= __d('user', 'Username'); ?></th>
				<?php } ?>
				<th class="email"><?= __('Email'); ?></th>
				<?php if (!$this->getRequest()->query('status')) { ?>
					<th class="status"><?= __d('user', 'Status'); ?></th>
				<?php } ?>
				<?php if (!$this->getRequest()->query('created')) { ?>
					<th class="created"><?= __d('user', 'Created'); ?></th>
				<?php } ?>
				<?php if (!$this->getRequest()->query('modified')) { ?>
					<th class="modified"><?= __d('user', 'Modified'); ?></th>
				<?php } ?>
				<th class="actions"><?= __d('user', 'Actions'); ?></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="id"><?= $user['id']; ?></td>
				<?php if (!$this->getRequest()->query('username')) { ?>
					<td class="username">
						<?php
						$avatar = $this->Html->image(getAvatarUrl($user['User'], 20), ['width' => 20, 'height' => 20]);
						?>

						<?= $avatar . $this->Html->link($user['username'], ['admin' => false, 'action' => 'view', 'slug' => slug($user['username']), 'emailhash' => $user['emailhash']], ['escape' => false]); ?>
					</td>
					<td class="email"><?= $this->Html->link($user['email'], 'mailto:' . $user['email']); ?></td>
				<?php } ?>
				<?php if (!$this->getRequest()->query('status')) { ?>
					<td class="status"><?= $user['status']; ?></td>
				<?php } ?>
				<?php if (!$this->getRequest()->query('created')) { ?>
					<td class="created"><?= $user['created']; ?></td>
				<?php } ?>
				<?php if (!$this->getRequest()->query('modified')) { ?>
					<td class="modified"><?= $user['modified']; ?></td>
				<?php } ?>
				<td class="actions">
					<?= $this->Html->link(__('Edit'), ['action' => 'edit', 'slug' => slug($user['username']), 'id' => $user['id'], '?' => array_merge($this->getRequest()->query, ['redirect' => $_SERVER['REQUEST_URI']])], ['class' => 'edit', 'data-role' => 'button', 'data-inline' => 'true', 'data-iconpos' => 'notext', 'data-icon' => 'edit']); ?>
					<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', 'slug' => slug($user['username']), 'id' => $user['id'], '?' => ['redirect' => $_SERVER['REQUEST_URI']]], ['class' => 'delete', 'data-role' => 'button', 'data-inline' => 'true', 'data-iconpos' => 'notext', 'data-icon' => 'trash'], __d('user', 'Are you sure you want to delete {0} user?', $user['username'])); ?>
				</td>
			</tr>
			<?php foreach ($user['ChildUser'] as $childuser) { ?>
				<tr>
					<td class="id"><?= $childuser['id']; ?></td>
					<?php if (!$this->getRequest()->query('username')) { ?>
						<td class="username">
							<?php
							$avatar = $this->Html->image(getAvatarUrl($childuser, 20), ['width' => 20, 'height' => 20]);
							?>
							<?= $avatar . $this->Html->link($childuser['username'], ['admin' => false, 'action' => 'view', 'slug' => slug($childuser['username']), 'emailhash' => $childuser['emailhash']], ['escape' => false]); ?>
						</td>
						<td class="email"><?= $this->Html->link($childuser['email'], 'mailto:' . $childuser['email']); ?></td>
					<?php } ?>
					<?php if (!$this->getRequest()->query('status')) { ?>
						<td class="status"><?= $childuser['status']; ?></td>
					<?php } ?>
					<?php if (!$this->getRequest()->query('created')) { ?>
						<td class="created"><?= $childuser['created']; ?></td>
					<?php } ?>
					<?php if (!$this->getRequest()->query('modified')) { ?>
						<td class="modified"><?= $childuser['modified']; ?></td>
					<?php } ?>
					<td class="actions">
						<?= $this->Html->link(__('Edit'), ['action' => 'edit', 'slug' => slug($childuser['username']), 'id' => $childuser['id'], '?' => array_merge($this->getRequest()->query, ['redirect' => $_SERVER['REQUEST_URI']])], ['class' => 'edit', 'data-role' => 'button', 'data-inline' => 'true', 'data-iconpos' => 'notext', 'data-icon' => 'edit']); ?>
						<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', 'slug' => slug($childuser['username']), 'id' => $childuser['id'], '?' => ['redirect' => $_SERVER['REQUEST_URI']]], ['class' => 'delete', 'data-role' => 'button', 'data-inline' => 'true', 'data-iconpos' => 'notext', 'data-icon' => 'trash'], __d('user', 'Are you sure you want to delete {0} user?', $childuser['username'])); ?>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	<?php } ?>
	<?php if (empty($all)) echo $this->element('Asgraf.paginator'); ?>
	</section>
<?php } ?>
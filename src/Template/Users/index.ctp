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
	echo '<h2>' . __d('user', 'Users') . '</h2>';
	?>
	<table>
		<tr>
			<?php if (!$this->getRequest()->query('username')) { ?>
				<th class="username"><?= !empty($all) ? __d('user', 'Username') : $this->Paginator->sort('username', __d('user', 'Username')); ?></th>
			<?php } ?>
			<?php if (!$this->getRequest()->query('status')) { ?>
				<th class="status"><?= !empty($all) ? __d('user', 'Status') : $this->Paginator->sort('status', __d('user', 'Status')); ?></th>
			<?php } ?>
			<?php if (!$this->getRequest()->query('created')) { ?>
				<th class="created"><?= !empty($all) ? __d('user', 'Created') : $this->Paginator->sort('created', __d('user', 'Created')); ?></th>
			<?php } ?>
			<th class="actions"><?= __d('user', 'Actions'); ?></th>
		</tr>
		<?php foreach ($users as $user) { ?>
			<tr>
				<?php
				if (!$this->getRequest()->query('username')) {
					$avatar = $this->Gravatar->image(
						$user->email,
						['width' => 20, 'height' => 20]
					);
					?>
					<td class="username">
						<?= $this->Html->link($avatar . ' ' . h($user['username']), ['action' => 'view', 'slug' => slug($user['username']), 'emailhash' => $user['emailhash']], ['escape' => false]); ?>
					</td>
				<?php } ?>
				<?php if (!$this->getRequest()->query('status')) { ?>
					<td class="status"><?= $user['status']; ?></td>
				<?php } ?>
				<?php if (!$this->getRequest()->query('created')) { ?>
					<td class="created"><?= $user['created']; ?></td>
				<?php } ?>
				<td class="actions">
					<?= $this->Html->link(__('View'), ['action' => 'view', 'slug' => slug($user['username']), 'emailhash' => $user['emailhash']], ['class' => 'view', 'data-role' => 'button', 'data-inline' => 'true', 'data-iconpos' => 'notext', 'data-icon' => 'eye-open']); ?>
				</td>
			</tr>
		<?php } ?>
	</table>
	<?php if (empty($all)) echo $this->element('Asgraf.paginator'); ?>
</section>
<?php } ?>

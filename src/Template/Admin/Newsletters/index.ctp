<?php
/**
 * @var \App\View\AppView $this
 */
?>
<section class="newsletters index">
	<?php if (empty($newsletters)) {
		echo __('No items to show');
	} else {
	?>    <?= '<h2>' . __('Newsletters') . '</h2>'; ?>
	<table class="table">
		<thead>
		<tr>
			<th class="subject"><?= !empty($all) ? __('Subject') : $this->Paginator->sort('subject', __('Tytuł')); ?></th>
			<th class="status"><?= !empty($all) ? __('Status') : $this->Paginator->sort('status', __('Status')); ?></th>
			<th><?= __('Info'); ?></th>
			<th class="created"><?= !empty($all) ? __('Created') : $this->Paginator->sort('created', __('Utworzono')); ?></th>
			<th class="modified"><?= !empty($all) ? __('Modified') : $this->Paginator->sort('modified', __('Zmodyfikowano')); ?></th>
			<th class="actions"><?= __('Actions'); ?></th>
		</tr>
		</thead>
		<tbody><?php foreach ($newsletters as $newsletter) {
			if ($newsletter['status'] == 'queted' && $newsletter['sent_num'] > 0) {
				$newsletter['status'] = 'sending';
			}
			?>
			<tr<?= $newsletter['status'] == 'unsent' ? ' data-default-url="' . $this->Url->build(['action' => 'edit', 'id' => $newsletter['id']]) . '"' : '' ?>>
				<td class="subject"><?= $newsletter['subject']; ?></td>
				<td class="status"><?= $newsletter['status']; ?></td>
				<td><?php
					switch ($newsletter['status']) {
						case 'sent':
							break;
						case 'sending':
							echo '<meter title="' . $newsletter['sent_num'] . '/' . $newsletter['sent_max'] . '" value="' . $newsletter['sent_num'] . '" max="' . $newsletter['sent_max'] . '">' . $newsletter['sent_num'] . '/' . $newsletter['sent_max'] . '</meter>';
							break;
						default:
							if ($newsletter['sent_max'] > 0) {
								echo __('{0} przypisanych użytkowników', $newsletter['sent_max']);
							} else {
								echo __('Brak przypisanych użytkowników!!!');
								echo ' ';
								echo $this->Form->postLink('Przypisz wszystkich', ['action' => 'mass_assign_all_users', 'id' => $newsletter['id'], 'slug' => slug($newsletter['subject'])]);
							}

					}
					?></td>
				<td class="created"><?= $newsletter['created']; ?></td>
				<td class="modified"><?= $newsletter['modified']; ?></td>
				<td class="actions">
					<?php
					echo $this->Html->link(__('View'), ['action' => 'view', 'slug' => slug($newsletter['subject']), 'id' => $newsletter['id']], ['class' => 'view btn btn-default']) . ' ';
					echo $this->Form->postLink(__('Wyślij mi maila testowego'), ['action' => 'send_test_email', 'slug' => slug($newsletter['subject']), 'id' => $newsletter['id']], ['class' => 'view btn btn-default']) . ' ';
					if ($newsletter['sent_max'] > 0 && in_array($newsletter['status'], ['unsent', 'draft'])) {
						echo $this->Form->postLink(__('Roześij Newslettera'), ['action' => 'edit', 'slug' => slug($newsletter['subject']), 'id' => $newsletter['id']], ['data' => ['status' => 'queted'], 'class' => 'email btn btn-success']) . ' ';
					}
					if (in_array($newsletter['status'], ['unsent', 'draft'])) {
						echo $this->Html->link(__('Edit'), ['action' => 'edit', 'slug' => slug($newsletter['subject']), 'id' => $newsletter['id']], ['class' => 'edit btn btn-default']) . ' ';
					}
					echo $this->Form->postLink(__('Delete'), ['action' => 'delete', 'slug' => slug($newsletter['subject']), 'id' => $newsletter['id']], ['class' => 'delete btn btn-danger'], __('Are you sure you want to delete {0} newsletter?', $newsletter['id']));
					?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php if (empty($all))
		echo $this->element('Asgraf.paginator'); ?>
</section>
<?php } ?>
<nav class="actions">
	<?= $this->Html->link(__('New Newsletter'), ['action' => 'add', '?' => $this->getRequest()->query], ['class' => 'btn btn-default']); ?>
</nav>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Postulate[]|\Cake\Collection\CollectionInterface $postulates
 */
$this->set('title', 'Postulaty');
?>


<section class="postulates index">
	<?php if (empty($postulates)) {
		echo __('No items to show');
	} else {
	?>
	<table class="table">
		<thead>
		<tr>
			<?php if (!$this->getRequest()->query('name')) { ?>
				<th class="name"><?= __('Tytuł') ?></th>
			<?php } ?>
			<?php if (!$this->getRequest()->query('status')) { ?>
				<th class="status"><?= !empty($all) ? __('Status') : $this->Paginator->sort('status', __('Status')); ?></th>
			<?php } ?>
			<?php if (!$this->getRequest()->query('created')) { ?>
				<th class="created"><?= !empty($all) ? __('Utworzono') : $this->Paginator->sort('created', __('Utworzono')); ?></th>
			<?php } ?>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($postulates as $postulate) { ?>
			<tr>
				<?php if (!$this->getRequest()->query('name')) { ?>
					<td class="name">
						<?= $this->Html->link($postulate['name'], ['action' => 'edit', '_entity' => $postulate], ['class' => 'edit']); ?>
						<p><?= $postulate['description']; ?></p>
					</td>
				<?php } ?>
				<?php if (!$this->getRequest()->query('status')) { ?>
					<td class="status"><?= $postulate['status']; ?></td>
				<?php } ?>
				<?php if (!$this->getRequest()->query('created')) { ?>
					<td class="created"><?= $postulate['created']; ?></td>
				<?php } ?>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php if (empty($all)) echo $this->element('Asgraf.paginator'); ?>
</section>
<?php } ?>
<nav class="actions">
	<?= $this->Html->link(__('Dodaj'), ['action' => 'add', '?' => $this->getRequest()->query], ['class' => 'add btn btn-default', 'data-role' => 'button', 'data-inline' => 'true', 'data-icon' => 'plus']); ?>
</nav>

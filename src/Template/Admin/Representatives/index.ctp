<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Representative[]|\Cake\Collection\CollectionInterface $representatives
 */
$this->set('title', 'Reprezentanci');
$width = $height = 60;
?>


<section class="representatives index">
	<?php if (empty($representatives)) {
		echo __('No items to show');
	} else {
	?>
	<table class="table">
		<thead>
		<tr>
			<th class="name"><?php
				echo !empty($all) ? __('Imię') : $this->Paginator->sort('firstname', __('Imię'));
				echo '&nbsp;';
				echo !empty($all) ? __('Nazwisko') : $this->Paginator->sort('lastname', __('Nazwisko'));
				?></th>
			<th class="description"><?= __('Krótki opis'); ?></th>
			<?php if (!$this->getRequest()->query('status')) { ?>
				<th class="status"><?= !empty($all) ? __('Status') : $this->Paginator->sort('status', __('Status')); ?></th>
			<?php } ?>

			<?php if (!$this->getRequest()->query('created')) { ?>
				<th class="created"><?= !empty($all) ? __('Dodano') : $this->Paginator->sort('created', __('Dodano')); ?></th>
			<?php } ?>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($representatives as $representative) { ?>
			<tr>
				<td class="name">
					<?= $this->Html->link($representative['name'], ['action' => 'edit', '_entity' => $representative], ['class' => 'edit']); ?>
				</td>
				<td class="description"><?= $representative['description']; ?></td>
				<?php if (!$this->getRequest()->query('status')) { ?>
					<td class="status"><?= $representative['status']; ?></td>
				<?php } ?>
				<?php if (!$this->getRequest()->query('created')) { ?>
					<td class="created"><?= $representative['created']; ?></td>
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

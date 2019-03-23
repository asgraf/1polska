<?php
/**
 * @var \App\View\AppView $this
 */
?>


<section class="tags index">
	<?php if (empty($tags)) {
		echo __('No items to show');
	} else {
	echo '<h2>' . __d('default', 'Tags') . '</h2>';
	?>
	<table class="table">
		<thead>
		<tr>
			<?php if (!$this->getRequest()->query('name')) { ?>
				<th class="name"><?= !empty($all) ? __d('default', 'Tag') : $this->Paginator->sort('name', __d('default', 'Tag')); ?></th>
			<?php } ?>
			<th class="actions"><?= __d('default', 'Actions'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($tags as $tag) { ?>
			<tr>
				<?php if (!$this->getRequest()->query('name')) { ?>
					<td class="name">
						<?= $this->Html->link($tag['name'], ['action' => 'edit', '_entity' => $tag]); ?>
					</td>
				<?php } ?>
				<td class="actions">
					<?= $this->Html->link(__('Edit'), ['action' => 'edit', '_entity' => $tag, '?' => array_merge($this->getRequest()->query, ['redirect' => $_SERVER['REQUEST_URI']])], ['class' => 'edit', 'data-role' => 'button', 'data-inline' => 'true', 'data-iconpos' => 'notext', 'data-icon' => 'edit']); ?>
					<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', '_entity' => $tag, '?' => ['redirect' => $_SERVER['REQUEST_URI']]], ['class' => 'delete', 'data-role' => 'button', 'data-inline' => 'true', 'data-iconpos' => 'notext', 'data-icon' => 'trash'], __d('default', 'Are you sure you want to delete {0} tag?', $tag['name'])); ?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php if (empty($all)) echo $this->element('Asgraf.paginator'); ?>
</section>
<?php } ?>
<nav class="actions">
	<?= $this->Html->link(__d('default', 'Nowy Tag'), ['action' => 'add', '?' => array_merge($this->getRequest()->query, ['redirect' => $_SERVER['REQUEST_URI']])], ['class' => 'add', 'data-role' => 'button', 'data-inline' => 'true', 'data-icon' => 'plus']); ?>
</nav>

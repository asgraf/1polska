<?php
/**
 * @var \App\View\AppView $this
 */
?>

<div class="tags form">
	<?= $this->Form->create('Tag'); ?>
	<fieldset id="form" class="form_page">
		<legend><?= __('Tag'); ?></legend>
		<?php
		echo $this->Form->control('parent_id', ['label' => __('Parent')]);
		echo $this->Form->control('name', ['label' => __('Name')]);
		?>
	</fieldset>
	<?= $this->Form->button(__('Submit'), ['class' => 'submit', 'data-icon' => 'ok']); ?>
	<?= $this->Form->end(); ?>
</div>
<nav class="actions">
	<ul>
		<li><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', 'slug' => slug($this->Form->value('Tag.name')), 'id' => $this->Form->value('Tag.id')], ['class' => 'delete', 'data-role' => 'button', 'data-inline' => 'true', 'data-icon' => 'trash'], __('Are you sure you want to delete {0} tag?', $this->Form->value('Tag.name'))); ?></li>
		<li><?= $this->Html->link(__('List Tags'), ['action' => 'index'], ['class' => 'index', 'data-role' => 'button', 'data-inline' => 'true', 'data-icon' => 'list-ol']); ?></li>
	</ul>
</nav>

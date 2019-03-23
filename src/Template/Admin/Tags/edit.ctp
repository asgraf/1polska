<?php
/**
 * @var \App\View\AppView $this
 */
?>

<div class="tags form">
	<?= $this->Form->create($tag); ?>
	<fieldset id="form" class="form_page">
		<legend><?= __d('default', 'Tag'); ?></legend>
		<?php
		echo $this->Form->control('name', ['label' => __d('default', 'Name')]);
		?>
	</fieldset>
	<?php
	echo $this->Form->control('Postulate', ['label' => __d('default', 'Postulate')]);
	echo $this->Form->control('Representative', ['label' => __d('default', 'Representative')]);
	?>
	<?= $this->Form->button(__d('default', 'Submit'), ['class' => 'submit', 'data-icon' => 'ok']); ?>
	<?= $this->Form->end(); ?>
</div>
<nav class="actions">
	<ul>
		<li><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', 'slug' => slug($tag . name')),'id'=>$this->Form->value('Tag->id),['class' => 'delete', 'data-role' => 'button', 'data-inline' => 'true', 'data-icon' => 'trash'],__d('default', 'Are you sure you want to delete {0} tag?', $this->Form->value('Tag.name'))]; ?></li>
		<li><?= $this->Html->link(__d('default', 'List Tags'), ['action' => 'index'], ['class' => 'index', 'data-role' => 'button', 'data-inline' => 'true', 'data-icon' => 'list-ol']); ?></li>
	</ul>
</nav>

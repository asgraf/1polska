<?= $this->fetch('before_index'); ?>

<div class="<?= $this->CrudView->getCssClasses(); ?>">

	<?php
	if (!$this->exists('search') && !empty($this->get('searchInputs'))) {
		$this->start('search');
		echo $this->Panel->create('Szukaj', ['type' => 'primary', 'class' => 'panel-search']);
		echo $this->element('search');
		echo $this->Panel->end();
		$this->end();
	}
	?>
	<?= $this->fetch('search'); ?>
	<?= $this->fetch('afterSearch'); ?>

	<?= $this->element('index/bulk_actions/form_start', compact('bulkActions')); ?>

	<?= $this->element('index/finder_scopes', compact('indexFinderScopes')); ?>
	<?php
	$_data = [
		'fields' => $fields,
		'actions' => $actions,
		'bulkActions' => $bulkActions,
		'primaryKey' => $primaryKey,
		'singularVar' => $singularVar,
		'viewVar' => $viewVar,
		$viewVar => ${$viewVar},
	];
	echo $this->Panel->create($this->get('title'), ['type' => 'primary', 'class' => 'panel-index']);
	switch ($indexType) {
		case 'table':
			echo $this->element('index/table', $_data);
			break;
		case 'gallery':
			echo $this->element('index/gallery', $_data);
			break;
		case 'blog':
			echo $this->element('index/blog', $_data);
			break;
		default:
			echo $this->element($indexType, $_data);
			break;
	}
	echo $this->Panel->end();
	?>


	<?= $this->element('index/pagination'); ?>
	<?= $this->element('action-header') ?>
	<?= $this->element('index/bulk_actions/form_end', compact('bulkActions')); ?>
</div>

<?= $this->fetch('after_index'); ?>

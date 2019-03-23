<?php
/**
 * @var \App\View\AppView $this
 * @var $viewVar
 * @var $actions
 */
if (!$this->exists('actions')) {
	$this->start('actions');
	echo $this->element('actions', [
		'actions' => $actions['table'],
		'singularVar' => false,
	]);
	// to make sure ${$viewVar} is a single entity, not a collection
	if (${$viewVar} instanceof \Cake\Datasource\EntityInterface && !${$viewVar}->isNew()) {
		echo $this->element('actions', [
			'actions' => $actions['entity'],
			'singularVar' => ${$viewVar},
		]);
	}
	$this->end();
}
?>
<div class="actions-wrapper">
	<?= $this->fetch('actions'); ?>
</div>

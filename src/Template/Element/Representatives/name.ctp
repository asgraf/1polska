<?php
/**
 * @var \App\View\AppView $this
 * @var array $options
 * @var \App\Model\Entity\Representative $context
 * @var $value
 * @var string $field
 */
if ($context->user_id) {
	echo $this->Html->icon('ok', [
		'title' => 'Ten reprezentant posiada aktywne konto użytkownika na 1polska.pl',
		'class' => 'text-success'
	]);
}
echo $this->Html->link(
	$context->full_name,
	['action' => 'view', '_entity' => $context],
	['class' => 'view visitable']
);
echo $this->Html->tag('p', $context->description);
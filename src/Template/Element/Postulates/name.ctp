<?php
/**
 * @var \App\View\AppView $this
 * @var array $options
 * @var \App\Model\Entity\Postulate $context
 * @var $value
 * @var string $field
 */
echo $this->Html->link(
	$context->name,
	['action' => 'view', '_entity' => $context],
	['class' => 'view visitable']
);
echo $this->Html->tag('p', h($context->description));
<?php
/**
 * @var \App\View\AppView $this
 * @var array $options
 * @var \App\Model\Entity\Representative $context
 * @var $value
 * @var string $field
 */
echo $this->Html->link(
	$this->Html->image(
		$context->getPhotoThumbUrl(60, 60, true),
		['class' => 'img-rounded', 'width' => 60, 'height' => 60]
	),
	['action' => 'view', '_entity' => $context],
	['escape' => false]
);
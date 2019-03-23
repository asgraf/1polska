<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Representative $representative
 */

$fields = $this->get('fields');
$fields['photo']['help'] = $this->Html->image($representative->getPhotoUrl());
$this->set('fields', $fields);

echo $this->element('form');

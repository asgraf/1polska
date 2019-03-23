<?php
/**
 * @var \App\View\AppView $this
 * @var array $fields
 */
if ($this->get('form_fields')) {
	echo $this->element($this->get('form_fields'));
} else {
	echo $this->Form->controls($fields, ['legend' => false]);
}
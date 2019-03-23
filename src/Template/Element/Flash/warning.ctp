<?php
/**
 * @var $message
 * @var $params
 * @var \App\View\AppView $this
 */
use Cake\Utility\Hash;

$templateDefaults = [
	'title' => 'Ostrzeżenie',
	'icon' => 'attention-circled'
];
$params['templateVars'] = Hash::get($params, 'templateVars', []) + $templateDefaults;
echo $this->Html->alert($message, 'warning', $params);
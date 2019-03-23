<?php
/**
 * @var $message
 * @var $params
 * @var \App\View\AppView $this
 */
use Cake\Utility\Hash;

$templateDefaults = [
	'title' => 'Sukces',
	'icon' => 'ok-circled'
];
$params['templateVars'] = Hash::get($params, 'templateVars', []) + $templateDefaults;
echo $this->Html->alert($message, 'success', $params);
<?php
/**
 * @var \App\View\AppView $this
 */
echo $this->Form->checkbox('privacy_policy',
	[
		'id' => 'privacy_policy_checkbox',
		'escape' => false,
		'required',
		'type' => 'checkbox',
		'value' => true,
	]
);
echo '&nbsp;';
echo 'Zapoznałem się i akceptuję ' .
	$this->Html->link(
		'politykę prywatności',
		[
			'controller' => 'Pages',
			'action' => 'display',
			'polityka_prywatnosci'
		]
	);
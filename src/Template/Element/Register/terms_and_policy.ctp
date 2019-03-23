<?php
/**
 * @var \App\View\AppView $this
 */
echo $this->Form->control(
	'terms_and_conditions',
	[
		'escape' => false,
		'required',
		'type' => 'checkbox',
		'value' => true,
		'label' => 'Zapoznałem się i akceptuję ' .
			$this->Html->link(
				'regulamin',
				[
					'controller' => 'Pages',
					'action' => 'display',
					'regulamin'
				]
			),
	]
);
echo $this->Form->control(
	'privacy_policy',
	[
		'id' => 'privacy_policy_checkbox',
		'escape' => false,
		'required',
		'type' => 'checkbox',
		'value' => true,
		'label' => 'Zapoznałem się i akceptuję ' .
			$this->Html->link(
				'politykę prywatności',
				[
					'controller' => 'Pages',
					'action' => 'display',
					'polityka_prywatnosci'
				]
			),
	]
);
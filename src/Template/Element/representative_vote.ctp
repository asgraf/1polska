<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Representative $representative
 */
$voteup_class = $votedown_class = '';
$div_title = 'Ten reprezentant jest Ci obojętny/neutralny. Brak Twojego głosu poparcia lub sprzeciwu.';
$value = 0;
if($representative->my_vote) {
	$value = $representative->my_vote->value;
}

$voteup_url = $votedown_url = [
	'controller' => 'Representatives',
	'action' => 'cancelvote',
	'id' => $representative->id
];

$voteup_title = $representative->upvotes . ' ' . $this->Html->image('thumb_up.png');
$votedown_title = $representative->downvotes . ' ' . $this->Html->image('thumb_down.png');

if ($value == 1) {
	$voteup_class = 'active';
	$div_title = 'Popierasz tego reprezentanta';
	$voteup_title = $representative->upvotes . ' ' . $this->Html->image('thumb_up2.png');
} else {
	$voteup_url['action'] = 'upvote';
}
if ($value == -1) {
	$votedown_class = 'active';
	$div_title = 'Nie popierasz tego reprezentanta';
	$votedown_title = $representative->downvotes . ' ' . $this->Html->image('thumb_down2.png');
} else {
	$votedown_url['action'] = 'downvote';
}

if (!$this->Identity->isLoggedIn()) {
	$div_title = 'Aby móc oddać głos najpierw musisz się zalogować';
	$votedown_url = $voteup_url = [
		'controller' => 'Users',
		'action' => 'login',
		'redirect' => $this->getRequest()->getRequestTarget()
	];
}
?>
<div title="<?= $div_title; ?>" class="representative_vote btn-group">
	<?php
	echo '<div class="btn btn-default btn-large default_cursor">' . $representative->vote_rate . '%</div>';
	echo $this->Form->postLink($voteup_title, $voteup_url, ['class' => 'btn btn-large btn-success ' . $voteup_class, 'escape' => false]);
	echo $this->Form->postLink($votedown_title, $votedown_url, ['class' => 'btn btn-large btn-danger ' . $votedown_class, 'escape' => false]);
	?>
</div>
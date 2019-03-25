<?php
/**
 * @var \App\Model\Entity\Postulate $postulate
 * @var \App\View\AppView $this
 */
$voteup_class = '';
$div_title = 'Ten postulat nie ma Twojego poparcia';
$value = 0;
if ($postulate->my_vote) {
	$value = $postulate->my_vote->value;
}

$voteup_url = [
	'controller' => 'Postulates',
	'action' => 'cancelvote',
	'id' => $postulate->id
];
if ($value == 1) {
	$voteup_class = 'active';
	$voteup_title = $this->Html->image('kratka_ptaszek.png') . 'Popieram';
	$div_title = 'Popierasz ten postulat';
} else {
	$voteup_url['action'] = 'upvote';
	$voteup_title = $this->Html->image('kratka_bez_ptaszka.png') . 'Popieram';
}

if (!$this->Identity->isLoggedIn()) {
	$div_title = 'Aby móc oddać głos najpierw musisz się zalogować';
	$voteup_url = [
		'controller' => 'Users',
		'action' => 'login',
		'redirect' => $this->getRequest()->getRequestTarget()
	];
}
?>
<div id="vote_widget_postulate_<?= $postulate->id ?>" title="<?= $div_title ?>"
     class="postulate_vote btn-group">
	<?php
	echo '<div class="btn btn-success default_cursor">' . $postulate->upvotes . '</div>';
	echo $this->Form->postLink(
		$voteup_title,
		$voteup_url,
		[
			'class' => 'btn btn-default ' . $voteup_class,
			'escape' => false
		]);
	?>
</div>
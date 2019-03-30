<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Constituency[]|\Cake\ORM\Query $constituencies
 */
echo '<ul class="breadcrumb">';
echo $this->Html->link(
	'Okręgi wyborcze',
	[
		'controller' => 'Constituencies',
		'action' => 'index',
	]
);
echo ':';
foreach ($constituencies as $constituency) {
	echo ' ';
	$url = [
		'controller' => 'Constituencies',
		'action' => 'view',
		'id' => $constituency->id
	];
	echo $this->Html->link($constituency->id, $url, ['title' => $constituency->display_name]);
}
echo '</ul>';
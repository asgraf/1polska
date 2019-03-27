<?php
/**
 * @var $description_for_layout
 * @var $representatives
 * @var $postulates
 * @var $users_count
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Postulate $postulate
 * @var \App\Model\Entity\Postulate[]|\Cake\Collection\CollectionInterface $postulates
 */
$this->set('title', 'Postulaty');
$this->Html->script('vote_overflow', ['block' => 'script'])
?>
<h1>Lista popieranych przez ciebie postulatów</h1>
<h4>Pozostaw na tej liście 12 postulatów które uważasz za najważniejsze, pozostałe usuń</h4>
<div class="row">
	<?php
	foreach ($postulates as $postulate) {
		$url = ['controller' => 'Postulates', 'action' => 'view', '_entity' => $postulate];
		echo '<div class="col-md-4 h210 vote_block">';
		echo '<div class="w140 truncate" data-click-url="' . $this->Url->build($url) . '">';
		echo '<h3>' . $this->Html->link($postulate['name'], $url) . '</h3>';
		echo h($postulate->description);
		echo '</div>';
		echo $this->Form->postLink('Usuń', ['controller' => 'Postulates', 'action' => 'cancelvote', 'id' => $postulate['id']], ['class' => 'btn btn-danger postulate_vote postulate_vote_overflow']);
		echo '</p>';
		echo '</div>';
	}
	?>
</div>

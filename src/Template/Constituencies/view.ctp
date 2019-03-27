<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Constituency $constituency
 */
?>
<section class="container">
	<h1><?= $constituency->display_name?></h1>
	<div class="row">
		<h2><?= $this->Html->link('Reprezentanci:', ['controller' => 'Representatives', 'action' => 'index']) ?></h2>
		<?php
		if (!$constituency->representatives) {
			echo 'Brak reprezentantów przypisanych do tego okręgu';
		}
		foreach ($constituency->representatives as $representative) {
			echo $this->element('representative_tile', ['representative' => $representative]);
		}
		?>
	</div>
	<div class="row">
		<h2><?= $this->Html->link('Postulaty:', ['controller' => 'Postulates', 'action' => 'index', 'constituency_id' => $constituency->id]) ?></h2>
		<?php
		if (!$constituency->postulates) {
			echo 'Brak postulatów przypisanych do tego okręgu';
		}
		foreach ($constituency->postulates as $postulate) {
			echo $this->element('postulate_tile', ['postulate' => $postulate]);
		}
		?>
	</div>
</section>
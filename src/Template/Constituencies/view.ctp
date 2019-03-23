<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Constituency $constituency
 */
$width = $height = 80;
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
			$url = ['controller' => 'Representatives', 'action' => 'view', '_entity' => $representative];
			echo '<div class="col-md-4 h210">';
			echo '<div class="max140 truncate" data-click-url="' . $this->Url->build($url) . '">';
			echo $this->Html->image(
				$representative->getPhotoThumbUrl($width, $height),
				['class' => 'pull-left img-circle', 'width' => $width, 'height' => $height]
			);
			echo '<h3>' . $this->Html->link($representative->full_name, $url) . '</h3>';
			echo $representative['description'];
			echo '</div>';
			echo '<p class="clearfix">';
			echo $this->element('representative_vote', ['representative' => $representative]) . '&nbsp;';
			echo '</p>';
			echo '</div>';
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
			$url = ['controller' => 'Postulates', 'action' => 'view', '_entity' => $postulate];
			echo '<div class="col-md-4 h210">';
			echo '<div class="w140 truncate" data-click-url="' . $this->Url->build($url) . '">';
			echo '<h3>' . $this->Html->link($postulate['name'], $url) . '</h3>';
			echo $postulate['description'];
			echo '</div>';
			echo '<p class="clearfix">';
			echo $this->element('postulate_vote', ['postulate' => $postulate]) . '&nbsp;';
			echo '</p>';
			echo '</div>';
		}
		?>
	</div>
</section>
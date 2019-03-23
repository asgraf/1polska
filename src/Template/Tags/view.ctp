<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Representative[]|\Cake\Collection\CollectionInterface $representatives
 * @var \App\Model\Entity\Postulate[]|\Cake\Collection\CollectionInterface $postulates
 */
?>
<div class="tags view">
	<h2><?= __d('default', 'Tag') . ' ' . h($tag['name']); ?></h2>
	<section class="container">
		<div class="row">
			<div class="col-md-12">
				<h3>Reprezentanci z tagiem <b><?= h($tag['name']) ?></b>:</h3>
				<ul style="-webkit-column-width: 300px;-moz-column-width: 300px;column-width: 300px;">
					<?php
					if ($representatives->isEmpty()) {
						echo '<li>Brak</li>';
					} else {
						foreach ($representatives as $representative) {
							$photo = $this->Html->image(
								$representative->getPhotoThumbUrl(16, 16, true),
								[
									'class' => 'pull-left img-circle',
									'width' => 16,
									'height' => 16
								]
							);
							echo $this->Html->listLink(
								$photo . '&nbsp;' . h($representative->full_name),
								[
									'controller' => 'Representatives',
									'action' => 'view',
									'_entity' => $representative
								],
								[
									'escape' => false
								]
							);
						}
					}
					?>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h3>Postulaty z tagiem <b><?= h($tag['name']) ?></b>:</h3>
				<ul style="-webkit-column-width: 300px;-moz-column-width: 300px;column-width: 300px;">
					<?php
					if ($postulates->isEmpty()) {
						echo '<li>Brak</li>';
					} else {
						foreach ($postulates as $postulate) {
							echo $this->Html->listLink(
								$postulate->name,
								[
									'controller' => 'Postulates',
									'action' => 'view',
									'_entity' => $postulate
								]
							);
						}
					}
					?>
				</ul>
			</div>
		</div>
	</section>
</div>


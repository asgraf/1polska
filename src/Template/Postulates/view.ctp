<?php
/**
 * @var $postulate
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Postulate $postulate
 * @var \App\Model\Entity\Representative[]|\Cake\ORM\Query $representatives
 */
$edit_time = strtotime($postulate['created']) + 15 * MINUTE;
$this->set('title', $postulate['name']);
if ($this->Identity->getId() == $postulate['user_id'] && $edit_time > time()) {
	echo $this->Html->link('Edycja', ['action' => 'edit', 'id' => $this->getRequest()->getParam('id'), 'slug' => $this->getRequest()->getParam('slug')], ['class' => 'btn btn-info']);
}
if ($this->Identity->get('admin')) {
	echo $this->Html->link('Edycja w panelu admina', ['admin' => true, 'action' => 'edit', 'id' => $this->getRequest()->getParam('id'), 'slug' => $this->getRequest()->getParam('slug')], ['class' => 'btn btn-info']);
}
?>

<div class="postulates view">
	<h1><?= h($postulate['name']); ?></h1>
	<?php
	echo '<p>' . $this->element('postulate_vote', ['postulate' => $postulate]) . '</p>';
	echo $this->element('social_buttons');
	?>
	<dl>
		<dt><?= __('Okręg wyborczy'); ?></dt>
		<dd class="constituency_id"><?php
			if ($postulate->constituency) {
				echo $this->Html->link(
					$postulate->constituency->display_name,
					[
						'controller' => 'Constituencies',
						'action' => 'view',
						'_entity' => $postulate->constituency,
					]);
			} else {
				echo 'Nie określono';
			} ?></dd>
		<dt><?= __('Krótki opis'); ?></dt>
		<dd class="description"><?= h($postulate->description); ?></dd>
		<dt><?= __('Pełny opis'); ?></dt>
		<dd class="content" data-markdown><?= h($postulate->content); ?></dd>
		<dt><?= __('Utworzono'); ?></dt>
		<dd class="created"><?= $postulate->created->diffForHumans(); ?></dd>
		<dt><?= __('Ostatnia modyfikacja'); ?></dt>
		<dd class="modified"><?= $postulate->modified->diffForHumans(); ?></dd>
		<dt><?= __('Tagi'); ?></dt>
		<dd><?php
			if (!$postulate->tags) {
				echo 'Brak tagów przypisanych do tego postulatu';
			} else {
				foreach ($postulate->tags as $tag) {
					echo $this->Html->link(
						'i:tag ' . h($tag->label),
						[
							'controller' => 'Tags',
							'action' => 'view',
							'_entity' => $tag
						],
						['class' => 'btn btn-info btn-xs', 'escape' => false]
					);
				}
			}
			?></dd>
<!--		<dt>--><?//= __('Głosy użytkowników'); ?><!--</dt>-->
<!--		<dd>--><?//= $this->cell('Votes', ['entity' => $postulate]) ?><!--</dd>-->
	</dl>
	<?php if (!$representatives->isEmpty()) { ?>
		<section class="container">
			<div class="row">
				<div class="col-md-12">
					<h3>Reprezentanci popierający ten postulat:</h3>
					<ul style="-webkit-column-width: 300px;-moz-column-width: 300px;column-width: 300px;">
						<?php
						foreach ($representatives as $representative) {
							echo $this->Html->listLink(
								$this->Html->image($representative->getPhotoThumbUrl(16, 16, true), ['width' => 16, 'height' => 16]) . h($representative->full_name),
								[
									'controller' => 'Representatives',
									'action' => 'view',
									'_entity' => $representative
								],
								['escape' => false]
							);
							echo '</a></li>';
						}
						?>
					</ul>
				</div>
			</div>
		</section>
	<?php } ?>
</div>
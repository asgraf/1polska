<?php
/**
 * @var $representative
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Representative $representative
 * @var \App\Model\Entity\Postulate $voted_postulate
 */
use Cake\Utility\Hash;

$this->set('title', $representative->full_name);
$connected_sign = Hash::get($representative, 'user.status') == 'active' ? '<span class="text-success glyphicon glyphicon-ok" aria-hidden="true" title="Ten reprezentant posiada aktywne konto użytkownika na 1polska.pl"></span> ' : '';
if (
	$representative->user_id &&
	$this->Identity->getId() == $representative->user_id
) {
	echo $this->Html->link(
		'Edycja',
		[
			'action' => 'edit',
			'_entity' => $representative
		],
		[
			'class' => 'btn btn-info'
		]
	);
}
if ($this->Identity->get('admin')) {
	echo $this->Html->link(
		'Edycja w panelu admina',
		[
			'prefix' => 'admin',
			'action' => 'edit',
			'_entity' => $representative
		],
		[
			'class' => 'btn btn-info'
		]
	);
}
?>

<div class="representatives view">
	<h1><?= $connected_sign . h($representative->full_name); ?></h1>
	<p class="representative_photo">
		<?php
		echo $this->Html->image(
			$representative->getPhotoUrl(),
			['class' => 'img-thumbnail']
		);
		if ($representative->photo['id']) {
			$this->Html->meta(
				[
					'property' => 'og:image',
					'content' => $this->Url->build($representative->getPhotoUrl(), true)
				],
				null,
				['inline' => false]
			);
		}
		?>
	</p>
	<?php
	echo '<p>' . $this->element('representative_vote', ['representative' => $representative]) . '</p>';
	echo $this->element('social_buttons');
	?>

	<dl>
		<dt><?= __('Okręg wyborczy'); ?></dt>
		<dd class="constituency_id"><?php
			if ($representative->constituency) {
				echo $this->Html->link(
					$representative->constituency->display_name,
					[
						'controller' => 'Constituencies',
						'action' => 'view',
						'_entity' => $representative->constituency,
					]);
			} else {
				echo 'Nie określono';
			} ?></dd>
		<dt><?= __('Krótki opis'); ?></dt>
		<dd class="description"><?= $representative->description; ?></dd>
		<dt><?= __('Pełny opis'); ?></dt>
		<dd class="content" data-markdown><?= $representative->content; ?></dd>
		<?php if (!$representative->user) { ?>
			<dt><?= __('Utworzono'); ?></dt>
			<dd class="created"><?= $representative->created->diffForHumans(); ?></dd>
			<dt><?= __('Ostatnia modyfikacja'); ?></dt>
			<dd class="modified"><?= $representative->modified->diffForHumans(); ?></dd>
		<?php } else { ?>
			<dt><?= __('Data rejestracji'); ?></dt>
			<dd class="registered"><?= $representative->user->created->diffForHumans(); ?></dd>
		<?php } ?>
		<dt><?= __('Tagi reprezentanta'); ?></dt>
		<dd><?php
			if (!$representative->tags) {
				echo 'Brak tagów przypisanych do tego reprezentanta';
			} else {
				foreach ($representative->tags as $tag) {
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
	</dl>
	<?php if ($representative->user) { ?>
		<section class="container">
			<div class="row">
				<div class="col-md-6">
					<h3>Reprezentanci, którym ufam:</h3>
					<ul>
						<?php
						$count = 0;
						if ($representative->user->voted_representatives) {
							foreach ($representative->user->voted_representatives as $voted_representative) {
								if ($voted_representative->get('_joinData')['value'] == 1) {
									echo $this->Html->listLink(
										$this->Html->image($voted_representative->getPhotoThumbUrl(16, 16, true), ['width' => 16, 'height' => 16]) . h($voted_representative->full_name),
										[
											'controller' => 'Representatives',
											'action' => 'view',
											'_entity' => $voted_representative
										],
										['escape' => false]
									);
									$count++;
								}
							}
						}

						if (!$count) {
							echo '<li>Brak</li>';
						}
						?>
					</ul>
				</div>
				<div class="col-md-6">
					<h3>Reprezentanci, którym nie ufam:</h3>
					<ul>
						<?php
						$count = 0;
						if ($representative->user->voted_representatives) {
							foreach ($representative->user->voted_representatives as $voted_representative) {
								if ($voted_representative->get('_joinData')['value'] == -1) {
									echo $this->Html->listLink(
										$this->Html->image($voted_representative->getPhotoThumbUrl(16, 16, true), ['width' => 16, 'height' => 16]) . h($voted_representative->full_name),
										[
											'controller' => 'Representatives',
											'action' => 'view',
											'_entity' => $voted_representative
										],
										['escape' => false]
									);
									$count++;
								}
							}
						}
						if (!$count) {
							echo '<li>Brak</li>';
						}
						?>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<h3>Postulaty, które popieram:</h3>
					<ul style="-webkit-column-width: 300px;-moz-column-width: 300px;column-width: 300px;">
						<?php
						$count = 0;
						if ($representative->user->voted_postulates) {
							foreach ($representative->user->voted_postulates as $voted_postulate) {
								if ($voted_postulate->get('_joinData')['value'] == 1) {
									echo $this->Html->listLink(
										$voted_postulate->name,
										[
											'controller' => 'Postulates',
											'action' => 'view',
											'_entity' => $voted_postulate
										]
									);
									$count++;
								}
							}
						}
						if (!$count) {
							echo '<li>Brak</li>';
						}
						?>
					</ul>
				</div>
			</div>
		</section>
	<?php } ?>
</div>
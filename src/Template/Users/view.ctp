<?php
/**
 * @var $user
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\Representative $representative
 * @var \App\Model\Entity\Postulate $postulate
 */

if ($this->Identity->get('admin')) {
	echo $this->Html->link(
		'Pokaż w panelu admina',
		[
			'prefix' => 'admin',
			'action' => 'view',
			'_entity' => $user
		],
		[
			'class' => 'btn btn-info'
		]
	);
}
?>
<div class="users view">
	<h2><?= h(ucfirst($user->name)) ?></h2>
	<?= $this->Gravatar->image(
		$user->email,
		['width' => 200, 'height' => 200]
	);
	?>
	<dl>
		<dt>Data rejestracji</dt>
		<dd class="created"><?= $user->created->diffForHumans(); ?></dd>
	</dl>
	<section class="container">
		<div class="row">
			<div class="col-md-6">
				<h3>Reprezentanci, którym ufam:</h3>
				<ul>
					<?php
					$count = 0;
					foreach ($user->voted_representatives as $representative) {
						if ($representative->get('_joinData')['value'] == 1) {
							echo $this->Html->listLink(
								$this->Html->image($representative->getPhotoThumbUrl(16, 16, true), ['width' => 16, 'height' => 16]) . h($representative->full_name),
								[
									'controller' => 'Representatives',
									'action' => 'view',
									'_entity' => $representative
								],
								[
									'escape' => false,
								]
							);
							$count++;
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
					foreach ($user->voted_representatives as $representative) {
						if ($representative->get('_joinData')['value'] == -1) {
							echo $this->Html->listLink(
								$this->Html->image($representative->getPhotoThumbUrl(16, 16, true), ['width' => 16, 'height' => 16]) . h($representative->full_name),
								[
									'controller' => 'Representatives',
									'action' => 'view',
									'_entity' => $representative
								],
								['escape' => false]
							);
							$count++;
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
					foreach ($user->voted_postulates as $postulate) {
						if ($postulate->get('_joinData')['value'] == 1) {
							echo $this->Html->listLink(
								$postulate->name,
								[
									'controller' => 'Postulates',
									'action' => 'view',
									'_entity' => $postulate
								]
							);
							$count++;
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
</div>
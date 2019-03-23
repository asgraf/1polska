<?php
/**
 * @var \App\View\AppView $this
 */
if ($this->Identity->isLoggedIn()) {
	$avatar = $this->Gravatar->image(
		$this->Identity->get('email'),
		['width' => 24, 'height' => 24]
	);
} else {
	$avatar = $this->Html->image('anonim.jpg', ['width' => 24, 'height' => 24]);
}
?>
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only"><?= __('Toggle navigation') ?></span>
				<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">1Polska.pl</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li><?= $this->Html->link('Użytkownicy', ['controller' => 'Users', 'action' => 'index']) ?></li>
				<li><?= $this->Html->link('Postulaty', ['controller' => 'Postulates', 'action' => 'index']) ?></li>
				<li><?= $this->Html->link('Reprezentanci', ['controller' => 'Representatives', 'action' => 'index']) ?></li>
				<li><?= $this->Html->link('Newslettery', ['controller' => 'Newsletters', 'action' => 'index']) ?></li>
				<li><?= $this->Html->link('Tagi', ['controller' => 'Tags', 'action' => 'index']) ?></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle"
					   data-toggle="dropdown"><?= $avatar . ' ' . h($this->Identity->get('username')) ?> <b
							class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><?= $this->Html->link('Podgląd profilu', ['prefix' => false, 'controller' => 'users', 'action' => 'view', '_entity' => $this->Identity->get()]) ?></li>
						<li><?= $this->Html->link('Edycja profilu', ['prefix' => false, 'controller' => 'users', 'action' => 'edit']) ?></li>
						<li><?= $this->Html->link('Wyloguj', ['prefix' => false, 'controller' => 'users', 'action' => 'logout']) ?></li>
						<li class="divider"></li>
						<li><?= $this->Html->link('Wyjdź z panelu admina', ['admin' => false, 'controller' => 'home', 'action' => 'main']) ?></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>
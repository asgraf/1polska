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
<nav class="navbar navbar-inverse navbar-fixed-top">
	<img src="img/ico_1_42x42.png"
	     alt="1Polska LOGO" style="width:42px;height:42px;position:fixed;top:6px;left:0;">

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
				<li><?= $this->Html->link('O co chodzi?', ['controller' => 'Pages', 'action' => 'display', 'o_co_chodzi']) ?></li>
				<li><?= $this->Html->link('Postulaty', ['controller' => 'Postulates', 'action' => 'index']) ?></li>
				<li><?= $this->Html->link('Reprezentanci', ['controller' => 'Representatives', 'action' => 'index']) ?></li>
				<li><?= $this->Html->link('Kontakt', ['controller' => 'Pages', 'action' => 'display', 'kontakt']) ?></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<?php if (!$this->Identity->isLoggedIn()) { ?>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $avatar ?> Niezalogowany <b
								class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?= $this->Html->link('Zaloguj', ['controller' => 'users', 'action' => 'login']) ?></li>
							<li><?= $this->Html->link('Zarejestruj się', ['controller' => 'users', 'action' => 'register']) ?></li>
<!--							<li class="divider"></li>-->
<!--							<li class="dropdown-header">Logowanie społecznościowe</li>-->
<!--							<li>--><?//= $this->Html->link($this->Html->image('Google.png') . ' Zaloguj za pomocą Google', ['controller' => 'home', 'action' => 'social_login', 'google'], ['escape' => false]) ?><!--</li>-->
						</ul>
					<?php } else { ?>
						<a href="#" class="dropdown-toggle"
						   data-toggle="dropdown"><?= $avatar . ' ' . h($this->Identity->get('name')) ?> <b
								class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?= $this->Html->link('Podgląd profilu', ['controller' => 'users', 'action' => 'view', '_entity' => $this->Identity->get()]) ?></li>
							<li><?= $this->Html->link('Edycja profilu', ['controller' => 'users', 'action' => 'edit']) ?></li>
							<li><?= $this->Html->link('Wyloguj', ['controller' => 'users', 'action' => 'logout']) ?></li>
							<?php if ($this->Identity->get('admin')) { ?>
								<li class="divider"></li>
								<li><?= $this->Html->link('Panel admina', ['admin' => true, 'controller' => 'users']) ?></li>
							<?php } ?>

						</ul>
					<?php } ?>
				</li>
			</ul>
		</div>
	</div>
</nav>

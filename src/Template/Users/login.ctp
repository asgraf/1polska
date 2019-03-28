<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->set('title', 'Logowanie');
if (!$this->getRequest()->is('ssl')) {
	?>
	<div class="alert alert-danger fade in">
		<span class="icon-container"><i class="icon-attention-circled"></i></span>
		<strong>Uwaga</strong>
		<p>Korzystasz z nieszyfrowanej wersji naszego serwisu. <br/>Jeśli logujesz się z kafejki internetowej, hotelu
			lub innego niezaufanego miejsca twoje połączenie z naszą stroną może zostać podsłuchane.</p>
		<?= $this->Html->link('Przejdź do bezpiecznego logowania', ['_ssl' => true], ['class' => 'btn btn-success']); ?>
	</div>
	<?php
}
?>

<!--<br/><p style="color:#BBBBBB">Aby kontynuować - Zaloguj się przez swoje konto społecznościowe:</p><br/>-->
<!---->
<? //= $this->Html->link($this->Html->image('Google.png') . ' Zaloguj za pomocą Google', ['admin' => false, 'plugin' => false, 'controller' => 'Home', 'action' => 'socialLogin', 'google'], ['escape' => false, 'class' => 'btn btn-default']) ?>
<!---->
<!--<br/><br/><p style="color:#BBBBBB">lub skorzystaj z formularza poniżej, jeśli nie masz / nie chcesz używać konta-->
<!--	społecznościowego:</p><br/>-->

<div class="row">
	<div class="col-md-5">
		<ul class="nav nav-tabs">
			<li role="presentation"
			    class="active"><?= $this->Html->link(__d('user', 'Login'), ['action' => 'login']); ?></a></li>
			<li role="presentation"><?= $this->Html->link(__d('user', 'Register'), ['action' => 'register']); ?></a></li>
		</ul>
		<div>
			<?= $this->Form->create(null, ['horizontal' => true]); ?>
			<fieldset>
				<?php
				echo $this->element('flashbox');
				echo $this->Form->control('email', ['label' => __d('user', 'Email')]);
				echo $this->Form->control('password', ['label' => __d('user', 'Password')]);
				echo $this->Recaptcha->display();
				echo $this->Form->submit(__('Zaloguj'));

				?>
				<?= $this->Html->link('Zapomniałeś hasła?', ['controller' => 'Users', 'action' => 'forgotPassword'], ['class' => 'pull-right']); ?>
			</fieldset>
			<?= $this->Form->end(); ?>
		</div>
	</div>
</div>
Logowanie oznacza akceptację <?= $this->Html->link('polityki prywatności', ['controller' => 'Pages', 'action' => 'display', 'polityka_prywatnosci']) ?>

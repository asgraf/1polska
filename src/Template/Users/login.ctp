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

<?= $this->Form->create(null, ['horizontal' => true]); ?>
<fieldset>
	<?php
	echo $this->Html->tag('h3', 'Logowanie');
	echo $this->Form->control('email', ['label' => __d('user', 'Email')]);
	echo $this->Form->control('password', ['label' => __d('user', 'Password')]);
	echo $this->Form->submit(__('Zaloguj'));

	?>
	<?= $this->Html->link('Zapomniałeś hasła?', ['controller' => 'Users', 'action' => 'forgotPassword'], ['class' => 'pull-right']); ?>
</fieldset>
<?= $this->Form->end(); ?>
Logowanie oznacza akceptację <?= $this->Html->link('polityki prywatności', ['controller' => 'Pages', 'action' => 'display', 'polityka_prywatnosci']) ?>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
	Witaj<br/>
	<br/>
	Twoje konto zostało pomyślnie utworzone
	<br/>
	<b>Kliknij w poniższy link aby aktywować konto:</b><br/>
<?php
echo $this->Html->link([
	'controller' => 'Users',
	'action' => 'activate',
	'email' => $user->email,
	'token' => $user->token,
	'_full' => true
]);
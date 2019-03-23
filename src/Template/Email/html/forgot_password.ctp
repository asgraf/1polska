<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
Witaj!<br/><br/>
Otrzymujesz ten email ponieważ ty lub ktoś inny szkorzystał z funkcjonalności "Zapomniałem hasła".<br/>
Kliknij w poniższy link aby zmienić swoje hasło:<br/><br/>
<?php
echo $this->Html->link([
	'controller' => 'Users',
	'action' => 'forgotPassword',
	'email' => $user->email,
	'token' => $user->token,
	'_full' => true
]);
?>
<br/>
<br/>
Jeżeli nie chcesz zmieniać hasła po prostu zignoruj ten email.
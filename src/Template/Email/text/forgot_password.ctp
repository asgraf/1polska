<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
Witaj!

Otrzymujesz ten email ponieważ ty lub ktoś inny szkorzystał z funkcjonalności "Zapomniałem hasła".
Kliknij w poniższy link aby zmienić swoje hasło:
<?php
echo $this->Html->link([
	'controller' => 'Users',
	'action' => 'forgotPassword',
	'email' => $user->email,
	'token' => $user->token,
	'_full' => true
]);
?>


Jeżeli nie chcesz zmieniać hasła po prostu zignoruj ten email.
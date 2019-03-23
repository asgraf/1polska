<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
use Cake\Routing\Router;

?>
	Witaj

	Twoje konto zostało pomyślnie utworzone
	Kliknij w poniższy link aby aktywować konto:
<?php
echo Router::url([
	'controller' => 'Users',
	'action' => 'activate',
	'email' => $user->email,
	'token' => $user->token,
	'_full' => true
]);
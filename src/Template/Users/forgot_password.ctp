<?php
/**
 * @var \App\View\AppView $this
 */
$this->set('title', 'Zapomniane hasło');
?>

<div class="users form">
	<?= $this->Form->create(null); ?>
	<fieldset id="form" class="form_page">
		<legend><?= $this->get('title'); ?></legend>
		<?php
		echo $this->Form->control('email', ['label' => __d('user', 'Email')]);
		echo $this->Recaptcha->display();
		?>
	</fieldset>
	<?= $this->Form->button(__d('user', 'Submit'), ['class' => 'btn submit', 'data-icon' => 'ok']); ?>
	<?= $this->Form->end(); ?>
</div>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="users form">
	<?= $this->Form->create(); ?>
	<fieldset id="form" class="form_page">
		<legend><?= $this->get('title'); ?></legend>
		<div class="form-group">
			<label class="col col-md-3 control-label">Czy na pewno chcesz usunąć swoje konto?</label>
			<div class="col col-md-9 required">
				<?php
				echo $this->Form->control('confirm1', ['required', 'type' => 'checkbox', 'div' => false, 'wrapInput' => false, 'class' => false, 'label' => ['text' => 'Tak, chcę usunąć swoje konto', 'class' => false]]);
				echo $this->Form->control('confirm2', ['required', 'type' => 'checkbox', 'div' => false, 'wrapInput' => false, 'class' => false, 'label' => ['text' => 'Oraz, anulować wszelkie oddane przeze mnie głosy', 'class' => false]]);
				echo $this->Form->control('confirm3', ['required', 'type' => 'checkbox', 'div' => false, 'wrapInput' => false, 'class' => false, 'label' => ['text' => 'Zdaję sobie sprawę że jest to operacja nie odwracalna', 'class' => false]]);
				echo $this->Form->control('confirm4', ['required', 'type' => 'checkbox', 'div' => false, 'wrapInput' => false, 'class' => false, 'label' => ['text' => 'I nie przekonacie mnie, do zmiany zdania ;)', 'class' => false]]);
				?>
			</div>
		</div>
		<?= $this->Recaptcha->display(); ?>
	</fieldset>
	<?php
	echo $this->Form->button('Usuń moje konto', ['class' => 'btn btn-danger submit pull-right']);
	echo $this->Html->link('Nie chcę usuwać konta', '/', ['class' => 'btn btn-success']);
	echo $this->Form->end();
	?>
</div>
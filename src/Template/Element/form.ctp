<?php
/**
 * @var \App\View\AppView $this
 * @var $viewVar
 * @var $formUrl
 * @var $enableDirtyCheck
 * @var $item \Cake\ORM\Entity
 */
?>
<?= $this->fetch('before_form'); ?>

	<div class="<?= $this->CrudView->getCssClasses(); ?>">
		<?= $this->Panel->create($this->get('title'), ['type' => 'primary']); ?>
		<?= $this->Form->create(${$viewVar}, ['role' => 'form', 'url' => $formUrl, 'type' => 'file', 'data-dirty-check' => $formEnableDirtyCheck, 'horizontal' => true]); ?>
		<?= $this->CrudView->redirectUrl(); ?>
		<div class="row">
			<div class="col-lg-<?= $this->exists('form.sidebar') ? '8' : '12' ?>">
				<?= $this->element('form_fields'); ?>
			</div>

			<?php if ($this->exists('form.sidebar')) : ?>
				<div class="col-lg-2">
					<?= $this->fetch('form.sidebar'); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="row">
			<div class="col-lg-<?= $this->exists('form.sidebar') ? '8' : '12' ?>">
				<div class="form-group">
					<?= $this->element('form/buttons') ?>
					<?= $this->element('action-header'); ?>
				</div>
			</div>
		</div>

		<?= $this->Form->end(); ?>
		<?= $this->Panel->end(); ?>
	</div>

<?= $this->fetch('after_form'); ?>
<?php
/**
 * @var \App\View\AppView $this
 */
use Cake\Core\Configure;
?>
<div class="form-group">
	<label class="control-label col-md-2 col-sm-6">Zaznacz kratkę obok</label>
	<div class="col-md-8 col-sm-6 required">
		<?= $this->Recaptcha->display()?>
	</div>
</div>
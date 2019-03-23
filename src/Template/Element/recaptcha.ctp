<?php
/**
 * @var \App\View\AppView $this
 */
use Cake\Core\Configure;

return;
$this->Html->script('https://www.google.com/recaptcha/api.js', ['block' => 'script']);
?>
<div class="form-group">
	<label class="col col-md-3 control-label">Zaznacz kratkę obok</label>
	<div class="col col-md-9 required">
		<div class="g-recaptcha" data-sitekey="<?= Configure::readOrFail('Recaptcha.site_key'); ?>"></div>
	</div>
</div>
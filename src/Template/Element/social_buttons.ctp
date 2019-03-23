<?php
/**
 * @var \App\View\AppView $this
 */
$url = $this->Url->build(['action' => $this->getRequest()->getParam('action'), 'id' => $this->getRequest()->getParam('id')], true);
?>
	<div class="social-box">
		<div class="g-plusone btn-block" data-url="<?= $url ?>" data-annotation="inline"></div>
		<div class="fb-like btn-block" data-href="<?= $url ?>" data-layout="standard" data-action="like"
		     data-show-faces="true" data-share="true"></div>
	</div>
<?php
$this->Html->script('https://apis.google.com/js/platform.js', ['block' => 'script', 'defer', 'async']);
$this->Html->script('https://connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.0', ['block' => 'script', 'defer', 'async', 'id' => 'facebook-jssdk']);
<?php
/**
 * @var $newsletter
 * @var \App\View\AppView $this
 */
if ($newsletter['status'] == 'queted' && $newsletter['sent_num'] > 0) {
	$newsletter['status'] = 'sending';
}
?>
<div class="newsletters view">
	<h2><?= __('Newsletter'); ?></h2>
	<dl>
		<dd class="txt"><?= $newsletter['txt']; ?></dd>
		<dt><?= __('Status'); ?></dt>
		<dd class="status"><?= $newsletter['status']; ?></dd>
		<dt><?= __('Postęp'); ?></dt>
		<dd class="progressbar"><?php
			switch ($newsletter['status']) {
				case 'sent':
					break;
				case 'sending':
					echo '<meter title="' . $newsletter['sent_num'] . '/' . $newsletter['sent_max'] . '" value="' . $newsletter['sent_num'] . '" max="' . $newsletter['sent_max'] . '">' . $newsletter['sent_num'] . '/' . $newsletter['sent_max'] . '</meter>';
					break;
				default:
					if ($newsletter['sent_max'] > 0) {
						echo __('{0} przypisanych użytkowników', $newsletter['sent_max']);
					} else {
						echo __('Brak przypisanych użytkowników!!!');
					}

			}
			?></dd>
		<dt><?= __('Utworzono'); ?></dt>
		<dd class="created"><?= $newsletter['created']; ?></dd>
		<dt><?= __('Zmodyfikowano'); ?></dt>
		<dd class="modified"><?= $newsletter['modified']; ?></dd>
		<dt><?= __('Tytuł'); ?></dt>
		<dd class="subject"><?= $newsletter['subject']; ?></dd>
		<dt><?= __('Treść'); ?></dt>

	</dl>
</div>
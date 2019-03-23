<?php
/**
 * @var \App\View\AppView $this
 */
$this->set('title', 'Feedback');
$this->Html->script('//1polska.disqus.com/embed.js', ['block' => 'script']);
?>
<h2>Tutaj można zgłaszać błędy na stronie, sugestie, opinie i komentarze</h2>
<div id="disqus_thread"></div>

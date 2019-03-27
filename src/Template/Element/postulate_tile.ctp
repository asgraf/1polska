<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Postulate $postulate
 */
$url = [
	'controller' => 'Postulates',
	'action' => 'view',
	'_entity' => $postulate
];
echo '<div class="col-md-4 h210">';
echo '<div class="w140 truncate" data-click-url="' . $this->Url->build($url) . '">';
echo $this->Html->tag('h3', $this->Html->link($postulate->name, $url));
echo h($postulate->description);
echo '</div>';
echo '<p class="clearfix">';
echo $this->element('postulate_vote', ['postulate' => $postulate]) . '&nbsp;';
echo '</p>';
echo '</div>';
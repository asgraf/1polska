<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Representative $representative
 */
$url = ['controller' => 'Representatives', 'action' => 'view', '_entity' => $representative];
echo '<div class="col-md-4 h210">';
echo '<div class="max140 truncate" data-click-url="' . $this->Url->build($url) . '">';
echo $this->Html->image(
	$representative->getPhotoThumbUrl($this->get('width', 80), $this->get('height', 80)),
	['class' => 'pull-left img-circle', 'width' => $this->get('width', 80), 'height' => $this->get('height', 80)]
);
echo $this->Html->tag('h3', $this->Html->link($representative->full_name, $url));
echo h($representative->description);
echo '</div>';
echo '<p class="clearfix">';
echo $this->element('representative_vote', ['representative' => $representative]) . '&nbsp;';
echo '</p>';
echo '</div>';
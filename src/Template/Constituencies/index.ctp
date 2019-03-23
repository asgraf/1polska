<?php
/**
 * @var \App\View\AppView $this
 */
$this->set('title', 'Okręgi wyborcze');
echo $this->Html->tag('h2','Okręgi wyborcze');
echo $this->element('contituencies_map');
<?php
/**
 * @var \App\View\AppView $this
 */

if ($this->get('flashbox_displayed')) return;
$this->set('flashbox_displayed', true);

echo '<section id="flashbox">';
echo $this->Flash->render('auth');
echo $this->Flash->render('flash');
echo $this->fetch('flash');
echo '</section>';
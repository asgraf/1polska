<?php
/**
 * @var \App\View\AppView $this
 */
if (empty($page_id)) $page_id = '';
if (empty($page_class)) $page_class = '';
$adminpanel = !empty($this->Identity->get('admin')) && $this->getRequest()->getParam('admin');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" id="<?= $page_id ?>" class="<?= $page_class ?>">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<title><?= $this->get('title'); ?></title>
	<?php
	if ($this->fetch('description')) echo $this->Html->meta('description', $this->fetch('description'));
	echo $this->Html->meta('icon', $this->Url->build('/favicon.png'));
	echo $this->fetch('meta');
	echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.1/modernizr.min.js');
	echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js');
	echo $this->Html->css('main');
	echo $this->Html->css('bootstrap-markdown.min.css');
	echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.5/select2.min.css');
	echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.5/select2-bootstrap.css');
	echo $this->Html->css('https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
	echo $this->Html->css('konrad');
	echo $this->fetch('css');
	?>
</head>
<body>
<header>
	<?= $this->element($adminpanel ? 'navbar_nav_admin' : 'navbar_nav') ?>
	<?= $this->fetch('header'); ?>
</header>
<div id="fb-root"></div>
<div class="container">
	<section id="flashbox">
		<?php
		echo $this->Flash->render('auth');
		echo $this->Flash->render('flash');
		echo $this->fetch('flash');
		?>
	</section>
	<?= $this->cell('Constituencies'); ?>
	<div class="view_content"><?= $this->fetch('content'); ?></div>
</div>
<footer id="footer" data-role="footer">
	<p class="text-muted">
		<?= $this->Html->link('Polityka prywatności', ['prefix' => false, 'controller' => 'Pages', 'action' => 'display', 'polityka_prywatnosci'], ['class' => 'text-muted']); ?>
		:: &copy; <?= ucfirst($this->getRequest()->host()); ?> <?= date('Y'); ?> ::
		<?= $this->Html->link('Zgłoś błąd lub sugestię', ['prefix' => false, 'controller' => 'Pages', 'action' => 'display', 'disqus'], ['class' => 'text-muted']); ?>
	</p>

</footer>
<?php
echo $this->Html->script('util.js');
echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js');
echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.4/underscore-min.js');
echo $this->Html->script('apply_active');
echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.1/js/bootstrap.min.js');
echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/js/standalone/selectize.min.js');
echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/jquery.dirtyforms/2.0.0/jquery.dirtyforms.min.js');
//echo $this->Html->script('apply_select2');
//echo $this->Html->script('apply_datalist');
echo $this->Html->script('markdown');
echo $this->Html->script('to-markdown');
echo $this->Html->script('apply_markdown');
echo $this->Html->script('bootstrap-markdown');
echo $this->Html->script('apply_click_url');
echo $this->fetch('action_link_forms');
echo $this->fetch('postLink');
echo $this->fetch('script');
?>
</body>
</html>

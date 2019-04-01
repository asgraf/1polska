<?php
/**
 * @var $description_for_layout
 * @var $representatives
 * @var $postulates
 * @var $users_count
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Postulate $postulate
 * @var \App\Model\Entity\Representative $representative
 * @var \App\Model\Entity\Postulate[]|\Cake\Collection\CollectionInterface $postulates
 * @var \App\Model\Entity\Representative[]|\Cake\Collection\CollectionInterface $representatives
 */
$this->Html->meta(['property' => 'og:image', 'content' => 'img/ico_1_42x42.png'], NULL, ['inline' => false]);
$this->set('title', '1Polska.pl - Strona główna');

$logged_id = $this->getRequest()->getAttribute('authentication')->getResult()->isValid();
$join_button = $logged_id ? '' : $this->Html->link('Przyłącz się »', ['controller' => 'Users', 'action' => 'register'], ['class' => 'btn btn-primary btn-lg pull-right']);
$this->append('header', '
<div class="jumbotron" style="padding:10px">
	<div class="container">
		<h5 style="font-style:italic; font-weight:normal; color:#99BB99">„Do triumfu zła wystarczy bierność ludzi dobrych” – Edmund Burke</h5>
		<h1 style="font-size:36px">Czas zjednoczyć dobrych ludzi wobec patologii obecnego systemu!</h1>
		<h4 style="color:#317EAC">Chcesz żyć w normalnym kraju? Masz dość absurdów IIIRP? Nie jesteś sam!</h4>
		<p style="font-size:15px">To my, polskie społeczeństwo, które ma już dość tego chorego systemu, jaki stworzyły nam tzw. "elity IIIRP". Pookrągłostołowemu, zabetonowanemu układowi partyjnemu i obecnej klasie politycznej już dziękujemy! Dość hipokryzji, obłudy i zakłamania polityków oraz mediów. Tu ustalimy wspólnie priorytety zmian i wreszcie połączymy siły żeby przywrócić w Polsce normalność. To my: normalni, zwykli ludzie chcący żyć uczciwie i godnie jesteśmy w Polsce większością - i czas to sobie uświadomić!</p>
		<h2 class="text-center">' . $this->Html->link('Zobacz więcej »', ['controller' => 'Pages', 'action' => 'display', 'o_co_chodzi'], ['class' => 'btn btn-primary btn-lg pull-left']) . '<strong style="margin-left:15px; margin-right:15px; font-weight:normal">Jest nas już <span style="color:#CC3344">' . number_format($users_count, 0, ',', '&nbsp;') . '</span> osób!</strong>
		' . $join_button . '
		</h2>
	</div>
</div>
');
?>
<section class="container">
	<img src="img/ico_1_42x42.png"
	     alt="1Polska LOGO" style="width:42px;height:42px;position:fixed;bottom:6px;right:0;">

	<div class="row">
	</div>
	<div class="row">
		<h2><?= $this->Html->link('PRIORYTETY ZMIAN - najpopularniejsze postulaty (to co nas łączy):', ['controller' => 'Postulates', 'action' => 'index', 'active' => true]) ?></h2>
		<?php
		foreach ($postulates as $postulate) {
			echo $this->element('postulate_tile', ['postulate' => $postulate]);
		}
		?>
		<div><?= $this->Html->link('Zobacz propozycje użytkowników', ['controller' => 'Postulates', 'action' => 'index', 'active' => true], ['class' => 'btn btn-primary']); ?></div>
	</div>

	<div class="row">
		<h2>LISTA SPOŁECZNA - co to takiego? (jak połączyć siły aby zmienić nasz kraj):</h2>
		<iframe
			src="https://docs.google.com/presentation/d/14gx2DUk-6XOd4p4TTQdhQJRuedCelurnQOc45IuTmTs/embed?start=true&loop=true&delayms=5000"
			frameborder="0" width="960" height="569" allowfullscreen="true" mozallowfullscreen="true"
			webkitallowfullscreen="true"></iframe>
	</div>

	<div class="row">
		<h2><?= $this->Html->link('REPREZENTANCI i liderzy - osoby popierające tę inicjatywę:', ['controller' => 'Representatives', 'action' => 'index']) ?></h2>

		
		<?php
		foreach ($representatives as $representative) {
			echo $this->element('representative_tile', ['representative' => $representative]);
		}
		?>
		<div><?= $this->Html->link('Zobacz wszystkich reprezentantów', ['controller' => 'Representatives', 'action' => 'index', 'active' => true], ['class' => 'btn btn-primary']); ?></div>
	</div>

	<div class="row">
		<h2><a href="/okregi_wyborcze">REPREZENTANCI wg OKRĘGÓW WYBORCZYCH - kliknij na mapę:</a></h2>
		<?= $this->element('contituencies_map'); ?>
	</div>


</section>

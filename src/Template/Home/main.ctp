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
$this->Html->meta(['property' => 'og:image', 'content' => 'http://lh6.googleusercontent.com/-hiYEyAESO8U/AAAAAAAAAAI/AAAAAAAAAAY/q7yeRBPt_Q4/photo.jpg'], NULL, ['inline' => false]);
$this->set('title', '1Polska.pl - Strona główna');

$logged_id = $this->getRequest()->getAttribute('authentication')->getResult()->isValid();
$join_button = $logged_id ? '' : $this->Html->link('Przyłącz się »', ['controller' => 'Users', 'action' => 'register'], ['class' => 'btn btn-primary btn-lg pull-right']);
$this->append('header', '
<div class="jumbotron" style="padding:10px">
	<div class="container">
		<h5 style="font-style:italic; font-weight:normal; color:#99BB99">„Do triumfu zła wystarczy bierność ludzi dobrych” – Edmund Burke</h5>
		<h1>Czas zjednoczyć dobrych ludzi wobec patologii obecnego systemu!</h1>
		<h4>Chcesz żyć w normalnym kraju? Masz dość absurdów IIIRP? Nie jesteś sam!</h4>
		<p style="font-size:15px">To my, polskie społeczeństwo, które ma już dość tego chorego systemu, jaki stworzyły nam tzw. "elity IIIRP". Pookrągłostołowemu, zabetonowanemu układowi partyjnemu i obecnej klasie politycznej już dziękujemy! Dość hipokryzji, obłudy i zakłamania polityków oraz mediów. Tu ustalimy wspólnie priorytety zmian i wreszcie połączymy siły żeby przywrócić w Polsce normalność. To my: normalni, zwykli ludzie chcący żyć uczciwie i godnie jesteśmy w Polsce większością - i czas to sobie uświadomić!</p>
		<h2 class="text-center">' . $this->Html->link('Zobacz więcej »', ['controller' => 'Pages', 'action' => 'display', 'o_co_chodzi'], ['class' => 'btn btn-primary btn-lg pull-left']) . '<strong style="margin-left:15px; margin-right:15px; font-weight:normal">Jest nas już <span style="color:#CC3344">' . number_format($users_count, 0, ',', '&nbsp;') . '</span> osób!</strong>
		' . $join_button . '
		</h2>
	</div>
</div>
');
?>
<section class="container">
	<img src="data:image/png;base64,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"
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
		<h2>REPREZENTANCI wg OKRĘGÓW WYBORCZYCH - kliknij na mapę:</h2>
		<?= $this->element('contituencies_map'); ?>
	</div>


</section>

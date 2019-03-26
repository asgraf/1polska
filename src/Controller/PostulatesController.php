<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;

/**
 * @property \App\Model\Table\PostulatesTable $Postulates
 * @property \Crud\Controller\Component\CrudComponent $Crud
 */
class PostulatesController extends AppController
{
	public $guestActions = ['index', 'view'];

	protected function beforeAction()
	{
		parent::beforeAction();

		/** @var \Crud\Action\BaseAction $action */
		$action = $this->Crud->action();

		if (
			!$this->Authentication->getResult()->isValid() ||
			!$this->Postulates->Users->Representatives->exists([
				'user_id' => $this->Authentication->getIdentityData('id')
			])
		) {
			$action->setConfig('scaffold.actions_blacklist', [
				'add', 'edit'
			]);
		}
	}

	public function add()
	{
		/** @var \App\Model\Entity\Representative|null $representative */
		$representative = $this->Postulates->Users->Representatives->find()
			->where([
				'user_id' => $this->Authentication->getIdentityData('id')
			])
			->first();
		if (!$representative) {
			$this->Flash->error('Nowe postulaty mogą proponować tylko ci użytkownicy którzy zgłosili się na reprezentanta');
			return $this->redirect($this->referer(['action' => 'index'], true));
		}
		if ($representative && $representative->created->addDay()->isFuture()) {
			$this->Flash->error('Zaproponowanie postulatu będzie możliwe nie wcześniej niż: ' . $representative->created->addDay()->diffForHumans() . '<br>(Można proponować postulat najwcześniej dobę po rejestracji jako reprezentant)');
			return $this->redirect($this->referer(['action' => 'index'], true));
		}
		/** @var \App\Model\Entity\Postulate|null $prev_postulate */
		$prev_postulate = $this->Postulates->find()
			->where([
				'user_id' => $this->Authentication->getIdentityData('id'),
				'created >' => FrozenTime::parse()->subDay()
			])
			->first();
		if ($prev_postulate) {
			$this->Flash->error('Zaproponowanie kolejnego postulatu będzie możliwe nie wcześniej niż: ' . $prev_postulate->created->addDay()->diffForHumans() . '<br>(Można proponować jeden postulat na dobę)');
			return $this->redirect($this->referer(['action' => 'index']));
		}
		/** @var \Crud\Action\AddAction $action */
		$action = $this->Crud->action();

		$action->setConfig('scaffold.fields', [
			'name' => [
				'label' => 'Tytuł',
				'maxlength' => 50
			],
			'constituency_id' => [
				'label' => [
					'text' => 'Okręg wyborczy<br>(jeśli dotyczy)',
					'escape' => false
				],
				'empty' => true,
				'options' => $this->Postulates->Constituencies->find('list')
			],
			'description' => [
				'label' => __('Krótki opis'),
				'maxlength' => 160
			],
			'content' => [
				'label' => __('Pełny Opis'),
				'data-markdown-editor'
			],
			'tag_list' => [
				'label' => __('Tagi'),
				'help' => 'Aby przypisać kilka tagów oddziel je przecinkiem'
			],
		]);

		$this->Crud->on('beforeSave', function (Event $event) {
			/** @var \App\Model\Entity\Postulate $postulate */
			$postulate = $event->getSubject()->entity;

			$postulate->user_id = $this->Authentication->getIdentityData('id');
		});

		$this->set('title', 'Nowy Postulat');

		$this->Crud->execute();
	}

	public function edit()
	{
		/** @var \Crud\Action\EditAction $action */
		$action = $this->Crud->action();

		$action->setConfig('scaffold.fields', [
			'name' => [
				'label' => 'Tytuł',
				'maxlength' => 50
			],
			'constituency_id' => [
				'label' => [
					'text' => 'Okręg wyborczy<br>(jeśli dotyczy)',
					'escape' => false
				],
				'empty' => true,
				'options' => $this->Postulates->Constituencies->find('list')
			],
			'description' => [
				'label' => __('Krótki opis'),
				'maxlength' => 160
			],
			'content' => [
				'label' => __('Pełny Opis'),
				'data-markdown-editor'
			],
			'tag_list' => [
				'label' => __('Tagi'),
				'help' => 'Aby przypisać kilka tagów oddziel je przecinkiem'
			],
		]);

		$this->Crud->on('beforeFind', function (Event $event) {
			/** @var \Cake\ORM\Query $query */
			$query = $event->getSubject()->query;
			$query->where([
				'user_id' => $this->Authentication->getIdentityData('id')
			]);
			$query->contain([
				'Tags'
			]);
		});
		$this->Crud->on('afterFind', function (Event $event) {
			/** @var \App\Model\Entity\Postulate $postulate */
			$postulate = $event->getSubject()->entity;

			$this->set('title', 'Edycja Postulatu ' . h($postulate->name));

			if ($postulate->created->addMinute(15)->isPast()) {
				$this->Flash->error('Nie możesz już edytować tego postulatu. Postulat można edytować tylko przez 15 minut od jego utworzenia');
				return $this->redirect(['action' => 'view', '_entity' => $postulate]);
			} else {
				$this->Flash->info('Postulaty można edytować tylko przez 15 minut od ich utworzenia. Możliwość dokonywania poprawek w tym postulacie wygaśnie: ' . $postulate->created->addMinute(15)->toDateTimeString());
			}
		});
		$this->Crud->execute();
	}

	public function index()
	{
		$this->set('title', 'Postulaty');

		$action = $this->Crud->action();

		$action->setConfig('scaffold.fields', [
			'name' => [
				'title' => 'Tytuł',
				'disableSort' => true,
				'formatter' => 'element',
				'element' => 'Postulates/name'
			],
			'upvotes' => [
				'title' => 'Poparcie',
				'formatter' => 'element',
				'element' => 'Postulates/vote'
			],
			'created' => [
				'title' => 'Utworzono',
				'formatter' =>
					function ($name, \Cake\I18n\FrozenTime $value) {
						return $value->diffForHumans();
					},
			],
		]);

		$this->Crud->on('beforePaginate', function (Event $event) {
			/** @var \Cake\ORM\Query $query */
			$query = $event->getSubject()->query;

			$query
				->where([
					'active' => true,
				])
				->contain([
					'MyVote',
				])
				->order([
					'Postulates.upvotes' => 'desc',
					'Postulates.created' => 'asc'
				]);
		});
		return $this->Crud->execute();
	}

	public function view()
	{
		$this->Crud->on('beforeFind', function (Event $event) {
			/** @var \Cake\ORM\Query $query */
			$query = $event->getSubject()->query;

			$query->contain([
				'MyVote',
				'Tags',
				'Constituencies'
			]);
		});

		if ($this->getRequest()->is('html')) {
			$this->Crud->on('afterFind', function (Event $event) {
				/** @var \App\Model\Entity\Postulate $postulate */
				$postulate = $event->getSubject()->entity;

				$this->Crud->on('beforeRender', function (Event $event) use ($postulate) {
					$representatives = $this->Postulates->Users->Representatives->find()
						->contain('Photo')
						->matching('Users.Votes', function (Query $query) use ($postulate) {
							return $query->where([
								'Votes.fk_id' => $postulate->id,
								'Votes.fk_model' => 'Postulates',
							]);
						});
					$this->set('representatives', $representatives);
				});
			});
		}

		return $this->Crud->execute();
	}

	private function _vote($value, $id = null)
	{
		/** @var \App\Model\Entity\Postulate $postulate */
		$postulate = $this->Postulates->get($id, [
			'contain' => [
				'MyVote'
			]
		]);

		if ($postulate->my_vote) {
			if ($postulate->my_vote->modified->addMinute()->isFuture()) {
				$this->Flash->error(__('Przed ponowną zmianą swojego głosu musisz odczekać jeszcze {0} sekund (Swój głos można zmieniać raz na minutę)', $postulate->my_vote->modified->addMinute()->diffInSeconds()));
				return $this->redirect($this->referer(['action' => 'index'], true));
			}
			$postulate->my_vote->value = $value;
		} else {
			$postulate->my_vote = $this->Postulates->MyVote->newEntity([
				'user_id' => $this->Authentication->getIdentityData('id'),
				'value' => $value,
				'fk_id' => $id,
				'fk_model' => 'Postulates',
			]);
		}

		$this->Postulates->MyVote->saveOrFail($postulate->my_vote);
		$postulate->updateVoteCount();
		$this->Postulates->saveOrFail($postulate);

		if ($value) {
			$this->Flash->success(__('Oddano głos {0} na "{1}"', $value > 0 ? '+' . $value : $value, h($postulate->name)));
		} else {
			$this->Flash->success(__('Anulowano głos na "{0}"', h($postulate->name)));
		}
		return $this->redirect($this->referer(['action' => 'index'], true));
	}

	public function upvote($id = null)
	{
		$this->getRequest()->allowMethod('post');
		$postulate_vote_count = $this->Postulates->Votes->find()
			->where([
				'user_id' => $this->Authentication->getIdentityData('id'),
				'fk_model' => 'Postulates'
			])
			->count();
		if ($postulate_vote_count >= 12) {
			$this->Flash->error(__('Można poprzeć maksymalnie 12 postulatów. Aby móc poprzeć ten postulat musisz wcześniej odebrać swój głos innemu postulatowi.'));
			return $this->redirect($this->referer(['action' => 'index'], true));
		}

		return $this->_vote(1, $id);
	}

	public function cancelvote($id = null)
	{
		$this->getRequest()->allowMethod('post');

		return $this->_vote(0, $id);
	}
}

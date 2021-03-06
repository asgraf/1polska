<?php
namespace App\Controller;

use Cake\Event\Event;

/**
 * @property \App\Model\Table\RepresentativesTable $Representatives
 * @property \Crud\Controller\Component\CrudComponent $Crud
 */
class RepresentativesController extends AppController
{
	public $guestActions = ['index', 'view'];

	public function _crudInit()
	{
		parent::_crudInit();
		$this->Crud->mapAction('delete', 'Crud.Delete');
	}

	function beforeAction()
	{
		/** @var \Crud\Action\BaseAction $action */
		$action = $this->Crud->action();

		if ($this->getRequest()->getParam('action') == 'index') {
			$action->setConfig('scaffold.actions', [
				'add' => [
					'scope' => 'table',
					'callback' => function ($config) {
						$config['title'] = 'i:plus Zaproponuj SIEBIE na reprezentanta';
						$config['url'] = [
							'action' => 'add',
						];
						return $config;
					}
				],
			]);
		} else {
			$action->setConfig('scaffold.actions_blacklist', [
				'add'
			]);
		}

		parent::beforeAction();
	}

	public function add()
	{
		$representative = $this->Representatives->find()->where([
			'user_id' => $this->Authentication->getIdentityData('id')
		])->first();

		if ($representative) {
			return $this->redirect([
				'action' => 'edit',
				'_entity' => $representative
			]);
		}

		/** @var \Crud\Action\AddAction $action */
		$action = $this->Crud->action();

		$action->setConfig('scaffold.fields', [
			'firstname' => [
				'label' => 'Imię',
				'maxlength' => 20,
			],
			'lastname' => [
				'label' => 'Nazwisko',
				'maxlength' => 20
			],
			'photo' => [
				'type' => 'file',
				'label' => __('Zdjęcie'),
				'accept' => implode(',', array_keys(getSupportedImageFormats()))
			],
			'constituency_id' => [
				'label' => [
					'text' => 'Okręg wyborczy<br>(jeśli dotyczy)',
					'escape' => false
				],
				'empty' => true,
				'options' => $this->Representatives->Constituencies->find('list')
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
			'privacy_policy' => [
				'type' => 'custom',
				'label' => 'Polityka prywatosci',
				'formatter' => 'element',
				'element' => 'Representatives/terms_and_policy'
			]
		]);

		$this->Crud->on('beforeSave', function (Event $event) {
			/** @var \App\Model\Entity\Representative $representative */
			$representative = $event->getSubject()->entity;

			$representative->user_id = $this->Authentication->getIdentityData('id');
		});

		$this->set('title', 'Nowy Reprezentant');

		$this->Crud->execute();
	}


	public function edit()
	{
		/** @var \Crud\Action\EditAction $action */
		$action = $this->Crud->action();

		$action->setConfig('scaffold.fields', [
			'firstname' => [
				'label' => 'Imię',
				'maxlength' => 20
			],
			'lastname' => [
				'label' => 'Nazwisko',
				'maxlength' => 20
			],
			'photo' => [
				'type' => 'file',
				'label' => __('Zdjęcie'),
				'accept' => implode(',', array_keys(getSupportedImageFormats()))
			],
			'constituency_id' => [
				'label' => [
					'text' => 'Okręg wyborczy<br>(jeśli dotyczy)',
					'escape' => false
				],
				'empty' => true,
				'options' => $this->Representatives->Constituencies->find('list')
			],
			'description' => [
				'label' => __('Krótki opis'),
				'maxlength' => 160
			],
			'content' => [
				'label' => __('Pełny Opis'),
				'data-markdown-editor'
			],
			/*'tag_list' => [
				'label' => __('Tagi'),
				'help' => 'Aby przypisać kilka tagów oddziel je przecinkiem'
			],*/
			'privacy_policy' => [
				'type' => 'custom',
				'label' => 'Polityka prywatosci',
				'formatter' => 'element',
				'element' => 'Representatives/terms_and_policy'
			]
		]);

		$this->Crud->on('beforeFind', function (Event $event) {
			/** @var \Cake\ORM\Query $query */
			$query = $event->getSubject()->query;
			$query->where([
				'user_id' => $this->Authentication->getIdentityData('id')
			]);
			$query->contain([
				'Photo',
				'Tags'
			]);
		});

		$this->Crud->on('afterFind', function (Event $event) use ($action) {
			/** @var \App\Model\Entity\Representative $representative */
			$representative = $event->getSubject()->entity;

			if ($representative->created->addMinutes(15)->isPast()) {
				$action->setConfig('scaffold.fields.firstname.disabled', true);
				$action->setConfig('scaffold.fields.lastname.disabled', true);
			}

			$this->set('title', 'Edycja Reprezentanta ' . h($representative->full_name));
		});


		return $this->Crud->execute();
	}

	public function delete()
	{
		$this->Crud->on('beforeFind', function (Event $event) {
			/** @var \Cake\ORM\Query $query */
			$query = $event->getSubject()->query;
			$query->where([
				'user_id' => $this->Authentication->getIdentityData('id')
			]);
		});

		$this->Crud->execute();
	}

	public function index()
	{
		$this->paginate['limit'] = 50;
		$this->set('title', 'Reprezentanci');

		$action = $this->Crud->action();

		$action->setConfig('scaffold.actions_blacklist', [
			'edit', 'delete'
		]);

		$action->setConfig('scaffold.fields', [
			'photo' => [
				'title' => ' ',
				'disableSort' => true,
				'formatter' => 'element',
				'element' => 'Representatives/photo'
			],
			'name' => [
				'title' => 'Imię i nazwisko',
				'disableSort' => true,
				'formatter' => 'element',
				'element' => 'Representatives/name'
			],
			'upvotes' => [
				'title' => 'Poparcie',
				'formatter' => 'element',
				'element' => 'Representatives/vote'
			],
			'created' => [
				'title' => 'Dodano',
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
					'Photo',
					'MyVote'
				])
				->order([
					'Representatives.upvotes' => 'desc',
					'Representatives.created' => 'asc'
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
				'Photo',
				'Users' => [
					'VotedPostulates' => [
						'conditions' => [
							'VotedPostulates.active' => true
						],
					],
					'VotedRepresentatives' => [
						'conditions' => [
							'VotedRepresentatives.active' => true,
							'VotedRepresentatives.id !=' => $this->getRequest()->getParam('id')
						],
						'Photo'
					],
				],
				'MyVote',
				'Tags',
				'Constituencies'
			]);
		});
		return $this->Crud->execute();
	}

	private function _vote($value, $id = null)
	{
		/** @var \App\Model\Entity\Representative $representative */
		$representative = $this->Representatives->get($id, [
			'contain' => [
				'MyVote'
			]
		]);

		if ($representative->my_vote) {
			if ($representative->my_vote->modified->addMinute()->isFuture()) {
				$this->Flash->error(__('Przed ponowną zmianą swojego głosu musisz odczekać jeszcze {0} sekund (Swój głos można zmieniać raz na minutę)', $representative->my_vote->modified->addMinute()->diffInSeconds()));
				return $this->redirect($this->referer(['action' => 'index'], true));
			}
			$representative->my_vote->value = $value;
		} else {
			$representative->my_vote = $this->Representatives->MyVote->newEntity([
				'user_id' => $this->Authentication->getIdentityData('id'),
				'value' => $value,
				'fk_id' => $id,
				'fk_model' => 'Representatives',
			]);
		}

		$this->Representatives->MyVote->saveOrFail($representative->my_vote);
		$representative->updateVoteCount();
		$this->Representatives->saveOrFail($representative);

		if ($value) {
			$this->Flash->success(__('Oddano głos {0} na "{1}"', $value > 0 ? '+' . $value : $value, h($representative->full_name)));
		} else {
			$this->Flash->success(__('Anulowano głos na "{0}"', h($representative->full_name)));
		}
		return $this->redirect($this->referer(['action' => 'index'], true));
	}

	public function upvote($id = null)
	{
		$this->getRequest()->allowMethod('post');
		return $this->_vote(1, $id);
	}

	public function downvote($id = null)
	{
		$this->getRequest()->allowMethod('post');
		return $this->_vote(-1, $id);
	}

	public function cancelvote($id = null)
	{
		$this->getRequest()->allowMethod('post');
		return $this->_vote(0, $id);
	}
}

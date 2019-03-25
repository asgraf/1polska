<?php
namespace App\Controller;

use App\Model\Entity\User;
use Cake\Event\Event;
use Cake\Http\Exception\InternalErrorException;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
	public $guestActions = [
		'register',
		'login',
		'logout',
		'forgotPassword',
		'activate',
		'index'
	];

	public function _crudInit()
	{
		$this->Crud->mapAction('login', 'CrudUsers.Login');
		$this->Crud->mapAction('logout', 'CrudUsers.Logout');
		$this->Crud->mapAction('edit', 'Crud.Edit');
		$this->Crud->mapAction('view', 'Crud.View');
		$this->Crud->mapAction('export', 'Crud.View');
		$this->Crud->mapAction('delete', 'Crud.Delete');
		$this->Crud->mapAction('register', [
			'className' => 'Crud.Add',
			'messages' => [
				'success' => [
					'text' => 'Konto pomyślnie utworzone',
				],
				'error' => [
					'text' => 'Napraw błedy i spróbuj ponownie',
				]
			],
			'saveOptions' => [
				'fieldList' => [
					'name',
					'email',
					'password',
					'login',
				]
			]
		]);
	}

	public function isAuthorized($user)
	{
		$parent = parent::isAuthorized($user);
		if (!$parent) return false;
		if (!empty($this->getRequest()->getParam('id') && $this->getRequest()->getParam('action') == 'edit')) {
			if ($this->getRequest()->getParam('id') != $this->Authentication->getIdentityData('id')) return false;
		}

		return true;
	}

	public function index()
	{
		return $this->redirect('/');
	}

	public function view()
	{
		$this->Crud->on('beforeFind', function (Event $event) {
			/** @var \Cake\ORM\Query $query */
			$query = $event->getSubject()->query;

			$query->contain([
				'Representatives',
				'VotedPostulates' => [
					'conditions' => [
						'VotedPostulates.active' => true
					],
				],
				'VotedRepresentatives' => [
					'conditions' => [
						'VotedRepresentatives.active' => true,
						'VotedRepresentatives.user_id !=' => $this->Authentication->getIdentityData('id')
					],
					'Photo'
				],
			]);
		});
		return $this->Crud->execute();
	}

	public function export()
	{
		$this->Crud->on('beforeFind', function (Event $event) {
			/** @var \Cake\ORM\Query $query */
			$query = $event->getSubject()->query;

			$query->contain([
				'Representatives' => [
					'Tags',
					'Constituencies'
				],
				'VotedPostulates' => [
					'fields' => ['id', 'name', 'Votes.user_id'],
					'conditions' => [
						'VotedPostulates.active' => true
					],
				],
				'VotedRepresentatives' => [
					'fields' => ['id', 'firstname', 'lastname', 'Votes.user_id', 'Votes.value'],
					'conditions' => [
						'VotedRepresentatives.active' => true,
						'VotedRepresentatives.user_id !=' => $this->Authentication->getIdentityData('id')
					],
					'Photo'
				],
			]);
		});
		return $this->Crud->execute(null, [
			'id' => $this->Authentication->getIdentityData('id')
		]);
	}

	function edit()
	{
		return $this->Crud->execute(null, [
			'id' => $this->Authentication->getIdentityData('id')
		]);
	}

	function register()
	{
		if ($this->Authentication->getResult()->isValid()) {
			if ($this->getRequest()->is('html')) {
				return $this->redirect('/');
			}
		}

		$this->Crud->on('beforeSave', function (Event $event) {
			/** @var User $user */
			$user = $event->getSubject()->entity;

			$user->verified = false;
			$user->generateToken();
		});

		$this->Crud->on('afterSave', function (Event $event) {
			/** @var User $user */
			$user = $event->getSubject()->entity;
			$success = $event->getSubject()->success;
			if ($success) {
				if (!$user->sendRegisterEmail()) {
					throw new InternalErrorException('Error sending email');
				}
				$this->Flash->info(__('Aby zakończyć proces rejestracyjny kliknij w link aktywacyjny wysłany na {0}', '<b>' . h($user->email) . '</b>'));
				$this->renewUserSession($user);
			}
		});

		return $this->Crud->execute();
	}

	function activate()
	{
		if ($this->Authentication->getResult()->isValid() && $this->Authentication->getIdentityData('verified')) {
			return $this->redirect('/');
		}
		$email = $this->getRequest()->getQuery('email');
		$token = $this->getRequest()->getQuery('token');
		$user = null;
		if ($email) {
			/** @var User|null $user */
			$user = $this->Users->find()
				->where(['email' => $email])
				->first();
		}
		if ($user && $token) {
			if ($user->validateToken($token) && !$user->verified) {
				$user->deleteToken();
				$user->verified = true;
				$this->Users->saveOrFail($user);

				$this->renewUserSession($user);
				$this->Flash->success('Twoje konto zostało pomyślnie aktywowane');
			}
		} else {
			$this->Flash->error(__('Nieprawidłowy lub wygasły token.'));
		}
		return $this->redirect('/');
	}

	function forgotPassword()
	{
		if ($this->Authentication->getResult()->isValid()) {
			return $this->redirect('/');
		}
		$email = $this->getRequest()->getData('email') ?: $this->getRequest()->getQuery('email');
		$user = $this->Users->find()
			->where([
				'email' => $email
			])
			->first();
		$token = $this->getRequest()->getQuery('token');
		if ($user instanceof User && $token) {
			if ($user->validateToken($token)) {
				$user->verified = true;
				$user->deleteToken();
				$this->Users->saveOrFail($user);
				$this->Authentication->setIdentity($user);
				$this->Flash->success(__('Pomyślnie zalogowano. Teraz możesz zmienić hasło.'));
				return $this->redirect(['action' => 'edit']);
			} else {
				$this->Flash->error(__('Nieprawidłowy lub wygasły token.'));
				return $this->redirect(['action' => 'forgotPassword']);
			}
		}
		if ($this->getRequest()->is('post')) {
			if ($user instanceof User) {
				$user->generateToken();
				$this->Users->saveOrFail($user);

				if (!$user->sendForgotPasswordEmail()) {
					throw new InternalErrorException('Error sending email');
				}

				$this->Flash->info('Link potwierdzajacy został wysłany na twój adres email');
				return $this->redirect('/');
			} else {
				$this->Flash->error('Nie udało się odnaleść konta powiązanego z adresem email <b>' . h($email) . '</b>');
			}
		}
	}

//	public function admin_pid() {
//		$pids = Hash::extract($this->Users->find('all',[
//			'fields'=>['DISTINCT User.pid'],
//			'conditions'=>[
//				'User.status !='=>'not_active',
//				'User.pid IS NOT NULL'
//			],
//		]),'{n}.User.pid');
//		$options = [
//			'conditions'=>['User.id'=>$pids],
//			'contain'=>[
//				'ChildUser'=>[
//					'order'=>'ChildUser.id DESC'
//				]
//			],
//			'limit'=>10,
//			'order'=>'User.id DESC'
//		];
//		if(!empty($this->getRequest()->getParam('all')) && ($this->getRequest()->is('ajax') || $this->getRequest()->getParam('requested'))) {
//			$users = $this->Users->find('all',$options);
//			$this->set('all',1);
//		} else {
//			$this->paginate = array_merge($this->paginate,$options);
//			$users = $this->Paginator->paginate();
//			$this->set('all',0);
//		}
//
//		$this->set('users',$users);
//		return $users;
//	}

	public function login()
	{
		$result = $this->Authentication->getResult();

		if ($result->isValid()) {
			/** @var \App\Model\Entity\User $user */
			$user = $this->Authentication->getIdentity()->getOriginalData();
			$user->last_ip = $this->getRequest()->clientIp();
			$this->Users->saveOrFail($user);
			return $this->redirect($this->request->getQuery('redirect', '/'));
		}

		if ($this->request->is(['post']) && !$result->isValid()) {
			$this->Flash->error('Invalid username or password');
		}
	}

	function logout()
	{
		return $this->redirect($this->Authentication->logout() ?: '/');
	}

	function delete()
	{
		$this->set('title', 'Usuwanie konta');

		if (!$this->getRequest()->is('get')) {
			return $this->Crud->execute(null, [
				'id' => $this->Authentication->getIdentityData('id')
			]);
		}
	}
}
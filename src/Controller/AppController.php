<?php
namespace App\Controller;

use App\Model\Entity\User;
use ArrayObject;
use Cake\Controller\Controller;
use Cake\Controller\Exception\SecurityException;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Http\ServerRequest;
use Cake\I18n\FrozenTime;
use Cake\I18n\I18n;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Utility\Security;
use Crud\Controller\ControllerTrait;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 * @property \Setup\Controller\Component\SetupComponent $Setup
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Recaptcha\Controller\Component\RecaptchaComponent $Recaptcha
 * @property \App\Model\Table\UsersTable $Users
 * @property \Crud\Controller\Component\CrudComponent $Crud
 * @property \App\Controller\Component\FlashComponent $Flash
 * @property \Search\Controller\Component\PrgComponent $Prg
 */
class AppController extends Controller
{
	use ControllerTrait {
		ControllerTrait::invokeAction as invokeActionCrud;
	}

	public $guestActions = [];

	/**
	 * Initialization hook method.
	 *
	 * Use this method to add common initialization code like loading components.
	 *
	 * e.g. `$this->loadComponent('Security');`
	 *
	 * @return void
	 */
	public function initialize()
	{
		I18n::setLocale('pl');
		$this->getRequest()->addDetector('html', function (ServerRequest $request) {
			return !$request->getParam('_ext');
		});

		if (!$this->getRequest()->getSession()->check('sid')) {
			$this->getRequest()->getSession()->write('sid', Security::randomString());
		}

		$this->loadComponent('RequestHandler', [
			'enableBeforeRedirect' => false,
		]);
		$this->loadComponent('Flash');

		/*
		 * Enable the following component for recommended CakePHP security settings.
		 * see https://book.cakephp.org/3.0/en/controllers/components/security.html
		 */
//		$this->loadComponent('Security',[
//			//'blackHoleCallback' => 'blackhole'
//		]);

		$this->loadComponent('Setup.Setup');
		$this->loadComponent('Authentication.Authentication');
		$this->loadComponent('Crud.Crud', [
			'listeners' => [
				'Crud.Api',
				'Crud.ApiPagination',
				'Crud.Redirect',
				'Crud.Search',
				'Crud.Redirect',
				'Crud.ApiQueryLog',
				'View',
				'CrudView.ViewSearch'
			],
		]);

		$this->loadComponent('Search.Prg', [
			'actions' => ['index', 'lookup']
		]);

		$this->loadComponent('Recaptcha.Recaptcha', [
			'sitekey' => Configure::readOrFail('Recaptcha.site_key'),
			'secret' => Configure::readOrFail('Recaptcha.secret_key'),
			'lang' => 'pl'
		]);

		$this->_crudInit();

		if ($this->Crud->isActionMapped()) {
			/** @var \Crud\Action\BaseAction $action */
			$action = $this->Crud->action();
			if (!$action->getConfig('messages.success.element')) {
				$action->setConfig('messages.success.element', 'success');
			}
			if (!$action->getConfig('messages.error.element')) {
				$action->setConfig('messages.error.element', 'error');
			}
		}

		EventManager::instance()->on(
			'Model.afterSaveCommit',
			function (Event $event, $user, ArrayObject $options) {
				if ($user instanceof User) {
					if ($this->Authentication->getResult()->isValid()) {
						if ($user->id == $this->Authentication->getIdentityData('id')) {
							$this->renewUserSession($user);
						}
					}
				}
			}
		);
	}

	public function _crudInit()
	{
		$this->Crud->mapAction('add', 'Crud.Add');
		$this->Crud->mapAction('index', 'Crud.Index');
		$this->Crud->mapAction('view', 'Crud.View');
		$this->Crud->mapAction('edit', 'Crud.Edit');
	}


	public function beforeFilter(Event $event)
	{
		$this->Authentication->allowUnauthenticated($this->guestActions);

		if ($this->getRequest()->is('get')) {
			if ($this->Authentication->getResult()->isValid()) {
				/** @var FrozenTime $last_activity */
				$last_activity = $this->Authentication->getIdentityData('last_activity');
				if ($last_activity instanceof FrozenTime && $last_activity->addMinute()->isPast() || !$last_activity) {
					$this->renewUserSession();
				}
			}
		}

		/** @var \App\Model\Entity\User|\Authentication\Identity $user */
		$user = $this->Authentication->getIdentity();
		if ($user && !$user->verified && !in_array($this->getRequest()->getParam('action'), $this->guestActions)) {
			$this->Flash->warning('Wykonanie tej akcji wymaga zweryfikowanego konta.<br>Aby tego dokonać kliknij w link aktywacyjny który otrzymałeś na swój adres emailowy <b>' . h($user->email) . '</b>');
			return $this->redirect($this->referer('/', true));
		}

		if ($this->Crud->isActionMapped()) {
			return $this->beforeAction();
		}

		if ($this->getRequest()->getParam('action') == 'index') {
			$this->set('title', __($this->getName()));
		} else {
			$this->set('title', __(Inflector::singularize($this->getName())));
		}


		if ($this->getName() == 'Error') return;
		//$this->alternativeRouteRedirect();
		//$this->checkCaptcha();

		$this->_setup_page_id();
	}

	protected function beforeAction()
	{
		$action = $this->Crud->action();

		$action->setConfig('scaffold.form_submit_extra_buttons', false);
		$action->setConfig('scaffold.form_submit_extra_left_buttons', false);
		$action->setConfig('scaffold.form_enable_dirty_check', true);
		$action->setConfig('scaffold.form_submit_button_text', 'Zapisz');

		$this->Crud->on('beforePaginate', function (Event $event) use ($action) {
			$fields = $action->getConfig('scaffold.fields');
			if ($fields) {
				$current_whitelist = Hash::get($this->paginate, 'sortWhitelist', []);
				$defined_fields = array_keys(Hash::normalize($fields));
				$this->paginate['sortWhitelist'] = array_merge($current_whitelist, ['id'], $defined_fields);
			}
			$this->paginate['maxLimit'] = $this->getRequest()->is('csv_api') ? PHP_INT_MAX : 1000;
		});

		$this->Crud->on('beforeRender', function (Event $event) use ($action) {
			$actions = $action->getConfig('scaffold.actions');
			if ($actions === null) {
				$actions = [];
				foreach (array_keys($this->Crud->getConfig('actions')) as $actionName) {
					$actions[$actionName] = [];
					switch ($actionName) {
						case 'index':
							$actions[$actionName]['link_title'] = 'i:th-list ' . 'Lista';
							break;
						case 'add':
							$actions[$actionName]['link_title'] = 'i:plus ' . 'Nowy';
							break;
						case 'view':
							if ($this->getRequest()->getParam('id')) {
								$actions[$actionName]['link_title'] = 'i:eye-open ' . 'Zobacz';
							} else {
								$actions[$actionName]['link_title'] = 'i:eye-open';
								$actions[$actionName]['title'] = 'Podgląd';
							}
							break;
						case 'edit':
							if ($this->getRequest()->getParam('id')) {
								$actions[$actionName]['link_title'] = 'i:pencil ' . 'Edycja';
							} else {
								$actions[$actionName]['link_title'] = 'i:pencil';
								$actions[$actionName]['title'] = 'Edycja';
							}
							break;
						case 'delete':
							if ($this->getRequest()->getParam('id')) {
								$actions[$actionName]['link_title'] = 'i:remove-circle ' . 'Usuń';
							} else {
								$actions[$actionName]['link_title'] = 'i:remove-circle';
								$actions[$actionName]['title'] = 'Usuń';
							}
							$actions[$actionName]['class'] = 'btn btn-danger';
							$actions[$actionName]['method'] = 'DELETE';
							$actions[$actionName]['escape'] = false;
							break;
					}
				}
				$action->setConfig('scaffold.actions', $actions);
			}
		}, ['priority' => 5]);

		if (!$action->getConfig('scaffold.fields_blacklist')) {
			if ($this->getRequest()->getParam('action') == 'index') {
				$action->setConfig('scaffold.fields_blacklist', [
					'id', 'content'
				]);
			}
			$action->setConfig('scaffold.fields_blacklist', [
				'lft', 'rght'
			]);
			$action->setConfig('scaffold.fields_blacklist', [
				'parent_id', 'user_id', 'active'
			]);
		}
		if ($this->Crud->isActionMapped('deleteAll')) {
			$action->setConfig('scaffold.bulk_actions', [
				Router::url(['action' => 'deleteAll']) => __('Delete selected'),
			]);
		}
	}

//	public $components = [
//		'Auth'=>[
//			'authenticate'=>[
//				'Form'=>[
//					'userModel'=>'User',
//					'fields'=>[
//						'username'=>'email'
//					],
//					'contain'=>[
//						'ConnectedRepresentative'=>[
//							'fields'=>['id','name','status','modified']
//						],
//						'PostulatesUser',
//						'RepresentativesUser'
//					],
//					'scope'=>[
//						'User.status'=>'active'
//					],
//					'passwordHasher'=>'Blowfish'
//				]
//			],
//			'authorize'=>'Controller',
//			'loginAction'=>['prefix' => false,'controller' => 'Users','action' => 'login'],
//			'logoutRedirect'=>['prefix' => false,'controller' => 'Home','action' => 'main'],
//		],
//];


	private function _setup_page_id()
	{
		$url_elements = [
			$this->getRequest()->getParam('plugin') ? Inflector::underscore($this->getRequest()->getParam('plugin')) . '_plugin' : 'app',
			Inflector::underscore($this->getRequest()->getParam('controller')) . '_controller',
			Inflector::underscore($this->getRequest()->getParam('action')) . '_action'];
		if ($this->getRequest()->getParam('id')) {
			$url_elements[] = 'id' . $this->getRequest()->getParam('id');
		}
		$this->set('page_id', implode('-', $url_elements));
		$this->set('page_class', implode(' ', $url_elements));
	}


	/**
	 * @param \App\Model\Entity\User $user
	 * @return bool
	 */
	protected function renewUserSession($user = null)
	{
		if ($user instanceof User) {
			$this->Authentication->setIdentity($user);
			return true;
		}

		if ($this->Authentication->getResult()->isValid()) {
			/** @var \App\Model\Entity\User $user */
			$user = $this->Authentication->getIdentity()->getOriginalData();
		} else {
			return false;
		}

		$this->loadModel('Users');
		try {
			$user = $this->Users->get($user->id);
			$user->updateLastActivity();
			$this->Users->saveOrFail($user);
			$this->Authentication->setIdentity($user);
			return true;
		} catch (RecordNotFoundException $e) {
			$this->Authentication->logout();
			return false;
		}
	}

	function blackhole($type, SecurityException $exception)
	{
		if ($type == 'secure' && $this->getRequest()->is('get')) {
			return $this->redirect('https://' . env('SERVER_NAME') . $_SERVER['REQUEST_URI'], 301);
		}

		if (in_array($type, ['auth', 'csrf'])) {
			$this->Flash->error(__('This request has been blackholed due to a {0} Security Error.', Inflector::humanize($type)));
			return $this->redirect($this->getRequest()->referer());
		} else {
			throw $exception;
		}
	}

	public function invokeAction()
	{
		if ($this->getResponse()->getStatusCode() != 200) {
			return $this->getResponse();//jeśli zaliczytliśmy blackhole'a to nie wywołujemy akcji
		}
		return $this->invokeActionCrud();
	}

//	protected function vote_id_key() {
//		if($this->Authentication->getIdentityData('PostulatesUser.0')) {
//			$this->Session->write('Auth.User.PostulatesUser',Hash::combine($this->Authentication->getIdentityData('PostulatesUser'),'{n}.postulate_id','{n}'));
//		}
//		if($this->Authentication->getIdentityData('RepresentativesUser.0')) {
//			$this->Session->write('Auth.User.RepresentativesUser',Hash::combine($this->Authentication->getIdentityData('RepresentativesUser'),'{n}.representative_id','{n}'));
//		}
//	}

	protected function afterLogin()
	{
		//$this->vote_id_key();
		//$this->updatePid();
//		$this->loadModel('Users');
//		$vote_count = $this->Users->PostulatesUsers->find('count',[
//			'conditions'=>[
//				'PostulatesUser.user_id'=>$this->Authentication->getIdentityData('id'),
//				'PostulatesUser.value'=>1,
//			]
//		]);
//		$this->Session->write('Auth.User.postulate_vote_count',$vote_count);
	}

	public function isAuthorized($user)
	{
		if (!$this->getRequest()->getParam('prefix')) {
			return true;
		}
		if (Hash::get($user, 'admin')) {
			return true;
		}
		return false;
	}

//	protected function updatePid() {
//		$pid = Session::read('pid');
//		$upid = $this->Authentication->getIdentityData('pid')?:$this->Authentication->getIdentityData('id');
//		if(!$upid) return;
//		if($pid) {
//			if($upid!=$pid) {
//				$this->loadModel('User');
//				if(!$this->User->updateAll(['User.pid'=>$upid],['OR'=>['User.pid'=>$pid,'User.id'=>$pid]])) {
//					throw new InternalErrorException('pid save error');
//				}
//			}
//		}
//		Session::write('pid',$upid);
//	}
}
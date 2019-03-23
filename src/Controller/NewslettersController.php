<?php
namespace App\Controller;

class NewslettersController extends AppController
{
	public $guestActions = [];

	/**
	 * index method
	 * @param array $conditions
	 * @return Response
	 */
	private function _index($conditions = [])
	{
		$conditions = array_merge($this->Search->getSearchConditions(), $conditions);
		$options = [
			'conditions' => $conditions,
		];
		if (!empty($this->getRequest()->getParam('all')) && ($this->getRequest()->is('ajax') || $this->getRequest()->getParam('requested'))) {
			$newsletters = $this->Newsletters->find('all', $options);
			$this->set('all', 1);
		} else {
			$this->paginate = array_merge($this->paginate, $options);
			$newsletters = $this->Paginator->paginate();
			$this->set('all', 0);
		}

		$this->set('newsletters', $newsletters);
		return null;
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return Response
	 */
	private function _view($id = null)
	{
		if (!$this->Newsletters->exists($id)) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		$options = [
			'conditions' => ['Newsletter.' . $this->Newsletters->primaryKey => $id],
		];
		$newsletter = $this->Newsletters->find('first', $options);
		$this->set('newsletter', $newsletter);
		$this->set('title_for_layout', $newsletter['subject']);

		if (!empty($this->getRequest()->params['requested'])) {
			return $newsletter;
		}
		$status = $this->Newsletters->getEnumValues('status');
		$this->set(compact('status'));
		if (empty($this->getRequest()->params['ext']) && $newsletter['status'] == 'queted') {
			$this->response->header("refresh:1");
		}
		return null;
	}

	/**
	 * search method
	 * @return void
	 */
	private function _search()
	{
		$users = $this->Newsletters->User->find('list');
		$status = $this->Newsletters->getEnumValues('status');
		$this->set(compact('users', 'status'));
	}

	function admin_send_test_email($id)
	{
		$this->getRequest()->allowMethod('post');
		if (!$this->Newsletters->exists($id)) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		$newsletter = $this->Newsletters->read(null, $id);
		$cakemail = new Email('default');
		$cakemail->to($this->Authentication->getIdentityData('email'));
		$cakemail->subject($newsletter['subject']);
		$cakemail->emailFormat('html');
		if ($cakemail->send($newsletter['txt'])) {
			$this->Message->flash(__('Wysłano mail testowy'), ['action' => 'index'], 'good');
		} else {
			$this->Message->flash(__('Problem z wysyłaniem maila'), ['action' => 'index'], 'bad');
		}
	}

	function admin_mass_assign_all_users($id)
	{
		$this->getRequest()->allowMethod('post');
		if (!$this->Newsletters->exists($id)) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		$result = $this->Newsletters->query("INSERT INTO newsletters_users (newsletter_id,user_id) SELECT " . intval($id) . ",id FROM user_users WHERE email IS NOT NULL AND status='active'");
		$this->redirect(['action' => 'index']);
	}

	private function _emailsToUserIds($emails)
	{
		$emails = Hash::filter(explode("\r\n", trim($emails)));
		$user_ids = $this->Newsletters->User->find('list', [
			'fields' => 'User.id',
			'conditions' => [
				'User.email' => array_unique($emails)
			]
		]);
		return array_keys($user_ids);
	}

	/**
	 * add method
	 * @param null $id
	 * @return void
	 */
	private function _add($id = null)
	{
		if ($this->getRequest()->is('post') || $this->getRequest()->is('put')) {
			/*$user_ids = $this->_emailsToUserIds($this->getRequest()->data('Newsletter.emails'));
			if(!empty($user_ids)) {
				$this->getRequest()->data['User']['User']=$user_ids;
			}*/
			$newsletter = $this->Newsletters->newEntity($this->getRequest()->data);
			if ($this->Newsletters->save($newsletter)) {
				$metadata = ['Newsletter' => [$this->Newsletters->primaryKey => $this->Newsletters->getLastInsertId()]];
				$this->Message->flash(__('The newsletter has been saved'), ['action' => 'index'], 'good', $metadata);
				return;
			} else {
				$this->Message->flash(__('The newsletter could not be saved. Please, try again.'), null, 'bad');
			}
		} elseif (!empty($id)) {
			$options = [
				'conditions' => ['Newsletter.' . $this->Newsletters->primaryKey => $id]
			];
			$newsletter = $this->Newsletters->find('first', $options);
			$this->getRequest()->data = $newsletter;
		}
		//$users = $this->Newsletters->User->find('list');
		$status = $this->Newsletters->getEnumValues('status');
		$this->set(compact('users', 'status'));
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	private function _edit($id = null)
	{
		if (!$this->Newsletters->exists($id)) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		if ($this->getRequest()->is('post') || $this->getRequest()->is('put')) {
			/*$user_ids = $this->_emailsToUserIds($this->getRequest()->data('Newsletter.emails'));
			if(!empty($user_ids)) {
				$this->getRequest()->data['User']['User']=$user_ids;
			}*/
			$this->Newsletters->id = $id;
			if ($metadata = $this->Newsletters->save($newsletter)) {
				$this->Message->flash(__('The newsletter has been saved'), $this->referer(['action' => 'index']), 'good');
				return;
			} else {
				$this->Message->flash(__('The newsletter could not be saved. Please, try again.'), null, 'bad');
			}
		} else {
			$options = [
				'conditions' => ['Newsletter.' . $this->Newsletters->primaryKey => $id],
				'contain' => [
					'User.email'
				],
			];
			$newsletter = $this->Newsletters->find('first', $options);
			$newsletter['emails'] = implode("\r\n", Hash::extract($newsletter, 'User.{n}.email'));
			$this->set('title_for_layout', $newsletter['subject']);
			$this->getRequest()->data = $newsletter;
		}
		$users = $this->Newsletters->User->find('list');
		$status = $this->Newsletters->getEnumValues('status');
		$this->set(compact('users', 'status'));
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @throws MethodNotAllowedException
	 * @param string $id
	 * @return void
	 */
	private function _delete($id = null)
	{
		$this->getRequest()->allowMethod(['post', 'delete']);
		if (empty($id) && !empty($this->getRequest()->data)) {
			if (empty($this->getRequest()->data['Newsletter'][0])) {
				$this->Message->flash(__('No newsletters selected'), $this->referer(['action' => 'index']), 'neutral');
			} elseif ($this->Newsletters->deleteAll(['Newsletter.' . $this->Newsletters->primaryKey => $this->getRequest()->data['Newsletter']])) {
				$this->Message->flash(__('Newsletters deleted'), ['action' => 'index'], 'good');
			} else {
				$this->Message->flash(__('Newsletters not deleted'), $this->referer(['action' => 'index']), 'bad');
			}
			return;
		}
		$this->Newsletters->id = $id;
		if (!$this->Newsletters->exists()) {
			throw new NotFoundException(__('Invalid newsletter'));
		}
		if ($this->Newsletters->delete($newsletter)) {
			$this->Message->flash(__('Newsletter deleted'), $this->referer(['action' => 'index']), 'good');
		} else {
			$this->Message->flash(__('Newsletter was not deleted'), $this->referer(['action' => 'index']), 'bad');
		}
	}

}
<?php
namespace App\Controller;

/**
 * @property \App\Model\Table\PostulatesTable $Postulates
 * @property \App\Model\Table\RepresentativesTable $Representatives
 * @property \App\Model\Table\UsersTable $Users
 */
class HomeController extends AppController
{
	public $modelClass = 'Users';
	public $guestActions = ['main', 'social_login', 'auth_callback'];

	public function main()
	{
		$postulates = $this->loadModel('Postulates')->find('all', [
			'conditions' => [
				'active' => true
			],
			//'order'=>'upvotes DESC',
			'limit' => 6,
		]);
		$representatives = $this->loadModel('Representatives')->find('all', [
			'conditions' => [
				'active' => true
			],
			//'order'=>'upvotes DESC',
			'limit' => 6,
			'contain' => [
				'Photo',
			],
		]);
		$users_count = $this->loadModel('Users')->find()->count();
		$this->set(compact('postulates', 'representatives', 'users_count'));
	}

//	public function socialLogin($provider) {
//		$this->storeLoginReferer();
//		$result = $this->ExtAuth->login($provider);
//		if ($result['success']) {
//			$this->redirect($result['redirectURL']);
//		} else {
//			$this->Flash->message($result['message']);
//			$this->redirect($this->Auth->loginAction);
//		}
//	}
//
//	public function authCallback($provider) {
//		$result = $this->ExtAuth->loginCallback($provider);
//		if ($result['success']) {
//			$this->__successfulExtAuth($result['profile'], $result['accessToken']);
//		} else {
//			$this->Flash->message($result['message']);
//			$this->redirect($this->Auth->loginAction);
//		}
//	}
//
//	private function __successfulExtAuth($incomingProfile, $accessToken) {
//		if(empty($incomingProfile['email']) || !filter_var($incomingProfile['email'],FILTER_VALIDATE_EMAIL)) {
//			return $this->Message->flash(__('{0} API odmówiło dostępu do twojego adresu email. Logowanie nie powiodło się',$incomingProfile['provider']),$this->Auth->redirectUrl(),'bad');
//		}
//		$incomingProfile['raw']=json_decode($incomingProfile['raw'],true)?:$incomingProfile['raw'];
//		$this->loadModel('User');
//		$data_to_save = $incomingProfile+array('metadata'=>array($incomingProfile['provider']=>$incomingProfile));
//		if(empty($data_to_save['username'])) {
//			$data_to_save['username'] =explode('@',$incomingProfile['email'])[0];
//		}
//		$data_to_save_register = $data_to_save+[
//				'register'=>true,
//				'created'=>date('Y-m-d H:i:s'),
//				'status'=>'active',
//				'avatar_from'=>$incomingProfile['provider'],
//				'password'=>generatePassword()
//			];
//		$this->User->Behaviors->disable('Status');
//		$user = $this->User->findByEmailhashAndEmaildomain(md5(strtolower($incomingProfile['email'])),getEmailDomain($incomingProfile['email']));
//		if(!$user) {
//			$this->User->create($data_to_save_register);
//		} else {
//			if($user['status']=='not_active') {
//				return $this->Message->flash(__('To konto jest nieaktywne. Nie można się na nie zalogować'),$this->Auth->redirectUrl(),'bad');
//			}
//			$this->User->create(false);
//			$this->User->id = $user['id'];
//			if($user['status']=='deleted'){
//				$this->User->set($data_to_save_register);
//			} else {
//				$this->User->set($data_to_save);
//			}
//		}
//		if($this->User->save()) {
//			if($this->renewUserSession($this->User->id)) {
//				return $this->redirect($this->Auth->redirectUrl());
//			} else {
//				$this->Message->flash(__d('user', 'Logowanie za pomocą {0}\'a nie powiodło się',$incomingProfile['provider']),$this->Auth->loginAction,'bad');
//			}
//		} else {
//			Log::critical(__('Failed to save profile {0} with due to following error {1}',json_encode($this->User->data),json_encode($this->User->validationErrors)));
//			throw new InternalErrorException('Failed to save profile');
//		}
//	}
} 
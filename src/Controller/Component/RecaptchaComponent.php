<?php
namespace App\Controller\Component;

use App\Network\Http\HttpSocket;
use Cake\Controller\Component;

use Cake\Core\Configure;

use Cake\Http\Client;
use Cake\Network\Exception\SocketException;


class RecaptchaComponent extends Component
{

	public function initialize(array $config)
	{
		parent::initialize($config);
		if ($this->getController()->components()->has('Security')) {
			//$this->getController()->Security->unlockedFields[]='g-recaptcha-response';
		}
	}

	public function checkCaptcha()
	{
		$request = $this->getController()->getRequest();
		if ($request->getParam('admin')) return true;
		if ($request->is('post') || $request->is('put')) {
			$recapcha = $request->getData('g-recaptcha-response');
			if (empty($recapcha)) {
				return false;
			}
			try {
				$HttpSocket = new Client();
				$result = $HttpSocket->get('https://www.google.com/recaptcha/api/siteverify', [
					'secret' => Configure::read('Recaptcha.secret_key'),
					'response' => $recapcha,
					'remoteip' => $request->clientIp()
				])->getJson();

				if (!is_array($result) || !array_key_exists('success', $result)) {
					return false;
				}
				return $result['success'];
			} catch (SocketException $e) {
				//problem z usługą recaptcha
				$this->getController()->Flash->set('Nie udało się zweryfikować Captchy');
				return false;
			}
		}
		return true;
	}
} 
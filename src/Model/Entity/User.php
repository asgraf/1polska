<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Entity;
use Cake\Utility\Security;
use Cake\Utility\Text;
use Tools\Mailer\Email;

/**
 * User Entity
 *
 * @property string $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $password
 * @property string|null $token
 * @property bool $verified
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime|null $last_activity
 * @property string|null $sid
 * @property string|null $metadata
 *
 * @property \App\Model\Entity\Postulate[] $postulates
 * @property \App\Model\Entity\Representative[] $representatives
 * @property \App\Model\Entity\Vote[] $votes
 * @property mixed $slug
 * @property \App\Model\Entity\Postulate[] $voted_postulates
 * @property \App\Model\Entity\Representative[] $voted_representatives
 */
class User extends Entity
{

	/**
	 * Fields that can be mass assigned using newEntity() or patchEntity().
	 *
	 * Note that when '*' is set to true, this allows all unspecified fields to
	 * be mass assigned. For security purposes, it is advised to set '*' to false
	 * (or remove it), and explicitly make individual fields accessible as needed.
	 *
	 * @var array
	 */
	protected $_accessible = [
		'name' => true,
		'email' => true,
		'password' => true,
		'token' => true,
		'verified' => true,
		'created' => true,
		'modified' => true,
		'last_activity' => true,
		'sid' => true,
		'metadata' => true,
		'postulates' => true,
		'representatives' => true,
		'votes' => true,
		'new_password' => true
	];

	/**
	 * Fields that are excluded from JSON versions of the entity.
	 *
	 * @var array
	 */
	protected $_hidden = [
		'password',
		'token',
		'new_password',
		'sid'
	];

	protected $_virtual = [
		'new_password'
	];

	protected function _setPassword($password)
	{
		return (new DefaultPasswordHasher())->hash($password);
	}

	protected function _getSlug()
	{
		if ($this->name) {
			return Text::slug(strtolower($this->name), '_');
		}
	}

	protected function _setNewPassword($password)
	{
		if ($password) {
			$this->password = $password;
		}
	}

	public function generateToken()
	{
		$this->token = Security::randomString();
	}

	public function deleteToken()
	{
		$this->token = null;
	}

	public function validateToken($token)
	{
		if ($this->token) {
			return $this->token == $token;
		} else {
			return false;
		}
	}

	private function sendEmail($template, $subject)
	{
		$email = new Email();
		$email
			->setViewVars(['user' => $this])
			->setTo($this->email)
			->setSubject($subject)
			->setEmailFormat('both')
			->viewBuilder()->setTemplate($template);
		$result = $email->send();
		if (!$result) {
			trigger_error($email->getError(), E_USER_WARNING);
		}
		return $result;
	}

	public function sendRegisterEmail()
	{
		$result = $this->sendEmail(
			'register',
			__('Witaj na {0}', env('HTTP_HOST'))
		);
		return $result;
	}

	public function sendForgotPasswordEmail()
	{
		if (!$this->token) {
			throw new InternalErrorException('Empty token');
		}
		$result = $this->sendEmail(
			'forgot_password',
			'Zmiana hasła'
		);

		return $result;
	}
}

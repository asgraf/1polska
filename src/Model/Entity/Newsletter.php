<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Tools\Mailer\Email;

/**
 * Newsletter Entity
 *
 * @property int $id
 * @property string $subject
 * @property string $txt
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User[] $users
 */
class Newsletter extends Entity
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
        'subject' => true,
        'txt' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'users' => true
    ];

	public function sendEmail($email_to)
	{
		$email = new Email();
		$email
			->setTo($email_to)
			->setSubject($this->subject)
			->setEmailFormat('html')
			->setViewVars(['content' => $this->txt])
			->viewBuilder()->setTemplate('default');

		$result = $email->send();
		if (!$result) {
			trigger_error($email->getError(), E_USER_WARNING);
		}
		return $result;
	}
}

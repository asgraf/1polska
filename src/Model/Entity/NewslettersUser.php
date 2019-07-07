<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NewslettersUser Entity
 *
 * @property int $id
 * @property int $newsletter_id
 * @property string $user_id
 * @property bool $sent
 *
 * @property \App\Model\Entity\Newsletter $newsletter
 * @property \App\Model\Entity\User $user
 */
class NewslettersUser extends Entity
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
        'newsletter_id' => true,
        'user_id' => true,
        'sent' => true,
        'newsletter' => true,
        'user' => true
    ];
}

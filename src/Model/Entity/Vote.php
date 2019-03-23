<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Vote Entity
 *
 * @property int $id
 * @property string $user_id
 * @property string $fk_model
 * @property string $fk_id
 * @property string $value
 * @property string|null $comment
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Postulate[] $postulates
 * @property \App\Model\Entity\Representative[] $representatives
 */
class Vote extends Entity
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
        'user_id' => true,
        'fk_model' => true,
        'fk_id' => true,
        'value' => true,
        'comment' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
    ];

	protected $_hidden = [
		'user_id',
		'fk_model',
		'fk_id',
	];
}

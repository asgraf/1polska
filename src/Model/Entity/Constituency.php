<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Constituency Entity
 *
 * @property int $id
 * @property string $name
 * @property mixed $display_name
 *
 * @property \App\Model\Entity\Postulate[] $postulates
 * @property \App\Model\Entity\Representative[] $representatives
 */
class Constituency extends Entity
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
		'postulates' => true,
		'representatives' => true
	];

	protected $_virtual = [
		'display_name'
	];

	protected $_hidden = [
		'display_name'
	];

	protected function _getDisplayName()
	{
		return 'Okręg nr ' . $this->id . ' - ' . $this->name;
	}
}

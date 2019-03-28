<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;

/**
 * Postulate Entity
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $user_id
 * @property string $name
 * @property string $description
 * @property string $content
 * @property int|null $constituency_id
 * @property bool $active
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $upvotes
 * @property int $downvotes
 *
 * @property \App\Model\Entity\Postulate $parent_postulate
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Constituency $constituency
 * @property \App\Model\Entity\Postulate[] $child_postulates
 * @property mixed $slug
 * @property \Tags\Model\Entity\Tagged[] $tagged
 * @property \Tags\Model\Entity\Tag[] $tags
 * @property \App\Model\Entity\Vote $my_vote
 * @property \App\Model\Entity\Vote[] $votes
 * @property int $tag_count
 */
class Postulate extends Entity
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
		'parent_id' => true,
		'user_id' => true,
		'name' => true,
		'description' => true,
		'content' => true,
		'constituency_id' => true,
		'active' => true,
		'created' => true,
		'modified' => true,
		'upvotes' => true,
		'downvotes' => true,
		'parent_postulate' => true,
		'user' => true,
		'constituency' => true,
		'child_postulates' => true,
		'my_vote' => true,
	];

	protected $_virtual = [
		'slug'
	];

	protected $_hidden = [
		'active',
		'tags',
		'constituency_id',
		'parent_id',
		'user_id',
		'Votes'
	];

	protected function _getSlug()
	{
		if ($this->name) {
			return Text::slug(strtolower($this->name), '_');
		}
	}

	public function updateVoteCount()
	{
		$this->upvotes = TableRegistry::getTableLocator()->get('Votes')->find()
			->where([
				'fk_model' => $this->getSource(),
				'fk_id' => $this->id,
				'value' => 1
			])
			->count();
		$this->downvotes = TableRegistry::getTableLocator()->get('Votes')->find()
			->where([
				'fk_model' => $this->getSource(),
				'fk_id' => $this->id,
				'value' => -1
			])
			->count();
	}
}

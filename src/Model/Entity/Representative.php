<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;

/**
 * Representative Entity
 *
 * @property int $id
 * @property string|null $user_id
 * @property string $firstname
 * @property string $lastname
 * @property string $description
 * @property int|null $constituency_id
 * @property string $content
 * @property bool $active
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $upvotes
 * @property int $downvotes
 * @property mixed $vote_rate
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Constituency $constituency
 * @property mixed $full_name
 * @property mixed $slug
 * @property \Tags\Model\Entity\Tagged[] $tagged
 * @property \Tags\Model\Entity\Tag[] $tags
 * @property \Filerepo\Model\Entity\Fileobject $photo
 * @property \App\Model\Entity\Vote $my_vote
 * @property \App\Model\Entity\Vote[] $votes
 * @property int $tag_count
 */
class Representative extends Entity
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
		'firstname' => true,
		'lastname' => true,
		'description' => true,
		'constituency_id' => true,
		'content' => true,
		'active' => true,
		'created' => true,
		'modified' => true,
		'upvotes' => true,
		'downvotes' => true,
		'user' => true,
		'constituency' => true,
		'photo' => true,
		'my_vote' => true,
	];

	protected $_virtual = [
		'full_name',
		'slug',
		'vote_rate'
	];

	protected $_hidden = [
		'active',
		'tags',
		'constituency_id',
		'user_id',
		'firstname',
		'lastname',
		'Votes'
	];

	protected function _getVoteRate()
	{
		if ($this->upvotes + $this->downvotes > 0) {
			return round($this->upvotes / ($this->upvotes + $this->downvotes) * 100);
		} else {
			return 0;
		}
	}

	protected function _getFullName()
	{
		return implode(' ', [$this->firstname, $this->lastname]) ?: null;
	}

	protected function _getSlug()
	{
		if ($this->full_name) {
			return Text::slug(strtolower($this->full_name), '_');
		}
	}

	public function getPhotoUrl()
	{
		$photo_url = null;

		if ($this->photo) {
			$photo_url = $this->photo->getUrl();
		}

		if (!$photo_url) {
			$photo_url = '/img/anonim.jpg';
		}

		return $photo_url;
	}

	public function getPhotoThumbUrl($width, $height = null, $crop = false)
	{
		$photo_url = null;

		if ($this->photo) {
			$photo_url = $this->photo->getThumbnail($width, $height, $crop)->getUrl();
		}

		if (!$photo_url) {
			$photo_url = '/img/anonim.jpg';
		}

		return $photo_url;
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

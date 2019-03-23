<?php
namespace App\View\Cell;

use App\Model\Entity\Vote;
use Cake\Datasource\EntityInterface;
use Cake\View\Cell;

/**
 * Votes cell
 *
 * @property \App\Model\Table\VotesTable $Votes
 */
class VotesCell extends Cell
{
	/**
	 * List of valid options that can be passed into this
	 * cell's constructor.
	 *
	 * @var array
	 */
	protected $_validCellOptions = [];

	/**
	 * Initialization logic run at the end of object construction.
	 *
	 * @return void
	 */
	public function initialize()
	{
	}

	public function display(EntityInterface $entity)
	{
		$this->loadModel('Votes');

		$my_vote = null;

		/** @var \App\Model\Entity\User|null $user */
		$user = $this->request->getAttribute('identity');
		if ($user) {
			$my_vote = $this->Votes->find()
				->where([
					'fk_model' => $entity->getSource(),
					'fk_id' => $entity->id,
					'user_id' => $user->id
				])
				->first();
		}

		/** @var \Cake\ORM\ResultSet $grouped_votes */
		$grouped_votes = $this->Votes->find()
			->select([
				'item_count' => 'COUNT(id)',
				'comment',
				'value'
			])
			->where([
				'fk_model' => $entity->getSource(),
				'fk_id' => $entity->id,
				'value !=' => '0'
			])
			->group([
				'value',
				'comment'
			])
			->order([
				'COUNT(id)' => 'desc'
			])
			->map(function (Vote $vote) use ($my_vote) {
				$vote->set('my_vote', (
					$my_vote instanceof Vote &&
					$my_vote->value == $vote->value &&
					$my_vote->comment == $vote->comment
				));
				return $vote;
			});

		$grouped_upvotes = $grouped_votes->filter(function (Vote $vote) {
			return $vote->value == '1';
		});

		$grouped_downvotes = $grouped_votes->filter(function (Vote $vote) {
			return $vote->value == '-1';
		});

		$upvote_count = $grouped_upvotes->sumOf('item_count');
		$downvote_count = $grouped_downvotes->sumOf('item_count');

		$this->set(compact('entity', 'my_vote', 'upvote_count', 'downvote_count', 'grouped_upvotes', 'grouped_downvotes'));
	}
}

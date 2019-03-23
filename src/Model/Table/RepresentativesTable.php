<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Validation\Validator;

/**
 * Representatives Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ConstituenciesTable|\Cake\ORM\Association\BelongsTo $Constituencies
 *
 * @method \App\Model\Entity\Representative get($primaryKey, $options = [])
 * @method \App\Model\Entity\Representative newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Representative[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Representative|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Representative saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Representative patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Representative[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Representative findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \Tags\Model\Table\TaggedTable|\Cake\ORM\Association\HasMany $Tagged
 * @property \Tags\Model\Table\TagsTable|\Cake\ORM\Association\BelongsToMany $Tags
 * @property \Filerepo\Model\Table\FileobjectsTable|\Cake\ORM\Association\HasOne $Photo
 * @mixin \Search\Model\Behavior\SearchBehavior
 * @mixin \Filerepo\Model\Behavior\AttachmentBehavior
 * @mixin \Tags\Model\Behavior\TagBehavior
 * @property \App\Model\Table\VotesTable|\Cake\ORM\Association\HasOne $MyVote
 * @property \App\Model\Table\VotesTable|\Cake\ORM\Association\HasMany $Votes
 */
class RepresentativesTable extends Table
{

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config)
	{
		parent::initialize($config);

		$this->setTable('representatives');
		$this->setDisplayField('full_name');
		$this->setPrimaryKey('id');

		$this->addBehavior('Timestamp');
		$this->addBehavior('Search.Search');
		$this->addBehavior('Filerepo.Attachment');
		$this->addBehavior('Tags.Tag');

		$this->addAttachmentField('photo');

		$this->belongsTo('Users', [
			'foreignKey' => 'user_id'
		]);
		$this->belongsTo('Constituencies', [
			'foreignKey' => 'constituency_id'
		]);

		$this->hasMany('Votes', [
			'foreignKey' => 'fk_id',
			'conditions' => [
				'Votes.fk_model' => 'Representatives',
			]
		]);

		$user_id = null;
		$request = Router::getRequest();
		if ($request) {
			$identity = $request->getAttribute('identity');
			if ($identity) {
				$user_id = $identity->id;
			}
		}
		$this->hasOne('MyVote', [
			'className' => 'Votes',
			'foreignKey' => 'fk_id',
			'conditions' => [
				'MyVote.fk_model' => 'Representatives',
				'MyVote.user_id' => $user_id,
			]
		]);
	}

	/**
	 * Default validation rules.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function validationDefault(Validator $validator)
	{
		$validator
			->allowEmptyString('id', 'create');

		$validator
			->scalar('firstname')
			->maxLength('firstname', 40)
			->requirePresence('firstname', 'create')
			->allowEmptyString('firstname', false);

		$validator
			->scalar('lastname')
			->maxLength('lastname', 40)
			->requirePresence('lastname', 'create')
			->allowEmptyString('lastname', false);

		$validator
			->scalar('description')
			->maxLength('description', 255)
			->requirePresence('description', 'create')
			->allowEmptyString('description', false);

		$validator
			->scalar('content')
			->requirePresence('content', 'create')
			->allowEmptyString('content', false);

		$validator
			->boolean('active')
			->allowEmptyString('active', false);

		$validator
			->allowEmptyString('upvotes', false);

		$validator
			->allowEmptyString('downvotes', false);

		return $validator;
	}

	/**
	 * Returns a rules checker object that will be used for validating
	 * application integrity.
	 *
	 * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
	 * @return \Cake\ORM\RulesChecker
	 */
	public function buildRules(RulesChecker $rules)
	{
		$rules->add($rules->existsIn(['user_id'], 'Users'));
		$rules->add($rules->existsIn(['constituency_id'], 'Constituencies'));

		return $rules;
	}
}

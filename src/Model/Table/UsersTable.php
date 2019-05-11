<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\PostulatesTable|\Cake\ORM\Association\HasMany $Postulates
 * @property \App\Model\Table\RepresentativesTable|\Cake\ORM\Association\HasOne $Representatives
 * @property \App\Model\Table\VotesTable|\Cake\ORM\Association\HasMany $Votes
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Search\Model\Behavior\SearchBehavior
 * @property \App\Model\Table\PostulatesTable|\Cake\ORM\Association\BelongsToMany $VotedPostulates
 * @property \App\Model\Table\RepresentativesTable|\Cake\ORM\Association\BelongsToMany $VotedRepresentatives
 */
class UsersTable extends Table
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

		$this->setTable('users');
		$this->setDisplayField('email');
		$this->setPrimaryKey('id');

		$this->addBehavior('Timestamp');
		$this->addBehavior('Search.Search');

		$this->hasMany('Postulates', [
			'foreignKey' => 'user_id'
		]);
		$this->hasOne('Representatives', [
			'foreignKey' => 'user_id',
			'dependent' => true,
		]);
		$this->hasMany('Votes', [
			'foreignKey' => 'user_id',
			'dependent' => true,
		]);

		$this->belongsToMany('VotedPostulates', [
			'className' => 'Postulates',
			'through' => 'Votes',
			'targetForeignKey' => 'fk_id',
			'conditions' => [
				'Votes.fk_model' => 'Postulates',
				'Votes.value !=' => '0',
			]
		]);

		$this->belongsToMany('VotedRepresentatives', [
			'className' => 'Representatives',
			'through' => 'Votes',
			'targetForeignKey' => 'fk_id',
			'conditions' => [
				'Votes.fk_model' => 'Representatives',
				'Votes.value !=' => '0',
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
			->uuid('id')
			->allowEmptyString('id', 'create');

		$validator
			->scalar('name')
			->maxLength('name', 50)
			->allowEmptyString('name');

		$validator
			->email('email')
			->allowEmptyString('email')
			->add('email', 'unique', [
				'rule' => 'validateUnique',
				'provider' => 'table',
				'message' => 'Konto powiązane z podanym adresem email już istnieje'
			]);

		$validator
			->minLength('password', 8)
			->notEmpty('password');

		$validator
			->minLength('password_new', 8);

		$validator
			->scalar('token')
			->maxLength('token', 64)
			->allowEmptyString('token')
			->add('token', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

		$validator
			->boolean('verified')
			->requirePresence('verified', 'create')
			->allowEmptyString('verified', false);

		$validator
			->dateTime('last_activity')
			->allowEmptyDateTime('last_activity');

		$validator
			->scalar('sid')
			->maxLength('sid', 64)
			->allowEmptyString('sid');

		$validator
			->scalar('metadata')
			->maxLength('metadata', 4294967295)
			->allowEmptyString('metadata');

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
		$rules->add($rules->isUnique(['email']));
		$rules->add($rules->isUnique(['token']));

		return $rules;
	}

	public function beforeSave(Event $event, User $user, ArrayObject $options)
	{
		$request = Router::getRequest();
		if ($request) {
			if ($user->sid != $request->getSession()->read('sid')) {
				if($user->sid) {
					$this->updateAll(
						[
							'sid' => $request->getSession()->read('sid')
						],
						[
							'sid' => $user->sid
						]
					);
				}
				$user->sid = $request->getSession()->read('sid');
			}

			$ip = filter_var($request->clientIp(), FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
			if ($ip) {
				$user->last_ip = $ip;
			}
		}
	}
}

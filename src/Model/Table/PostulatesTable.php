<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Validation\Validator;

/**
 * Postulates Model
 *
 * @property \App\Model\Table\PostulatesTable|\Cake\ORM\Association\BelongsTo $ParentPostulates
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ConstituenciesTable|\Cake\ORM\Association\BelongsTo $Constituencies
 * @property \App\Model\Table\PostulatesTable|\Cake\ORM\Association\HasMany $ChildPostulates
 *
 * @method \App\Model\Entity\Postulate get($primaryKey, $options = [])
 * @method \App\Model\Entity\Postulate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Postulate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Postulate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Postulate saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Postulate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Postulate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Postulate findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \Tags\Model\Table\TaggedTable|\Cake\ORM\Association\HasMany $Tagged
 * @property \Tags\Model\Table\TagsTable|\Cake\ORM\Association\BelongsToMany $Tags
 * @mixin \Search\Model\Behavior\SearchBehavior
 * @mixin \Tags\Model\Behavior\TagBehavior
 * @property \App\Model\Table\VotesTable|\Cake\ORM\Association\HasOne $MyVote
 * @property \App\Model\Table\VotesTable|\Cake\ORM\Association\HasMany $Votes
 */
class PostulatesTable extends Table
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

        $this->setTable('postulates');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
		$this->addBehavior('Search.Search');
		$this->addBehavior('Tags.Tag', ['taggedCounter' => false]);

        $this->belongsTo('ParentPostulates', [
            'className' => 'Postulates',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('Constituencies', [
            'foreignKey' => 'constituency_id'
        ]);
        $this->hasMany('ChildPostulates', [
            'className' => 'Postulates',
            'foreignKey' => 'parent_id'
        ]);

	    $this->hasMany('Votes', [
		    'foreignKey' => 'fk_id',
		    'conditions' => [
			    'Votes.fk_model' => 'Postulates',
		    ]
	    ]);

	    $user_id = null;
	    $request = Router::getRequest();
	    if($request) {
		    $identity = $request->getAttribute('identity');
		    if($identity) {
			    $user_id = $identity->id;
		    }
	    }
	    $this->hasOne('MyVote', [
	    	'className'=>'Votes',
		    'foreignKey' => 'fk_id',
		    'conditions' => [
			    'MyVote.fk_model' => 'Postulates',
			    'MyVote.user_id' => $user_id,
		    ]
	    ]);

	    $this->searchManager()
		    ->like('name', [
			    'before' => true,
			    'after' => true,
			    'form' => [
				    'label' => 'Tytuł',
				    'type' => 'search',
				    'class' => 'no-selectize',
			    ]
		    ])
		    ->value('constituency_id', [
			    'form' => [
				    'label' => 'Okręg',
				    'type' => 'select',
				    'options' => $this->Constituencies->find('list'),
				    'empty' => true
			    ]
		    ])
		    ->callback('tags', [
			    'callback' => function (Query $query, array $args) {
				    if (is_array($args['tags'])) {
					    foreach ($args['tags'] as $tag) {
						    $query->find('tagged', ['tag' => $tag]);
					    }
				    }
			    },
			    'form' => [
				    'label' => 'Tagi',
				    'type' => 'select',
				    'options' => $this->Tags->find('list', [
					    'keyField' => 'label',
					    'valueField' => 'slug',
				    ]),
				    'multiple' => true,
				    'empty' => true,
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
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false)
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

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
            ->requirePresence('active', 'create')
            ->allowEmptyString('active', false);

        $validator
            ->requirePresence('upvotes', 'create')
            ->allowEmptyString('upvotes', false);

        $validator
            ->requirePresence('downvotes', 'create')
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
        $rules->add($rules->isUnique(['name']));
        $rules->add($rules->existsIn(['parent_id'], 'ParentPostulates'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['constituency_id'], 'Constituencies'));

        return $rules;
    }
}

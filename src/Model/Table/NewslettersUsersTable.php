<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NewslettersUsers Model
 *
 * @property \App\Model\Table\NewslettersTable|\Cake\ORM\Association\BelongsTo $Newsletters
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\NewslettersUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\NewslettersUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\NewslettersUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NewslettersUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NewslettersUser saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NewslettersUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\NewslettersUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\NewslettersUser findOrCreate($search, callable $callback = null, $options = [])
 */
class NewslettersUsersTable extends Table
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

        $this->setTable('newsletters_users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Newsletters', [
            'foreignKey' => 'newsletter_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
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
            ->boolean('sent')
            ->allowEmptyString('sent', false);

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
        $rules->add($rules->existsIn(['newsletter_id'], 'Newsletters'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}

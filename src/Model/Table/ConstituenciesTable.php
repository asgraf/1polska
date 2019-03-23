<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Constituencies Model
 *
 * @property \App\Model\Table\PostulatesTable|\Cake\ORM\Association\HasMany $Postulates
 * @property \App\Model\Table\RepresentativesTable|\Cake\ORM\Association\HasMany $Representatives
 *
 * @method \App\Model\Entity\Constituency get($primaryKey, $options = [])
 * @method \App\Model\Entity\Constituency newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Constituency[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Constituency|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Constituency saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Constituency patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Constituency[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Constituency findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Search\Model\Behavior\SearchBehavior
 */
class ConstituenciesTable extends Table
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

        $this->setTable('constituencies');
        $this->setDisplayField('display_name');
        $this->setPrimaryKey('id');

        $this->hasMany('Postulates', [
            'foreignKey' => 'constituency_id'
        ]);
        $this->hasMany('Representatives', [
            'foreignKey' => 'constituency_id'
        ]);
	    $this->addBehavior('Search.Search');
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
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        return $validator;
    }
}

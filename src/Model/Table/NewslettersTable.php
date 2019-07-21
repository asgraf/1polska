<?php
namespace App\Model\Table;

use App\Model\Entity\Newsletter;
use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Newsletters Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsToMany $Users
 *
 * @method \App\Model\Entity\Newsletter get($primaryKey, $options = [])
 * @method \App\Model\Entity\Newsletter newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Newsletter[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Newsletter|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Newsletter saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Newsletter patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Newsletter[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Newsletter findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \App\Model\Table\NewslettersUsersTable|\Cake\ORM\Association\HasMany $NewslettersUsers
 */
class NewslettersTable extends Table
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

        $this->setTable('newsletters');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Users', [
            'foreignKey' => 'newsletter_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'newsletters_users'
        ]);

	    $this->hasMany('NewslettersUsers', [
		    'foreignKey' => 'newsletter_id',
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
            ->scalar('subject')
            ->maxLength('subject', 255)
            ->requirePresence('subject', 'create')
            ->allowEmptyString('subject', false);

        $validator
            ->scalar('txt')
            ->maxLength('txt', 4294967295)
            ->requirePresence('txt', 'create')
            ->allowEmptyString('txt', false);

        $validator
            ->scalar('status')
            ->allowEmptyString('status', false);

        return $validator;
    }

	public function sendEmailsToAttachedUsers(Newsletter $newsletter)
	{
		$newsletters_users = $this->NewslettersUsers->find()
			->where([
				'newsletter_id' => $newsletter->id,
				'sent' => 0,
				'Users.verified' => 1,
			])
			->contain([
				'Users'
			]);

		if (Configure::read('debug') && Configure::readOrFail('Config.live')) {
			$adminEmail = Configure::readOrFail('Config.adminEmail');
			$newsletters_users->where([
				'Users.email' => $adminEmail
			]);

			if ($newsletters_users->isEmpty()) {
				return true;
			}
		}

		$errors = 0;
		foreach ($newsletters_users as $newslettersUser) {
			if ($newsletter->sendEmail($newslettersUser->user->email)) {
				$newslettersUser->sent = true;
				$this->NewslettersUsers->saveOrFail($newslettersUser);
				sleep(Configure::readOrFail('email_sleep'));
			} else {
				$errors++;
			}
		}

		if (!$errors) {
			if (Configure::read('debug') && Configure::readOrFail('Config.live')) {
				return true;
			} else {
				$newsletter->status = 'sent';
				$this->saveOrFail($newsletter);
			}
		}
		return !$errors;
	}
}

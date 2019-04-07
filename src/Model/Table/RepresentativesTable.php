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

		$this->searchManager()
			->like('firstname', [
				'before' => true,
				'after' => true,
				'form' => [
					'label' => 'Imię',
					'type' => 'search',
					'class' => 'no-selectize',
				]
			])
			->like('lastname', [
				'before' => true,
				'after' => true,
				'form' => [
					'label' => 'Nazwisko',
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

		$validator->add('photo', 'custom', [
			'rule' => function ($photo, $context) {
				$suported_formats = getSupportedImageFormats();

				if (
					isset($photo['tmp_name']) &&
					is_uploaded_file($photo['tmp_name']) &&
					isset($photo['type'])
				) {
					$content_type_exploded = explode('/', $photo['type']);
					if ($content_type_exploded[0] != 'image') {
						return 'Załączony plik nie jest obrazkiem';
					}
					$image = false;
					switch ($photo['type']) {
						case 'image/jpeg':
							if (function_exists('imagecreatefromjpeg')) {
								$image = imagecreatefromjpeg($photo['tmp_name']);
							}
							break;
						case 'image/png':
							if (function_exists('imagecreatefrompng')) {
								$image = imagecreatefrompng($photo['tmp_name']);
							}
							break;
						case 'image/bmp':
						case 'image/x-windows-bmp':
							if (function_exists('imagecreatefrombmp')) {
								$image = imagecreatefrombmp($photo['tmp_name']);
							}
							break;
						case 'image/webp':
							if (function_exists('imagecreatefromwebp')) {
								$image = imagecreatefromwebp($photo['tmp_name']);
							}
							break;
						case 'image/gif':
							if (function_exists('imagecreatefromgif')) {
								$image = imagecreatefromgif($photo['tmp_name']);
							}
							break;
					}
					if ($image) {
						$w = imagesx($image);
						$h = imagesy($image);
						if (!$w || !$h) {
							return 'Nieprawidłowy lub uszkodzony obrazek';
						}
						$maxSize = 320;
						if ($w > $maxSize || $h > $maxSize) {
							return 'Maksymalny rozmiar obrazka to ' . $maxSize . 'x' . $maxSize . ' pixeli. Przesłany obrazek ma wymiary ' . $w . 'x' . $h . ' pixeli';
						}
						return true;
					} else {
						return 'Przesłane zdjęcie jest uszkodzone lub w nie obsługiwanym formacje. Obsługiwane formaty to: ' . implode(', ', array_unique($suported_formats));
					}
				}
				return false;
			},
		]);

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

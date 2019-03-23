<?php
namespace App\Controller;

use Cake\Event\Event;

/**
 * @property \Crud\Controller\Component\CrudComponent $Crud
 * @property \Tags\Model\Table\TaggedTable $Tagged
 * @property \App\Model\Table\RepresentativesTable $Representatives
 * @property \App\Model\Table\PostulatesTable $Postulates
 */
class TagsController extends AppController
{
	public $modelClass = 'Tags.Tagged';

	public $guestActions = ['index', 'view'];

	public function _crudInit()
	{
	}

	public function index()
	{
		$tagged = $this->Tagged->find('cloud')->toArray();
		$this->set('tagged', $tagged);
		$this->set('_serialize', 'tagged');
	}

	public function view()
	{

		$this->loadModel('Representatives');
		$this->loadModel('Postulates');

		$slug = $this->getRequest()->getParam('slug');

		$tag = $this->Tagged->Tags->find()
			->where([
				'slug' => $slug
			])
			->firstOrFail();

		$representatives = $this->Representatives->find('tagged', ['tag' => $slug])
			->where([
				'active' => true
			])
			->contain('Photo');

		$postulates = $this->Postulates->find('tagged', ['tag' => $slug])
			->where([
				'active' => true
			]);


		$this->set('tag', $tag);
		$this->set('representatives', $representatives);
		$this->set('postulates', $postulates);
	}
}

<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Constituencies Controller
 *
 * @property \App\Model\Table\ConstituenciesTable $Constituencies
 */
class ConstituenciesController extends AppController
{
	public $guestActions = ['index','view'];
	public function _crudInit()
	{
		$this->Crud->mapAction('index', 'Crud.Index');
		$this->Crud->mapAction('view', 'Crud.View');
	}

	public function view() {

		$this->Crud->on('beforeFind', function (Event $event) {
			/** @var \Cake\ORM\Query $query */
			$query = $event->getSubject()->query;

			$query->contain([
				'Postulates' => [
					'conditions' => [
						'Postulates.active' => true
					],
				],
				'Representatives' => [
					'conditions' => [
						'Representatives.active' => true
					],
					'Photo'
				],
			]);
		});
		return $this->Crud->execute();
	}
}

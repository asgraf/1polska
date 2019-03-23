<?php
namespace App\Listener;

use Cake\Event\Event;

class ViewListener extends \CrudView\Listener\ViewListener
{
	public function beforeFind(Event $event)
	{
	}

	public function beforePaginate(Event $event)
	{
	}

	protected function _getPageTitle() {
		if(isset($this->_controller()->viewVars['title'])) {
			return $this->_controller()->viewVars['title'];
		} else {
			parent::_getPageTitle();
		}
	}
}

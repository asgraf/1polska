<?php

namespace App\Routing\Route;

use Cake\Utility\Inflector;

class EntityRoute extends \Cake\Routing\Route\EntityRoute
{
	public function parse($url, $method = '')
	{
		$params = parent::parse($url, $method);
		if (!$params) {
			return false;
		}
		if (!empty($params['controller'])) {
			$params['controller'] = Inflector::camelize($params['controller'], '-');
		}
		if (!empty($params['plugin'])) {
			$params['plugin'] = $this->_camelizePlugin($params['plugin']);
		}

		if (!empty($params['action'])) {
			$params['action'] = Inflector::variable(str_replace(
				'-',
				'_',
				$params['action']
			));
		}

		return $params;
	}

	protected function _camelizePlugin($plugin)
	{
		$plugin = str_replace('-', '_', $plugin);
		if (strpos($plugin, '/') === false) {
			return Inflector::camelize($plugin);
		}
		list($vendor, $plugin) = explode('/', $plugin, 2);

		return Inflector::camelize($vendor) . '/' . Inflector::camelize($plugin);
	}
}
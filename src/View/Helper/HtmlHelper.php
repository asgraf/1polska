<?php
namespace App\View\Helper;


use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

/**
 * Html helper
 *
 * @property \Cake\View\Helper\UrlHelper $Url
 */
class HtmlHelper extends \Bootstrap\View\Helper\HtmlHelper
{
	public function listLink($title, $url = null, array $link_options = [], $tagOptions = [], $tagName = 'li', $tagActiveClass = 'active')
	{
		if ($url === null) {
			$url = $title;
		}
		$url = parseUrl($url) ?: $url;
		if (is_array($title)) {
			$title = Router::url($url);
		}

		$tagOptions['class'] = (array)Hash::get($tagOptions, 'class', []);

		if ($this->isHere($url)) {
			$tagOptions['class'][] = $tagActiveClass;
		}
		return $this->tag($tagName, $this->link($title, $url, $link_options), $tagOptions);
	}

	public function isHere($url = null, $strict = true, $related = false)
	{
		if (!is_array($url)) {
			$url = parseUrl($url);
		}
		if (!is_array($url)) {
			return false;
		}
		$request = $this->getView()->getRequest();
		if (isset($url['language']) && $url['language'] != $request->getParam('language', false)) return false;
		if (isset($url['plugin']) && $url['plugin'] != $request->getParam('plugin', false)) return false;
		if (isset($url['prefix']) && $url['prefix'] != $request->getParam('prefix', false)) return false;
		if (isset($url['controller'])) {
			if (!$strict && $related) {
				if ($url['controller'] == $request->getParam('controller')) return true;
				$property = Inflector::singularize(Inflector::underscore($url['controller']));

				/** @var \Cake\ORM\Table $Table */
				$Table = TableRegistry::getTableLocator()->get($request->getParam('controller'));
				if (get_class($Table) !== 'Cake\ORM\Table') {
					$assoc = $Table->associations()->getByProperty($property);
					if ($assoc) {
						if (isset($url['action']) && $url['action'] == 'index' && $request->getQuery($assoc->getForeignKey())) {
							return true;
						}
						if ($request->getParam('id')) {
							$item = $this->getView()->get($this->getView()->get('viewVar'));
							if (isset($url['?'][$assoc->getForeignKey()]) && $url['?'][$assoc->getForeignKey()] == $item->get($assoc->getForeignKey())) {
								return true;
							}
							if (isset($url[$assoc->getForeignKey()]) && $url[$assoc->getForeignKey()] == $item->get($assoc->getForeignKey())) {
								return true;
							}
						}
					}
				} else {
					if ($request->getQuery($property . '_id') || $request->getQuery('?.' . $property . '_id')) return true;

				}
			}
			if ($url['controller'] != $request->getParam('controller')) return false;
		}
		if (isset($url['action']) && $url['action'] == 'index' && !$strict) return true;
		if (isset($url['action']) && $url['action'] != $request->getParam('action', 'index')) return false;
		if (isset($url['id']) && $url['id'] != $request->getParam('id')) return false;
		foreach ($request->getParam('pass') as $key => $val) {
			if (!array_key_exists($key, $url)) break;
			if ($url[$key] != $val) {
				return false;
			}
		}
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function link($title, $url = null, array $options = [])
	{
		try {
			if ($url === null) {
				$url = $title;
			}

			$url = parseUrl($url) ?: $url;
			if (is_array($title)) {
				$title = Router::url($url);
			}

			if (!isset($options['not_here'])) {
				if ($this->isHere($url, true)) {//here tylko jeśli ten sam url
					$class = (array)Hash::get($options, 'class', []);
					$options['class'] = array_merge((array)$class, ['here']);
				}
				if ($this->isHere($url, false)) {//here2 tylko jeśli ten sam url lub jeśli link do akcji index tego samego kontrolera
					$class = (array)Hash::get($options, 'class', []);
					$options['class'] = array_merge((array)$class, ['here2']);
				}
				if ($this->isHere($url, false, true)) {
					$class = (array)Hash::get($options, 'class', []);//here3 jeżeli modele linków są powiązane relacją
					$options['class'] = array_merge((array)$class, ['here3']);
				}
			}
			unset($options['not_here']);
			if (isset($options['target']) && $options['target'] == '_blank') {
				$options['rel'] = array_merge((array)Hash::get($options, 'rel', []), ['noopener', 'noreferrer']);
			}
			return parent::link($title, $url, $options);
		} catch (\Cake\Routing\Exception\MissingRouteException $e) {
			trigger_error($e->getMessage(), E_USER_WARNING);
		}
	}

	public function toogleListLink($title, $url1, $url2, array $link_options = [], $tagOptions = [], $tagName = 'li', $tagActiveClass = 'active')
	{
		$url1 = parseUrl($url1) ?: $url1;
		$url2 = parseUrl($url2) ?: $url2;

		$tagOptions['class'] = (array)Hash::get($tagOptions, 'class', []);

		if ($this->isHere($url1) || $this->isHere($url2)) {
			$tagOptions['class'][] = $tagActiveClass;
		}
		$url = $this->isHere($url1) ? $url2 : $url1;
		return $this->tag($tagName, $this->link($title, $url, $link_options), $tagOptions);
	}
}

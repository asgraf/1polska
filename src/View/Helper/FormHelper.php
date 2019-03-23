<?php
namespace App\View\Helper;

use Cake\View\Form\EntityContext;

/**
 * @property \Cake\View\Helper\UrlHelper $Url
 * @property \App\View\Helper\HtmlHelper $Html
 */
class FormHelper extends \Bootstrap\View\Helper\FormHelper
{
	public function custom($fieldName, array $options = [])
	{
		$context = $this->_getContext();
		$entity = null;
		$value = $this->_getContext()->val($fieldName);
		if ($context instanceof EntityContext) {
			$entity = $context->entity();
			$value = $entity->get($fieldName);
		}

		if (isset($options['formatter'])) {
			if ($options['formatter'] === 'element') {
				return $this->_View->element(
					$options['element'],
					[
						'options' => ['formatter' => null] + $options,
						'context' => $entity ?: $context,
						'value' => $value,
						'field' => $fieldName,
					]
				);
			}

			if (is_callable($options['formatter'])) {
				return $options['formatter'](
					$fieldName,
					$value,
					$entity ?: $context,
					['formatter' => null] + $options,
					$this->_View
				);
			}
		}
		trigger_error('Invalid formatter for field ' . $fieldName, E_USER_WARNING);
	}

	protected function _magicOptions($fieldName, $options, $allowOverride)
	{
		$fields = $this->_View->get('fields');
		if (isset($fields[$fieldName]) && is_array($fields[$fieldName])) {
			$options += $fields[$fieldName];
		}
		if ($this->_View->get($fieldName)) {
			if (substr($fieldName, -3) == '_id' && !isset($options['value'])) {
				$options['value'] = $this->_View->get($fieldName);
			} elseif (!isset($options['options'])) {
				$options['options'] = $this->_View->get($fieldName);
			}
		}

		if ($fieldName == 'ip') {
			$options += ['type' => 'text', 'pattern' => '((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$', 'title' => 'ipv4'];
		}
		if (in_array($fieldName, ['url', 'href', 'website']) || strpos($fieldName, '_url') !== false) {
			$options += ['type' => 'url'];
		}
		if (in_array($fieldName, ['email']) || strpos($fieldName, '_email') !== false) {
			$options += ['type' => 'email'];
		}

		return parent::_magicOptions($fieldName, $options, $allowOverride);
	}
}
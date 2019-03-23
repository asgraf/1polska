<?php
namespace App\View\Helper;

use Cake\Http\Exception\InternalErrorException;
use Cake\Utility\Inflector;

/**
 * Flash helper
 *
 * @method string success(string $message, array $options = []) Set a message using "success" element
 * @method string warning(string $message, array $options = []) Set a message using "warning" element
 * @method string error(string $message, array $options = []) Set a message using "error" element
 * @method string info(string $message, array $options = []) Set a message using "info" element
 */
class FlashHelper extends \Cake\View\Helper\FlashHelper
{
	public function __call($name, $args)
	{
		$element = Inflector::underscore($name);

		if (count($args) < 1) {
			throw new InternalErrorException('Flash message missing.');
		}

		$options = ['element' => $element];

		if (!empty($args[1])) {
			$options += (array)$args[1];
		}
		return $this->flash($args[0], $options);
	}

	public function flash($message, array $options = [])
	{
		if (empty($message)) {
			throw new InternalErrorException('Flash message missing.');
		}
		$element = 'Flash/default';
		if (!empty($options['element'])) {
			$element = 'Flash/' . $options['element'];
		}
		if (!empty($options['plugin'])) {
			$element = $options['plugin'] . '.' . $element;
		}

		$output = $this->_View->element($element, ['message' => $message, 'params' => $options]);
		return $output;
	}

}

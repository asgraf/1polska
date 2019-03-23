<?php
namespace App\View;


/**
 * A view class that is used for AJAX responses.
 * Currently only switches the default layout and sets the response type -
 * which just maps to text/html by default.
 */
class AjaxView extends AppView
{

	/**
	 * The name of the layout file to render the view inside of. The name
	 * specified is the filename of the layout in /src/Template/Layout without
	 * the .ctp extension.
	 *
	 * @var string
	 */
	public $layout = 'ajax';

	/**
	 * Initialization hook method.
	 *
	 * @return void
	 */
	public function initialize()
	{
		parent::initialize();

		$this->response = $this->response->withType('ajax');
	}
}

<?php
namespace App\View;

/**
 * Application View
 *
 * Your application’s default view class
 *
 * @link https://book.cakephp.org/3.0/en/views.html#the-app-view
 *
 * @property \App\View\Helper\FormHelper $Form
 * @property \App\View\Helper\HtmlHelper $Html
 * @property \Bootstrap\View\Helper\PaginatorHelper $Paginator
 * @property \Bootstrap\View\Helper\PanelHelper $Panel
 * @property \Bootstrap\View\Helper\ModalHelper $Modal
 * @property \App\View\Helper\FlashHelper $Flash
 * @property \Bootstrap\View\Helper\BreadcrumbsHelper $Breadcrumbs
 * @property \Tools\View\Helper\FormatHelper $Format
 * @property \CrudView\View\Helper\CrudViewHelper $CrudView
 * @property \Authentication\View\Helper\IdentityHelper $Identity
 * @property \Tools\View\Helper\GravatarHelper $Gravatar
 * @property \Tags\View\Helper\TagHelper $Tag
 * @property \Tags\View\Helper\TagCloudHelper $TagCloud
 * @property \Recaptcha\View\Helper\RecaptchaHelper $Recaptcha
 */
class AppView extends \CrudView\View\CrudView
{
	public $layout = 'default';

	/**
	 * Initialization hook method.
	 *
	 * Use this method to add common initialization code like loading helpers.
	 *
	 * e.g. `$this->loadHelper('Html');`
	 *
	 * @return void
	 */
	public function initialize()
	{
		parent::initialize();
	}

	protected function _setupHelpers()
	{
		$this->loadHelper('Html');
		$this->Html->setTemplates([
			'alert' => <<<HTML
				<div class="alert alert-{{type}}{{attrs.class}}" role="alert"{{attrs}}>
					{{close}}
					<span class="icon-container"><i class="icon-{{icon}}"></i></span>
					<div class="flash-container">
						<strong>{{title}}</strong>
						<p>{{content}}</p>
					</div>
				</div>
HTML
		]);
		$this->loadHelper('Form', [
			'widgets' => [
				'datetime' => ['CrudView\View\Widget\DateTimeWidget', 'select'],

			],
			'columns' => [
				'sm' => [
					'label' => 6,
					'input' => 6,
					'error' => 0
				],
				'md' => [
					'label' => 2,
					'input' => 8,
					'error' => 2
				]
			]
		]);

		$this->Form->setConfig('autoSetCustomValidity', true);
		$this->loadHelper('Authentication.Identity');
		$this->loadHelper('Flash');
		$this->loadHelper('Text');
		$this->loadHelper('Paginator', ['className' => 'Bootstrap.Paginator']);
		$this->loadHelper('Breadcrumbs', ['className' => 'Bootstrap.Breadcrumbs']);
		$this->loadHelper('Panel', ['className' => 'Bootstrap.Panel']);
		$this->loadHelper('Modal', ['className' => 'Bootstrap.Modal']);
		$this->loadHelper('CrudView.CrudView');
		$this->loadHelper('Tools.Gravatar', [
			'default' => 'identicon',
			'rating' => 'g'
		]);
		$this->loadHelper('Tags.Tag');
		$this->loadHelper('Tags.TagCloud');
	}
}

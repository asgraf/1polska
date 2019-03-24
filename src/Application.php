<?php
namespace App;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\EncryptedCookieMiddleware;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\Router;
use DateTime;
use ForceUTF8\Encoding;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function bootstrap()
	{
		// Call parent to load bootstrap from files.
		parent::bootstrap();

		if (PHP_SAPI === 'cli') {
			$this->bootstrapCli();
		}

		/*
		 * Only try to load DebugKit in development mode
		 * Debug Kit should not be installed on a production system
		 */
		if (Configure::read('debug')) {
			$this->addPlugin('DebugKit', ['routes' => true, 'bootstrap' => true]);
			$this->addPlugin('IdeHelper');
		}

		// Load more plugins here
		$this->addPlugin('Crud');
		$this->addPlugin('CrudUsers');
		$this->addPlugin('CrudView');
		$this->addPlugin('Search');
		$this->addPlugin('Tags');
		$this->addPlugin('Bootstrap');
		$this->addPlugin('Tools');
		$this->addPlugin('Setup', ['bootstrap' => true]);
		$this->addPlugin('Authentication');
		$this->addPlugin('Filerepo', ['routes' => true]);
	}

	/**
	 * Setup the middleware queue your application will use.
	 *
	 * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
	 * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
	 */
	public function middleware($middlewareQueue)
	{
		$middlewareQueue
			->add(new EncryptedCookieMiddleware(['rememberMe'], Configure::readOrFail('cookieCrypt')))
			// Catch any exceptions in the lower layers,
			// and make an error page/response
			->add(new ErrorHandlerMiddleware(null, Configure::read('Error')))
			// Handle plugin/theme assets like CakePHP normally does.
			->add(new AssetMiddleware([
				'cacheTime' => Configure::read('Asset.cacheTime')
			]))
			// Add routing middleware.
			// Routes collection cache enabled by default, to disable route caching
			// pass null as cacheConfig, example: `new RoutingMiddleware($this)`
			// you might want to disable this cache in case your routing is extremely simple
			->add(new RoutingMiddleware($this, '_cake_routes_'))
			->add(new AuthenticationMiddleware($this, [
				'unauthenticatedRedirect' => '/zaloguj',
				'queryParam' => 'redirect',
			]));
		return $middlewareQueue;
	}

	/**
	 * Returns a service provider instance.
	 *
	 * @param \Psr\Http\Message\ServerRequestInterface $request Request
	 * @param \Psr\Http\Message\ResponseInterface $response Response
	 * @return \Authentication\AuthenticationServiceInterface
	 */
	public function getAuthenticationService(ServerRequestInterface $request, ResponseInterface $response)
	{
		$service = new AuthenticationService();

		$fields = [
			'username' => 'email',
			'password' => 'password'
		];

		$service->loadAuthenticator('Authentication.Session', [
			'fields' => $fields
		]);
		$service->loadAuthenticator('Authentication.Form', [
			'loginUrl' => '/zaloguj',
			'fields' => $fields,
		]);
		$service->loadAuthenticator('Authentication.Cookie', [
			'fields' => $fields,
			'cookie' => [
				'name' => 'rememberMe',
				'expire' => new DateTime('+1 month'),
				'secure' => true,
				'httpOnly' => true,
			]
		]);
		$service->loadIdentifier('Authentication.Password', [
			'fields' => $fields,
			'passwordHasher' => 'Authentication.Default'
		]);

		return $service;
	}

	/**
	 * @return void
	 */
	protected function bootstrapCli()
	{
		try {
			$this->addPlugin('Bake');
		} catch (MissingPluginException $e) {
			// Do not halt if the plugin is missing
		}

		$this->addPlugin('Migrations');

		// Load more plugins here
		$this->addPlugin('IdeHelper');
	}
}

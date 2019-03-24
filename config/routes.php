<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `$routes->defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 * Cache: Routes are cached to improve performance, check the RoutingMiddleware
 * constructor in your `src/Application.php` file to change this behavior.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
	// Register scoped middleware for in scopes.
	$routes->registerMiddleware('csrf', new CsrfProtectionMiddleware([
		'httpOnly' => true
	]));

	/**
	 * Apply a middleware to the current route scope.
	 * Requires middleware to be registered via `Application::routes()` with `registerMiddleware()`
	 */
	$routes->applyMiddleware('csrf');

	/**
	 * Here, we are connecting '/' (base path) to a controller called 'Pages',
	 * its action called 'display', and we pass a param to select the view file
	 * to use (in this case, src/Template/Pages/Home.ctp)...
	 */
	//$routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'Home']);

	/**
	 * ...and connect the rest of 'Pages' controller's URLs.
	 */

	$routes->connect('/', ['controller' => 'Home', 'action' => 'main']);
	$routes->connect('/auth_callback/*', ['controller' => 'Home', 'action' => 'auth_callback']);
	$routes->connect('/kontakt', ['controller' => 'Pages', 'action' => 'display', 'kontakt']);
	$routes->connect('/o_co_chodzi', ['controller' => 'Pages', 'action' => 'display', 'o_co_chodzi']);
	$routes->connect('/feedback', ['controller' => 'Pages', 'action' => 'display', 'disqus']);
	$routes->connect('/polityka_prywatnosci', ['controller' => 'Pages', 'action' => 'display', 'polityka_prywatnosci']);
	$routes->connect('/regulamin', ['controller' => 'Pages', 'action' => 'display', 'regulamin']);
	$routes->connect('/postulaty/historia_glosowania', ['controller' => 'PostulatesUsersChanges']);
	$routes->connect('/postulaty/:id/edycja', ['controller' => 'Postulates', 'action' => 'edit'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/postulaty/:slug_:id/edycja', ['controller' => 'Postulates', 'action' => 'edit'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/postulaty/:slug_:id/*', ['controller' => 'Postulates', 'action' => 'view'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/postulaty/:id/*', ['controller' => 'Postulates', 'action' => 'view'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/postulaty/nowy/*', ['controller' => 'Postulates', 'action' => 'add']);
	$routes->connect('/postulaty/glos/poparcie/:id', ['controller' => 'Postulates', 'action' => 'upvote'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/postulaty/glos/brak_poparcia/:id', ['controller' => 'Postulates', 'action' => 'downvote'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/postulaty/glos/anuluj_glos/:id', ['controller' => 'Postulates', 'action' => 'cancelvote'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/postulaty/propozycje_uzytkownikow/*', ['controller' => 'Postulates', 'status' => 'not_active']);
	$routes->connect('/postulaty/*', ['controller' => 'Postulates', 'active' => true]);
	$routes->connect('/postulaty/*', ['controller' => 'Postulates']);
	$routes->connect('/reprezentanci/historia_glosowania', ['controller' => 'RepresentativesUsersChanges']);
	$routes->connect('/reprezentanci/:id/edycja', ['controller' => 'Representatives', 'action' => 'edit'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/reprezentanci/:slug_:id/edycja', ['controller' => 'Representatives', 'action' => 'edit'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/reprezentanci/:slug_:id/*', ['controller' => 'Representatives', 'action' => 'view'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/reprezentanci/:id/*', ['controller' => 'Representatives', 'action' => 'view'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/reprezentanci/nowy/*', ['controller' => 'Representatives', 'action' => 'add']);
	$routes->connect('/reprezentanci/glos/poparcie/:id', ['controller' => 'Representatives', 'action' => 'upvote'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/reprezentanci/glos/brak_poparcia/:id', ['controller' => 'Representatives', 'action' => 'downvote'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/reprezentanci/glos/anuluj_glos/:id', ['controller' => 'Representatives', 'action' => 'cancelvote'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/reprezentanci/propozycje_uzytkownikow/*', ['controller' => 'Representatives', 'status' => 'not_active']);
	$routes->connect('/reprezentanci/*', ['controller' => 'Representatives', 'active' => true]);
	$routes->connect('/reprezentanci/*', ['controller' => 'Representatives']);
	$routes->connect('/okregi_wyborcze', ['controller' => 'Constituencies']);
	$routes->connect('/okregi_wyborcze/:id', ['controller' => 'Constituencies', 'action' => 'view'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/zapomniane_haslo', ['controller' => 'Users', 'action' => 'forgot_password']);
	$routes->connect('/profil/edycja', ['controller' => 'Users', 'action' => 'edit']);
	$routes->connect('/profil/usuwanie', ['controller' => 'Users', 'action' => 'delete']);
	$routes->connect('/profil/:id/*', ['controller' => 'Users', 'action' => 'view'], ['pass' => ['id'], 'routeClass' => 'EntityRoute']);
	$routes->connect('/profil/dane', ['controller' => 'Users', 'action' => 'export'])->setExtensions(['json', 'xml']);
	$routes->prefix('admin', function (RouteBuilder $routes) {
		$routes->connect('/', ['controller' => 'Users']);
		$routes->connect('/profil/:slug/:emailhash/*', ['controller' => 'Users', 'action' => 'view'], ['pass' => ['id'], 'emailhash' => '[0-9a-f]{32}', 'routeClass' => 'EntityRoute']);
		$routes->connect('/profil/:emailhash/*', ['controller' => 'Users', 'action' => 'view'], ['pass' => ['id'], 'emailhash' => '[0-9a-f]{32}', 'routeClass' => 'EntityRoute']);
		$routes->fallbacks(DashedRoute::class);

	});
	$routes->connect('/rejestracja', ['controller' => 'Users', 'action' => 'register']);
	$routes->connect('/zaloguj', ['controller' => 'Users', 'action' => 'login']);
	$routes->connect('/zaloguj/*', ['controller' => 'Home', 'action' => 'social_login']);
	$routes->connect('/wyloguj', ['controller' => 'Users', 'action' => 'logout']);
	$routes->connect('/aktywacja', ['controller' => 'Users', 'action' => 'activate']);

	$routes->connect('/tagi', ['controller' => 'Tags', 'action' => 'index']);
	$routes->connect('/tagi/:slug', ['controller' => 'Tags', 'action' => 'view'], ['routeClass' => 'EntityRoute']);

	$routes->connect('/s/*', ['controller' => 'Pages', 'action' => 'display']);

	/**
	 * Connect catchall routes for all controllers.
	 *
	 * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
	 *
	 * ```
	 * $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);
	 * $routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);
	 * ```
	 *
	 * Any route class can be used with this method, such as:
	 * - DashedRoute
	 * - InflectedRoute
	 * - Route
	 * - Or your own route class
	 *
	 * You can remove these routes once you've connected the
	 * routes you want in your application.
	 */
	$routes->fallbacks(DashedRoute::class);
});

/**
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * $routes->scope('/api', function (RouteBuilder $routes) {
 *     // No $routes->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */

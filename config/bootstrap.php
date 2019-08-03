<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/*
 * Configure paths required to find CakePHP + general filepath constants
 */
require __DIR__ . '/paths.php';

/*
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Database\Type;
use Cake\Datasource\ConnectionManager;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;
use Cake\Utility\Inflector;
use Cake\Utility\Security;
use Tools\Error\ErrorHandler;

/**
 * Uncomment block of code below if you want to use `.env` file during development.
 * You should copy `config/.env.default to `config/.env` and set/modify the
 * variables as required.
 *
 * It is HIGHLY discouraged to use a .env file in production, due to security risks
 * and decreased performance on each request. The purpose of the .env file is to emulate
 * the presence of the environment variables like they would be present in production.
 */
// if (!env('APP_NAME') && file_exists(CONFIG . '.env')) {
//     $dotenv = new \josegonzalez\Dotenv\Loader([CONFIG . '.env']);
//     $dotenv->parse()
//         ->putenv()
//         ->toEnv()
//         ->toServer();
// }

/*
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
	Configure::load('global');
	Configure::load('local');
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

/*
 * Load an environment local configuration file.
 * You can use a file like app_local.php to provide local overrides to your
 * shared configuration.
 */
//Configure::load('app_local', 'default');

/*
 * When debug = true the metadata cache should only last
 * for a short time.
 */
if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_core_.duration', '+2 minutes');
    // disable router cache during development
    Configure::write('Cache._cake_routes_.duration', '+2 seconds');
}

/*
 * Set the default server timezone. Using UTC makes time calculations / conversions easier.
 * Check http://php.net/manual/en/timezones.php for list of valid timezone strings.
 */
date_default_timezone_set(Configure::read('App.defaultTimezone'));

/*
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/*
 * Register application error and exception handlers.
 */
$isCli = PHP_SAPI === 'cli';
if ($isCli) {
    (new ConsoleErrorHandler(Configure::read('Error')))->register();
} else {
    (new ErrorHandler(Configure::read('Error')))->register();
}

/*
 * Include the CLI bootstrap overrides.
 */
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}

/*
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 *
 * If you define fullBaseUrl in your config file you can remove this.
 */
if (!Configure::read('App.fullBaseUrl')) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
    }
    unset($httpHost, $s);
}

Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
TransportFactory::setConfig(Configure::consume('EmailTransport'));
Email::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

/*
 * The default crypto extension in 3.0 is OpenSSL.
 * If you are migrating from 2.x uncomment this code to
 * use a more compatible Mcrypt based implementation
 */
//Security::engine(new \Cake\Utility\Crypto\Mcrypt());

/*
 * Setup detectors for mobile and tablet.
 */
ServerRequest::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isMobile();
});
ServerRequest::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isTablet();
});

/*
 * Enable immutable time objects in the ORM.
 *
 * You can enable default locale format parsing by adding calls
 * to `useLocaleParser()`. This enables the automatic conversion of
 * locale specific date formats. For details see
 * @link https://book.cakephp.org/3.0/en/core-libraries/internationalization-and-localization.html#parsing-localized-datetime-data
 */
Type::build('time')
    ->useImmutable();
Type::build('date')
    ->useImmutable();
Type::build('datetime')
    ->useImmutable();
Type::build('timestamp')
    ->useImmutable();
/*
 * Custom Inflector rules, can be set to correctly pluralize or singularize
 * table, model, controller names or whatever other string is passed to the
 * inflection functions.
 */
//Inflector::rules('plural', ['/^(inflect)or$/i' => '\1ables']);
//Inflector::rules('irregular', ['red' => 'redlings']);
//Inflector::rules('uninflected', ['dontinflectme']);
//Inflector::rules('transliteration', ['/å/' => 'aa']);

function parseUrl($url, $request = null)
{
	if ($url == '#') return false;
	if (!($request instanceof \Cake\Http\ServerRequest)) {
		$request = \Cake\Routing\Router::getRequest();
	}
	if (is_array($url)) {
		if (isset($url['controller'])) {
			$url['controller'] = Inflector::camelize($url['controller']);
		}
		$url = \Cake\Routing\Router::url($url);
	}
	if (is_string($url)) {
		$url_exploded = explode('#', $url);
		if (count($url_exploded) == 2) {
			list($url_part, $hash) = $url_exploded;
			$url = parseUrl($url_part);
			if ($url) {
				return ['#' => $hash] + $url;
			} else {
				return false;
			}
		}
	}

	try {
		$query = [];
		if (is_string($url)) {
			if (isset(parse_url($url)['query'])) {
				parse_str(parse_url($url)['query'], $query);
			}
		}

		$url = \Cake\Routing\Router::getRouteCollection()->parse($url) + ['prefix' => false];

		foreach (array_keys($url) as $key) {
			if (in_array($key, ['_ext', '_base', '_scheme', '_host', '_ssl', '_method', '_name', '_entity', '_full'])) continue;
			if (substr($key, 0, 1) === '_') {
				unset($url[$key]);
			}
		}
		if (!$url['plugin']) {
			$url['plugin'] = false;
		}
		$url['controller'] = Inflector::camelize($url['controller']);
		if (array_key_exists('id', $url) && array_key_exists(0, $url['pass']) && $url['id'] === $url['pass']['0']) {
			array_shift($url['pass']);
		}
		$url = array_merge($url, $url['pass']);
		unset($url['pass']);

		if (!empty($query)) {
			if (empty($url['?'])) {
				$url['?'] = $query;
			} else {
				$url['?'] = array_merge($url['?'], $query);
			}
		}

		return $url;
	} catch (\Cake\Routing\Exception\RedirectException $e) {
		$url = $e->getMessage();
		$parsed_url = parse_url($url);

		if (isset($parsed_url['host']) && $request->host() == $parsed_url['host']) {
			unset($parsed_url['host']);
		} else {
			return false;
		}
		if (isset($parsed_url['scheme']) && $request->scheme() == $parsed_url['scheme']) {
			unset($parsed_url['scheme']);
		}
		$url = unparse_url($parsed_url);
		$url = parseUrl($url);
		return $url;
	} catch (Exception $e) {
		if ($e instanceof \Cake\Routing\Exception\MissingRouteException) {
			return false;
		}
		if (Configure::read('debug') && isset($e->xdebug_message)) {
			echo '<div class="xdebug-error">';
			echo $e->xdebug_message;
			echo '</div>';
		} else {
			debug($e);
		}
		return false;
	}
}

function unparse_url($parsed_url, $ommit = [])
{
	$p = [];
	$p['scheme'] = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
	$p['host'] = isset($parsed_url['host']) ? $parsed_url['host'] : '';
	$p['port'] = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
	$p['user'] = isset($parsed_url['user']) ? $parsed_url['user'] : '';
	$p['pass'] = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
	$p['pass'] = ($p['user'] || $p['pass']) ? $p['pass'] . "@" : '';
	$p['path'] = isset($parsed_url['path']) ? $parsed_url['path'] : '';
	$p['query'] = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
	$p['fragment'] = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
	if ($ommit) {
		foreach ($ommit as $key) {
			if (isset($p[$key])) {
				$p[$key] = '';
			}
		}
	}

	return $p['scheme'] . $p['user'] . $p['pass'] . $p['host'] . $p['port'] . $p['path'] . $p['query'] . $p['fragment'];
}

function getSupportedImageFormats() {
	$suported_formats = [];
	if (function_exists('imagecreatefromjpeg')) {
		$suported_formats['image/jpeg'] = 'jpeg';
	}
	if (function_exists('imagecreatefrompng')) {
		$suported_formats['image/png'] = 'png';
	}
	if (function_exists('imagecreatefrombmp')) {
		$suported_formats['image/bmp'] = 'bmp';
		$suported_formats['image/x-windows-bmp'] = 'bmp';
	}
	if (function_exists('imagecreatefromwebp')) {
		$suported_formats['image/webp'] = 'webp';
	}
	if (function_exists('imagecreatefromgif')) {
		$suported_formats['image/gif'] = 'gif';
	}
	return $suported_formats;
}

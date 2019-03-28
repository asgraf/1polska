<?php
namespace App\Middleware;

use Cake\Cache\Cache;
use Cake\Http\Client;
use Cake\Http\Exception\HttpException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\ServerRequest;
use Psr\Http\Message\ResponseInterface;

/**
 * Cloudflare middleware
 */
class CloudflareMiddleware
{
	protected function getCloudflareRanges()
	{
		return Cache::remember('server_ranges', function () {
			$client = new Client([
				'host' => 'www.cloudflare.com',
				'scheme' => 'https',
			]);

			$response = $client->get('/ips-v4');
			if($response->isOk()) {
				return $response->getStringBody();
			} else {
				throw new InternalErrorException('Failed to fetch cloudflare server ranges');
			}
		}, 'cloudflare');
	}

	function isIpInRange($ip, $range)
	{
		if (strpos($range, '/') == false) {
			$range .= '/32';
		}

		list($range, $netmask) = explode('/', $range, 2);
		$range_decimal = ip2long($range);
		$ip_decimal = ip2long($ip);
		$wildcard_decimal = pow(2, (32 - $netmask)) - 1;
		$netmask_decimal = ~$wildcard_decimal;
		return (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal));
	}

	protected function isCloudflareIp($ip)
	{
		try {
			foreach ($this->getCloudflareRanges() as $range) {
				if ($this->isIpInRange($ip, $range)) return true;
			}
		} catch (HttpException $e) {
			trigger_error($e->getMessage(),E_USER_WARNING);
		}

		return false;
	}

	public function __invoke(ServerRequest $request, ResponseInterface $response, $next)
	{

		if ($this->isCloudflareIp($request->getEnv('REMOTE_ADDR'))) {
			$request->trustProxy = true;
			$request->setTrustedProxies([$request->getEnv('REMOTE_ADDR')]);
			$request = $request->withAttribute('cloduflare',true);
		} else {
			$request = $request->withAttribute('cloduflare', false);
		}
		return $next($request, $response);
	}
}

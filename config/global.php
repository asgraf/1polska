<?php
return [
	'debug' => false,
	'Config' => [
		'live' => 1,
		'adminEmail' => 'asgraf@gmail.com',
		'systemEmail' => '1polska@1polska.pl',
		'systemName' => '1Polska.pl'
	],
	'Cache' => [
		'cloudflare' => [
			'className' => 'File',
			'duration' => '+1 day',
			'path' => CACHE,
			'prefix' => 'cloudflare_'
		],
	],
	'DatabaseLog' => [
		'datasource' => 'default',
	],
	'Log' => [
		'debug' => [
			'className' => 'DatabaseLog.Database'
		],
		'error' => [
			'className' => 'DatabaseLog.Database'
		],
		'404' => [
			'className' => 'Cake\Log\Engine\FileLog',
			'path' => LOGS,
			'file' => '404',
			'url' => env('LOG_ERROR_URL', null),
			'scopes' => ['404'],
		],
	],
	'email_sleep' => 5
];
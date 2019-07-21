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
	'email_sleep' => 5
];
<?php

return [
	/**
	 * The client ID, issued by St.George.
	 */
	'clientId'            => env('IPGCLIENTID'),

	/**
	 * The certificate password, issued by St.George.
	 */
	'certificatePassword' => env('IPGCERTIFICATEPASSWORD'),

	/**
	 * The certificate password, issued by St.George.
	 */
	'certificatePath'     => env('IPGCERTIFICATEPATH', resource_path('webpay/cert.cert')),

	/**
	 * Whether to connect to the live or test server.
	 */
	'test'                => env('IPGTEST', FALSE),

	/**
	 * Whether to debug the requests and responses.
	 */
	'debug'               => env('IPGDEBUG', FALSE),

	/**
	 * The log file path if debugging is enabled.
	 */
	'logPath'             => env('IPGLOGPATH', storage_path('logs/webpay/' . date('Y-m-d') . '.log')),
];
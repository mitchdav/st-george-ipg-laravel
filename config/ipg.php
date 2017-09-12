<?php

return [
	/**
	 * The provider to use to connect to St.George IPG.
	 */
	'provider'            => \StGeorgeIPG\Providers\WebService::class,

	/**
	 * The client ID, issued by St.George.
	 */
	'clientId'            => env('IPG_CLIENT_ID'),

	/**
	 * The authentication token, created in the St.George Merchant Administration Console.
	 *
	 * This is required for the WebService provider, and optional for the Extension provider.
	 */
	'authenticationToken' => env('IPG_AUTHENTICATION_TOKEN'),

	/**
	 * Whether to connect to the live or test environment.
	 */
	'test'                => env('IPG_TEST', FALSE),

	/**
	 * Extension-specific configuration.
	 */
	'extension'           => [
		/**
		 * The certificate password, issued by St.George.
		 */
		'certificatePassword' => env('IPG_CERTIFICATE_PASSWORD'),

		/**
		 * The path to the certificate file that was issued by St.George.
		 */
		'certificatePath'     => env('IPG_CERTIFICATE_PATH', resource_path('webpay/cert.cert')),

		/**
		 * Whether to debug the requests and responses.
		 */
		'debug'               => env('IPG_DEBUG', FALSE),

		/**
		 * The log file path if debugging is enabled.
		 */
		'logFile'             => env('IPG_LOG_FILE', storage_path('logs/webpay/' . date('Y-m-d') . '.log')),
	],
];
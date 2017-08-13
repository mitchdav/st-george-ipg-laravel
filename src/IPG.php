<?php

namespace StGeorgeIPG\Laravel;

use StGeorgeIPG\Client;
use StGeorgeIPG\Webpay;

/**
 * Class IPG
 * @package StGeorgeIPG\Laravel
 */
class IPG
{
	/**
	 * @var \StGeorgeIPG\Client $client
	 */
	private static $client;

	/**
	 * Forward on calls to the client
	 *
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed
	 */
	public static function __callStatic($name, $arguments)
	{
		return call_user_func_array(
			[self::getClient(), $name],
			$arguments
		);
	}

	/**
	 * @return \StGeorgeIPG\Client
	 */
	private static function getClient()
	{
		if (self::$client == NULL) {
			$webpay = new Webpay();

			self::$client = new Client(config('ipg.clientId'), config('ipg.certificatePassword'), $webpay, config('ipg.certificatePath'), config('ipg.debug'), config('ipg.logPath'), (config('ipg.test')) ? (Client::PORT_TEST) : (Client::PORT_LIVE));
		}

		return self::$client;
	}

	/**
	 * Forward on calls to the client
	 *
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed
	 */
	public function __call($name, $arguments)
	{
		return call_user_func_array(
			[self::getClient(), $name],
			$arguments
		);
	}
}
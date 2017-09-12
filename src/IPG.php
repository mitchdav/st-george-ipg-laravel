<?php

namespace StGeorgeIPG\Laravel;

use StGeorgeIPG\Client;
use StGeorgeIPG\Providers\Extension;
use StGeorgeIPG\Providers\WebService;
use StGeorgeIPG\Request;
use StGeorgeIPG\Response;

/**
 * Class IPG
 * @package StGeorgeIPG\Laravel
 *
 * @method static \StGeorgeIPG\Client createWithExtension($terminalType = Request::TERMINAL_TYPE_INTERNET, $interface = Request::INTERFACE_CREDIT_CARD)
 * @method static \StGeorgeIPG\Client createWithWebService($terminalType = Request::TERMINAL_TYPE_INTERNET, $interface = Request::INTERFACE_CREDIT_CARD)
 * @method static \StGeorgeIPG\Contracts\Provider getProvider()
 * @method static \StGeorgeIPG\Client setProvider($provider)
 * @method static integer getTerminalType($provider)
 * @method static \StGeorgeIPG\Client setTerminalType($terminalType)
 * @method static string getInterface($provider)
 * @method static \StGeorgeIPG\Client setInterface($interface)
 * @method static \StGeorgeIPG\Request purchase($amount, $cardNumber, $month, $year, $cvc = NULL, $clientReference = NULL, $comment = NULL, $description = NULL, $cardHolderName = NULL, $taxAmount = NULL)
 * @method static \StGeorgeIPG\Request refund($amount, $originalTransactionReference, $clientReference = NULL, $comment = NULL, $description = NULL, $cardHolderName = NULL, $taxAmount = NULL)
 * @method static \StGeorgeIPG\Request preAuth($amount, $cardNumber, $month, $year, $cvc = NULL, $clientReference = NULL, $comment = NULL, $description = NULL, $cardHolderName = NULL, $taxAmount = NULL)
 * @method static \StGeorgeIPG\Request completion($amount, $originalTransactionReference, $authorisationNumber, $clientReference = NULL, $comment = NULL, $description = NULL, $cardHolderName = NULL, $taxAmount = NULL)
 * @method static \StGeorgeIPG\Request status($transactionReference)
 * @method static \StGeorgeIPG\Response getResponse(Request $request, $maxTries = 3)
 * @method static \StGeorgeIPG\Response execute(Request $request, $maxTries = 3)
 * @method static \StGeorgeIPG\Response validateResponse(Response $response)
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
		return call_user_func_array([
			self::getClient(),
			$name,
		], $arguments);
	}

	/**
	 * @param string $providerClass
	 *
	 * @return \StGeorgeIPG\Client
	 */
	public static function getClient($providerClass = NULL)
	{
		if (self::$client == NULL) {
			self::$client = self::createClient($providerClass);
		}

		return self::$client;
	}

	/**
	 * @param string $providerClass
	 *
	 * @return \StGeorgeIPG\Client
	 */
	public static function createClient($providerClass = NULL)
	{
		if ($providerClass === NULL) {
			$providerClass = config('ipg.provider');
		}

		$provider = new $providerClass();

		if (!($provider instanceof \StGeorgeIPG\Contracts\Provider)) {
			throw new \InvalidArgumentException('The IPG provider must be an instance of ' . \StGeorgeIPG\Contracts\Provider::class . '.');
		} else {
			$provider->setTest(config('ipg.test'));

			switch ($providerClass) {
				case Extension::class: {
					/** @var Extension $provider */

					$provider->setClientId(config('ipg.clientId'))
					         ->setAuthenticationToken(config('ipg.authenticationToken'))
					         ->setCertificatePassword(config('ipg.extension.certificatePassword'))
					         ->setCertificatePath(config('ipg.extension.certificatePath'))
					         ->setDebug(config('ipg.extension.debug'))
					         ->setLogFile(config('ipg.extension.logFile'));

					break;
				}

				case WebService::class: {
					/** @var WebService $provider */

					$provider->setClientId(config('ipg.clientId'))
					         ->setAuthenticationToken(config('ipg.authenticationToken'));

					break;
				}

				default: {
					break;
				}
			}
		}

		return new Client($provider);
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
		return call_user_func_array([
			self::getClient(),
			$name,
		], $arguments);
	}
}
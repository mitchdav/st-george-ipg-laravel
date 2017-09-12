<?php

namespace StGeorgeIPG\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use StGeorgeIPG\Laravel\IPG as BaseIPG;
use StGeorgeIPG\Request;
use StGeorgeIPG\Response;

/**
 * Class IPG
 * @package StGeorgeIPG\Laravel\Facades
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
 * @method static \StGeorgeIPG\Client getClient($providerClass = NULL)
 * @method static \StGeorgeIPG\Client createClient($providerClass = NULL)
 */
class IPG extends Facade
{
	protected static function getFacadeAccessor()
	{
		return BaseIPG::class;
	}
}
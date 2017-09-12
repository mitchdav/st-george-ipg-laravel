<?php

namespace StGeorgeIPG\Laravel\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use StGeorgeIPG\Exceptions\ResponseCodes\Exception;
use StGeorgeIPG\Laravel\IPG;
use StGeorgeIPG\Providers\Extension;

class TestPurchase extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'ipg:test-purchase {provider?} {cert?}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Checks the connection to the IPG by attempting a test purchase.';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$providerClass   = $this->argument('provider');
		$certificatePath = $this->argument('cert');

		if ($providerClass !== NULL) {
			/** @var \StGeorgeIPG\Contracts\Provider $provider */
			$provider = IPG::createClient($providerClass)
			               ->getProvider();

			IPG::setProvider($provider);
		}

		$provider = IPG::getProvider();

		$provider->setTest();

		if (get_class($provider) == Extension::class && $certificatePath !== NULL) {
			/** @var Extension $provider */

			$provider->setCertificatePath($certificatePath);
		}

		$oneYearAhead = (new Carbon())->addYear();

		$amount     = 10.00; // In dollars
		$cardNumber = '4111111111111111';
		$month      = $oneYearAhead->month;
		$year       = $oneYearAhead->year;

		$purchaseRequest = IPG::purchase($amount, $cardNumber, $month, $year);

		try {
			$purchaseResponse = IPG::execute($purchaseRequest);

			$this->info('The test purchase was successful.');
		} catch (Exception $ex) {
			$this->error('The test purchase was unsuccessful.');
			$this->error($ex->getMessage());
		}
	}
}
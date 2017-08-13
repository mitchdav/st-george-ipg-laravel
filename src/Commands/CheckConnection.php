<?php

namespace StGeorgeIPG\Laravel\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use StGeorgeIPG\Exceptions\ResponseCodes\Exception;
use StGeorgeIPG\Laravel\Facades\IPG;

class CheckConnection extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'ipg:check-connection';

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
		$oneYearAhead = (new Carbon())->addYear();

		$amount     = 10.00; // In dollars
		$cardNumber = '4111111111111111';
		$month      = $oneYearAhead->month;
		$year       = $oneYearAhead->year;

		$purchaseRequest = IPG::purchase($amount, $cardNumber, $month, $year);

		try {
			$purchaseResponse = IPG::execute($purchaseRequest);

			$this->comment('The charge was successful.');
		} catch (Exception $ex) {
			$this->error('The charge was unsuccessful.');
			$this->error($ex->getMessage());
		}
	}
}
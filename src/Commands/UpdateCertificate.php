<?php

namespace StGeorgeIPG\Laravel\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use StGeorgeIPG\Providers\Extension;

class UpdateCertificate extends Command
{
	const URL = 'https://www.ipg.stgeorge.com.au/downloads/cert.zip';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'ipg:update-certificate {path?} {url?} {--skip-test}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Downloads the latest certificate to be used with the Extension provider.';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$path     = $this->argument('path');
		$url      = $this->argument('url');
		$skipTest = $this->option('skip-test');

		if ($path === NULL) {
			$path = config('ipg.extension.certificatePath');
		}

		if ($path === NULL) {
			$path = resource_path('webpay/cert.cert');
		}

		if ($url === NULL) {
			$url = UpdateCertificate::URL;
		}

		$zipPath     = tempnam(sys_get_temp_dir(), 'ipg');
		$extractPath = sys_get_temp_dir() . '/ipg' . time();

		mkdir($extractPath, 0777, TRUE);

		try {
			$client = new Client();

			$response = $client->request('GET', $url, [
				'sink' => $zipPath,
			]);

			$this->info('The zip file was downloaded successfully.', 'v');

			$zip = new \ZipArchive();

			$openResult = $zip->open($zipPath);

			if ($openResult === TRUE) {
				$this->info('The zip file was opened successfully.', 'v');

				$extractToResult = $zip->extractTo($extractPath, [
					'cert.cert',
				]);

				if ($extractToResult === TRUE) {
					$this->info('The zip file was extracted successfully.', 'v');

					$certPath = $extractPath . '/cert.cert';

					$existed = file_exists($path);

					$error = FALSE;

					try {
						$testPurchaseResult = $this->call('ipg:test-purchase', [
							'provider' => Extension::class,
							'cert'     => $certPath,
						]);

						if ($testPurchaseResult !== 0) {
							$error = TRUE;
						}
					} catch (\Exception $ex) {
						$error = TRUE;
					}

					if (!$error || $skipTest === TRUE) {
						$copyResult = copy($certPath, $path);

						if ($copyResult === TRUE) {
							if ($existed === TRUE) {
								$this->info('The cert.cert file was updated successfully and saved to ' . $path . '.');
							} else {
								$this->info('The cert.cert file was installed successfully and saved to ' . $path . '.');
							}
						} else {
							$this->error('There was an error while copying the cert.cert file.');
						}
					} else {
						$this->error('There was an error while testing the certificate, so it has been rejected.');
					}
				} else {
					$this->error('There was an error while extracting the zip file.');
				}

				$zip->close();
			} else {
				$this->error('There was an error while opening the zip file.');
			}
		} catch (\Exception $ex) {
			$this->error('There was an error while downloading the zip file.');
			$this->error($ex->getMessage());
		}

		unlink($zipPath);
		$this->deleteDirectory($extractPath);
		$this->deleteDirectory($extractPath); // Once seems to not be enough to delete the parent directory, so run it again
	}

	/**
	 * @param string $dir
	 *
	 * @return boolean
	 *
	 * @link https://stackoverflow.com/a/1653776/7503569
	 */
	private function deleteDirectory($dir)
	{
		if (!file_exists($dir)) {
			return TRUE;
		}

		if (!is_dir($dir)) {
			return unlink($dir);
		}

		foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') {
				continue;
			}

			if ($this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
				return FALSE;
			}
		}

		return rmdir($dir);
	}
}
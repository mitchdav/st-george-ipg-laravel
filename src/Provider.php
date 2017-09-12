<?php

namespace StGeorgeIPG\Laravel;

use Illuminate\Support\ServiceProvider;
use StGeorgeIPG\Laravel\Commands\TestPurchase;
use StGeorgeIPG\Laravel\Commands\UpdateCertificate;

class Provider extends ServiceProvider
{
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/../config/ipg.php' => config_path('ipg.php'),
		], 'config');

		if ($this->app->runningInConsole()) {
			$this->commands([
				TestPurchase::class,
				UpdateCertificate::class,
			]);
		}
	}

	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/ipg.php', 'ipg');
	}
}
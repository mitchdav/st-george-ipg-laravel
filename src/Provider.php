<?php

namespace StGeorgeIPG\Laravel;

use Illuminate\Support\ServiceProvider;
use StGeorgeIPG\Laravel\Commands\CheckConnection;

class Provider extends ServiceProvider
{
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/../config/ipg.php' => config_path('ipg.php'),
		], 'config');

		if ($this->app->runningInConsole()) {
			$this->commands([
				CheckConnection::class,
			]);
		}
	}

	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ . '/../config/ipg.php', 'ipg'
		);
	}
}
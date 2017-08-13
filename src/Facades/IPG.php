<?php

namespace StGeorgeIPG\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use StGeorgeIPG\Laravel\IPG as BaseIPG;

class IPG extends Facade
{
	protected static function getFacadeAccessor()
	{
		return BaseIPG::class;
	}
}
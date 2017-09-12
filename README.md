# St.George IPG Client

[![Latest Stable Version](https://poser.pugx.org/mitchdav/st-george-ipg/v/stable.svg)](https://packagist.org/packages/mitchdav/st-george-ipg-laravel)
[![Total Downloads](https://poser.pugx.org/mitchdav/st-george-ipg/downloads.svg)](https://packagist.org/packages/mitchdav/st-george-ipg-laravel)
[![License](https://poser.pugx.org/mitchdav/st-george-ipg/license.svg)](https://packagist.org/packages/mitchdav/st-george-ipg-laravel)

A Laravel service provider for the St.George Internet Payment Gateway.

Please review the [mitchdav/st-george-ipg](https://github.com/mitchdav/st-george-ipg) package readme for installation of the base package.

## Installation

    composer require mitchdav/st-george-ipg-laravel

Once the library is installed, add the following service provider to your ```config/app.php``` file:

```php
StGeorgeIPG\Laravel\Provider::class,
```

You can also add the facade if required:

```php
'IPG' => StGeorgeIPG\Laravel\Facades\IPG::class,
```

You can then export the config file with the following command:

    php artisan vendor:publish --provider="StGeorgeIPG\Laravel\Provider"

## Configuration

You first need to define the following environment variable in your ```.env``` file:

    IPG_CLIENT_ID=

Depending on whether you have an authentication token set (required for the WebService provider, but only required if already set in the Merchant Administration Console for the Extension provider):

    IPG_AUTHENTICATION_TOKEN=

You can set the system to use the test mode so that all transactions will go to the St.George IPG test server:

    IPG_TEST=TRUE

If you are using the Extension provider, you'll need to define your certificate password, and optionally the certificate path, log file and the debug setting:

    IPG_CERTIFICATE_PASSWORD=
    IPG_CERTIFICATE_PATH=
    IPG_LOG_FILE=
    IPG_DEBUG=TRUE

Both ```IPG_CERTIFICATE_PATH``` and ```IPG_LOG_FILE``` are set to suitable defaults so you can leave these empty if you wish.

Finally, review or edit the ```config/ipg.php``` file, which reads the environment variables and populates the configuration for the package.

```php
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
```

If you wish to use the Extension provider instead, change the ```provider``` value to ```\StGeorgeIPG\Providers\Extension::class```.

## Usage

The package provides a [Client](https://github.com/mitchdav/st-george-ipg-laravel/blob/master/src/IPG.php) and [Facade](https://github.com/mitchdav/st-george-ipg-laravel/blob/master/src/Facades/IPG.php) which are both configured using the values from the ```config/ipg.php``` file.

You can then use the client in the same way as the [mitchdav/st-george-ipg](https://github.com/mitchdav/st-george-ipg) package.

### Charging the Customer (purchase)

```php
use Carbon\Carbon;
use StGeorgeIPG\Exceptions\ResponseCodes\Exception;
use StGeorgeIPG\Laravel\IPG;
//use StGeorgeIPG\Laravel\Facades\IPG; // Or this

$oneYearAhead = (new Carbon())->addYear();

$amount     = 10.00; // In dollars
$cardNumber = '4111111111111111';
$month      = $oneYearAhead->month;
$year       = $oneYearAhead->year;

$purchaseRequest = IPG::purchase($amount, $cardNumber, $month, $year);

try {
    $purchaseResponse = IPG::execute($purchaseRequest);

    echo 'The charge was successful.' . "\n";
} catch (Exception $ex) {
    echo 'The charge was unsuccessful.' . "\n";
    echo $ex->getMessage() . "\n";

    var_dump($purchaseRequest);
    var_dump($ex->getResponse());
}
```

### Others

Check the usage guide on the [mitchdav/st-george-ipg](https://github.com/mitchdav/st-george-ipg) package.

## Commands

### ipg:test-purchase {provider?} {cert?}

This command allows you to test your connection to the St.George IPG server by performing a test purchase. The command will instruct the provider to use the test server (regardless of your configuration), so it is safe to use in a live environment.

    php artisan ipg:test-purchase

- You can optionally tell the command to use a specific provider, by providing the full class name.
- If using the Extension provider, you can also optionally pass in the certificate file to use when connecting.

### ipg:update-certificate {path?} {url?} {--skip-test}

This command allows you to update the ```cert.cert``` certificate file used by the Extension provider to connect to the St.George IPG server. The command will download the latest certificate, test it, and if successful it will save it to the configured or provided path.

    php artisan ipg:update-certificate

- You can optionally set the path to save the certificate to, which will default to the value in the ```config/ipg.php``` file.
- You can optionally specify the URL to download the certificate from, which will default to ```https://www.ipg.stgeorge.com.au/downloads/cert.zip```.
- You can optionally force the command to skip the test to ensure that the latest certificate is saved, though this is not recommended.

## Testing

The library is currently not formally tested, however it is known to work with Laravel 5.4 projects. I aim to implement testing soon, however if you discover any problems please submit an issue and I will get back to you as soon as possible.
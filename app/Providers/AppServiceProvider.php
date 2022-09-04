<?php

namespace App\Providers;

use Adyen\Client;
use Adyen\Service\Checkout;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Checkout::class, function () {
            $client = new Client();
            $client->setTimeout(config('adyen.timeout'));
            $client->setMerchantAccount(config('adyen.merchant'));
            $client->setXApiKey(config('adyen.api_key'));
            $client->setEnvironment(config('adyen.environment'), config('adyen.url_prefix'));

            return new Checkout($client);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace Webkul\MercadoPago\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\MercadoPago\Console\Commands\ApproveMercadoPagoPayment;
use Webkul\MercadoPago\Console\Commands\TestMercadoPagoPix;

class MercadopagoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/payment-methods.php',
            'payment_methods'
        );

        $this->mergeConfigFrom(
            __DIR__.'/../Config/system.php',
            'core'
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Http/routes.php');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'mercadopago');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'mercadopago');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ApproveMercadoPagoPayment::class,
                TestMercadoPagoPix::class,
            ]);
        }
    }
}

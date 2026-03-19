<?php

namespace Webkul\MercadoPago\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\MercadoPago\Console\Commands\ApproveMercadoPagoPayment;
use Webkul\MercadoPago\Console\Commands\TestMercadoPagoPix;
use Webkul\Theme\ViewRenderEventManager;

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

        Event::listen(
            'checkout.order.save.after',
            function ($order): void {
                $additional = session('mercadopago.payment_additional');

                if (empty($additional)) {
                    return;
                }

                if (! str_starts_with($order->payment->method ?? '', 'mercadopago')) {
                    return;
                }

                $order->payment->update(['additional' => $additional]);

                session()->forget('mercadopago.payment_additional');
            }
        );

        Event::listen(
            'bagisto.shop.customers.account.orders.view.payment_method_details.after',
            function (ViewRenderEventManager $viewRenderEventManager): void {
                $order = $viewRenderEventManager->getParam('order');

                if (! $order?->payment || ! str_starts_with($order->payment->method ?? '', 'mercadopago')) {
                    return;
                }

                $additional = $order->payment->additional;

                if (empty($additional)) {
                    return;
                }

                $viewRenderEventManager->addTemplate(
                    'mercadopago::shop.order-payment-details',
                    ['additional' => $additional]
                );
            }
        );

        Event::listen(
            'bagisto.admin.sales.order.payment-method.after',
            function (ViewRenderEventManager $viewRenderEventManager): void {
                $order = $viewRenderEventManager->getParam('order');

                if (! $order?->payment || ! str_starts_with($order->payment->method ?? '', 'mercadopago')) {
                    return;
                }

                $additional = $order->payment->additional;

                if (empty($additional)) {
                    return;
                }

                $viewRenderEventManager->addTemplate(
                    'mercadopago::admin.order-payment-details',
                    ['additional' => $additional]
                );
            }
        );
    }
}

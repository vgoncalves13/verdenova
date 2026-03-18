<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Webkul\MercadoPago\Http\Controllers\MercadoPagoController;
use Webkul\MercadoPago\Http\Controllers\MercadoPagoStatusController;

/**
 * Web routes (need session).
 */
Route::middleware([
    'web',
    'shop',
    'locale',
    'currency',
    ])->group(function () {

        Route::post('/api/mercadopago/token', [MercadoPagoController::class, 'saveToken'])
            ->name('mercadopago.token');

        Route::get('/checkout/mercadopago/pending', [MercadoPagoController::class, 'pending'])
            ->name('mercadopago.pending');

        Route::get('/api/mercadopago/status/{mpOrderId}', [MercadoPagoStatusController::class, 'show'])
            ->name('mercadopago.status');
    });

/**
 * Webhook — no session needed, no CSRF.
 */
Route::post('/api/webhook/mercadopago', [MercadoPagoController::class, 'webhook'])
    ->name('mercadopago.webhook')
    ->withoutMiddleware([VerifyCsrfToken::class]);

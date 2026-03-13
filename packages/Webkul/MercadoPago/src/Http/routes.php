<?php

use Illuminate\Support\Facades\Route;
use Webkul\MercadoPago\Http\Controllers\MercadoPagoController;
use Webkul\MercadoPago\Http\Controllers\MercadoPagoStatusController;

/**
 * Web routes (need session).
 */
Route::middleware(['web'])->group(function () {

    // Saves the Payment Brick formData in session (called from the checkout JS).
    Route::post('/api/mercadopago/token', [MercadoPagoController::class, 'saveToken'])
        ->name('mercadopago.token');

    // Pending payment page (Pix QR code / Boleto link).
    Route::get('/checkout/mercadopago/pending', [MercadoPagoController::class, 'pending'])
        ->name('mercadopago.pending');

    // Status polling endpoint used by the pending page.
    Route::get('/api/mercadopago/status/{mpOrderId}', [MercadoPagoStatusController::class, 'show'])
        ->name('mercadopago.status');
});

/**
 * Webhook — no session needed, no CSRF.
 */
Route::post('/mercadopago/webhook', [MercadoPagoController::class, 'webhook'])
    ->name('mercadopago.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

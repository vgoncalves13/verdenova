<?php

use Illuminate\Support\Facades\Route;
use Webkul\MelhorEnvio\Http\Controllers\OAuthController;

/**
 * Admin-protected route: initiates the OAuth authorization redirect.
 * Sits under the same prefix as the rest of the admin panel.
 */
Route::group([
    'middleware' => ['web', 'admin'],
    'prefix'     => config('app.admin_url'),
], function () {
    Route::get('/melhorenvio/connect', [OAuthController::class, 'connect'])
        ->name('melhorenvio.oauth.connect');
});

/**
 * Public callback: Melhor Envio redirects the user here after authorization.
 */
Route::middleware(['web'])
    ->get('/melhorenvio/oauth/callback', [OAuthController::class, 'callback'])
    ->name('melhorenvio.oauth.callback');

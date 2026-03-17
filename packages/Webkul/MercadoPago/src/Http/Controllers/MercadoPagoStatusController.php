<?php

namespace Webkul\MercadoPago\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Routing\Controller;

class MercadoPagoStatusController extends Controller
{
    /**
     * Poll the MercadoPago Orders API for the current order status.
     * Called by the pending page every 5 seconds via JavaScript.
     */
    public function show(string $mpOrderId)
    {
        $accessToken = core()->getConfigData('sales.payment_methods.mercadopago.access_token');

        $client = new Client(['base_uri' => 'https://api.mercadopago.com']);
        $response = $client->get("/v1/payments/{$mpOrderId}", [
            'headers' => ['Authorization' => "Bearer {$accessToken}"],
            'http_errors' => false,
        ]);

        $mpOrder = json_decode((string) $response->getBody(), true);

        $mpStatus = $mpOrder['status'] ?? 'unknown';

        // Normalise to: paid | pending | rejected
        if ($mpStatus === 'approved') {
            $status = 'paid';
        } elseif ($mpStatus === 'rejected' || $mpStatus === 'cancelled') {
            $status = 'rejected';
        } else {
            $status = 'pending';
        }

        return response()->json(['status' => $status]);
    }
}

<?php

namespace Webkul\MercadoPago\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Sales\Repositories\OrderRepository;

class MercadoPagoController extends Controller
{
    public function __construct(protected OrderRepository $orderRepository) {}

    /**
     * Store the Payment Brick form data in session so the payment class
     * can use it when storeOrder() calls getRedirectUrl().
     */
    public function saveToken(Request $request)
    {
        session([
            'mercadopago.payment_data' => $request->only([
                'token',
                'payment_method_id',
                'payment_type_id',
                'installments',
                'payer',
                'issuer_id',
            ]),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Show the pending-payment page (Pix QR code or Boleto link).
     */
    public function pending()
    {
        $data = session('mercadopago.pending_payment');

        if (! $data) {
            return redirect()->route('shop.home.index');
        }

        return view('mercadopago::checkout.pending', compact('data'));
    }

    /**
     * Handle asynchronous webhook notifications from MercadoPago.
     *
     * MP expects a 200 response within 22 s; it retries on failure.
     */
    public function webhook(Request $request)
    {
        try {
            $this->validateWebhookSignature($request);
        } catch (\Exception) {
            // Respond 200 even on signature mismatch to prevent MP from flooding retries.
            // Log the failure for investigation.
            \Log::warning('[MercadoPago] Webhook signature validation failed.', $request->all());

            return response()->json(['ok' => false], 200);
        }

        $type = $request->input('type') ?? $request->input('topic');
        $dataId = $request->input('data.id') ?? $request->input('id');

        if ($type === 'payment' && $dataId) {
            $this->handleOrderNotification($dataId);
        }

        return response()->json(['ok' => true], 200);
    }

    /**
     * Validate the X-Signature header sent by MercadoPago.
     *
     * @throws \Exception when signature is invalid
     */
    protected function validateWebhookSignature(Request $request): void
    {
        $secret = config('mercadopago.webhook_secret');

        // If no secret configured, skip validation (not recommended in production).
        if (! $secret) {
            return;
        }

        $xSignature = $request->header('x-signature', '');
        $xRequestId = $request->header('x-request-id', '');

        $parts = [];
        foreach (explode(',', $xSignature) as $part) {
            [$k, $v] = array_pad(explode('=', trim($part), 2), 2, '');
            $parts[$k] = $v;
        }

        $ts = $parts['ts'] ?? '';
        $v1 = $parts['v1'] ?? '';

        $dataId = $request->input('data.id', '');
        $template = "id:{$dataId};request-id:{$xRequestId};ts:{$ts};";

        $calculated = hash_hmac('sha256', $template, $secret);

        if (! hash_equals($calculated, $v1)) {
            throw new \Exception('Invalid webhook signature');
        }
    }

    /**
     * Fetch the order from MP and update the Bagisto order status.
     */
    protected function handleOrderNotification(string $mpOrderId): void
    {
        $accessToken = core()->getConfigData('sales.payment_methods.mercadopago.access_token');

        $client = new Client(['base_uri' => 'https://api.mercadopago.com']);
        $response = $client->get("/v1/payments/{$mpOrderId}", [
            'headers' => ['Authorization' => "Bearer {$accessToken}"],
            'http_errors' => false,
        ]);

        $mpOrder = json_decode((string) $response->getBody(), true);

        if (($mpOrder['status'] ?? '') !== 'approved') {
            return;
        }

        $externalReference = $mpOrder['external_reference'] ?? null;

        if (! $externalReference) {
            return;
        }

        $order = $this->orderRepository->findOneByField('id', $externalReference);

        if (! $order) {
            return;
        }

        if ($order->status === 'pending') {
            $this->orderRepository->updateOrderStatus($order, 'processing');
        }
    }
}

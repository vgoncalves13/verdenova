<?php

namespace Webkul\MercadoPago\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

class MercadoPagoController extends Controller
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,
    ) {}

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
     * Redirect to the Bagisto success page re-flashing the order_id.
     *
     * The flash is consumed when the pending page loads, so by the time the JS
     * polling detects payment and redirects here, it would be gone. This endpoint
     * reads the order_id from the non-flash pending_payment session and re-flashes it.
     */
    public function success()
    {
        $data = session('mercadopago.pending_payment');

        if (! empty($data['bagisto_order_id'])) {
            session()->flash('order_id', $data['bagisto_order_id']);
        }

        session()->forget('mercadopago.pending_payment');

        return redirect()->route('shop.checkout.onepage.success');
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

        \Log::info('[MercadoPago] Webhook recebido', ['type' => $type, 'data_id' => $dataId]);

        if ($type === 'order' && $dataId) {
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

        // Skip validation in sandbox or when no secret configured.
        // In production (live_mode = true), the secret must be set.
        if (! $secret || ! $request->boolean('live_mode')) {
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
            \Log::debug('[MercadoPago] Webhook signature mismatch', [
                'x_signature' => $xSignature,
                'x_request_id' => $xRequestId,
                'data_id' => $dataId,
                'ts' => $ts,
                'template' => $template,
                'expected_v1' => $v1,
                'calculated' => $calculated,
            ]);

            throw new \Exception('Invalid webhook signature');
        }
    }

    /**
     * Fetch the order from MP and mark the Bagisto order as paid (create invoice).
     */
    protected function handleOrderNotification(string $mpOrderId): void
    {
        $accessToken = core()->getConfigData('sales.payment_methods.mercadopago.access_token');

        $client = new Client(['base_uri' => 'https://api.mercadopago.com']);
        $response = $client->get("/v1/orders/{$mpOrderId}", [
            'headers' => ['Authorization' => "Bearer {$accessToken}"],
            'http_errors' => false,
        ]);

        $mpOrder = json_decode((string) $response->getBody(), true);

        if (($mpOrder['status'] ?? '') !== 'processed') {
            return;
        }

        // external_reference holds the cart_id set when the MP order was created.
        $cartId = $mpOrder['external_reference'] ?? null;

        if (! $cartId) {
            return;
        }

        $order = $this->orderRepository->findOneByField('cart_id', $cartId);

        if (! $order) {
            \Log::warning('[MercadoPago] Webhook: order not found for cart_id', ['cart_id' => $cartId, 'mp_order_id' => $mpOrderId]);

            return;
        }

        // Skip if already invoiced (idempotency).
        if ($order->invoices->isNotEmpty()) {
            return;
        }

        $items = [];

        foreach ($order->items as $item) {
            if ($item->qty_to_invoice > 0) {
                $items[$item->id] = $item->qty_to_invoice;
            }
        }

        if (empty($items)) {
            return;
        }

        try {
            $this->invoiceRepository->create([
                'order_id' => $order->id,
                'invoice' => ['items' => $items],
            ]);

            \Log::info('[MercadoPago] Webhook: invoice created', ['order_id' => $order->id, 'mp_order_id' => $mpOrderId]);
        } catch (\Exception $e) {
            \Log::error('[MercadoPago] Webhook: failed to create invoice', ['order_id' => $order->id, 'error' => $e->getMessage()]);
        }
    }
}

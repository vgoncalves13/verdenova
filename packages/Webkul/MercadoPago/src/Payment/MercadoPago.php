<?php

namespace Webkul\MercadoPago\Payment;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Webkul\Checkout\Facades\Cart;
use Webkul\Payment\Payment\Payment;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;

class MercadoPago extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'mercadopago';

    /**
     * Return the redirect URL after placing the order.
     *
     * Uses the Orders API (/v1/orders).
     * - Card (processed)                      → success page
     * - Pix / Boleto (action_required)        → pending page with QR / boleto link
     * - Rejected                              → throws exception shown in checkout
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        $formData = session('mercadopago.payment_data', []);

        $cart = $this->getCart();

        $totalAmount = number_format((float) $cart->grand_total, 2, '.', '');

        $paymentTypeId = $formData['payment_type_id'] ?? 'credit_card';
        $paymentMethodId = $formData['payment_method_id'] ?? '';

        $payload = $this->buildPayload($formData, $paymentTypeId, $paymentMethodId, $totalAmount, $cart);

        $response = $this->callOrdersApi($payload);

        $status = $response['status'] ?? '';

        if ($status === 'processed') {
            session()->forget(['mercadopago.payment_data', 'mercadopago.pending_payment']);

            return null;
        }

        if ($status === 'action_required') {
            $payment = $response['transactions']['payments'][0] ?? [];
            $paymentMethod = $payment['payment_method'] ?? [];

            Log::info('[MercadoPago] Pix/Boleto criado', [
                'mp_order_id' => $response['id'] ?? null,
                'payment_type' => $paymentTypeId,
            ]);

            $order = $this->createBagistoOrder($cart);

            session([
                'mercadopago.pending_payment' => [
                    'mp_payment_id' => $response['id'] ?? null,
                    'bagisto_order_id' => $order?->id,
                    'payment_type' => $paymentTypeId,
                    'ticket_url' => $paymentMethod['ticket_url'] ?? null,
                    'digitable_line' => $paymentMethod['barcode'] ?? null,
                    'qr_code' => $paymentMethod['qr_code'] ?? null,
                    'qr_code_base64' => $paymentMethod['qr_code_base64'] ?? null,
                ],
            ]);

            session()->forget('mercadopago.payment_data');

            return route('mercadopago.pending');
        }

        if (in_array($status, ['failed', 'reverted', 'cancelled'])) {
            $statusDetail = $response['transactions']['payments'][0]['status_detail'] ?? $status;

            Log::warning('[MercadoPago] Order failed/cancelled', [
                'status' => $status,
                'status_detail' => $statusDetail,
                'order_id' => $response['id'] ?? null,
            ]);

            throw new \Exception(trans('mercadopago::app.payment.rejected'));
        }

        Log::error('[MercadoPago] Unexpected order status', ['response' => $response]);

        throw new \Exception(trans('mercadopago::app.payment.error'));
    }

    /**
     * Create the Bagisto order and deactivate the cart.
     */
    protected function createBagistoOrder(mixed $cart): mixed
    {
        try {
            $data = (new OrderResource($cart))->jsonSerialize();
            $order = app(OrderRepository::class)->create($data);

            Cart::deActivateCart();

            session()->flash('order_id', $order->id);

            return $order;
        } catch (\Exception $e) {
            Log::error('[MercadoPago] Failed to create Bagisto order', ['error' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Build the Orders API payload.
     */
    protected function buildPayload(
        array $formData,
        string $paymentTypeId,
        string $paymentMethodId,
        string $totalAmount,
        mixed $cart
    ): array {
        $email = $cart->customer_email ?? ($formData['payer']['email'] ?? '');
        $firstName = $cart->customer_first_name ?? ($formData['payer']['first_name'] ?? null);
        $lastName = $cart->customer_last_name ?? ($formData['payer']['last_name'] ?? null);

        $payer = ['email' => $email];

        if ($firstName) {
            $payer['first_name'] = $firstName;
        }

        if ($lastName) {
            $payer['last_name'] = $lastName;
        }

        $base = [
            'type' => 'online',
            'processing_mode' => 'automatic',
            'external_reference' => (string) $cart->id,
            'total_amount' => $totalAmount,
            'payer' => $payer,
        ];

        if ($paymentTypeId === 'bank_transfer') {
            $base['transactions']['payments'][] = [
                'amount' => $totalAmount,
                'payment_method' => ['id' => 'pix', 'type' => 'bank_transfer'],
            ];

            return $base;
        }

        if ($paymentTypeId === 'ticket') {
            $base['transactions']['payments'][] = [
                'amount' => $totalAmount,
                'payment_method' => [
                    'id' => $paymentMethodId ?: 'bolbradesco',
                    'type' => 'ticket',
                ],
            ];

            return $base;
        }

        // Credit / debit card
        $paymentMethod = [
            'id' => $paymentMethodId,
            'type' => $paymentTypeId,
            'token' => $formData['token'] ?? '',
            'installments' => (int) ($formData['installments'] ?? 1),
        ];

        $base['transactions']['payments'][] = [
            'amount' => $totalAmount,
            'payment_method' => $paymentMethod,
        ];

        return $base;
    }

    /**
     * POST to the MercadoPago Orders API (/v1/orders).
     *
     * @throws \Exception
     */
    protected function callOrdersApi(array $payload): array
    {
        $client = new Client(['base_uri' => $this->getApiUrl()]);

        $idempotencyKey = sprintf('%s-%s', $payload['external_reference'], uniqid());

        $response = $client->post('/v1/orders', [
            'headers' => [
                'Authorization' => 'Bearer '.$this->getConfigData('access_token'),
                'X-Idempotency-Key' => $idempotencyKey,
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
            'http_errors' => false,
        ]);

        $body = json_decode((string) $response->getBody(), true);
        $httpStatus = $response->getStatusCode();

        // HTTP 402 means the order was created but a transaction failed.
        // Return the body so getRedirectUrl() can inspect the order status.
        if ($httpStatus === 402) {
            Log::warning('[MercadoPago] Orders API 402 — transaction failed', [
                'response' => $body,
                'payload' => $payload,
            ]);

            return $body['data'] ?? $body ?? [];
        }

        if ($httpStatus >= 400) {
            Log::error('[MercadoPago] Orders API error', [
                'http_status' => $httpStatus,
                'response' => $body,
                'payload' => $payload,
            ]);

            throw new \Exception(trans('mercadopago::app.payment.error'));
        }

        return $body ?? [];
    }

    /**
     * Fields that are shared across all MercadoPago methods (card, pix, boleto).
     * All other fields (active, title, sort, etc.) use the method's own code.
     */
    protected array $sharedConfigFields = [
        'access_token',
        'public_key',
        'sandbox',
        'pix_expiration',
    ];

    /**
     * Read shared operational config from the 'mercadopago' namespace.
     * Display/status fields use the method's own code so each method can be
     * independently enabled and has its own title.
     */
    public function getConfigData($field): mixed
    {
        $namespace = in_array($field, $this->sharedConfigFields) ? 'mercadopago' : $this->code;

        return core()->getConfigData('sales.payment_methods.'.$namespace.'.'.$field);
    }

    /**
     * Return the payment method image URL.
     */
    public function getImage(): string
    {
        $url = $this->getConfigData('image');

        return $url
            ? Storage::url($url)
            : asset('images/payment/mercadopago.png');
    }

    /**
     * Return the MP public key for the frontend Brick.
     */
    public function getPublicKey(): ?string
    {
        return $this->getConfigData('public_key');
    }

    /**
     * Return whether sandbox mode is enabled.
     */
    public function isSandbox(): bool
    {
        return (bool) $this->getConfigData('sandbox');
    }

    /**
     * Return the MercadoPago API base URL.
     */
    public function getApiUrl(): string
    {
        return 'https://api.mercadopago.com';
    }
}

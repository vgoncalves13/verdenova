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
     * Uses the classic Payments API (/v1/payments), which works with TEST credentials.
     * - Card (approved/accredited)           → success page
     * - Pix / Boleto (pending)               → pending page with QR / boleto link
     * - Rejected                             → throws exception shown in checkout
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        $formData = session('mercadopago.payment_data', []);

        $cart = $this->getCart();

        $totalAmount = (float) number_format((float) $cart->grand_total, 2, '.', '');

        $paymentTypeId = $formData['payment_type_id'] ?? 'credit_card';
        $paymentMethodId = $formData['payment_method_id'] ?? '';

        $payload = $this->buildPayload($formData, $paymentTypeId, $paymentMethodId, $totalAmount, $cart);

        $response = $this->callPaymentsApi($payload);

        $status = $response['status'] ?? '';
        $statusDetail = $response['status_detail'] ?? '';

        if ($status === 'approved' && $statusDetail === 'accredited') {
            session()->forget(['mercadopago.payment_data', 'mercadopago.pending_payment']);

            // Return null so Bagisto creates the order and redirects to success normally.
            return null;
        }

        if ($status === 'pending' || $status === 'in_process') {
            $qr = $response['point_of_interaction']['transaction_data'] ?? [];
            $txData = $response['transaction_details'] ?? [];

            // Create the Bagisto order before redirecting to the pending page,
            // because Bagisto only creates the order when getRedirectUrl() returns null.
            $order = $this->createBagistoOrder($cart);

            session([
                'mercadopago.pending_payment' => [
                    'mp_payment_id' => $response['id'] ?? null,
                    'bagisto_order_id' => $order?->id,
                    'payment_type' => $paymentTypeId,
                    'ticket_url' => $txData['external_resource_url'] ?? null,
                    'barcode_content' => $response['barcode']['content'] ?? null,
                    'digitable_line' => $response['barcode']['content'] ?? null,
                    'qr_code' => $qr['qr_code'] ?? null,
                    'qr_code_base64' => $qr['qr_code_base64'] ?? null,
                ],
            ]);

            session()->forget('mercadopago.payment_data');

            return route('mercadopago.pending');
        }

        if ($status === 'rejected') {
            Log::warning('[MercadoPago] Payment rejected', [
                'status_detail' => $statusDetail,
                'payment_id' => $response['id'] ?? null,
            ]);

            throw new \Exception(trans('mercadopago::app.payment.rejected'));
        }

        Log::error('[MercadoPago] Unexpected payment status', ['response' => $response]);

        throw new \Exception(trans('mercadopago::app.payment.error'));
    }

    /**
     * Create the Bagisto order and deactivate the cart.
     *
     * Used for Pix/Boleto flows where getRedirectUrl() returns a non-null URL,
     * which prevents Bagisto's OnepageController from creating the order itself.
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
     * Build the Payments API payload based on the payment type.
     */
    protected function buildPayload(
        array $formData,
        string $paymentTypeId,
        string $paymentMethodId,
        float $totalAmount,
        mixed $cart
    ): array {
        $email = $cart->customer_email ?? ($formData['payer']['email'] ?? '');

        $base = [
            'transaction_amount' => $totalAmount,
            'description' => 'Pedido #'.$cart->id,
            'external_reference' => (string) $cart->id,
            'payer' => ['email' => $email],
        ];

        if ($paymentTypeId === 'bank_transfer') {
            return array_merge($base, ['payment_method_id' => 'pix']);
        }

        if ($paymentTypeId === 'ticket') {
            return array_merge($base, [
                'payment_method_id' => $paymentMethodId ?: 'bolbradesco',
                'payment_type_id' => 'ticket',
            ]);
        }

        // Credit / debit card
        $payload = array_merge($base, [
            'token' => $formData['token'] ?? '',
            'installments' => (int) ($formData['installments'] ?? 1),
            'payment_method_id' => $paymentMethodId,
        ]);

        if (! empty($formData['issuer_id'])) {
            $payload['issuer_id'] = $formData['issuer_id'];
        }

        return $payload;
    }

    /**
     * POST to the MercadoPago Payments API (/v1/payments).
     *
     * Works with TEST-... credentials in sandbox mode.
     *
     * @throws \Exception
     */
    protected function callPaymentsApi(array $payload): array
    {
        $client = new Client(['base_uri' => $this->getApiUrl()]);

        $idempotencyKey = sprintf('%s-%s', $payload['external_reference'], uniqid());

        $response = $client->post('/v1/payments', [
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

        if ($httpStatus >= 400) {
            Log::error('[MercadoPago] Payments API error', [
                'http_status' => $httpStatus,
                'response' => $body,
                'payload' => $payload,
            ]);

            throw new \Exception(trans('mercadopago::app.payment.error'));
        }

        return $body ?? [];
    }

    /**
     * Return the payment method image URL.
     * Falls back to the official MercadoPago logo.
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

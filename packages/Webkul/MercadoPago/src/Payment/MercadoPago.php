<?php

namespace Webkul\MercadoPago\Payment;

use GuzzleHttp\Client;
use Webkul\Payment\Payment\Payment;

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
     * Handles the full MP Orders API flow:
     * - Card (processed/accredited)  → success page
     * - Pix / Boleto (action_required) → pending page
     * - Rejected                       → throws exception shown in checkout
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        $formData = session('mercadopago.payment_data', []);

        $cart = $this->getCart();

        $totalAmount = number_format((float) $cart->grand_total, 2, '.', '');

        $paymentTypeId  = $formData['payment_type_id'] ?? 'credit_card';
        $paymentMethodId = $formData['payment_method_id'] ?? '';

        $payment = [
            'amount' => $totalAmount,
            'payment_method' => $this->buildPaymentMethod($formData, $paymentTypeId, $paymentMethodId),
        ];

        $payer = ['email' => $cart->customer_email ?? ($formData['payer']['email'] ?? '')];

        $payload = [
            'type'              => 'online',
            'processing_mode'   => 'automatic',
            'total_amount'      => $totalAmount,
            'external_reference' => (string) $cart->id,
            'transactions'      => [
                'payments' => [$payment],
            ],
            'payer' => $payer,
        ];

        $response = $this->callOrdersApi($payload);

        $status       = $response['status'] ?? '';
        $statusDetail = $response['status_detail'] ?? '';

        if ($status === 'processed' && $statusDetail === 'accredited') {
            session()->forget(['mercadopago.payment_data', 'mercadopago.pending_payment']);

            return route('shop.checkout.onepage.success');
        }

        if ($status === 'action_required') {
            $txPayment = $response['transactions']['payments'][0] ?? [];
            $ticket    = $txPayment['ticket'] ?? [];
            $qr        = $txPayment['point_of_interaction']['transaction_data'] ?? [];

            session([
                'mercadopago.pending_payment' => [
                    'mp_order_id'      => $response['id'] ?? null,
                    'payment_type'     => $paymentTypeId,
                    'ticket_url'       => $ticket['url'] ?? ($txPayment['ticket_url'] ?? null),
                    'barcode_content'  => $ticket['barcode']['content'] ?? null,
                    'digitable_line'   => $ticket['barcode']['content'] ?? null,
                    'qr_code'          => $qr['qr_code'] ?? null,
                    'qr_code_base64'   => $qr['qr_code_base64'] ?? null,
                ],
            ]);

            session()->forget('mercadopago.payment_data');

            return route('mercadopago.pending');
        }

        if ($status === 'rejected') {
            throw new \Exception(trans('mercadopago::app.payment.rejected'));
        }

        throw new \Exception(trans('mercadopago::app.payment.error'));
    }

    /**
     * Build the payment_method object for the Orders API payload.
     */
    protected function buildPaymentMethod(array $formData, string $paymentTypeId, string $paymentMethodId): array
    {
        if ($paymentTypeId === 'bank_transfer') {
            // Pix
            return [
                'id'   => 'pix',
                'type' => 'bank_transfer',
            ];
        }

        if ($paymentTypeId === 'ticket') {
            // Boleto
            return [
                'id'   => $paymentMethodId ?: 'bolbradesco',
                'type' => 'ticket',
            ];
        }

        // Credit / debit card
        $method = [
            'id'           => $paymentMethodId,
            'type'         => $paymentTypeId,
            'token'        => $formData['token'] ?? '',
            'installments' => (int) ($formData['installments'] ?? 1),
        ];

        if (! empty($formData['issuer_id'])) {
            $method['issuer_id'] = $formData['issuer_id'];
        }

        return $method;
    }

    /**
     * POST to the MercadoPago Orders API.
     *
     * @throws \Exception
     */
    protected function callOrdersApi(array $payload): array
    {
        $client = new Client(['base_uri' => $this->getApiUrl()]);

        $idempotencyKey = sprintf('%s-%s', $payload['external_reference'], uniqid());

        $response = $client->post('/v1/orders', [
            'headers' => [
                'Authorization'   => 'Bearer ' . $this->getConfigData('access_token'),
                'X-Idempotency-Key' => $idempotencyKey,
                'Content-Type'    => 'application/json',
            ],
            'json'    => $payload,
            'http_errors' => false,
        ]);

        $body = json_decode((string) $response->getBody(), true);

        $httpStatus = $response->getStatusCode();

        if ($httpStatus >= 500) {
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
            ? \Illuminate\Support\Facades\Storage::url($url)
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

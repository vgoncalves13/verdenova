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
            $mpOrderId = $response['id'] ?? null;
            $mpPaymentId = $response['transactions']['payments'][0]['id'] ?? null;

            $additional = $this->buildAdditionalData($formData, $mpOrderId, $mpPaymentId, $cart);

            // Order doesn't exist yet — Bagisto creates it after getRedirectUrl() returns null.
            // Store data in session; the service provider listener will apply it on checkout.order.save.after.
            session(['mercadopago.payment_additional' => $additional]);

            session()->forget(['mercadopago.payment_data', 'mercadopago.pending_payment', 'mercadopago.installment_total']);

            return null;
        }

        if ($status === 'action_required') {
            $payment = $response['transactions']['payments'][0] ?? [];
            $paymentMethod = $payment['payment_method'] ?? [];

            $mpOrderId = $response['id'] ?? null;
            $mpPaymentId = $payment['id'] ?? null;

            Log::info('[MercadoPago] Pix/Boleto criado', [
                'mp_order_id' => $mpOrderId,
                'payment_type' => $paymentTypeId,
            ]);

            $additional = $this->buildAdditionalData($formData, $mpOrderId, $mpPaymentId, $cart);
            $order = $this->createBagistoOrder($cart, $additional);

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
    protected function createBagistoOrder(mixed $cart, array $additional = []): mixed
    {
        try {
            $data = (new OrderResource($cart))->jsonSerialize();

            if (! empty($additional)) {
                $data['payment']['additional'] = $additional;
            }

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
     * Build the array to persist in order_payment.additional.
     */
    protected function buildAdditionalData(
        array $formData,
        ?string $mpOrderId,
        ?string $mpPaymentId,
        mixed $cart
    ): array {
        $additional = [
            'mp_order_id' => $mpOrderId,
            'mp_payment_id' => $mpPaymentId,
            'payment_method_id' => $formData['payment_method_id'] ?? null,
        ];

        $installments = (int) ($formData['installments'] ?? 1);
        $installmentTotal = session('mercadopago.installment_total');

        if ($installments > 1 && $installmentTotal !== null) {
            $totalPaid = (float) $installmentTotal;
            $interestAmount = $totalPaid - (float) $cart->grand_total;

            $additional['installments'] = $installments;
            $additional['total_paid'] = number_format($totalPaid, 2, '.', '');
            $additional['interest_amount'] = number_format($interestAmount, 2, '.', '');
        }

        return $additional;
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
            $cpf = preg_replace('/\D/', '', $formData['cpf'] ?? '');

            if ($cpf) {
                $payer['identification'] = ['type' => 'CPF', 'number' => $cpf];
            }

            $billing = $cart->billing_address;

            if ($billing) {
                $rawAddress = $billing->address ?? '';
                $parts = array_map('trim', explode(',', $rawAddress, 2));
                $streetName = $parts[0] ?? $rawAddress;
                $streetNumber = $parts[1] ?? '0';

                $payer['address'] = [
                    'zip_code' => preg_replace('/\D/', '', $billing->postcode ?? ''),
                    'street_name' => $streetName,
                    'street_number' => $streetNumber ?: '0',
                    'neighborhood' => $billing->city ?? '',
                    'city' => $billing->city ?? '',
                    'state' => $billing->state ?? '',
                ];
            }

            $base['payer'] = $payer;

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
        $installments = (int) ($formData['installments'] ?? 1);

        $chargeAmount = $installments > 1
            ? ($this->fetchInstallmentTotal($paymentMethodId, $totalAmount, $installments) ?? $totalAmount)
            : $totalAmount;

        if ($chargeAmount !== $totalAmount) {
            $base['total_amount'] = $chargeAmount;
            session(['mercadopago.installment_total' => $chargeAmount]);

            Log::info('[MercadoPago] Installment total with fee', [
                'installments' => $installments,
                'original' => $totalAmount,
                'with_fee' => $chargeAmount,
            ]);
        }

        $paymentMethod = [
            'id' => $paymentMethodId,
            'type' => $paymentTypeId,
            'token' => $formData['token'] ?? '',
            'installments' => $installments,
        ];

        $base['transactions']['payments'][] = [
            'amount' => $chargeAmount,
            'payment_method' => $paymentMethod,
        ];

        return $base;
    }

    /**
     * Query the MP installments API and return the total amount with fees
     * for the given payment method and installment count.
     * Returns null if unavailable or installments == 1.
     */
    protected function fetchInstallmentTotal(string $paymentMethodId, string $amount, int $installments): ?string
    {
        try {
            $client = new Client(['base_uri' => $this->getApiUrl()]);

            $response = $client->get('/v1/payment_methods/installments', [
                'headers' => ['Authorization' => 'Bearer '.$this->getConfigData('access_token')],
                'query' => [
                    'payment_method_id' => $paymentMethodId,
                    'amount' => $amount,
                    'locale' => 'pt-BR',
                ],
                'http_errors' => false,
            ]);

            $data = json_decode((string) $response->getBody(), true);

            foreach ($data[0]['payer_costs'] ?? [] as $cost) {
                if ((int) $cost['installments'] === $installments) {
                    return number_format((float) $cost['total_amount'], 2, '.', '');
                }
            }
        } catch (\Exception $e) {
            Log::warning('[MercadoPago] Failed to fetch installment total', ['error' => $e->getMessage()]);
        }

        return null;
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

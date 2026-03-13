<?php

namespace Webkul\MelhorEnvio\Carriers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Core\Models\CoreConfig;
use Webkul\Shipping\Carriers\AbstractShipping;

class MelhorEnvio extends AbstractShipping
{
    protected $code   = 'melhorenvio';
    protected $method = 'melhorenvio_melhorenvio';

    public function calculate(): array|false
    {
        if (! $this->isAvailable()) {
            return false;
        }

        $token = $this->getAccessToken();

        if (! $token) {
            Log::warning('MelhorEnvio: nenhum token OAuth disponível. Conecte o app no painel admin.');
            return false;
        }

        $cart = Cart::getCart();
        $address = $cart->shipping_address;

        if (! $address || ! $address->postcode) {
            return false;
        }

        $destPostcode   = preg_replace('/\D/', '', $address->postcode);
        $originPostcode = preg_replace('/\D/', '', $this->getConfigData('origin_postcode') ?? '');

        if (strlen($destPostcode) !== 8 || strlen($originPostcode) !== 8) {
            return false;
        }

        $products = $this->buildProductsPayload($cart);

        if (empty($products)) {
            return false;
        }

        $payload = [
            'from'     => ['postal_code' => $originPostcode],
            'to'       => ['postal_code' => $destPostcode],
            'products' => $products,
            'options'  => [
                'receipt'  => false,
                'own_hand' => false,
            ],
        ];

        try {
            $baseUrl = $this->baseUrl();

            $response = Http::withToken($token)
                ->withHeaders(['User-Agent' => 'EcoVasos/1.0 (contato@ecovasos.com.br)'])
                ->timeout(10)
                ->post("{$baseUrl}/api/v2/me/shipment/calculate", $payload);

            if (! $response->successful()) {
                Log::warning('MelhorEnvio API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            return $this->parseRates($response->json());

        } catch (\Throwable $e) {
            Log::error('MelhorEnvio exception', ['message' => $e->getMessage()]);
            return false;
        }
    }

    // -------------------------------------------------------------------------
    // Payload builder
    // -------------------------------------------------------------------------

    /**
     * Build the products array using per-product dimensions from Bagisto.
     * Falls back to the configured defaults when a product has no dimensions.
     */
    private function buildProductsPayload($cart): array
    {
        $defaultWeight = (float) ($this->getConfigData('default_weight') ?? 0.3);
        $defaultHeight = (float) ($this->getConfigData('default_height') ?? 10);
        $defaultWidth  = (float) ($this->getConfigData('default_width')  ?? 15);
        $defaultLength = (float) ($this->getConfigData('default_length') ?? 20);

        $products = [];

        foreach ($cart->items as $item) {
            $product = $item->product;

            $products[] = [
                'id'              => (string) $product->id,
                'width'           => (float) ($product->width  ?: $defaultWidth),
                'height'          => (float) ($product->height ?: $defaultHeight),
                'length'          => (float) ($product->length ?: $defaultLength),
                'weight'          => max((float) ($product->weight ?: $defaultWeight), 0.1),
                'insurance_value' => round((float) $item->base_price, 2),
                'quantity'        => (int) $item->quantity,
            ];
        }

        return $products;
    }

    // -------------------------------------------------------------------------
    // Response parser
    // -------------------------------------------------------------------------

    private function parseRates(array $services): array
    {
        $rates = [];

        foreach ($services as $service) {
            if (! empty($service['error']) || empty($service['custom_price'])) {
                continue;
            }

            $price = (float) $service['custom_price'];
            $deliveryTime = $service['custom_delivery_time'] ?? $service['delivery_time'] ?? '?';

            $rate = new CartShippingRate;
            $rate->carrier            = $this->getCode();
            $rate->carrier_title      = $this->getConfigData('title') ?? 'Melhor Envio';
            $rate->method             = 'melhorenvio_' . $service['id'];
            $rate->method_title       = ($service['company']['name'] ?? '') . ' ' . ($service['name'] ?? '');
            $rate->method_description = json_encode([
                'days' => 'Entrega em ' . $deliveryTime . ' dias úteis',
                'logo' => $service['company']['picture'] ?? null,
            ]);
            $rate->price              = core()->convertPrice($price);
            $rate->base_price         = $price;

            $rates[] = $rate;
        }

        return $rates;
    }

    // -------------------------------------------------------------------------
    // OAuth token management
    // -------------------------------------------------------------------------

    private function getAccessToken(): ?string
    {
        // Personal token takes priority — no OAuth needed (dev/sandbox use case)
        $personalToken = $this->getConfigData('token');

        if ($personalToken) {
            return $personalToken;
        }

        // OAuth token flow
        $accessToken = $this->getConfigData('access_token');

        if (! $accessToken) {
            return null;
        }

        $expiresAt = $this->getConfigData('token_expires_at');

        // Refresh proactively when less than 1 day remains
        if ($expiresAt && now()->addDay()->gt($expiresAt)) {
            $accessToken = $this->refreshToken() ?? $accessToken;
        }

        return $accessToken;
    }

    private function refreshToken(): ?string
    {
        $refreshToken = $this->getConfigData('refresh_token');

        if (! $refreshToken) {
            return null;
        }

        try {
            $response = Http::withHeaders(['User-Agent' => 'EcoVasos/1.0 (contato@ecovasos.com.br)'])
                ->timeout(10)
                ->post($this->baseUrl() . '/oauth/token', [
                    'grant_type'    => 'refresh_token',
                    'client_id'     => $this->getConfigData('client_id'),
                    'client_secret' => $this->getConfigData('client_secret'),
                    'refresh_token' => $refreshToken,
                ]);

            if (! $response->successful()) {
                Log::warning('MelhorEnvio token refresh failed', ['body' => $response->body()]);
                return null;
            }

            $data = $response->json();

            $this->storeTokens($data);

            return $data['access_token'];

        } catch (\Throwable $e) {
            Log::error('MelhorEnvio refresh exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    public static function storeTokens(array $data): void
    {
        CoreConfig::updateOrCreate(
            ['code' => 'sales.carriers.melhorenvio.access_token'],
            ['value' => $data['access_token']]
        );

        CoreConfig::updateOrCreate(
            ['code' => 'sales.carriers.melhorenvio.refresh_token'],
            ['value' => $data['refresh_token']]
        );

        CoreConfig::updateOrCreate(
            ['code' => 'sales.carriers.melhorenvio.token_expires_at'],
            ['value' => now()->addSeconds((int) $data['expires_in'])->toDateTimeString()]
        );
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function baseUrl(): string
    {
        return $this->getConfigData('environment') === 'production'
            ? 'https://melhorenvio.com.br'
            : 'https://sandbox.melhorenvio.com.br';
    }
}

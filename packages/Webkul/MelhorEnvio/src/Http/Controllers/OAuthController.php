<?php

namespace Webkul\MelhorEnvio\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\MelhorEnvio\Carriers\MelhorEnvio;

class OAuthController extends Controller
{
    /**
     * Redirect the admin to Melhor Envio's OAuth authorization page.
     */
    public function connect()
    {
        $environment = core()->getConfigData('sales.carriers.melhorenvio.environment') ?? 'sandbox';
        $clientId    = core()->getConfigData('sales.carriers.melhorenvio.client_id');

        if (! $clientId) {
            return redirect($this->configUrl())
                ->with('error', 'Configure o Client ID antes de conectar.');
        }

        $baseUrl = $this->baseUrl($environment);

        $query = http_build_query([
            'client_id'     => $clientId,
            'redirect_uri'  => route('melhorenvio.oauth.callback'),
            'response_type' => 'code',
            'scope'         => 'shipping-calculate',
        ]);

        return redirect("{$baseUrl}/oauth/authorize?{$query}");
    }

    /**
     * Handle the OAuth callback from Melhor Envio.
     * Exchanges the authorization code for an access token and stores it.
     */
    public function callback(Request $request)
    {
        $code = $request->get('code');

        if (! $code) {
            return redirect($this->configUrl())
                ->with('error', 'Autorização negada ou código ausente.');
        }

        $environment  = core()->getConfigData('sales.carriers.melhorenvio.environment') ?? 'sandbox';
        $clientId     = core()->getConfigData('sales.carriers.melhorenvio.client_id');
        $clientSecret = core()->getConfigData('sales.carriers.melhorenvio.client_secret');
        $baseUrl      = $this->baseUrl($environment);

        try {
            $response = Http::withHeaders(['User-Agent' => 'EcoVasos/1.0 (contato@ecovasos.com.br)'])
                ->timeout(15)
                ->post("{$baseUrl}/oauth/token", [
                    'grant_type'    => 'authorization_code',
                    'client_id'     => $clientId,
                    'client_secret' => $clientSecret,
                    'redirect_uri'  => route('melhorenvio.oauth.callback'),
                    'code'          => $code,
                ]);

            if (! $response->successful()) {
                Log::error('MelhorEnvio OAuth callback failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return redirect($this->configUrl())
                    ->with('error', 'Falha ao obter token: ' . ($response->json('message') ?? $response->body()));
            }

            MelhorEnvio::storeTokens($response->json());

        } catch (\Throwable $e) {
            Log::error('MelhorEnvio OAuth exception', ['message' => $e->getMessage()]);

            return redirect($this->configUrl())
                ->with('error', 'Erro ao conectar: ' . $e->getMessage());
        }

        return redirect($this->configUrl())
            ->with('success', 'Melhor Envio conectado com sucesso!');
    }

    private function baseUrl(string $environment): string
    {
        return $environment === 'production'
            ? 'https://melhorenvio.com.br'
            : 'https://sandbox.melhorenvio.com.br';
    }

    private function configUrl(): string
    {
        return url(config('app.admin_url') . '/configuration/sales/carriers');
    }
}

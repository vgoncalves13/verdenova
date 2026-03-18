<?php

namespace Webkul\MercadoPago\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class TestMercadoPagoPix extends Command
{
    protected $signature = 'mercadopago:test-pix
                            {--email= : E-mail do comprador de teste}
                            {--amount=10.00 : Valor do pagamento}';

    protected $description = 'Cria uma order Pix de teste no MercadoPago e verifica aprovação automática (APRO)';

    public function handle(): int
    {
        $accessToken = core()->getConfigData('sales.payment_methods.mercadopago.access_token');

        if (! $accessToken) {
            $this->error('Access Token do MercadoPago não configurado no admin.');

            return self::FAILURE;
        }

        $email = $this->option('email') ?? $this->ask('E-mail do comprador de teste', 'test@testuser.com');
        $amount = number_format((float) $this->option('amount'), 2, '.', '');

        $this->info('Criando order Pix de teste (APRO)...');
        $this->line("  Valor: <comment>R$ {$amount}</comment>");
        $this->line("  E-mail: <comment>{$email}</comment>");
        $this->newLine();

        $client = new Client(['base_uri' => 'https://api.mercadopago.com']);

        $payload = [
            'type' => 'online',
            'processing_mode' => 'automatic',
            'external_reference' => 'test-pix-'.uniqid(),
            'total_amount' => $amount,
            'payer' => [
                'email' => $email,
                'first_name' => 'APRO',
            ],
            'transactions' => [
                'payments' => [
                    [
                        'amount' => $amount,
                        'payment_method' => [
                            'id' => 'pix',
                            'type' => 'bank_transfer',
                        ],
                    ],
                ],
            ],
        ];

        $response = $client->post('/v1/orders', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'X-Idempotency-Key' => 'test-pix-'.uniqid(),
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
            'http_errors' => false,
        ]);

        $body = json_decode((string) $response->getBody(), true);
        $httpStatus = $response->getStatusCode();

        if ($httpStatus >= 400) {
            $this->error("Erro ao criar order (HTTP {$httpStatus}):");
            $this->line(json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return self::FAILURE;
        }

        $orderId = $body['id'] ?? null;
        $orderStatus = $body['status'] ?? 'unknown';

        $this->info("Order criada: <comment>{$orderId}</comment>");
        $this->info("Status inicial: <comment>{$orderStatus}</comment>");
        $this->newLine();

        if ($orderStatus === 'processed') {
            $this->info('✓ Pagamento aprovado instantaneamente!');

            return self::SUCCESS;
        }

        // Aguarda aprovação automática do MercadoPago (APRO)
        $this->line('Aguardando aprovação automática...');

        $approved = $this->waitForApproval($client, $accessToken, $orderId);

        if ($approved) {
            $this->newLine();
            $this->info('✓ Pagamento aprovado com sucesso!');
            $this->line('  O fluxo de Pix está funcionando corretamente.');

            return self::SUCCESS;
        }

        $this->newLine();
        $this->warn('Aprovação automática não detectada no tempo esperado.');
        $this->line("Verifique manualmente: GET /v1/orders/{$orderId}");

        return self::FAILURE;
    }

    protected function waitForApproval(Client $client, string $accessToken, string $orderId, int $maxAttempts = 10): bool
    {
        for ($i = 1; $i <= $maxAttempts; $i++) {
            sleep(2);

            $response = $client->get("/v1/orders/{$orderId}", [
                'headers' => ['Authorization' => "Bearer {$accessToken}"],
                'http_errors' => false,
            ]);

            $body = json_decode((string) $response->getBody(), true);
            $status = $body['status'] ?? '';

            $this->line("  Tentativa {$i}/{$maxAttempts}: <comment>{$status}</comment>");

            if ($status === 'processed') {
                return true;
            }

            if (in_array($status, ['failed', 'cancelled', 'reverted'])) {
                $this->error("Order com status inesperado: {$status}");

                return false;
            }
        }

        return false;
    }
}

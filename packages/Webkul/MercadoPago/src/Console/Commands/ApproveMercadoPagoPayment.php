<?php

namespace Webkul\MercadoPago\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ApproveMercadoPagoPayment extends Command
{
    protected $signature = 'mercadopago:approve {id? : ID da order MercadoPago (ORD...)}';

    protected $description = 'Verifica o status de uma order Pix/Boleto no MercadoPago';

    public function handle(): int
    {
        $orderId = $this->argument('id') ?? $this->askForOrderId();

        if (! $orderId) {
            $this->error('ID do pedido não informado.');

            return self::FAILURE;
        }

        $accessToken = core()->getConfigData('sales.payment_methods.mercadopago.access_token');

        if (! $accessToken) {
            $this->error('Access Token do MercadoPago não configurado no admin.');

            return self::FAILURE;
        }

        $client = new Client(['base_uri' => 'https://api.mercadopago.com']);

        $this->info("Buscando status da order <comment>{$orderId}</comment>...");

        $response = $client->get("/v1/orders/{$orderId}", [
            'headers' => ['Authorization' => "Bearer {$accessToken}"],
            'http_errors' => false,
        ]);

        $body = json_decode((string) $response->getBody(), true);
        $status = $response->getStatusCode();

        if ($status !== 200) {
            $this->error("Erro ao buscar pedido (HTTP {$status}):");
            $this->line(json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return self::FAILURE;
        }

        $orderStatus = $body['status'] ?? 'unknown';
        $paymentStatus = $body['transactions']['payments'][0]['status'] ?? 'unknown';

        $this->info("Status da order: <comment>{$orderStatus}</comment>");
        $this->info("Status do pagamento: <comment>{$paymentStatus}</comment>");

        if ($orderStatus === 'processed') {
            $this->info('✓ Pagamento aprovado!');
        } else {
            $this->newLine();
            $this->warn('Pagamento ainda não aprovado.');
            $this->line('');
            $this->line('Para testar aprovação automática de Pix, crie uma order com:');
            $this->line('  payer.first_name = <comment>APRO</comment>');
            $this->line('');
            $this->line('Isso faz o MercadoPago aprovar automaticamente em modo teste.');
        }

        return self::SUCCESS;
    }

    protected function askForOrderId(): ?string
    {
        $recent = $this->getRecentOrderIdFromLog();

        if ($recent) {
            $confirmed = $this->confirm("Usar ID mais recente do log: <comment>{$recent}</comment>?", true);

            if ($confirmed) {
                return $recent;
            }
        }

        return $this->ask('Informe o ID da order MercadoPago (ORD...)');
    }

    protected function getRecentOrderIdFromLog(): ?string
    {
        $logPath = storage_path('logs/laravel.log');

        if (! file_exists($logPath)) {
            return null;
        }

        $lines = array_reverse(file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        foreach ($lines as $line) {
            if (str_contains($line, 'Pix/Boleto criado') && preg_match('/"mp_order_id":"([^"]+)"/', $line, $m)) {
                return $m[1];
            }
        }

        return null;
    }
}

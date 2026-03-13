@php
    $accessToken = core()->getConfigData('sales.carriers.melhorenvio.access_token');
    $expiresAt   = core()->getConfigData('sales.carriers.melhorenvio.token_expires_at');
    $isConnected = ! empty($accessToken);
@endphp

<div class="mb-4">
    @if ($isConnected)
        <div class="flex items-center gap-2 rounded border border-green-300 bg-green-50 px-4 py-3 dark:border-green-700 dark:bg-green-950">
            <span class="text-green-600 dark:text-green-400">&#10003;</span>
            <div>
                <p class="font-medium text-green-700 dark:text-green-300">Conectado ao Melhor Envio</p>
                @if ($expiresAt)
                    <p class="text-sm text-green-600 dark:text-green-400">
                        Token expira em: {{ \Carbon\Carbon::parse($expiresAt)->format('d/m/Y') }}
                    </p>
                @endif
            </div>
        </div>
    @else
        <div class="flex items-center gap-2 rounded border border-red-300 bg-red-50 px-4 py-3 dark:border-red-700 dark:bg-red-950">
            <span class="text-red-600 dark:text-red-400">&#10007;</span>
            <p class="font-medium text-red-700 dark:text-red-300">Não conectado ao Melhor Envio</p>
        </div>
    @endif

    <a
        href="{{ route('melhorenvio.oauth.connect') }}"
        class="mt-3 inline-block rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
    >
        {{ $isConnected ? 'Reconectar ao Melhor Envio' : 'Conectar ao Melhor Envio' }}
    </a>

    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
        Você será redirecionado para autorizar o aplicativo.
        Certifique-se de salvar o <strong>Client ID</strong> e o <strong>Client Secret</strong> antes de clicar.
    </p>
</div>

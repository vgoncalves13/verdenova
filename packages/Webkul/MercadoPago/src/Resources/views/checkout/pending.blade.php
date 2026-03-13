<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('mercadopago::app.pending.title') — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-md max-w-lg w-full p-8 text-center">

        <img src="https://http2.mlstatic.com/frontend-assets/mp-web-navigation/ui-navigation/5.21.22/mercadopago/logo__small@2x.png"
             alt="Mercado Pago" class="h-10 mx-auto mb-6">

        <h1 class="text-2xl font-semibold text-gray-800 mb-2">
            @lang('mercadopago::app.pending.title')
        </h1>

        <p class="text-gray-500 mb-8">
            Seu pedido foi recebido. Aguardando confirmação do pagamento.
        </p>

        {{-- ── PIX ──────────────────────────────────────────────────────────── --}}
        @if ($data['payment_type'] === 'bank_transfer' && ! empty($data['qr_code_base64']))
            <div class="mb-6">
                <img src="data:image/jpeg;base64,{{ $data['qr_code_base64'] }}"
                     alt="QR Code Pix"
                     class="mx-auto w-56 h-56 border rounded-xl">
            </div>

            @if (! empty($data['qr_code']))
                <p class="text-sm font-medium text-gray-700 mb-2">
                    @lang('mercadopago::app.pending.pix-copy')
                </p>
                <div class="flex items-center gap-2 mb-6">
                    <input id="pix-code"
                           type="text"
                           readonly
                           value="{{ $data['qr_code'] }}"
                           class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-xs text-gray-600 bg-gray-50 focus:outline-none">
                    <button onclick="copyPix()"
                            class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition">
                        Copiar
                    </button>
                </div>
                <p id="pix-copied-msg" class="text-sm text-teal-600 hidden mb-4">
                    @lang('mercadopago::app.pending.pix-copied')
                </p>
            @endif
        @endif

        {{-- ── BOLETO ───────────────────────────────────────────────────────── --}}
        @if ($data['payment_type'] === 'ticket' && ! empty($data['ticket_url']))
            <a href="{{ $data['ticket_url'] }}"
               target="_blank"
               rel="noopener"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-xl transition mb-6">
                @lang('mercadopago::app.pending.boleto-button')
            </a>

            @if (! empty($data['digitable_line']))
                <p class="text-xs text-gray-500 break-all mb-4">
                    {{ $data['digitable_line'] }}
                </p>
            @endif
        @endif

        {{-- ── Status polling ───────────────────────────────────────────────── --}}
        <p id="status-msg" class="text-sm text-gray-500">
            @lang('mercadopago::app.pending.waiting')
        </p>
    </div>

    <script>
        function copyPix() {
            const input = document.getElementById('pix-code');
            input.select();
            document.execCommand('copy');
            document.getElementById('pix-copied-msg').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('pix-copied-msg').classList.add('hidden');
            }, 3000);
        }

        @if (! empty($data['mp_order_id']))
        (function poll() {
            const url = '{{ route('mercadopago.status', $data['mp_order_id']) }}';
            const successUrl = '{{ route('shop.checkout.onepage.success') }}';

            fetch(url)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'paid') {
                        document.getElementById('status-msg').textContent =
                            '{{ trans('mercadopago::app.pending.confirmed') }}';
                        window.location.href = successUrl;
                    } else if (data.status === 'pending') {
                        setTimeout(poll, 5000);
                    }
                    // rejected → stop polling
                })
                .catch(() => setTimeout(poll, 10000));
        })();
        @endif
    </script>
</body>
</html>

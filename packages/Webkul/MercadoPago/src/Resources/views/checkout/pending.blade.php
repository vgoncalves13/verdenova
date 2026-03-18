<x-eco-vasos-theme::layouts
    :has-header="true"
    :has-feature="false"
    :has-footer="true"
>
    <x-slot:title>
        @lang('mercadopago::app.pending.title') — {{ config('app.name') }}
    </x-slot:title>

    <div class="min-h-[60vh] bg-[#f4f1ea] py-16 px-4">
        <div class="mx-auto max-w-md">

            <!-- Header -->
            <div class="mb-6 text-center">
                @if ($data['payment_type'] === 'bank_transfer')
                    <div class="mb-3 flex justify-center">
                        <svg class="h-12 w-12 text-[#016630]" viewBox="0 0 512 512" fill="currentColor">
                            <path d="M242.4 292.5C247.8 287.1 257.1 287.1 262.5 292.5L339.5 369.5C353.7 383.7 372.6 391.5 392.6 391.5H407.7L310.2 488.1C280.3 517.7 231.1 517.7 201.2 488.1L103.3 391.5H118.4C138.4 391.5 157.3 383.7 171.5 369.5L242.4 292.5zM262.5 218.9C257.1 224.4 247.8 224.4 242.4 218.9L171.5 141.9C157.3 127.7 138.4 119.9 118.4 119.9H103.3L201.2 23.3C231.1-6.3 280.3-6.3 310.2 23.3L407.7 119.9H392.6C372.6 119.9 353.7 127.7 339.5 141.9L262.5 218.9zM112 144H118.4C132 144 144.1 149.3 153 158.1L240.1 244.4C253.1 257.4 258.9 257.4 271.9 244.4L358.1 158.1C367 149.3 379.1 144 392.6 144H399C419 144 438.6 151.5 453.4 165.2L484.3 195.2C512.5 222.9 512.5 268.7 484.3 296.5L453.4 326.5C438.6 340.2 419 347.7 399 347.7H392.6C379.1 347.7 367 342.4 358.1 333.6L271.9 247.3C258.9 234.3 253.1 234.3 240.1 247.3L153 333.6C144.1 342.4 132 347.7 118.4 347.7H112C92 347.7 72.4 340.2 57.6 326.5L26.7 296.5C-1.5 268.7-1.5 222.9 26.7 195.2L57.6 165.2C72.4 151.5 92 144 112 144z"/>
                        </svg>
                    </div>
                    <h1 style="font-family:'DM Serif Display',serif" class="text-3xl text-[#1a2214]">Pague com PIX</h1>
                    <p class="mt-1 text-sm text-zinc-500">Escaneie o QR Code ou copie o código</p>
                @else
                    <div class="mb-3 flex justify-center">
                        <svg class="h-12 w-12 text-[#016630]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="M7 8v8M10 8v8M14 8v8M17 8v8M5 8v8"/>
                        </svg>
                    </div>
                    <h1 style="font-family:'DM Serif Display',serif" class="text-3xl text-[#1a2214]">Boleto Gerado</h1>
                    <p class="mt-1 text-sm text-zinc-500">Pague em qualquer banco ou lotérica</p>
                @endif

                @if (! empty($data['order_id']))
                    <p class="mt-2 text-xs text-zinc-400">Pedido #{{ $data['order_id'] }}</p>
                @endif
            </div>

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm">

                {{-- ── PIX ──────────────────────────────────────────────────────────── --}}
                @if ($data['payment_type'] === 'bank_transfer' && ! empty($data['qr_code_base64']))
                    <div class="flex flex-col items-center p-6">

                        <!-- QR Code pequeno -->
                        <img
                            src="data:image/jpeg;base64,{{ $data['qr_code_base64'] }}"
                            alt="QR Code Pix"
                            class="mb-5 h-40 w-40 rounded-lg border border-[#016630]/20"
                        >

                        @if (! empty($data['qr_code']))
                            <p class="mb-2 text-xs font-medium text-zinc-500">PIX Copia e Cola</p>

                            <!-- Input + botão copiar -->
                            <div class="flex w-full items-center gap-2">
                                <input
                                    id="pix-code"
                                    type="text"
                                    readonly
                                    value="{{ $data['qr_code'] }}"
                                    class="flex-1 rounded-xl border border-[#016630]/20 bg-[#f4f1ea] px-3 py-2.5 text-xs text-zinc-600 outline-none"
                                >
                                <button
                                    id="pix-copy-btn"
                                    onclick="copyPix()"
                                    class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-[#016630] px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90"
                                >
                                    <svg id="pix-icon-copy" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="9" y="9" width="13" height="13" rx="2"/>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                                    </svg>
                                    <svg id="pix-icon-check" class="hidden h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path d="M20 6 9 17l-5-5"/>
                                    </svg>
                                    <span id="pix-copy-label">Copiar</span>
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- ── BOLETO ───────────────────────────────────────────────────────── --}}
                @if ($data['payment_type'] === 'ticket' && ! empty($data['ticket_url']))
                    <div class="flex flex-col items-center p-8">
                        <a
                            href="{{ $data['ticket_url'] }}"
                            target="_blank"
                            rel="noopener"
                            class="inline-block rounded-xl bg-[#016630] px-10 py-4 text-base font-semibold text-white transition hover:opacity-90"
                        >
                            @lang('mercadopago::app.pending.boleto-button')
                        </a>

                        @if (! empty($data['digitable_line']))
                            <p class="mt-5 break-all text-center text-xs text-zinc-400">
                                {{ $data['digitable_line'] }}
                            </p>
                        @endif
                    </div>
                @endif

                {{-- ── Status polling ───────────────────────────────────────────────── --}}
                <div class="border-t border-[#016630]/10 px-6 py-4 text-center">
                    <div id="status-waiting" class="flex items-center justify-center gap-2">
                        <span class="inline-block h-2 w-2 animate-pulse rounded-full bg-amber-400"></span>
                        <p class="text-sm text-zinc-500">@lang('mercadopago::app.pending.waiting')</p>
                    </div>
                    <div id="status-confirmed" class="hidden items-center justify-center gap-2">
                        <span class="inline-block h-2 w-2 rounded-full bg-[#016630]"></span>
                        <p class="text-sm font-semibold text-[#016630]">@lang('mercadopago::app.pending.confirmed')</p>
                    </div>
                </div>
            </div>

            <div class="mt-5 text-center">
                <a href="{{ route('shop.home.index') }}" class="text-sm text-zinc-400 transition hover:text-[#016630]">
                    Voltar para a loja
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function copyPix() {
                const code = document.getElementById('pix-code').value;

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(code).then(showCopied).catch(fallbackCopy);
                } else {
                    fallbackCopy();
                }
            }

            function fallbackCopy() {
                const input = document.getElementById('pix-code');
                input.select();
                input.setSelectionRange(0, 99999);
                document.execCommand('copy');
                showCopied();
            }

            function showCopied() {
                document.getElementById('pix-icon-copy').classList.add('hidden');
                document.getElementById('pix-icon-check').classList.remove('hidden');
                document.getElementById('pix-copy-label').textContent = 'Copiado!';

                setTimeout(() => {
                    document.getElementById('pix-icon-copy').classList.remove('hidden');
                    document.getElementById('pix-icon-check').classList.add('hidden');
                    document.getElementById('pix-copy-label').textContent = 'Copiar';
                }, 2500);
            }

            @if (! empty($data['mp_payment_id']))
            (function poll() {
                const url = '{{ route('mercadopago.status', $data['mp_payment_id']) }}';
                const successUrl = '{{ route('shop.checkout.onepage.success') }}';

                fetch(url)
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'paid') {
                            document.getElementById('status-waiting').classList.add('hidden');
                            const confirmed = document.getElementById('status-confirmed');
                            confirmed.classList.remove('hidden');
                            confirmed.classList.add('flex');
                            setTimeout(() => { window.location.href = successUrl; }, 1500);
                        } else if (data.status === 'pending') {
                            setTimeout(poll, 5000);
                        }
                    })
                    .catch(() => setTimeout(poll, 10000));
            })();
            @endif
        </script>
    @endpush
</x-eco-vasos-theme::layouts>

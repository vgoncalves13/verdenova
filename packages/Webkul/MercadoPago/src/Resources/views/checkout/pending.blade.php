<x-eco-vasos-theme::layouts
    :has-header="true"
    :has-feature="false"
    :has-footer="true"
>
    <x-slot:title>
        @lang('mercadopago::app.pending.title') — {{ config('app.name') }}
    </x-slot:title>

    @push('styles')
    <style>
        @keyframes pulse-ring {
            0%   { transform: scale(1);   opacity: .45; }
            70%  { transform: scale(1.6); opacity: 0;   }
            100% { transform: scale(1.6); opacity: 0;   }
        }
        @keyframes spin-slow {
            to { transform: rotate(360deg); }
        }
        @keyframes float-in {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0);    }
        }
        @keyframes shimmer-move {
            0%   { background-position: -400px 0; }
            100% { background-position: 400px 0;  }
        }

        .vn-pending-wrapper {
            min-height: 68vh;
            background: #f4f1ea;
            padding: 52px 16px 80px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .vn-pending-card {
            width: 100%;
            max-width: 460px;
        }

        .vn-pending-card.is-visible {
            animation: float-in 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        /* ── Header ──────────────────────────────── */
        .vn-pending-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .vn-pending-icon-wrap {
            position: relative;
            width: 72px;
            height: 72px;
            margin: 0 auto 18px;
        }

        .vn-pending-icon-bg {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: rgba(1, 102, 48, .12);
            animation: pulse-ring 2.4s ease-out infinite;
        }

        .vn-pending-icon-circle {
            position: relative;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #016630 0%, #008138 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(1, 102, 48, .28);
        }

        .vn-pending-icon-circle svg {
            width: 34px;
            height: 34px;
            color: #fff;
        }

        .vn-pending-title {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            color: #1a2214;
            line-height: 1.15;
            margin: 0 0 6px;
        }

        .vn-pending-subtitle {
            font-size: 0.875rem;
            color: #6b7264;
            margin: 0;
        }

        .vn-pending-order {
            display: inline-block;
            margin-top: 10px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #8a9180;
            background: rgba(1, 102, 48, .06);
            border: 1px solid rgba(1, 102, 48, .12);
            border-radius: 100px;
            padding: 4px 14px;
        }

        /* ── Main panel ──────────────────────────── */
        .vn-pending-panel {
            background: #fff;
            border-radius: 20px;
            border: 1px solid rgba(1, 77, 36, .1);
            overflow: hidden;
            box-shadow:
                0 4px 6px -2px rgba(1, 77, 36, .05),
                0 16px 48px -4px rgba(1, 77, 36, .10);
        }

        .vn-pending-panel__accent {
            height: 3px;
            background: linear-gradient(90deg, #016630, #008138 55%, #fac800);
        }

        /* ── PIX section ─────────────────────────── */
        .vn-pix-body {
            padding: 32px 28px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .vn-pix-qr-wrap {
            position: relative;
            margin-bottom: 24px;
        }

        .vn-pix-qr-frame {
            width: 168px;
            height: 168px;
            border-radius: 16px;
            border: 2px solid rgba(1, 77, 36, .15);
            overflow: hidden;
            background: #f9f8f5;
            padding: 6px;
        }

        .vn-pix-qr-frame img {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            display: block;
        }

        .vn-pix-qr-corner {
            position: absolute;
            width: 18px;
            height: 18px;
            border-color: #016630;
            border-style: solid;
        }
        .vn-pix-qr-corner--tl { top: -2px; left: -2px;  border-width: 3px 0 0 3px; border-radius: 4px 0 0 0; }
        .vn-pix-qr-corner--tr { top: -2px; right: -2px; border-width: 3px 3px 0 0; border-radius: 0 4px 0 0; }
        .vn-pix-qr-corner--bl { bottom: -2px; left: -2px;  border-width: 0 0 3px 3px; border-radius: 0 0 0 4px; }
        .vn-pix-qr-corner--br { bottom: -2px; right: -2px; border-width: 0 3px 3px 0; border-radius: 0 0 4px 0; }

        .vn-pix-copy-label-text {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #8a9180;
            margin-bottom: 10px;
            align-self: flex-start;
        }

        .vn-pix-copy-row {
            display: flex;
            width: 100%;
            gap: 8px;
            align-items: stretch;
        }

        .vn-pix-input {
            flex: 1;
            background: #f4f1ea;
            border: 1px solid rgba(1, 77, 36, .15);
            border-radius: 12px;
            padding: 11px 14px;
            font-size: 0.72rem;
            color: #4a5240;
            font-family: 'Courier New', monospace;
            outline: none;
            min-width: 0;
        }

        .vn-pix-copy-btn {
            display: flex;
            align-items: center;
            gap: 7px;
            background: #016630;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 11px 18px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s, transform 0.15s;
            font-family: 'Poppins', sans-serif;
            flex-shrink: 0;
        }

        .vn-pix-copy-btn:hover {
            background: #008138;
            transform: translateY(-1px);
        }

        .vn-pix-copy-btn:active { transform: translateY(0); }

        .vn-pix-copy-btn svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
        }

        /* ── Boleto section ──────────────────────── */
        .vn-boleto-body {
            padding: 40px 28px 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .vn-boleto-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #016630;
            color: #fff;
            border-radius: 14px;
            padding: 16px 36px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 6px 20px rgba(1, 102, 48, .25);
        }

        .vn-boleto-btn:hover {
            background: #008138;
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(1, 102, 48, .32);
            color: #fff;
        }

        .vn-boleto-btn svg {
            width: 18px;
            height: 18px;
        }

        .vn-boleto-line {
            font-size: 0.72rem;
            color: #9ca3af;
            text-align: center;
            word-break: break-all;
            line-height: 1.6;
            font-family: 'Courier New', monospace;
            background: #f9f8f5;
            border: 1px solid rgba(1, 77, 36, .08);
            border-radius: 10px;
            padding: 10px 14px;
            width: 100%;
        }

        /* ── Status bar ──────────────────────────── */
        .vn-pending-status {
            border-top: 1px solid rgba(1, 77, 36, .08);
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .vn-status-dot {
            position: relative;
            width: 10px;
            height: 10px;
            flex-shrink: 0;
        }

        .vn-status-dot__inner {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #fac800;
        }

        .vn-status-dot__ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: #fac800;
            animation: pulse-ring 2s ease-out infinite;
        }

        .vn-status-dot--confirmed .vn-status-dot__inner { background: #016630; }
        .vn-status-dot--confirmed .vn-status-dot__ring  { background: #016630; animation: none; }

        .vn-status-text {
            font-size: 0.82rem;
            color: #6b7264;
        }

        .vn-status-text--confirmed {
            color: #016630;
            font-weight: 600;
        }

        /* ── Tips row ────────────────────────────── */
        .vn-pending-tips {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .vn-tip {
            background: #fff;
            border: 1px solid rgba(1, 77, 36, .08);
            border-radius: 14px;
            padding: 14px 12px;
            text-align: center;
        }

        .is-visible .vn-tip { animation: float-in 0.55s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .is-visible .vn-tip:nth-child(1) { animation-delay: 0.08s; }
        .is-visible .vn-tip:nth-child(2) { animation-delay: 0.16s; }
        .is-visible .vn-tip:nth-child(3) { animation-delay: 0.24s; }

        .vn-tip__icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f4f1ea 0%, #e8dcc8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
        }

        .vn-tip__icon svg {
            width: 16px;
            height: 16px;
            color: #016630;
        }

        .vn-tip__text {
            font-size: 0.68rem;
            color: #8a9180;
            line-height: 1.4;
        }

        /* ── Back link ───────────────────────────── */
        .vn-pending-back {
            display: block;
            text-align: center;
            margin-top: 24px;
            font-size: 0.82rem;
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.2s;
        }

        .vn-pending-back:hover { color: #016630; }

        @media (max-width: 480px) {
            .vn-pending-tips { grid-template-columns: 1fr 1fr; }
            .vn-tip:nth-child(3) { grid-column: span 2; }
            .vn-pix-body { padding: 24px 18px 20px; }
            .vn-boleto-body { padding: 28px 18px 24px; }
        }
    </style>
    @endpush

    <div class="vn-pending-wrapper">
        <div class="vn-pending-card">

            {{-- ── Header ──────────────────────────────────────────────────── --}}
            <div class="vn-pending-header">
                <div class="vn-pending-icon-wrap">
                    <div class="vn-pending-icon-bg"></div>
                    <div class="vn-pending-icon-circle">
                        @if ($data['payment_type'] === 'bank_transfer')
                            <svg viewBox="0 0 512 512" fill="currentColor">
                                <path d="M242.4 292.5C247.8 287.1 257.1 287.1 262.5 292.5L339.5 369.5C353.7 383.7 372.6 391.5 392.6 391.5H407.7L310.2 488.1C280.3 517.7 231.1 517.7 201.2 488.1L103.3 391.5H118.4C138.4 391.5 157.3 383.7 171.5 369.5L242.4 292.5zM262.5 218.9C257.1 224.4 247.8 224.4 242.4 218.9L171.5 141.9C157.3 127.7 138.4 119.9 118.4 119.9H103.3L201.2 23.3C231.1-6.3 280.3-6.3 310.2 23.3L407.7 119.9H392.6C372.6 119.9 353.7 127.7 339.5 141.9L262.5 218.9zM112 144H118.4C132 144 144.1 149.3 153 158.1L240.1 244.4C253.1 257.4 258.9 257.4 271.9 244.4L358.1 158.1C367 149.3 379.1 144 392.6 144H399C419 144 438.6 151.5 453.4 165.2L484.3 195.2C512.5 222.9 512.5 268.7 484.3 296.5L453.4 326.5C438.6 340.2 419 347.7 399 347.7H392.6C379.1 347.7 367 342.4 358.1 333.6L271.9 247.3C258.9 234.3 253.1 234.3 240.1 247.3L153 333.6C144.1 342.4 132 347.7 118.4 347.7H112C92 347.7 72.4 340.2 57.6 326.5L26.7 296.5C-1.5 268.7-1.5 222.9 26.7 195.2L57.6 165.2C72.4 151.5 92 144 112 144z"/>
                            </svg>
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="2" y="4" width="20" height="16" rx="2.5"/>
                                <path d="M6 8v8M9 8v8M13 8v8M16 8v8M19 8v8"/>
                            </svg>
                        @endif
                    </div>
                </div>

                @if ($data['payment_type'] === 'bank_transfer')
                    <h1 class="vn-pending-title">Pague com PIX</h1>
                    <p class="vn-pending-subtitle">Escaneie o QR Code ou copie o código abaixo</p>
                @else
                    <h1 class="vn-pending-title">Boleto Gerado</h1>
                    <p class="vn-pending-subtitle">Pague em qualquer banco, app ou lotérica</p>
                @endif

                @if (! empty($data['order_id']))
                    <span class="vn-pending-order">Pedido #{{ $data['order_id'] }}</span>
                @endif
            </div>

            {{-- ── Main panel ───────────────────────────────────────────────── --}}
            <div class="vn-pending-panel">
                <div class="vn-pending-panel__accent"></div>

                {{-- ── PIX ────────────────────────────────────────────────────── --}}
                @if ($data['payment_type'] === 'bank_transfer' && ! empty($data['qr_code_base64']))
                    <div class="vn-pix-body">
                        <div class="vn-pix-qr-wrap">
                            <div class="vn-pix-qr-frame">
                                <img
                                    src="data:image/png;base64,{{ $data['qr_code_base64'] }}"
                                    alt="QR Code Pix"
                                >
                            </div>
                            <div class="vn-pix-qr-corner vn-pix-qr-corner--tl"></div>
                            <div class="vn-pix-qr-corner vn-pix-qr-corner--tr"></div>
                            <div class="vn-pix-qr-corner vn-pix-qr-corner--bl"></div>
                            <div class="vn-pix-qr-corner vn-pix-qr-corner--br"></div>
                        </div>

                        @if (! empty($data['qr_code']))
                            <p class="vn-pix-copy-label-text">PIX Copia e Cola</p>

                            <div class="vn-pix-copy-row">
                                <input
                                    id="pix-code"
                                    type="text"
                                    readonly
                                    value="{{ $data['qr_code'] }}"
                                    class="vn-pix-input"
                                >
                                <button
                                    id="pix-copy-btn"
                                    onclick="copyPix()"
                                    class="vn-pix-copy-btn"
                                >
                                    <svg id="pix-icon-copy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="9" y="9" width="13" height="13" rx="2"/>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                                    </svg>
                                    <svg id="pix-icon-check" class="hidden" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path d="M20 6 9 17l-5-5"/>
                                    </svg>
                                    <span id="pix-copy-label">Copiar</span>
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- ── BOLETO ──────────────────────────────────────────────────── --}}
                @if ($data['payment_type'] === 'ticket' && ! empty($data['ticket_url']))
                    <div class="vn-boleto-body">
                        <a
                            href="{{ $data['ticket_url'] }}"
                            target="_blank"
                            rel="noopener"
                            class="vn-boleto-btn"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            @lang('mercadopago::app.pending.boleto-button')
                        </a>

                        @if (! empty($data['digitable_line']))
                            <div class="vn-boleto-line">{{ $data['digitable_line'] }}</div>
                        @endif
                    </div>
                @endif

                {{-- ── Status polling ──────────────────────────────────────────── --}}
                <div class="vn-pending-status">
                    <div id="status-waiting" style="display:flex;align-items:center;gap:10px;">
                        <div class="vn-status-dot">
                            <div class="vn-status-dot__ring"></div>
                            <div class="vn-status-dot__inner"></div>
                        </div>
                        <span class="vn-status-text">@lang('mercadopago::app.pending.waiting')</span>
                    </div>
                    <div id="status-confirmed" style="display:none;align-items:center;gap:10px;">
                        <div class="vn-status-dot vn-status-dot--confirmed">
                            <div class="vn-status-dot__inner"></div>
                        </div>
                        <span class="vn-status-text vn-status-text--confirmed">@lang('mercadopago::app.pending.confirmed')</span>
                    </div>
                </div>
            </div>

            {{-- ── Tips ─────────────────────────────────────────────────────── --}}
            <div class="vn-pending-tips">
                @if ($data['payment_type'] === 'bank_transfer')
                    <div class="vn-tip">
                        <div class="vn-tip__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <p class="vn-tip__text">Válido por<br><strong>30 minutos</strong></p>
                    </div>
                    <div class="vn-tip">
                        <div class="vn-tip__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="5" y="2" width="14" height="20" rx="2"/>
                                <line x1="12" y1="18" x2="12" y2="18"/>
                            </svg>
                        </div>
                        <p class="vn-tip__text">Abra o app<br>do seu banco</p>
                    </div>
                    <div class="vn-tip">
                        <div class="vn-tip__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <p class="vn-tip__text">Confirmação<br>imediata</p>
                    </div>
                @else
                    <div class="vn-tip">
                        <div class="vn-tip__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <p class="vn-tip__text">Vence em<br><strong>3 dias úteis</strong></p>
                    </div>
                    <div class="vn-tip">
                        <div class="vn-tip__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="3" width="20" height="14" rx="2"/>
                                <path d="M8 21h8M12 17v4"/>
                            </svg>
                        </div>
                        <p class="vn-tip__text">Pague em<br>qualquer banco</p>
                    </div>
                    <div class="vn-tip">
                        <div class="vn-tip__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        </div>
                        <p class="vn-tip__text">Confirmação<br>em até 1 dia</p>
                    </div>
                @endif
            </div>

            <a href="{{ route('shop.home.index') }}" class="vn-pending-back">
                ← Voltar para a loja
            </a>
        </div>
    </div>

    @push('scripts')
        <script>
            // Adiciona a animação só após o Vue montar, evitando o double-render
            window.addEventListener('load', function () {
                requestAnimationFrame(function () {
                    const card = document.querySelector('.vn-pending-card');
                    if (card) { card.classList.add('is-visible'); }
                });
            });

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
                const successUrl = '{{ route('mercadopago.success') }}';

                fetch(url)
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'paid') {
                            document.getElementById('status-waiting').style.display = 'none';
                            const confirmed = document.getElementById('status-confirmed');
                            confirmed.style.display = 'flex';
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

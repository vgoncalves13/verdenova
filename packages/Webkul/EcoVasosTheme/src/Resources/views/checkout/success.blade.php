<x-shop::layouts
    :has-header="true"
    :has-feature="false"
    :has-footer="true"
>
    <x-slot:title>Pedido Confirmado — EcoVasos</x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Poppins:wght@300;400;500;600&display=swap');

        .success-page {
            background: #edf0e7;
            min-height: 80vh;
            position: relative;
            font-family: 'Poppins', sans-serif;
        }

        /* ── Card entrance ── */
        .success-card {
            animation: cardRise 0.75s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes cardRise {
            from { opacity: 0; transform: translateY(44px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0)    scale(1); }
        }

        /* ── Plant SVG draw-on ── */
        .plant-path {
            stroke-dasharray: 500;
            stroke-dashoffset: 500;
            animation: drawPlant 1.4s cubic-bezier(0.4, 0, 0.2, 1) 0.3s forwards;
        }

        @keyframes drawPlant { to { stroke-dashoffset: 0; } }

        .plant-leaf-fill {
            opacity: 0;
            animation: leafFill 0.5s ease 1.5s forwards;
        }

        @keyframes leafFill { to { opacity: 1; } }

        /* ── Pulse ring ── */
        .pulse-ring {
            animation: pulseRing 2.2s ease-out 1.2s infinite;
        }

        @keyframes pulseRing {
            0%   { transform: scale(1);   opacity: 0.45; }
            100% { transform: scale(1.7); opacity: 0; }
        }

        /* ── Staggered reveals ── */
        .reveal { opacity: 0; animation: revealUp 0.55s ease forwards; }
        .d1 { animation-delay: 0.9s; }
        .d2 { animation-delay: 1.1s; }
        .d3 { animation-delay: 1.3s; }
        .d4 { animation-delay: 1.5s; }
        .d5 { animation-delay: 1.7s; }

        @keyframes revealUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Step progress bars ── */
        .step-bar {
            height: 2px;
            background: #008138;
            width: 0;
            border-radius: 4px;
            animation: stepGrow 0.55s ease forwards;
        }

        .s1 .step-bar { animation-delay: 1.9s; }
        .s2 .step-bar { animation-delay: 2.1s; }
        .s3 .step-bar { animation-delay: 2.3s; }

        @keyframes stepGrow { to { width: 100%; } }

        /* ── Badge shimmer ── */
        .order-badge { position: relative; overflow: hidden; }
        .order-badge::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 55%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.55), transparent);
            animation: badgeShimmer 0.7s ease 2.4s forwards;
        }

        @keyframes badgeShimmer { to { left: 160%; } }

        /* ── Bottom bar ── */
        .bottom-bar {
            height: 5px;
            background: linear-gradient(90deg, #4caf50, #008138, #fac800, #4caf50);
            background-size: 300% 100%;
            animation: barSlide 5s linear infinite;
        }

        @keyframes barSlide {
            0%   { background-position: 0% 50%; }
            100% { background-position: 300% 50%; }
        }

        /* ── Divider ── */
        .botanical-rule {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .botanical-rule::before,
        .botanical-rule::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, #a5d6a7 40%, #a5d6a7 60%, transparent);
        }

        /* ── Buttons ── */
        .btn-eco {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-eco:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(0,129,56,0.3);
        }

        .btn-outline {
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .btn-outline:hover {
            background: #060C3B !important;
            color: #F6F2EB !important;
            border-color: #060C3B !important;
            transform: translateY(-1px);
        }
    </style>

    <section class="success-page py-16 max-md:py-10">
        <div style="max-width:600px; margin:0 auto; padding:0 1.5rem; position:relative; z-index:1;">

            {{-- ── Main card ── --}}
            <div class="success-card bg-white rounded-3xl overflow-hidden mt-5 mb-5"
                 style="box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07), 0 20px 60px -10px rgba(0,129,56,0.12);">

                {{-- Top gradient bar --}}
                <div style="height:5px; background:linear-gradient(90deg,#008138,#4caf50,#fac800);"></div>

                <div style="padding:2.5rem 2.75rem 3rem;" class="max-md:!px-6 max-md:!py-8">

                    {{-- Plant icon --}}
                    <div style="display:flex; justify-content:center; margin-bottom:2rem;">
                        <div style="position:relative;">
                            <div class="pulse-ring" style="
                                position:absolute; inset:-10px;
                                border-radius:9999px;
                                border:2px solid #4caf50;
                            "></div>
                            <div style="
                                width:88px; height:88px; border-radius:9999px;
                                background:linear-gradient(135deg,#e8f5e9,#c8e6c9);
                                display:flex; align-items:center; justify-content:center;
                            ">
                                <svg width="50" height="50" viewBox="0 0 52 52" fill="none">
                                    <path class="plant-leaf-fill" d="M18 40h16l-2 6H20l-2-6z M16 36h20l2 4H14l2-4z" fill="#8d6e63"/>
                                    <path class="plant-path" d="M26 38 C26 32 26 26 26 18" stroke="#2e7d32" stroke-width="2.5" stroke-linecap="round"/>
                                    <path class="plant-path" d="M26 28 C20 26 14 20 16 14 C20 18 24 22 26 28z" stroke="#008138" stroke-width="1.5" fill="none"/>
                                    <path class="plant-leaf-fill" d="M26 28 C20 26 14 20 16 14 C20 18 24 22 26 28z" fill="#4caf50" opacity="0.85"/>
                                    <path class="plant-path" d="M26 22 C32 20 38 14 36 8 C32 12 28 16 26 22z" stroke="#008138" stroke-width="1.5" fill="none"/>
                                    <path class="plant-leaf-fill" d="M26 22 C32 20 38 14 36 8 C32 12 28 16 26 22z" fill="#2e7d32" opacity="0.9"/>
                                    <path class="plant-path" d="M26 18 C24 12 20 6 14 4 C18 10 22 14 26 18z" stroke="#008138" stroke-width="1.5" fill="none"/>
                                    <path class="plant-leaf-fill" d="M26 18 C24 12 20 6 14 4 C18 10 22 14 26 18z" fill="#66bb6a" opacity="0.8"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Heading --}}
                    <div class="reveal d1" style="text-align:center;">
                        <h1 style="font-family:'DM Serif Display',serif; font-size:clamp(1.9rem,4.5vw,2.6rem); color:#060C3B; line-height:1.15; margin:0;">
                            Pedido confirmado!
                        </h1>
                        <p style="font-family:'DM Serif Display',serif; font-style:italic; color:#008138; font-size:1rem; margin:6px 0 0;">
                            sua natureza está a caminho
                        </p>
                    </div>

                    {{-- Order badge --}}
                    <div class="reveal d2" style="display:flex; justify-content:center; margin-bottom:1.5rem;">
                        <div class="order-badge" style="
                            display:inline-flex; align-items:center; gap:12px;
                            background:#f4f9f4; border:1.5px solid #c8e6c9;
                            border-radius:16px; padding:10px 22px;
                        ">
                            <span style="font-size:0.72rem; font-weight:700; color:#008138; letter-spacing:0.13em; text-transform:uppercase;">Pedido</span>
                            <span style="width:1px; height:16px; background:#c8e6c9; display:block;"></span>
                            <span style="font-family:'DM Serif Display',serif; font-size:1.2rem; color:#060C3B;">
                                @if (auth()->guard('customer')->user())
                                    <a href="{{ route('shop.customers.account.orders.view', $order->id) }}" style="color:#060C3B; text-decoration:none;">
                                        #{{ $order->increment_id }}
                                    </a>
                                @else
                                    #{{ $order->increment_id }}
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Message --}}
                    <p class="reveal d3" style="text-align:center; color:#666; font-size:0.92rem; line-height:1.75; max-width:360px; margin:0 auto 2rem;">
                        @if (! empty($order->checkout_message))
                            {!! nl2br(e($order->checkout_message)) !!}
                        @else
                            Recebemos seu pedido e já estamos cuidando dele com carinho.
                            Você receberá as atualizações por e-mail.
                        @endif
                    </p>

                    {{-- Steps --}}
                    <div class="reveal d4" style="display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:2.25rem;">
                        @foreach ([
                            ['🌱', 'Pedido recebido',     true,  's1'],
                            ['📦', 'Preparando embalagem', false, 's2'],
                            ['🚚', 'A caminho',            false, 's3'],
                        ] as [$icon, $label, $active, $cls])
                            <div class="{{ $cls }}" style="text-align:center; display:flex; flex-direction:column; align-items:center; gap:8px;">
                                <div style="
                                    width:48px; height:48px; border-radius:9999px;
                                    background:{{ $active ? '#e8f5e9' : '#f5f5f5' }};
                                    border:1.5px solid {{ $active ? '#4caf50' : '#e0e0e0' }};
                                    display:flex; align-items:center; justify-content:center;
                                    font-size:1.2rem;
                                ">{{ $icon }}</div>
                                <div class="step-bar" style="width:100%;"></div>
                                <span style="font-size:0.67rem; color:{{ $active ? '#008138' : '#bbb' }}; font-weight:{{ $active ? '600' : '400' }}; letter-spacing:0.03em; line-height:1.3;">
                                    {{ $label }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- CTAs --}}
                    <div class="reveal d5" style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
                        @if (auth()->guard('customer')->user())
                            <a href="{{ route('shop.customers.account.orders.view', $order->id) }}"
                               class="btn-eco"
                               style="display:inline-flex; align-items:center; gap:8px; background:#008138; color:#fff; border-radius:14px; padding:11px 26px; font-size:0.88rem; font-weight:600; text-decoration:none; letter-spacing:0.01em;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Ver meu pedido
                            </a>
                        @endif

                        <a href="{{ route('shop.home.index') }}"
                           class="btn-outline"
                           style="display:inline-flex; align-items:center; gap:8px; background:transparent; color:#060C3B; border:1.5px solid #060C3B; border-radius:14px; padding:11px 26px; font-size:0.88rem; font-weight:500; text-decoration:none;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Continuar comprando
                        </a>
                    </div>

                </div>

                {{-- Bottom animated bar --}}
                <div class="bottom-bar"></div>
            </div>

            {{-- Security note --}}
            <p class="reveal d5 mb-5" style="text-align:center; margin-top:1.4rem; font-size:0.74rem; color:#999; letter-spacing:0.04em;">
                🔒 &nbsp;Compra realizada com segurança via Mercado Pago
            </p>

        </div>
    </section>

    {{ view_render_event('bagisto.shop.checkout.success.continue-shopping.before', ['order' => $order]) }}
    {{ view_render_event('bagisto.shop.checkout.success.continue-shopping.after', ['order' => $order]) }}

</x-shop::layouts>

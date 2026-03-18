<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.customers.reset-password.title')"/>
    <meta name="keywords" content="@lang('shop::app.customers.reset-password.title')"/>
@endPush

@push('styles')
<style>
    * { box-sizing: border-box; }

    html, body { height: 100%; margin: 0; padding: 0; }

    #app { height: 100%; }

    /* ── Layout ─────────────────────────────────── */
    .eco-reset-root {
        display: flex;
        min-height: 100dvh;
        font-family: 'Poppins', sans-serif;
    }

    /* ── Left botanical panel ────────────────────── */
    .eco-reset-botanical {
        position: relative;
        flex: 0 0 52%;
        background-color: #012b17;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 3rem 3.5rem 3.5rem;
    }

    .eco-reset-botanical::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 80% 60% at 20% 10%, rgba(0,129,56,.55) 0%, transparent 60%),
            radial-gradient(ellipse 60% 50% at 80% 80%, rgba(1,74,32,.7) 0%, transparent 55%),
            radial-gradient(ellipse 100% 100% at 50% 50%, #012b17 40%, #00180d 100%);
        z-index: 0;
    }

    .eco-reset-botanical::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.06'/%3E%3C/svg%3E");
        z-index: 1;
        pointer-events: none;
    }

    .eco-leaf {
        position: absolute;
        border-radius: 50% 0 50% 0;
        z-index: 2;
    }
    .eco-leaf-1 {
        width: 420px; height: 420px;
        top: -80px; right: -100px;
        background: radial-gradient(ellipse at 40% 60%, rgba(76,175,80,.18) 0%, transparent 70%);
        border: 1px solid rgba(76,175,80,.12);
        transform: rotate(25deg);
    }
    .eco-leaf-2 {
        width: 260px; height: 360px;
        top: 60px; right: 60px;
        background: rgba(0,129,56,.08);
        border: 1px solid rgba(0,129,56,.15);
        border-radius: 70% 30% 70% 30% / 30% 70% 30% 70%;
        transform: rotate(-15deg);
    }
    .eco-leaf-3 {
        width: 180px; height: 280px;
        top: 120px; right: 140px;
        background: rgba(76,175,80,.06);
        border: 1px solid rgba(76,175,80,.1);
        border-radius: 40% 60% 40% 60% / 60% 40% 60% 40%;
        transform: rotate(40deg);
    }
    .eco-leaf-4 {
        width: 500px; height: 500px;
        bottom: -120px; left: -140px;
        background: radial-gradient(ellipse at 60% 40%, rgba(0,129,56,.12) 0%, transparent 65%);
        border: 1px solid rgba(0,129,56,.08);
        border-radius: 50% 0 50% 0;
        transform: rotate(55deg);
    }
    .eco-leaf-5 {
        width: 140px; height: 220px;
        bottom: 180px; left: 60px;
        background: rgba(76,175,80,.07);
        border: 1px solid rgba(76,175,80,.12);
        border-radius: 60% 40% 60% 40% / 40% 60% 40% 60%;
        transform: rotate(-20deg);
        animation: ecoFloat 8s ease-in-out infinite;
    }
    .eco-leaf-6 {
        width: 80px; height: 130px;
        top: 40%; left: 38%;
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.07);
        border-radius: 50% 0 50% 0;
        transform: rotate(70deg);
        animation: ecoFloat 11s ease-in-out infinite reverse;
    }

    .eco-circle-art {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 340px; height: 340px;
        border: 1px solid rgba(76,175,80,.12);
        border-radius: 50%;
        z-index: 2;
    }
    .eco-circle-art::before {
        content: '';
        position: absolute;
        inset: 20px;
        border: 1px solid rgba(76,175,80,.07);
        border-radius: 50%;
    }
    .eco-circle-art::after {
        content: '';
        position: absolute;
        inset: 50px;
        border: 1px solid rgba(76,175,80,.05);
        border-radius: 50%;
    }

    @keyframes ecoFloat {
        0%, 100% { transform: rotate(-20deg) translateY(0); }
        50%       { transform: rotate(-20deg) translateY(-14px); }
    }

    .eco-botanical-content {
        position: relative;
        z-index: 3;
    }
    .eco-botanical-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        font-size: .7rem;
        font-weight: 600;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: rgba(76,175,80,.85);
        margin-bottom: 1.25rem;
    }
    .eco-botanical-eyebrow span {
        display: block;
        width: 28px; height: 1px;
        background: rgba(76,175,80,.5);
    }
    .eco-botanical-headline {
        font-family: 'DM Serif Display', serif;
        font-size: clamp(2rem, 3.2vw, 3rem);
        font-style: italic;
        line-height: 1.2;
        color: #fff;
        margin: 0 0 1.5rem;
        max-width: 380px;
    }
    .eco-botanical-headline em {
        font-style: normal;
        color: #4caf50;
    }
    .eco-botanical-divider {
        width: 40px; height: 1px;
        background: rgba(76,175,80,.4);
        margin-bottom: 1.25rem;
    }
    .eco-botanical-desc {
        font-size: .85rem;
        line-height: 1.75;
        color: rgba(255,255,255,.5);
        max-width: 340px;
    }
    .eco-botanical-dots {
        display: flex;
        gap: .5rem;
        margin-top: 2.5rem;
    }
    .eco-botanical-dots span {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: rgba(76,175,80,.3);
    }
    .eco-botanical-dots span:first-child {
        background: rgba(76,175,80,.8);
        width: 22px; border-radius: 3px;
    }

    /* ── Right form panel ────────────────────────── */
    .eco-reset-form-panel {
        flex: 1;
        background: #F6F2EB;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 2.5rem;
        position: relative;
        overflow: hidden;
    }

    .eco-reset-form-panel::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 280px; height: 280px;
        border: 1px solid rgba(0,129,56,.1);
        border-radius: 50%;
        pointer-events: none;
    }
    .eco-reset-form-panel::after {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 160px; height: 160px;
        border: 1px solid rgba(0,129,56,.07);
        border-radius: 50%;
        pointer-events: none;
    }

    .eco-form-inner {
        width: 100%;
        max-width: 380px;
        position: relative;
        z-index: 1;
    }

    /* logo */
    .eco-logo-wrap {
        display: flex;
        justify-content: center;
        margin-bottom: 2.5rem;
    }
    .eco-logo-wrap img {
        height: 96px;
        width: auto;
    }

    /* icon */
    .eco-reset-icon {
        width: 52px; height: 52px;
        background: rgba(0,129,56,.1);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }
    .eco-reset-icon svg {
        width: 24px; height: 24px;
        stroke: #008138;
    }

    /* form heading */
    .eco-form-heading {
        font-family: 'DM Serif Display', serif;
        font-size: 2rem;
        color: #012b17;
        margin: 0 0 .4rem;
        line-height: 1.2;
    }
    .eco-form-subtext {
        font-size: .85rem;
        color: #6b7280;
        margin: 0 0 2rem;
        line-height: 1.65;
    }

    /* field label */
    .eco-reset-root .control-label {
        font-size: .78rem;
        font-weight: 600;
        letter-spacing: .04em;
        color: #374151;
        margin-bottom: .4rem;
        display: block;
        text-transform: uppercase;
    }
    .eco-reset-root label.required::after,
    .eco-reset-root .label.required::after {
        content: ' *';
        color: #008138;
    }

    /* inputs */
    .eco-reset-root .eco-form-inner input[type="email"],
    .eco-reset-root .eco-form-inner input[type="password"] {
        width: 100%;
        background: #fff;
        border: 1.5px solid #d1d5db;
        border-radius: 10px;
        padding: .85rem 1.1rem;
        font-size: .9rem;
        font-family: 'Poppins', sans-serif;
        color: #111827;
        transition: border-color .2s, box-shadow .2s;
        outline: none;
        appearance: none;
    }
    .eco-reset-root .eco-form-inner input[type="email"]:focus,
    .eco-reset-root .eco-form-inner input[type="password"]:focus {
        border-color: #008138;
        box-shadow: 0 0 0 3px rgba(0,129,56,.12);
    }
    .eco-reset-root .eco-form-inner input::placeholder {
        color: #9ca3af;
        font-size: .85rem;
    }

    /* password strength hint */
    .eco-pw-hint {
        font-size: .73rem;
        color: #9ca3af;
        margin-top: .35rem;
        display: flex;
        align-items: center;
        gap: .3rem;
    }
    .eco-pw-hint svg {
        width: 12px; height: 12px;
        stroke: #9ca3af;
        flex-shrink: 0;
    }

    /* submit button */
    .eco-submit-btn {
        display: block;
        width: 100%;
        padding: .95rem 1.5rem;
        background: #008138;
        color: #fff;
        font-family: 'Poppins', sans-serif;
        font-size: .9rem;
        font-weight: 600;
        letter-spacing: .04em;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: background .2s, transform .15s, box-shadow .2s;
        position: relative;
        overflow: hidden;
        margin-top: 1.5rem;
    }
    .eco-submit-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.08) 50%, transparent 100%);
        transform: translateX(-100%);
        transition: transform .4s;
    }
    .eco-submit-btn:hover {
        background: #016630;
        box-shadow: 0 6px 20px rgba(0,129,56,.3);
        transform: translateY(-1px);
    }
    .eco-submit-btn:hover::before { transform: translateX(100%); }
    .eco-submit-btn:active { transform: translateY(0); }

    /* footer note */
    .eco-footer-note {
        position: absolute;
        bottom: 1.2rem;
        left: 0; right: 0;
        text-align: center;
        font-size: .7rem;
        color: #9ca3af;
    }

    /* error messages */
    .eco-reset-root .eco-form-inner .invalid-feedback,
    .eco-reset-root .eco-form-inner [class*="error"] {
        font-size: .75rem;
        color: #dc2626;
        margin-top: .3rem;
    }

    /* ── Responsive ──────────────────────────────── */
    @media (max-width: 900px) {
        .eco-reset-root { flex-direction: column; }

        .eco-reset-botanical {
            flex: 0 0 auto;
            min-height: 200px;
            padding: 2rem 2rem 2.5rem;
            justify-content: flex-end;
        }
        .eco-leaf-1 { width: 260px; height: 260px; }
        .eco-leaf-4 { width: 300px; height: 300px; }
        .eco-circle-art { width: 200px; height: 200px; }
        .eco-botanical-headline { font-size: 1.6rem; }
        .eco-botanical-desc { display: none; }

        .eco-reset-form-panel {
            padding: 2.5rem 1.5rem 4rem;
            justify-content: flex-start;
        }
        .eco-footer-note { position: static; margin-top: 1.5rem; }
    }

    @media (max-width: 480px) {
        .eco-reset-botanical { min-height: 160px; padding: 1.5rem 1.25rem 2rem; }
        .eco-botanical-headline { font-size: 1.35rem; }
        .eco-reset-form-panel { padding: 2rem 1.25rem 3rem; }
    }
</style>
@endpush

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.reset-password.title')
    </x-slot>

    <div class="eco-reset-root">

        <!-- ── Left: botanical panel ────────────────── -->
        <div class="eco-reset-botanical">
            <div class="eco-leaf eco-leaf-1"></div>
            <div class="eco-leaf eco-leaf-2"></div>
            <div class="eco-leaf eco-leaf-3"></div>
            <div class="eco-leaf eco-leaf-4"></div>
            <div class="eco-leaf eco-leaf-5"></div>
            <div class="eco-leaf eco-leaf-6"></div>
            <div class="eco-circle-art"></div>

            <div class="eco-botanical-content">
                <div class="eco-botanical-eyebrow">
                    <span></span>
                    Nova senha
                </div>

                <h2 class="eco-botanical-headline">
                    Quase lá,<br>
                    crie uma senha<br>
                    <em>segura</em>
                </h2>

                <div class="eco-botanical-divider"></div>

                <p class="eco-botanical-desc">
                    Escolha uma senha forte para proteger sua conta. Use pelo menos 6 caracteres, combinando letras e números.
                </p>

                <div class="eco-botanical-dots">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>

        <!-- ── Right: form panel ─────────────────────── -->
        <div class="eco-reset-form-panel">
            <div class="eco-form-inner">

                {!! view_render_event('bagisto.shop.customers.reset_password.logo.before') !!}

                <!-- Logo -->
                <div class="eco-logo-wrap">
                    <a
                        href="{{ route('shop.home.index') }}"
                        aria-label="@lang('shop::app.customers.reset-password.bagisto')"
                    >
                        <img
                            src="{{ asset('images/logo-verde.png') }}"
                            alt="{{ config('app.name') }}"
                            width="393"
                            height="87"
                        >
                    </a>
                </div>

                {!! view_render_event('bagisto.shop.customers.reset_password.logo.after') !!}

                <!-- Icon -->
                <div class="eco-reset-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>

                <!-- Heading -->
                <h1 class="eco-form-heading">
                    @lang('shop::app.customers.reset-password.title')
                </h1>
                <p class="eco-form-subtext">
                    Defina sua nova senha abaixo para recuperar o acesso à sua conta.
                </p>

                {!! view_render_event('bagisto.shop.customers.reset_password.before') !!}

                <!-- Form -->
                <x-shop::form :action="route('shop.customers.reset_password.store')">

                    <x-shop::form.control-group.control
                        type="hidden"
                        name="token"
                        :value="$token"
                    />

                    {!! view_render_event('bagisto.shop.customers.reset_password_form_controls.before') !!}

                    <!-- Email -->
                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label class="required control-label">
                            @lang('shop::app.customers.reset-password.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            id="email"
                            name="email"
                            rules="required|email"
                            :value="old('email')"
                            :label="trans('shop::app.customers.reset-password.email')"
                            placeholder="seu@email.com"
                            :aria-label="trans('shop::app.customers.reset-password.email')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    <!-- New Password -->
                    <x-shop::form.control-group class="mb-1">
                        <x-shop::form.control-group.label class="required control-label">
                            @lang('shop::app.customers.reset-password.password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            name="password"
                            rules="required|min:6"
                            value=""
                            :label="trans('shop::app.customers.reset-password.password')"
                            :placeholder="trans('shop::app.customers.reset-password.password')"
                            ref="password"
                            :aria-label="trans('shop::app.customers.reset-password.password')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password" />
                    </x-shop::form.control-group>

                    <p class="eco-pw-hint">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/>
                        </svg>
                        Mínimo de 6 caracteres
                    </p>

                    <!-- Confirm Password -->
                    <x-shop::form.control-group class="mb-2 mt-4">
                        <x-shop::form.control-group.label class="control-label">
                            @lang('shop::app.customers.reset-password.confirm-password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            name="password_confirmation"
                            rules="confirmed:@password"
                            value=""
                            :label="trans('shop::app.customers.reset-password.confirm-password')"
                            :placeholder="trans('shop::app.customers.reset-password.confirm-password')"
                            :aria-label="trans('shop::app.customers.reset-password.confirm-password')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password_confirmation" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.reset_password_form_controls.after') !!}

                    {!! view_render_event('bagisto.shop.customers.reset_password.submit_button.before') !!}

                    <!-- Submit -->
                    <button class="eco-submit-btn" type="submit">
                        @lang('shop::app.customers.reset-password.submit-btn-title')
                    </button>

                    {!! view_render_event('bagisto.shop.customers.reset_password.submit_button.after') !!}

                </x-shop::form>

                {!! view_render_event('bagisto.shop.customers.reset_password.after') !!}

            </div><!-- /.eco-form-inner -->

            <!-- Footer note -->
            <p class="eco-footer-note">
                @if (core()->getConfigData('general.content.footer.copyright_content'))
                    {!! core()->getConfigData('general.content.footer.copyright_content') !!}
                @else
                    @lang('shop::app.components.layouts.footer.footer-text', ['current_year' => date('Y')])
                @endif
            </p>
        </div>

    </div><!-- /.eco-reset-root -->
</x-shop::layouts>

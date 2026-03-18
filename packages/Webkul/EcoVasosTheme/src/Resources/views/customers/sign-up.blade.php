<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.customers.signup-form.page-title')"/>
    <meta name="keywords" content="@lang('shop::app.customers.signup-form.page-title')"/>
@endPush

@push('styles')
<style>
    * { box-sizing: border-box; }

    html, body { height: 100%; margin: 0; padding: 0; }

    #app { height: 100%; }

    /* ── Layout ─────────────────────────────────── */
    .eco-signup-root {
        display: flex;
        min-height: 100dvh;
        font-family: 'Poppins', sans-serif;
    }

    /* ── Left botanical panel ────────────────────── */
    .eco-signup-botanical {
        position: sticky;
        top: 0;
        height: 100dvh;
        flex: 0 0 42%;
        background-color: #012b17;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 3rem 3rem 3.5rem;
    }

    .eco-signup-botanical::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 80% 60% at 20% 10%, rgba(0,129,56,.55) 0%, transparent 60%),
            radial-gradient(ellipse 60% 50% at 80% 80%, rgba(1,74,32,.7) 0%, transparent 55%),
            radial-gradient(ellipse 100% 100% at 50% 50%, #012b17 40%, #00180d 100%);
        z-index: 0;
    }

    .eco-signup-botanical::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.06'/%3E%3C/svg%3E");
        z-index: 1;
        pointer-events: none;
    }

    /* decorative leaf shapes */
    .eco-leaf {
        position: absolute;
        border-radius: 50% 0 50% 0;
        z-index: 2;
    }
    .eco-leaf-1 {
        width: 380px; height: 380px;
        top: -70px; right: -90px;
        background: radial-gradient(ellipse at 40% 60%, rgba(76,175,80,.18) 0%, transparent 70%);
        border: 1px solid rgba(76,175,80,.12);
        transform: rotate(25deg);
    }
    .eco-leaf-2 {
        width: 240px; height: 320px;
        top: 50px; right: 50px;
        background: rgba(0,129,56,.08);
        border: 1px solid rgba(0,129,56,.15);
        border-radius: 70% 30% 70% 30% / 30% 70% 30% 70%;
        transform: rotate(-15deg);
    }
    .eco-leaf-3 {
        width: 160px; height: 260px;
        top: 110px; right: 130px;
        background: rgba(76,175,80,.06);
        border: 1px solid rgba(76,175,80,.1);
        border-radius: 40% 60% 40% 60% / 60% 40% 60% 40%;
        transform: rotate(40deg);
    }
    .eco-leaf-4 {
        width: 460px; height: 460px;
        bottom: -110px; left: -130px;
        background: radial-gradient(ellipse at 60% 40%, rgba(0,129,56,.12) 0%, transparent 65%);
        border: 1px solid rgba(0,129,56,.08);
        border-radius: 50% 0 50% 0;
        transform: rotate(55deg);
    }
    .eco-leaf-5 {
        width: 130px; height: 200px;
        bottom: 200px; left: 50px;
        background: rgba(76,175,80,.07);
        border: 1px solid rgba(76,175,80,.12);
        border-radius: 60% 40% 60% 40% / 40% 60% 40% 60%;
        transform: rotate(-20deg);
        animation: ecoFloat 8s ease-in-out infinite;
    }
    .eco-leaf-6 {
        width: 70px; height: 120px;
        top: 42%; left: 36%;
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
        width: 300px; height: 300px;
        border: 1px solid rgba(76,175,80,.12);
        border-radius: 50%;
        z-index: 2;
    }
    .eco-circle-art::before {
        content: '';
        position: absolute;
        inset: 18px;
        border: 1px solid rgba(76,175,80,.07);
        border-radius: 50%;
    }
    .eco-circle-art::after {
        content: '';
        position: absolute;
        inset: 46px;
        border: 1px solid rgba(76,175,80,.05);
        border-radius: 50%;
    }

    @keyframes ecoFloat {
        0%, 100% { transform: rotate(-20deg) translateY(0); }
        50%       { transform: rotate(-20deg) translateY(-14px); }
    }

    /* botanical content text */
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
        font-size: clamp(1.8rem, 2.8vw, 2.7rem);
        font-style: italic;
        line-height: 1.2;
        color: #fff;
        margin: 0 0 1.5rem;
        max-width: 340px;
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
        font-size: .82rem;
        line-height: 1.75;
        color: rgba(255,255,255,.5);
        max-width: 300px;
    }
    .eco-botanical-steps {
        margin-top: 2rem;
        display: flex;
        flex-direction: column;
        gap: .6rem;
    }
    .eco-botanical-step {
        display: flex;
        align-items: center;
        gap: .75rem;
        font-size: .75rem;
        color: rgba(255,255,255,.45);
    }
    .eco-botanical-step-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: rgba(76,175,80,.5);
        flex-shrink: 0;
    }

    /* ── Right form panel ────────────────────────── */
    .eco-signup-form-panel {
        flex: 1;
        background: #F6F2EB;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 3rem 2.5rem 3.5rem;
        position: relative;
        overflow-x: hidden;
        overflow-y: auto;
        min-height: 100dvh;
    }

    .eco-signup-form-panel::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 280px; height: 280px;
        border: 1px solid rgba(0,129,56,.1);
        border-radius: 50%;
        pointer-events: none;
    }
    .eco-signup-form-panel::after {
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
        max-width: 400px;
        position: relative;
        z-index: 1;
        flex: 1;
    }

    /* logo */
    .eco-logo-wrap {
        margin-bottom: 2rem;
    }
    .eco-logo-wrap img {
        height: 32px;
        width: auto;
    }

    /* form heading */
    .eco-form-heading {
        font-family: 'DM Serif Display', serif;
        font-size: 1.9rem;
        color: #012b17;
        margin: 0 0 .4rem;
        line-height: 1.2;
    }
    .eco-form-subtext {
        font-size: .85rem;
        color: #6b7280;
        margin: 0 0 1.75rem;
    }

    /* two-column row */
    .eco-name-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    /* field labels */
    .eco-signup-root .control-label {
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .04em;
        color: #374151;
        margin-bottom: .4rem;
        display: block;
        text-transform: uppercase;
    }
    .eco-signup-root label.required::after,
    .eco-signup-root .label.required::after {
        content: ' *';
        color: #008138;
    }

    /* inputs */
    .eco-signup-root .eco-form-inner input[type="email"],
    .eco-signup-root .eco-form-inner input[type="password"],
    .eco-signup-root .eco-form-inner input[type="text"] {
        width: 100%;
        background: #fff;
        border: 1.5px solid #d1d5db;
        border-radius: 10px;
        padding: .8rem 1rem;
        font-size: .88rem;
        font-family: 'Poppins', sans-serif;
        color: #111827;
        transition: border-color .2s, box-shadow .2s;
        outline: none;
        appearance: none;
    }
    .eco-signup-root .eco-form-inner input[type="email"]:focus,
    .eco-signup-root .eco-form-inner input[type="password"]:focus,
    .eco-signup-root .eco-form-inner input[type="text"]:focus {
        border-color: #008138;
        box-shadow: 0 0 0 3px rgba(0,129,56,.12);
    }
    .eco-signup-root .eco-form-inner input::placeholder {
        color: #9ca3af;
        font-size: .82rem;
    }

    /* submit button */
    .eco-submit-btn {
        display: block;
        width: 100%;
        padding: .92rem 1.5rem;
        background: #008138;
        color: #fff;
        font-family: 'Poppins', sans-serif;
        font-size: .88rem;
        font-weight: 600;
        letter-spacing: .04em;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: background .2s, transform .15s, box-shadow .2s;
        position: relative;
        overflow: hidden;
        margin-top: 1.25rem;
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

    /* checkbox newsletter */
    .eco-checkbox-row {
        display: flex;
        align-items: center;
        gap: .55rem;
        margin-top: 1rem;
    }
    .eco-checkbox-row input[type="checkbox"] {
        accent-color: #008138;
        width: 15px; height: 15px;
        cursor: pointer;
        flex-shrink: 0;
    }
    .eco-checkbox-row label {
        font-size: .8rem;
        color: #6b7280;
        cursor: pointer;
        user-select: none;
        line-height: 1.4;
    }

    /* GDPR agreement */
    .eco-gdpr-row {
        display: flex;
        align-items: flex-start;
        gap: .55rem;
        margin-top: .75rem;
    }
    .eco-gdpr-row input[type="checkbox"] {
        accent-color: #008138;
        width: 15px; height: 15px;
        cursor: pointer;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .eco-gdpr-row label {
        font-size: .78rem;
        color: #6b7280;
        cursor: pointer;
        user-select: none;
        line-height: 1.5;
    }
    .eco-gdpr-row span {
        color: #008138;
        font-weight: 500;
        cursor: pointer;
        text-decoration: underline;
    }

    /* login link */
    .eco-login-line {
        margin-top: 1.5rem;
        font-size: .82rem;
        color: #6b7280;
        text-align: center;
    }
    .eco-login-line a {
        color: #008138;
        font-weight: 600;
        text-decoration: none;
        transition: color .15s;
    }
    .eco-login-line a:hover { color: #016630; text-decoration: underline; }

    /* footer note */
    .eco-footer-note {
        margin-top: 2rem;
        text-align: center;
        font-size: .7rem;
        color: #9ca3af;
    }

    /* error messages */
    .eco-signup-root .eco-form-inner .invalid-feedback,
    .eco-signup-root .eco-form-inner [class*="error"] {
        font-size: .73rem;
        color: #dc2626;
        margin-top: .3rem;
    }

    /* social buttons wrapper */
    .eco-social-wrap {
        margin-top: 1.5rem;
    }

    /* ── Responsive ──────────────────────────────── */
    @media (max-width: 960px) {
        .eco-signup-root { flex-direction: column; }

        .eco-signup-botanical {
            position: relative;
            height: auto;
            min-height: 180px;
            padding: 2rem 2rem 2.5rem;
            justify-content: flex-end;
        }
        .eco-leaf-1 { width: 240px; height: 240px; }
        .eco-leaf-4 { width: 280px; height: 280px; }
        .eco-circle-art { width: 180px; height: 180px; }
        .eco-botanical-headline { font-size: 1.5rem; }
        .eco-botanical-desc { display: none; }
        .eco-botanical-steps { display: none; }

        .eco-signup-form-panel {
            padding: 2.5rem 1.5rem 3.5rem;
            min-height: auto;
        }
    }

    @media (max-width: 480px) {
        .eco-signup-botanical { min-height: 150px; padding: 1.5rem 1.25rem 2rem; }
        .eco-botanical-headline { font-size: 1.3rem; }
        .eco-signup-form-panel { padding: 2rem 1.25rem 3rem; }
        .eco-name-row { grid-template-columns: 1fr; gap: 0; }
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
        @lang('shop::app.customers.signup-form.page-title')
    </x-slot>

    <div class="eco-signup-root">

        <!-- ── Left: botanical panel ────────────────── -->
        <div class="eco-signup-botanical">
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
                    Bem-vindo à família
                </div>

                <h2 class="eco-botanical-headline">
                    Cultive o seu<br>
                    espaço com<br>
                    <em>propósito</em>
                </h2>

                <div class="eco-botanical-divider"></div>

                <p class="eco-botanical-desc">
                    Crie sua conta e acesse curadoria botânica exclusiva, pedidos salvos e inspirações para transformar cada ambiente.
                </p>

                <div class="eco-botanical-steps">
                    <div class="eco-botanical-step">
                        <div class="eco-botanical-step-dot"></div>
                        Acesso a produtos exclusivos
                    </div>
                    <div class="eco-botanical-step">
                        <div class="eco-botanical-step-dot"></div>
                        Acompanhe seus pedidos
                    </div>
                    <div class="eco-botanical-step">
                        <div class="eco-botanical-step-dot"></div>
                        Lista de desejos personalizada
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Right: form panel ─────────────────────── -->
        <div class="eco-signup-form-panel">
            <div class="eco-form-inner">

                {!! view_render_event('bagisto.shop.customers.sign-up.logo.before') !!}

                <!-- Logo -->
                <div class="eco-logo-wrap">
                    <a
                        href="{{ route('shop.home.index') }}"
                        aria-label="@lang('shop::app.customers.signup-form.bagisto')"
                    >
                        <img
                            src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                            alt="{{ config('app.name') }}"
                            width="131"
                            height="29"
                        >
                    </a>
                </div>

                {!! view_render_event('bagisto.shop.customers.sign-up.logo.before') !!}

                <!-- Heading -->
                <h1 class="eco-form-heading">
                    @lang('shop::app.customers.signup-form.page-title')
                </h1>
                <p class="eco-form-subtext">
                    @lang('shop::app.customers.signup-form.form-signup-text')
                </p>

                <!-- Form -->
                <x-shop::form :action="route('shop.customers.register.store')">

                    {!! view_render_event('bagisto.shop.customers.signup_form_controls.before') !!}

                    <!-- First + Last name -->
                    <div class="eco-name-row">
                        <x-shop::form.control-group class="mb-4">
                            <x-shop::form.control-group.label class="required control-label">
                                @lang('shop::app.customers.signup-form.first-name')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="first_name"
                                rules="required"
                                :value="old('first_name')"
                                :label="trans('shop::app.customers.signup-form.first-name')"
                                :placeholder="trans('shop::app.customers.signup-form.first-name')"
                                :aria-label="trans('shop::app.customers.signup-form.first-name')"
                                aria-required="true"
                            />

                            <x-shop::form.control-group.error control-name="first_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.signup_form.first_name.after') !!}

                        <x-shop::form.control-group class="mb-4">
                            <x-shop::form.control-group.label class="required control-label">
                                @lang('shop::app.customers.signup-form.last-name')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="last_name"
                                rules="required"
                                :value="old('last_name')"
                                :label="trans('shop::app.customers.signup-form.last-name')"
                                :placeholder="trans('shop::app.customers.signup-form.last-name')"
                                :aria-label="trans('shop::app.customers.signup-form.last-name')"
                                aria-required="true"
                            />

                            <x-shop::form.control-group.error control-name="last_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.signup_form.last_name.after') !!}
                    </div>

                    <!-- Email -->
                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label class="required control-label">
                            @lang('shop::app.customers.signup-form.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            name="email"
                            rules="required|email"
                            :value="old('email')"
                            :label="trans('shop::app.customers.signup-form.email')"
                            placeholder="seu@email.com"
                            :aria-label="trans('shop::app.customers.signup-form.email')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.signup_form.email.after') !!}

                    <!-- Password -->
                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label class="required control-label">
                            @lang('shop::app.customers.signup-form.password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            name="password"
                            rules="required|min:6"
                            :value="old('password')"
                            :label="trans('shop::app.customers.signup-form.password')"
                            :placeholder="trans('shop::app.customers.signup-form.password')"
                            ref="password"
                            :aria-label="trans('shop::app.customers.signup-form.password')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.signup_form.password.after') !!}

                    <!-- Confirm Password -->
                    <x-shop::form.control-group class="mb-2">
                        <x-shop::form.control-group.label class="control-label">
                            @lang('shop::app.customers.signup-form.confirm-pass')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            name="password_confirmation"
                            rules="confirmed:@password"
                            value=""
                            :label="trans('shop::app.customers.signup-form.password')"
                            :placeholder="trans('shop::app.customers.signup-form.confirm-pass')"
                            :aria-label="trans('shop::app.customers.signup-form.confirm-pass')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password_confirmation" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.signup_form.password_confirmation.after') !!}

                    <!-- Captcha -->
                    @if (core()->getConfigData('customer.captcha.credentials.status'))
                        <x-shop::form.control-group class="mt-4 mb-2">
                            {!! \Webkul\Customer\Facades\Captcha::render() !!}
                            <x-shop::form.control-group.error control-name="g-recaptcha-response" />
                        </x-shop::form.control-group>
                    @endif

                    <!-- Newsletter -->
                    @if (core()->getConfigData('customer.settings.create_new_account_options.news_letter'))
                        <div class="eco-checkbox-row">
                            <input
                                type="checkbox"
                                name="is_subscribed"
                                id="is-subscribed"
                            />
                            <label for="is-subscribed">
                                @lang('shop::app.customers.signup-form.subscribe-to-newsletter')
                            </label>
                        </div>
                    @endif

                    {!! view_render_event('bagisto.shop.customers.signup_form.newsletter_subscription.after') !!}

                    <!-- GDPR -->
                    @if(
                        core()->getConfigData('general.gdpr.settings.enabled')
                        && core()->getConfigData('general.gdpr.agreement.enabled')
                    )
                        <div class="eco-gdpr-row">
                            <x-shop::form.control-group.control
                                type="checkbox"
                                name="agreement"
                                id="agreement"
                                value="0"
                                rules="required"
                                for="agreement"
                            />
                            <label for="agreement" v-pre>
                                {{ core()->getConfigData('general.gdpr.agreement.agreement_label') }}
                                @if (core()->getConfigData('general.gdpr.agreement.agreement_content'))
                                    <span @click="$refs.termsModal.open()">
                                        @lang('shop::app.customers.signup-form.click-here')
                                    </span>
                                @endif
                            </label>
                        </div>
                        <x-shop::form.control-group.error control-name="agreement" />
                    @endif

                    <!-- Submit -->
                    <button class="eco-submit-btn" type="submit">
                        @lang('shop::app.customers.signup-form.button-title')
                    </button>

                    {!! view_render_event('bagisto.shop.customers.signup_form_controls.after') !!}

                </x-shop::form>

                <!-- Social login -->
                <div class="eco-social-wrap">
                    {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}
                </div>

                <!-- Login link -->
                <p class="eco-login-line">
                    @lang('shop::app.customers.signup-form.account-exists')
                    <a href="{{ route('shop.customer.session.index') }}">
                        @lang('shop::app.customers.signup-form.sign-in-button')
                    </a>
                </p>

                <!-- Footer note -->
                <p class="eco-footer-note">
                    @if (core()->getConfigData('general.content.footer.copyright_content'))
                        {!! core()->getConfigData('general.content.footer.copyright_content') !!}
                    @else
                        @lang('shop::app.components.layouts.footer.footer-text', ['current_year' => date('Y')])
                    @endif
                </p>

            </div><!-- /.eco-form-inner -->
        </div>

    </div><!-- /.eco-signup-root -->

    @push('scripts')
        {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}
    @endpush

    <!-- Terms & Conditions Modal -->
    <x-shop::modal ref="termsModal">
        <x-slot:toggle></x-slot>

        <x-slot:header class="!p-5">
            <p>@lang('shop::app.customers.signup-form.terms-conditions')</p>
        </x-slot>

        <x-slot:content class="!p-5">
            <div class="max-h-[500px] overflow-auto">
                {!! core()->getConfigData('general.gdpr.agreement.agreement_content') !!}
            </div>
        </x-slot>
    </x-shop::modal>
</x-shop::layouts>

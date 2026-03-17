{!! view_render_event('bagisto.shop.layout.footer.before') !!}

@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

@php
    $channel = core()->getCurrentChannel();

    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'footer_links',
        'status'     => 1,
        'theme_code' => $channel->theme,
        'channel_id' => $channel->id,
    ]);
@endphp

<footer style="background:#016630; color:#fff;">

    {{-- ── Main footer strip ── --}}
    <div style="
        max-width: 1440px;
        margin: 0 auto;
        padding: 32px 60px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 40px;
        flex-wrap: wrap;
    " class="max-md:!px-8 max-sm:!px-4 max-sm:!py-6">

        {{-- Brand mark --}}
        <div style="flex-shrink:0; min-width:120px;">
            <a href="{{ route('shop.home.index') }}" style="display:inline-block; text-decoration:none;">
                <img
                    src="{{ core()->getCurrentChannel()->logo_url ?? asset('images/logo.png') }}"
                    width="110"
                    height="25"
                    alt="{{ config('app.name') }}"
                    class="brightness-0 invert"
                >
            </a>
            <p style="font-size:0.72rem; color:rgba(255,255,255,0.45); margin-top:8px; line-height:1.5; max-width:140px;">
                Plantas e vasos com amor pela natureza.
            </p>
        </div>

        {{-- Footer links (desktop) ── --}}
        @if ($customization?->options)
            <div class="max-md:hidden" style="display:flex; gap:40px; flex-wrap:wrap; flex:1; max-1060:hidden;">
                @foreach ($customization->options as $footerLinkSection)
                    @php
                        usort($footerLinkSection, fn($a, $b) => $a['sort_order'] - $b['sort_order']);
                        $first = $footerLinkSection[0] ?? null;
                    @endphp

                    <ul style="list-style:none; margin:0; padding:0; display:grid; gap:8px;" v-pre>
                        @if ($first)
                            <li class="footer-col-title">{{ $first['title'] }}</li>
                        @endif

                        @foreach (array_slice($footerLinkSection, 1) as $link)
                            <li>
                                <a href="{{ $link['url'] }}" class="footer-link">
                                    {{ $link['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        @endif

        {{-- Mobile accordion ── --}}
        <x-shop::accordion
            :is-active="false"
            class="hidden !w-full rounded-lg !border !border-white/20 max-md:block"
        >
            <x-slot:header class="rounded-t-lg bg-[#014d25] text-white text-sm font-medium max-sm:px-3 max-sm:py-2">
                @lang('shop::app.components.layouts.footer.footer-content')
            </x-slot>

            <x-slot:content class="flex flex-wrap gap-6 !bg-transparent !p-4">
                @if ($customization?->options)
                    @foreach ($customization->options as $footerLinkSection)
                        <ul class="grid gap-2 text-sm" v-pre>
                            @php
                                usort($footerLinkSection, fn($a, $b) => $a['sort_order'] - $b['sort_order']);
                            @endphp
                            @foreach ($footerLinkSection as $link)
                                <li>
                                    <a href="{{ $link['url'] }}" class="footer-link">
                                        {{ $link['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                @endif
            </x-slot>
        </x-shop::accordion>

        {{-- Newsletter ── --}}
        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.before') !!}

        @if (core()->getConfigData('customer.settings.newsletter.subscription'))
            <div style="flex-shrink:0; min-width:220px; max-width:300px;" class="max-sm:!max-w-full max-sm:!w-full">
                <p class="footer-col-title">Newsletter</p>
                <p style="font-size:0.78rem; color:rgba(255,255,255,0.55); margin-bottom:10px; line-height:1.5;">
                    @lang('shop::app.components.layouts.footer.subscribe-stay-touch')
                </p>

                <x-shop::form :action="route('shop.subscription.store')">
                    <div style="display:flex; gap:6px; align-items:flex-start;">
                        <div style="flex:1;">
                            <x-shop::form.control-group.control
                                type="email"
                                class="footer-input"
                                name="email"
                                rules="required|email"
                                label="Email"
                                :aria-label="trans('shop::app.components.layouts.footer.email')"
                                placeholder="seu@email.com"
                            />
                            <x-shop::form.control-group.error control-name="email" />
                        </div>

                        <button type="submit" class="footer-subscribe-btn">
                            @lang('shop::app.components.layouts.footer.subscribe')
                        </button>
                    </div>
                </x-shop::form>
            </div>
        @endif

        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}

    </div>

    {{-- ── Bottom bar ── --}}
    <div style="background:#014d25; padding:10px 60px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;"
         class="max-md:!px-8 max-sm:!px-4 max-sm:justify-center">

        {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}

        <p style="font-size:0.72rem; color:rgba(255,255,255,0.5); margin:0;">
            @if (core()->getConfigData('general.content.footer.copyright_content'))
                {!! core()->getConfigData('general.content.footer.copyright_content') !!}
            @else
                @lang('shop::app.components.layouts.footer.footer-text', ['current_year' => date('Y')])
            @endif
        </p>

        <div style="display:flex; gap:16px; align-items:center;">
            {{-- Eco badge --}}
            <span style="font-size:0.68rem; color:rgba(255,255,255,0.35); display:flex; align-items:center; gap:4px;">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="rgba(255,255,255,0.35)">
                    <path d="M12 2C6 2 2 8 2 14c0 3 2 6 6 7 1-3 0-7 4-9-2 3-1 7 0 9 4-1 6-4 6-7 0-6-4-12-6-12z"/>
                </svg>
                Feito com cuidado pelo planeta
            </span>
        </div>

        {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}
    </div>

</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}

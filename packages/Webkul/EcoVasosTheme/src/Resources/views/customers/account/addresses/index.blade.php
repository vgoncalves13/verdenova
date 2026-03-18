<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.addresses.index.add-address')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="addresses" />
        @endSection
    @endif

    @push('styles')
    <style>
        .eco-addresses-wrap {
            flex: 1;
            min-width: 0;
            font-family: 'Poppins', sans-serif;
            padding-bottom: 3rem;
        }

        .eco-addresses-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
        }
        .eco-addresses-header-left {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .eco-addresses-back {
            display: none;
            color: #374151;
            font-size: 1.4rem;
            text-decoration: none;
        }
        @media (max-width: 768px) { .eco-addresses-back { display: flex; } }

        .eco-addresses-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: #012b17;
            margin: 0;
        }

        .eco-add-btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .55rem 1.1rem;
            border: 1.5px solid #008138;
            border-radius: 8px;
            font-size: .82rem;
            font-weight: 600;
            color: #008138;
            text-decoration: none;
            background: transparent;
            transition: background .15s, color .15s;
            font-family: 'Poppins', sans-serif;
            white-space: nowrap;
        }
        .eco-add-btn:hover { background: #008138; color: #fff; }

        /* Address grid */
        .eco-address-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 900px) { .eco-address-grid { grid-template-columns: 1fr; } }

        .eco-address-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.25rem 1.4rem;
            transition: box-shadow .15s, border-color .15s;
        }
        .eco-address-card:hover {
            border-color: #d1d5db;
            box-shadow: 0 4px 12px rgba(0,0,0,.06);
        }
        .eco-address-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: .9rem;
        }
        .eco-address-name {
            font-size: .9rem;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }
        .eco-address-company {
            font-size: .78rem;
            color: #9ca3af;
        }
        .eco-address-actions {
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .eco-default-badge {
            display: inline-flex;
            align-items: center;
            padding: .2rem .6rem;
            background: #dcfce7;
            color: #008138;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            border-radius: 20px;
        }
        .eco-address-text {
            font-size: .83rem;
            color: #6b7280;
            line-height: 1.65;
            margin: 0;
        }

        /* Empty state */
        .eco-addresses-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 5rem 2rem;
            text-align: center;
            gap: 1rem;
        }
        .eco-addresses-empty img { width: 120px; opacity: .6; }
        .eco-addresses-empty p {
            font-size: .95rem;
            color: #9ca3af;
        }
    </style>
    @endpush

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="eco-addresses-wrap mx-4">

        <!-- Header -->
        <div class="eco-addresses-header">
            <div class="eco-addresses-header-left">
                <a class="eco-addresses-back" href="{{ route('shop.customers.account.index') }}">
                    <span class="icon-arrow-left rtl:icon-arrow-right"></span>
                </a>
                <h2 class="eco-addresses-title">
                    @lang('shop::app.customers.account.addresses.index.title')
                </h2>
            </div>

            <a href="{{ route('shop.customers.account.addresses.create') }}" class="eco-add-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                @lang('shop::app.customers.account.addresses.index.add-address')
            </a>
        </div>

        @if (! $addresses->isEmpty())

            {!! view_render_event('bagisto.shop.customers.account.addresses.list.before', ['addresses' => $addresses]) !!}

            <div class="eco-address-grid">
                @foreach ($addresses as $address)
                    <div class="eco-address-card">
                        <div class="eco-address-card-top">
                            <div>
                                <p class="eco-address-name" v-pre>
                                    {{ $address->first_name }} {{ $address->last_name }}
                                </p>
                                @if ($address->company_name)
                                    <span class="eco-address-company" v-pre>({{ $address->company_name }})</span>
                                @endif
                            </div>

                            <div class="eco-address-actions">
                                @if ($address->default_address)
                                    <span class="eco-default-badge">
                                        @lang('shop::app.customers.account.addresses.index.default-address')
                                    </span>
                                @endif

                                <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                                    <x-slot:toggle>
                                        <button
                                            class="icon-more cursor-pointer rounded-md px-1.5 py-1 text-2xl text-zinc-500 transition-all hover:bg-gray-100 hover:text-black focus:bg-gray-100 focus:text-black"
                                            aria-label="More Options"
                                        ></button>
                                    </x-slot>

                                    <x-slot:menu class="!py-1">
                                        <x-shop::dropdown.menu.item>
                                            <a href="{{ route('shop.customers.account.addresses.edit', $address->id) }}">
                                                <p class="w-full">@lang('shop::app.customers.account.addresses.index.edit')</p>
                                            </a>
                                        </x-shop::dropdown.menu.item>

                                        <x-shop::dropdown.menu.item>
                                            <form
                                                method="POST"
                                                ref="addressDelete"
                                                action="{{ route('shop.customers.account.addresses.delete', $address->id) }}"
                                            >
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                            <a
                                                href="javascript:void(0);"
                                                @click="$emitter.emit('open-confirm-modal', {
                                                    agree: () => { $refs['addressDelete'].submit() }
                                                })"
                                            >
                                                <p class="w-full">@lang('shop::app.customers.account.addresses.index.delete')</p>
                                            </a>
                                        </x-shop::dropdown.menu.item>

                                        @if (! $address->default_address)
                                            <x-shop::dropdown.menu.item>
                                                <form
                                                    method="POST"
                                                    ref="setAsDefault"
                                                    action="{{ route('shop.customers.account.addresses.update.default', $address->id) }}"
                                                >
                                                    @method('PATCH')
                                                    @csrf
                                                </form>
                                                <a
                                                    href="javascript:void(0);"
                                                    @click="$emitter.emit('open-confirm-modal', {
                                                        agree: () => { $refs['setAsDefault'].submit() }
                                                    })"
                                                >
                                                    <button>@lang('shop::app.customers.account.addresses.index.set-as-default')</button>
                                                </a>
                                            </x-shop::dropdown.menu.item>
                                        @endif
                                    </x-slot>
                                </x-shop::dropdown>
                            </div>
                        </div>

                        <p class="eco-address-text" v-pre>
                            {{ $address->address }},
                            {{ $address->city }},
                            {{ $address->state }}, {{ $address->country }},
                            {{ $address->postcode }}
                        </p>
                    </div>
                @endforeach
            </div>

            {!! view_render_event('bagisto.shop.customers.account.addresses.list.after', ['addresses' => $addresses]) !!}

        @else
            <div class="eco-addresses-empty">
                <img src="{{ bagisto_asset('images/no-address.png') }}" alt="Nenhum endereço">
                <p>@lang('shop::app.customers.account.addresses.index.empty-address')</p>
            </div>
        @endif

    </div>
</x-shop::layouts.account>

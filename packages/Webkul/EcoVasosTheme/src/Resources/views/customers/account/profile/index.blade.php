<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.profile.index.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="profile" />
        @endSection
    @endif

    @push('styles')
    <style>
        .eco-profile-wrap {
            flex: 1;
            min-width: 0;
            font-family: 'Poppins', sans-serif;
        }

        /* Page header */
        .eco-profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
        }
        .eco-profile-header-left {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .eco-profile-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: #012b17;
            margin: 0;
            line-height: 1.2;
        }
        .eco-profile-back {
            display: none;
            color: #374151;
            font-size: 1.4rem;
            text-decoration: none;
        }
        @media (max-width: 768px) {
            .eco-profile-back { display: flex; }
        }

        /* Edit button */
        .eco-edit-btn {
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
        }
        .eco-edit-btn:hover { background: #008138; color: #fff; }
        .eco-edit-btn svg { width: 14px; height: 14px; }

        /* Info card */
        .eco-profile-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .eco-profile-row {
            display: grid;
            grid-template-columns: 180px 1fr;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            gap: 1rem;
        }
        .eco-profile-row:last-child { border-bottom: none; }

        .eco-profile-row-label {
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #9ca3af;
            margin: 0;
        }
        .eco-profile-row-value {
            font-size: .88rem;
            font-weight: 500;
            color: #111827;
            margin: 0;
        }

        /* Delete section */
        .eco-profile-danger {
            margin-top: 1.5rem;
            padding: 1.25rem 1.5rem;
            background: #fff;
            border-radius: 16px;
            border: 1px solid #fee2e2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .eco-profile-danger-text h4 {
            font-size: .88rem;
            font-weight: 600;
            color: #dc2626;
            margin: 0 0 .2rem;
        }
        .eco-profile-danger-text p {
            font-size: .78rem;
            color: #9ca3af;
            margin: 0;
        }
        .eco-delete-btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .55rem 1.1rem;
            border: 1.5px solid #dc2626;
            border-radius: 8px;
            font-size: .82rem;
            font-weight: 600;
            color: #dc2626;
            background: transparent;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }
        .eco-delete-btn:hover { background: #dc2626; color: #fff; }

        @media (max-width: 640px) {
            .eco-profile-row { grid-template-columns: 1fr; gap: .25rem; padding: .85rem 1rem; }
            .eco-profile-danger { flex-direction: column; align-items: flex-start; }
        }
    </style>
    @endpush

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="eco-profile-wrap mx-4 max-md:mx-6 max-sm:mx-4">

        <!-- Header -->
        <div class="eco-profile-header">
            <div class="eco-profile-header-left">
                <a class="eco-profile-back" href="{{ route('shop.customers.account.index') }}">
                    <span class="icon-arrow-left rtl:icon-arrow-right"></span>
                </a>
                <h2 class="eco-profile-title">
                    @lang('shop::app.customers.account.profile.index.title')
                </h2>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit_button.before') !!}

            <a href="{{ route('shop.customers.account.profile.edit') }}" class="eco-edit-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                @lang('shop::app.customers.account.profile.index.edit')
            </a>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit_button.after') !!}
        </div>

        <!-- Info card -->
        <div class="eco-profile-card">

            {!! view_render_event('bagisto.shop.customers.account.profile.first_name.before') !!}
            <div class="eco-profile-row">
                <p class="eco-profile-row-label">@lang('shop::app.customers.account.profile.index.first-name')</p>
                <p class="eco-profile-row-value" v-pre>{{ $customer->first_name }}</p>
            </div>
            {!! view_render_event('bagisto.shop.customers.account.profile.first_name.after') !!}

            {!! view_render_event('bagisto.shop.customers.account.profile.last_name.before') !!}
            <div class="eco-profile-row">
                <p class="eco-profile-row-label">@lang('shop::app.customers.account.profile.index.last-name')</p>
                <p class="eco-profile-row-value" v-pre>{{ $customer->last_name }}</p>
            </div>
            {!! view_render_event('bagisto.shop.customers.account.profile.last_name.after') !!}

            {!! view_render_event('bagisto.shop.customers.account.profile.gender.before') !!}
            <div class="eco-profile-row">
                <p class="eco-profile-row-label">@lang('shop::app.customers.account.profile.index.gender')</p>
                <p class="eco-profile-row-value" v-pre>{{ $customer->gender ?? '-' }}</p>
            </div>
            {!! view_render_event('bagisto.shop.customers.account.profile.gender.after') !!}

            {!! view_render_event('bagisto.shop.customers.account.profile.date_of_birth.before') !!}
            <div class="eco-profile-row">
                <p class="eco-profile-row-label">@lang('shop::app.customers.account.profile.index.dob')</p>
                <p class="eco-profile-row-value" v-pre>{{ $customer->date_of_birth ?? '-' }}</p>
            </div>
            {!! view_render_event('bagisto.shop.customers.account.profile.date_of_birth.after') !!}

            {!! view_render_event('bagisto.shop.customers.account.profile.email.before') !!}
            <div class="eco-profile-row">
                <p class="eco-profile-row-label">@lang('shop::app.customers.account.profile.index.email')</p>
                <p class="eco-profile-row-value" v-pre>{{ $customer->email }}</p>
            </div>
            {!! view_render_event('bagisto.shop.customers.account.profile.email.after') !!}

        </div>

        <!-- Danger zone -->
        {!! view_render_event('bagisto.shop.customers.account.profile.delete.before') !!}

        <x-shop::form action="{{ route('shop.customers.account.profile.destroy') }}">
            <x-shop::modal>
                <x-slot:toggle>
                    <div class="eco-profile-danger">
                        <div class="eco-profile-danger-text">
                            <h4>@lang('shop::app.customers.account.profile.index.delete-profile')</h4>
                            <p>Esta ação é irreversível e removerá todos os seus dados.</p>
                        </div>
                        <button type="button" class="eco-delete-btn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6m4-6v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                            </svg>
                            @lang('shop::app.customers.account.profile.index.delete-profile')
                        </button>
                    </div>
                </x-slot>

                <x-slot:header>
                    <h2 class="text-xl font-medium">
                        @lang('shop::app.customers.account.profile.index.enter-password')
                    </h2>
                </x-slot>

                <x-slot:content>
                    <x-shop::form.control-group class="!mb-0">
                        <x-shop::form.control-group.control
                            type="password"
                            name="password"
                            class="px-6 py-4"
                            rules="required"
                            placeholder="Enter your password"
                        />
                        <x-shop::form.control-group.error class="text-left" control-name="password" />
                    </x-shop::form.control-group>
                </x-slot>

                <x-slot:footer>
                    <button type="submit" class="eco-delete-btn">
                        @lang('shop::app.customers.account.profile.index.delete')
                    </button>
                </x-slot>
            </x-shop::modal>
        </x-shop::form>

        {!! view_render_event('bagisto.shop.customers.account.profile.delete.after') !!}

    </div>

</x-shop::layouts.account>
